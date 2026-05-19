# Epic Games Tubes - Database Query Documentation

Dokumen ini berisi penjelasan semua query database yang digunakan di proyek Epic Games Tubes, dengan terjemahan dari Laravel Eloquent/Query Builder ke SQL MySQL.

## Daftar Isi
1. [Migration Files (DDL)](#migrations)
2. [Dashboard Controller (Complex Queries)](#dashboard)
3. [Game Controller (Filters & Search)](#game-controller)
4. [Checkout Controller (DML & Stored Procedures)](#checkout)
5. [User Model (Sub-queries)](#user-model)

---

## Migrations - DDL Queries {#migrations}

Semua file migration di `database/migrations/` telah anotasi dengan SQL equivalent. Berikut ringkasannya:

### Core Tables
- **users**: User account dengan is_admin, is_active flags
- **publishers, developers, genres, features, platform, tags, age**: Master data tables
- **games**: Main games table dengan foreign keys ke publishers/developers, enum untuk game_type dan refund_type
- **news**: News/announcements table

### Relationship/Junction Tables
- **game_genre**: Many-to-many antara games dan genres
- **game_features**: Many-to-many antara games dan features
- **game_platform**: Many-to-many antara games dan platform
- **game_tags**: Many-to-many antara games dan tags
- **game_social_links**: Social media links untuk tiap game
- **bundle_contents**: Item yang ada di bundle game

### Transaction & Purchase Tables
- **transactions**: Main transaction record dengan completed_at untuk menandai transaksi selesai
- **transaction_details**: Line items per transaction (game yang dibeli + harga)
- **cart_temp**: Temporary cart untuk processing checkout (digunakan stored procedure)

### User Activity Tables
- **library**: User's owned games (banyak-ke-banyak via user_id + game_id)
- **wishlist**: User's wishlisted games
- **discounts**: Active discounts per game dengan date range
- **achievements**: Game achievements
- **critic_reviews**: Reviews dari critic/reviewer

### System Tables
- **cache, cache_locks, sessions**: Laravel system tables

### Special Migrations
- **2026_05_19_031447_add_fulltext_to_games_table.php**:
  ```sql
  ALTER TABLE games ADD FULLTEXT KEY games_search_fulltext (title, main_desc, `desc`);
  ```

---

## Dashboard Controller - Complex Queries {#dashboard}

### 1. Game Type Distribution (Aggregation + GROUP BY)
```sql
SELECT game_type, COUNT(*) as count
FROM games
WHERE is_active = 1
GROUP BY game_type
ORDER BY count DESC;
```
**Eloquent**: `Game::where('is_active', true)->select('game_type', DB::raw('COUNT(*) as count'))->groupBy('game_type')->orderByDesc('count')->get()`

### 2. Top 5 Selling Games (LEFT JOIN + Aggregation + ORDER BY)
```sql
SELECT g.game_id, g.title, g.cover_image_url, g.avg_rating,
       p.name AS publisher,
       COUNT(td.game_id) AS total_sold,
       COALESCE(SUM(td.price_at_purchase),0) AS total_revenue
FROM games AS g
LEFT JOIN publishers AS p ON g.publisher_id = p.publisher_id
LEFT JOIN transaction_details AS td ON g.game_id = td.game_id
LEFT JOIN transactions AS t ON td.transaction_id = t.transaction_id 
  AND t.completed_at IS NOT NULL
WHERE g.is_active = 1
GROUP BY g.game_id, g.title, g.cover_image_url, g.avg_rating, p.name
ORDER BY total_sold DESC
LIMIT 5;
```

### 3. Top 5 High Spenders (JOIN + Aggregation + ORDER BY)
```sql
SELECT u.user_id, u.username, u.email,
       COUNT(t.transaction_id) AS total_transactions,
       SUM(t.total_amount) AS total_spent
FROM users AS u
JOIN transactions AS t ON u.user_id = t.user_id 
  AND t.completed_at IS NOT NULL
WHERE u.is_admin = 0
GROUP BY u.user_id, u.username, u.email
ORDER BY total_spent DESC
LIMIT 5;
```

### 4. Popular Genres (3-way JOIN + Aggregation)
```sql
SELECT g.genre_id, g.name AS genre_name,
       COUNT(DISTINCT td.game_id) AS total_games,
       COUNT(td.detail_id) AS total_sold,
       COALESCE(SUM(td.price_at_purchase),0) AS total_revenue
FROM genres AS g
JOIN game_genres AS gg ON g.genre_id = gg.genre_id
JOIN transaction_details AS td ON gg.game_id = td.game_id
JOIN transactions AS t ON td.transaction_id = t.transaction_id 
  AND t.completed_at IS NOT NULL
GROUP BY g.genre_id, g.name
ORDER BY total_sold DESC
LIMIT 6;
```

### 5. Monthly Revenue Chart (DATE_FORMAT + GROUPING)
```sql
SELECT YEAR(completed_at) AS tahun,
       MONTH(completed_at) AS bulan,
       DATE_FORMAT(completed_at, '%b %Y') AS nama_bulan,
       SUM(total_amount) AS total_pendapatan
FROM transactions
WHERE completed_at IS NOT NULL
  AND completed_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
GROUP BY YEAR(completed_at), MONTH(completed_at), 
         DATE_FORMAT(completed_at, '%b %Y')
ORDER BY tahun, bulan;
```

---

## Game Controller - Filter & Search Queries {#game-controller}

### Base Query
```sql
SELECT g.*
FROM games AS g
WHERE g.is_active = 1
  AND g.cover_image_url IS NOT NULL;
```
**Eloquent**: `Game::active()->whereNotNull('cover_image_url')->with([...])`

### Search with FULLTEXT (MATCH...AGAINST)
```sql
WHERE (MATCH(title, main_desc, `desc`) AGAINST('keyword' IN NATURAL LANGUAGE MODE)
       OR title LIKE '%keyword%')
```
**Eloquent**:
```php
$query->where(function ($q) use ($keyword) {
    $q->whereRaw("MATCH(title, main_desc, `desc`) AGAINST(? IN NATURAL LANGUAGE MODE)", [$keyword])
      ->orWhere('title', 'LIKE', "%{$keyword}%");
});
```

### Filter by Genre (EXISTS subquery via whereHas)
```sql
WHERE EXISTS (
  SELECT 1 FROM game_genres
  WHERE game_genres.game_id = games.game_id
    AND game_genres.genre_id = ?
)
```
**Eloquent**: `$query->whereHas('genres', fn($q) => $q->where('genres.genre_id', $request->genre))`

### Filter by Features & Platforms
Same pattern as genres - uses `whereHas()` on many-to-many relationships.

### Filter by Discount (Active)
```sql
WHERE EXISTS (
  SELECT 1 FROM discounts
  WHERE discounts.game_id = games.game_id
    AND discounts.is_active = 1
    AND discounts.start_date <= NOW()
    AND discounts.end_date >= NOW()
)
```

---

## Checkout Controller - DML & Stored Procedures {#checkout}

### Clear Temporary Cart
```sql
DELETE FROM cart_temp WHERE user_id = ?;
```

### Insert Cart Items (Batch INSERT)
```sql
INSERT INTO cart_temp (user_id, game_id, price_at_purchase, discount_applied)
VALUES (?, ?, ?, ?),
       (?, ?, ?, ?),
       ...
```

### Call Stored Procedure
```sql
CALL sp_checkout(user_id, payment_method, @transaction_id, @result);
```
**Parameters**:
- IN: user_id, payment_method
- OUT: @transaction_id, @result

The SP (`sp_checkout`) likely:
1. Reads cart_temp
2. Creates transaction record
3. Creates transaction_detail records for each game
4. Triggers update to library table
5. Clears cart_temp
6. Returns transaction_id and result status

### Get Output Variables
```sql
SELECT @tid AS transaction_id, @res AS result;
```

---

## User Model - Sub-queries {#user-model}

### Check if User Already Owns Game(s)
```sql
SELECT EXISTS(
  SELECT 1 FROM library
  WHERE user_id = ? AND game_id IN (?, ?, ...)
);
```
**Eloquent**: `$this->library()->whereIn('game_id', $ids)->exists()`

### Get Owned Game IDs from List
```sql
SELECT game_id FROM library
WHERE user_id = ? AND game_id IN (?, ?, ...)
```
**Eloquent**: `$this->library()->whereIn('game_id', $gameIds)->pluck('game_id')`

---

## Views (Database Views) {#views}

Proyek menggunakan beberapa views yang di-reference di DashboardController:
- `vw_top_selling_games`: Pre-calculated top sellers
- `vw_high_spenders`: Pre-calculated high spender users
- `vw_popular_genres`: Pre-calculated genre popularity
- `vw_monthly_revenue`: Pre-calculated monthly revenue

Jika views tidak ada, controller fallback ke query builder equivalents.

---

## Performance Optimization Notes

### Indexes Recommended
1. `games (is_active, cover_image_url)` - used in browse/filter queries
2. `games (title)` + FULLTEXT index - used in search
3. `game_genres (game_id, genre_id)` + `game_genres (genre_id)` - JOIN queries
4. `transaction_details (game_id, transaction_id)` - aggregation queries
5. `transactions (completed_at, user_id)` - time range queries
6. `library (user_id, game_id)` - ownership checks
7. `discounts (game_id, is_active, start_date, end_date)` - discount filtering

### Query Pattern Summary
- **SELECT + WHERE**: Basic filtering (whereIn, where, whereHas)
- **JOIN**: Relates games to publishers, transactions to details
- **LEFT JOIN**: Optional relations (publishers may be null)
- **GROUP BY + COUNT**: Aggregations for stats
- **ORDER BY**: Sorting results
- **LIMIT**: Pagination top N
- **FULLTEXT INDEX + MATCH...AGAINST**: Full-text search on game description
- **IN subquery**: Filter games by relationships
- **EXISTS**: Check ownership in library table

