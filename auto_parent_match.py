import re
import pandas as pd
import mysql.connector

from rapidfuzz import fuzz

# =========================================================
# DATABASE CONFIG
# =========================================================

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="epicgames_final"
)

cursor = conn.cursor(dictionary=True)

# =========================================================
# HELPER
# =========================================================

REMOVE_WORDS = [
    "ultimate edition",
    "deluxe edition",
    "gold edition",
    "premium edition",
    "complete edition",
    "game of the year edition",
    "director's cut",
    "remastered",
    "remaster",
    "bundle",
    "demo",
    "dlc",
    "addon",
    "add-on",
    "expansion",
    "season pass",
    "founder's pack",
    "starter pack",
    "edition",
    "og",
    "beta",
    "alpha",
    "test server",
]

def clean_title(title):

    t = title.lower()

    # hapus simbol aneh
    t = re.sub(r'[^a-z0-9\s]', ' ', t)

    # hapus keyword edition/addon/dll
    for word in REMOVE_WORDS:
        t = t.replace(word, ' ')

    # hapus spasi berlebih
    t = re.sub(r'\s+', ' ', t).strip()

    return t

# =========================================================
# AMBIL DATA
# =========================================================

cursor.execute("""
    SELECT game_id, title
    FROM games
    WHERE game_type = 'base_game'
""")

base_games = cursor.fetchall()

cursor.execute("""
    SELECT game_id, title, game_type
    FROM games
    WHERE game_type != 'base_game'
""")

child_games = cursor.fetchall()

print(f"Base games: {len(base_games)}")
print(f"Child games: {len(child_games)}")

# =========================================================
# MATCHING
# =========================================================

results = []

for child in child_games:

    child_clean = clean_title(child['title'])

    best_match = None
    best_score = 0

    for base in base_games:

        base_clean = clean_title(base['title'])

        # kombinasi scoring
        score_1 = fuzz.ratio(child_clean, base_clean)
        score_2 = fuzz.partial_ratio(child_clean, base_clean)
        score_3 = fuzz.token_sort_ratio(child_clean, base_clean)

        final_score = (
            score_1 * 0.3 +
            score_2 * 0.4 +
            score_3 * 0.3
        )

        if final_score > best_score:
            best_score = final_score
            best_match = base

    # threshold aman
    if best_match and best_score >= 72:

        results.append({
            "child_id": child['game_id'],
            "child_title": child['title'],
            "game_type": child['game_type'],
            "parent_id": best_match['game_id'],
            "parent_title": best_match['title'],
            "score": round(best_score, 2)
        })

# =========================================================
# SAVE PREVIEW
# =========================================================

df = pd.DataFrame(results)

df = df.sort_values(by="score", ascending=False)

df.to_excel("parent_match_preview.xlsx", index=False)

print()
print("===================================")
print("PREVIEW GENERATED")
print("parent_match_preview.xlsx")
print("===================================")
print()

print(df.head(30))

# =========================================================
# APPLY TO DATABASE?
# =========================================================

apply = input("\nApply changes to database? (y/n): ")

if apply.lower() == 'y':

    updated = 0

    for row in results:

        cursor.execute("""
            UPDATE games
            SET parent_game_id = %s
            WHERE game_id = %s
        """, (
            row['parent_id'],
            row['child_id']
        ))

        updated += 1

    conn.commit()

    print(f"\nDONE. Updated {updated} rows.")

else:

    print("\nCancelled. No database changes.")

cursor.close()
conn.close()