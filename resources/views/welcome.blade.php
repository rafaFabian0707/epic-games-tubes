<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Epic Games Store</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --eg-bg: #121212;
      --eg-surface: #1c1c1c;
      --eg-card: #202020;
      --eg-border: #2a2a2a;
      --eg-text: #f0f0f0;
      --eg-muted: #8a8a8a;
      --eg-accent: #0078f2;
      --eg-accent2: #00d4ff;
      --eg-green: #2de370;
      --eg-yellow: #f5c518;
    }

    body {
      background: var(--eg-bg);
      color: var(--eg-text);
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
    }

    /* NAV */
    .nav {
      background: rgba(18, 18, 18, 0.95);
      border-bottom: 1px solid var(--eg-border);
      padding: 0 24px;
      display: flex;
      align-items: center;
      gap: 24px;
      height: 52px;
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(8px);
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .nav-logo svg {
      width: 28px;
      height: 28px;
    }

    .nav-logo span {
      font-weight: 800;
      font-size: 16px;
      letter-spacing: -0.3px;
    }

    .nav-links {
      display: flex;
      gap: 20px;
      margin-left: 8px;
    }

    .nav-link {
      font-size: 13px;
      color: var(--eg-muted);
      text-decoration: none;
      transition: color 0.2s;
      cursor: pointer;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--eg-text);
    }

    .nav-right {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-search {
      background: var(--eg-surface);
      border: 1px solid var(--eg-border);
      border-radius: 4px;
      padding: 6px 12px;
      font-size: 13px;
      color: var(--eg-text);
      outline: none;
      width: 180px;
      font-family: inherit;
    }

    .nav-search:focus {
      border-color: var(--eg-accent);
    }

    .nav-btn {
      background: white;
      color: #000;
      border: none;
      border-radius: 4px;
      padding: 7px 16px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      letter-spacing: 0.5px;
      font-family: inherit;
      transition: opacity 0.2s;
    }

    .nav-btn:hover {
      opacity: 0.85;
    }

    .nav-btn.outline {
      background: transparent;
      color: var(--eg-text);
      border: 1px solid var(--eg-border);
    }

    .nav-btn.outline:hover {
      border-color: #555;
      background: rgba(255, 255, 255, 0.05);
    }

    /* HERO */
    .hero {
      position: relative;
      height: 460px;
      overflow: hidden;
    }

    .hero-slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      transition: opacity 0.7s ease;
      pointer-events: none;
    }

    .hero-slide.active {
      opacity: 1;
      pointer-events: auto;
    }

    .hero-bg {
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
    }

    .hero-art {
      position: absolute;
      right: 0;
      top: 0;
      width: 55%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .hero-art-text {
      text-align: center;
      opacity: 0.45;
    }

    .hero-gradient {
      position: absolute;
      inset: 0;
      background: linear-gradient(to right, rgba(18, 18, 18, 0.92) 30%, rgba(18, 18, 18, 0.2) 100%);
    }

    .hero-content {
      position: absolute;
      bottom: 60px;
      left: 48px;
      max-width: 420px;
    }

    .hero-tag {
      background: var(--eg-accent);
      color: white;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 3px 10px;
      border-radius: 2px;
      display: inline-block;
      margin-bottom: 12px;
    }

    .hero-title {
      font-size: 38px;
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 10px;
      letter-spacing: -0.5px;
    }

    .hero-desc {
      font-size: 14px;
      color: var(--eg-muted);
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .hero-price-wrap {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
    }

    .hero-free {
      background: var(--eg-green);
      color: #000;
      font-weight: 800;
      font-size: 13px;
      padding: 4px 10px;
      border-radius: 3px;
    }

    .price-orig {
      text-decoration: line-through;
      color: var(--eg-muted);
    }

    .price-now {
      font-weight: 800;
    }

    .price-disc {
      background: var(--eg-accent);
      font-size: 10px;
      font-weight: 700;
      color: white;
      padding: 2px 6px;
      border-radius: 2px;
    }

    .hero-btn {
      background: white;
      color: #000;
      border: none;
      border-radius: 4px;
      padding: 11px 28px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      letter-spacing: 0.5px;
      font-family: inherit;
      transition: transform 0.15s, opacity 0.15s;
    }

    .hero-btn:hover {
      transform: scale(1.03);
      opacity: 0.9;
    }

    .hero-dots {
      position: absolute;
      bottom: 22px;
      left: 48px;
      display: flex;
      gap: 8px;
    }

    .dot {
      width: 28px;
      height: 3px;
      background: rgba(255, 255, 255, 0.25);
      border-radius: 2px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .dot.active {
      background: white;
    }

    .hero-thumbs {
      position: absolute;
      bottom: 0;
      right: 0;
      display: flex;
      gap: 8px;
      padding: 16px 24px;
    }

    .hero-thumb {
      width: 100px;
      height: 60px;
      border-radius: 4px;
      overflow: hidden;
      cursor: pointer;
      opacity: 0.6;
      border: 2px solid transparent;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .hero-thumb.active {
      opacity: 1;
      border-color: white;
    }

    .hero-thumb-inner {
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 9px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.5);
    }

    /* TABS */
    .tabs {
      display: flex;
      border-bottom: 1px solid var(--eg-border);
      padding: 0 24px;
      gap: 2px;
      overflow-x: auto;
    }

    .tabs::-webkit-scrollbar {
      display: none;
    }

    .tab {
      font-size: 13px;
      font-weight: 600;
      color: var(--eg-muted);
      padding: 12px 16px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
      white-space: nowrap;
      font-family: inherit;
      background: none;
      border-top: none;
      border-left: none;
      border-right: none;
    }

    .tab:hover {
      color: var(--eg-text);
    }

    .tab.active {
      color: white;
      border-bottom-color: white;
    }

    /* MAIN LAYOUT */
    .main-content {
      display: grid;
      grid-template-columns: 1fr 280px;
    }

    .content-left {
      border-right: 1px solid var(--eg-border);
      min-width: 0;
    }

    .sidebar {
      padding: 24px 20px;
      min-width: 0;
    }

    /* SECTIONS */
    .section {
      padding: 32px 24px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
    }

    .see-all {
      font-size: 13px;
      color: var(--eg-accent2);
      cursor: pointer;
      text-decoration: none;
      transition: opacity 0.2s;
    }

    .see-all:hover {
      opacity: 0.75;
    }

    /* GAME CARDS */
    .games-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 12px;
    }

    .game-card {
      background: var(--eg-card);
      border-radius: 6px;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s, border-color 0.2s;
      border: 1px solid var(--eg-border);
    }

    .game-card:hover {
      transform: translateY(-3px);
      border-color: #444;
    }

    .game-thumb {
      height: 130px;
      background-size: cover;
      background-position: center;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .thumb-label {
      font-size: 22px;
      font-weight: 900;
      color: rgba(255, 255, 255, 0.2);
    }

    .game-badge {
      position: absolute;
      top: 8px;
      left: 8px;
      font-size: 10px;
      font-weight: 700;
      color: white;
      padding: 2px 7px;
      border-radius: 2px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .badge-free {
      background: var(--eg-green);
      color: #000;
    }

    .badge-sale {
      background: #d44444;
    }

    .game-info {
      padding: 10px 12px;
    }

    .game-name {
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 4px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .game-sub {
      font-size: 11px;
      color: var(--eg-muted);
      margin-bottom: 8px;
    }

    .game-price-row {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .price-free-tag {
      font-size: 13px;
      font-weight: 700;
      color: var(--eg-green);
    }

    .price-orig-sm {
      font-size: 11px;
      color: var(--eg-muted);
      text-decoration: line-through;
    }

    .price-cur {
      font-size: 13px;
      font-weight: 700;
    }

    /* PROMO BANNER */
    .promo-banner {
      margin: 0 24px 24px;
      border-radius: 8px;
      padding: 20px 24px;
      background: linear-gradient(135deg, #0a3d7c 0%, #0c2a6e 100%);
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid #1a4a9a;
      gap: 16px;
    }

    .promo-text h3 {
      font-size: 17px;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .promo-text p {
      font-size: 13px;
      color: #89b4f0;
    }

    .promo-cta {
      background: white;
      color: #000;
      border: none;
      border-radius: 4px;
      padding: 9px 20px;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
      white-space: nowrap;
      font-family: inherit;
      flex-shrink: 0;
      transition: opacity 0.2s;
    }

    .promo-cta:hover {
      opacity: 0.85;
    }

    /* COLLECTIONS */
    .collections-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }

    .coll-card {
      border-radius: 6px;
      overflow: hidden;
      position: relative;
      height: 80px;
      cursor: pointer;
    }

    .coll-card:hover .coll-overlay {
      background: rgba(0, 0, 0, 0.35);
    }

    .coll-bg {
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
    }

    .coll-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: flex-end;
      padding: 8px;
      transition: background 0.2s;
    }

    .coll-name {
      font-size: 12px;
      font-weight: 700;
      line-height: 1.2;
    }

    /* SIDEBAR */
    .sidebar-section {
      margin-bottom: 28px;
    }

    .sidebar-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--eg-muted);
      margin-bottom: 12px;
    }

    .filter-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: var(--eg-muted);
      cursor: pointer;
      padding: 4px 0;
      transition: color 0.2s;
      user-select: none;
    }

    .filter-item:hover {
      color: var(--eg-text);
    }

    .filter-item.active {
      color: var(--eg-text);
    }

    .filter-dot {
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: var(--eg-accent);
      opacity: 0;
      flex-shrink: 0;
    }

    .filter-item.active .filter-dot {
      opacity: 1;
    }

    .filter-cb {
      width: 14px;
      height: 14px;
      border: 1px solid var(--eg-border);
      border-radius: 3px;
      background: var(--eg-surface);
      flex-shrink: 0;
      transition: background 0.2s, border-color 0.2s;
    }

    .filter-cb.checked {
      background: var(--eg-accent);
      border-color: var(--eg-accent);
    }

    /* UPCOMING */
    .upcoming-card {
      background: var(--eg-card);
      border-radius: 8px;
      border: 1px solid var(--eg-border);
      padding: 14px;
      margin-top: 4px;
    }

    .upcoming-item {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .upcoming-thumb {
      width: 48px;
      height: 36px;
      border-radius: 4px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 8px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.4);
    }

    .upcoming-title {
      font-size: 12px;
      font-weight: 600;
    }

    .upcoming-date {
      font-size: 11px;
      color: var(--eg-muted);
    }

    /* FOOTER */
    .footer {
      border-top: 1px solid var(--eg-border);
      padding: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: var(--eg-muted);
    }

    .footer-links {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .footer-link {
      font-size: 12px;
      color: var(--eg-muted);
      cursor: pointer;
      transition: color 0.2s;
    }

    .footer-link:hover {
      color: var(--eg-text);
    }

    /* SCROLLBAR */
    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--eg-bg);
    }

    ::-webkit-scrollbar-thumb {
      background: #333;
      border-radius: 3px;
    }

    @media (max-width: 768px) {
      .main-content {
        grid-template-columns: 1fr;
      }

      .sidebar {
        display: none;
      }

      .hero-thumbs {
        display: none;
      }

      .hero-content {
        left: 24px;
      }

      .collections-row {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>

<body>
  <!-- NAV -->
  <nav class="nav">
    <div class="nav-logo">
      <svg viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0h24v18.5L12 28 0 18.5V0z" fill="white" />
        <path d="M4 4h16v12.5L12 23 4 16.5V4z" fill="#121212" />
        <path d="M7 8h10v3H7V8zm0 4.5h7v3H7v-3z" fill="white" />
      </svg>
      <span>Epic Games</span>
    </div>
    <div class="nav-links">
      <a class="nav-link active">Toko</a>
      <a class="nav-link">Perpustakaan</a>
      <a class="nav-link">Unreal Engine</a>
      <a class="nav-link">Fab</a>
      <a class="nav-link">Berita</a>
    </div>
    <div class="nav-right">
      <input class="nav-search" placeholder="Cari di toko..." type="text" />
      <button class="nav-btn outline">Masuk</button>
      <button class="nav-btn">Daftar</button>
    </div>
  </nav>

  <!-- HERO SLIDER -->
  <div class="hero" id="hero">
    <!-- Slide 0: Fortnite -->
    <div class="hero-slide active" id="slide-0">
      <div class="hero-bg" style="background: linear-gradient(135deg,#0d1b2a 0%,#1a3a5c 60%,#0f2a45 100%);"></div>
      <div class="hero-art">
        <div class="hero-art-text">
          <div
            style="font-size:80px;font-weight:900;color:#fff;letter-spacing:-4px;text-transform:uppercase;line-height:1;">
            FORTNITE</div>
          <div style="font-size:18px;color:#89b4f0;letter-spacing:4px;">CHAPTER 6</div>
        </div>
      </div>
      <div class="hero-gradient"></div>
      <div class="hero-content">
        <span class="hero-tag">Gratis Sekarang</span>
        <h1 class="hero-title">Fortnite</h1>
        <p class="hero-desc">Bergabunglah dengan jutaan pemain di battle royale terpopuler di dunia. Bangun, bertarung,
          dan raih kemenangan.</p>
        <div class="hero-price-wrap">
          <span class="hero-free">GRATIS</span>
        </div>
        <button class="hero-btn">Dapatkan Sekarang</button>
      </div>
    </div>

    <!-- Slide 1: Hogwarts Legacy -->
    <div class="hero-slide" id="slide-1">
      <div class="hero-bg" style="background:linear-gradient(135deg,#1a0a2e 0%,#3d1a6e 60%,#1a0a2e 100%);"></div>
      <div class="hero-art">
        <div class="hero-art-text">
          <div style="font-size:64px;font-weight:900;color:#c084fc;letter-spacing:-2px;line-height:1;">HOGWARTS</div>
          <div style="font-size:64px;font-weight:900;color:#a855f7;letter-spacing:-2px;line-height:1;">LEGACY</div>
        </div>
      </div>
      <div class="hero-gradient"></div>
      <div class="hero-content">
        <span class="hero-tag" style="background:#7c3aed;">RPG</span>
        <h1 class="hero-title">Hogwarts Legacy</h1>
        <p class="hero-desc">Jelajahi dunia sihir di abad ke-1800. Jadilah penyihir yang kamu inginkan dalam kisah
          petualangan epik.</p>
        <div class="hero-price-wrap">
          <span class="price-disc" style="font-size:13px;padding:4px 10px;">-50%</span>
          <span class="price-orig" style="font-size:16px;color:var(--eg-muted);">Rp 699.999</span>
          <span class="price-now" style="font-size:20px;">Rp 349.999</span>
        </div>
        <button class="hero-btn">Beli Sekarang</button>
      </div>
    </div>

    <!-- Slide 2: Alan Wake 2 -->
    <div class="hero-slide" id="slide-2">
      <div class="hero-bg" style="background:linear-gradient(135deg,#0a1f0a 0%,#1a4a1a 60%,#0a2a0a 100%);"></div>
      <div class="hero-art">
        <div class="hero-art-text">
          <div style="font-size:72px;font-weight:900;color:#4ade80;letter-spacing:-3px;line-height:1;">ALAN</div>
          <div style="font-size:72px;font-weight:900;color:#22c55e;letter-spacing:-3px;line-height:1;">WAKE 2</div>
        </div>
      </div>
      <div class="hero-gradient"></div>
      <div class="hero-content">
        <span class="hero-tag" style="background:#166534;">Action</span>
        <h1 class="hero-title">Alan Wake 2</h1>
        <p class="hero-desc">Selamatkan dirimu dari kegelapan di sequel thriller aksi paling mendebarkan tahun ini dari
          Remedy Entertainment.</p>
        <div class="hero-price-wrap">
          <span class="price-now" style="font-size:20px;">Rp 499.999</span>
        </div>
        <button class="hero-btn">Lihat Detail</button>
      </div>
    </div>

    <div class="hero-dots">
      <div class="dot active" onclick="goSlide(0)"></div>
      <div class="dot" onclick="goSlide(1)"></div>
      <div class="dot" onclick="goSlide(2)"></div>
    </div>

    <div class="hero-thumbs">
      <div class="hero-thumb active" onclick="goSlide(0)">
        <div class="hero-thumb-inner" style="background:linear-gradient(135deg,#0d1b2a,#1a3a5c);">FORTNITE</div>
      </div>
      <div class="hero-thumb" onclick="goSlide(1)">
        <div class="hero-thumb-inner" style="background:linear-gradient(135deg,#1a0a2e,#3d1a6e);">HOGWARTS</div>
      </div>
      <div class="hero-thumb" onclick="goSlide(2)">
        <div class="hero-thumb-inner" style="background:linear-gradient(135deg,#0a1f0a,#1a4a1a);">ALAN WAKE</div>
      </div>
    </div>
  </div>

  <!-- TABS -->
  <div class="tabs">
    <button class="tab active" onclick="setTab(this)">Unggulan</button>
    <button class="tab" onclick="setTab(this)">Game Gratis</button>
    <button class="tab" onclick="setTab(this)">Terlaris</button>
    <button class="tab" onclick="setTab(this)">Terbaru</button>
    <button class="tab" onclick="setTab(this)">Akan Datang</button>
    <button class="tab" onclick="setTab(this)">Add-ons</button>
    <button class="tab" onclick="setTab(this)">Early Access</button>
  </div>

  <div class="main-content">
    <!-- LEFT CONTENT -->
    <div class="content-left">

      <!-- FREE GAMES -->
      <div class="section">
        <div class="section-header">
          <span class="section-title">🎁 Game Gratis Minggu Ini</span>
          <a class="see-all" href="#">Lihat Semua →</a>
        </div>
        <div class="games-grid">
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#7c2d12,#c2410c);">
              <span class="game-badge badge-free">Gratis</span>
              <span class="thumb-label">SIFU</span>
            </div>
            <div class="game-info">
              <div class="game-name">Sifu</div>
              <div class="game-sub">Sloclap · Action</div>
              <div class="game-price-row">
                <span class="price-free-tag">Gratis</span>
                <span class="price-orig-sm">Rp 299.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#0f172a,#1e3a5f);">
              <span class="game-badge badge-free">Gratis</span>
              <span class="thumb-label">METRO</span>
            </div>
            <div class="game-info">
              <div class="game-name">Metro Exodus</div>
              <div class="game-sub">4A Games · Shooter</div>
              <div class="game-price-row">
                <span class="price-free-tag">Gratis</span>
                <span class="price-orig-sm">Rp 249.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#064e3b,#059669);">
              <span class="game-badge badge-free">Gratis</span>
              <span class="thumb-label">SLIME</span>
            </div>
            <div class="game-info">
              <div class="game-name">Slime Rancher 2</div>
              <div class="game-sub">Monomi Park · Sim</div>
              <div class="game-price-row">
                <span class="price-free-tag">Gratis</span>
                <span class="price-orig-sm">Rp 199.999</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SALES -->
      <div class="section" style="padding-top:0;">
        <div class="section-header">
          <span class="section-title">🔥 Penawaran Terbaik</span>
          <a class="see-all" href="#">Lihat Semua →</a>
        </div>
        <div class="games-grid">
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
              <span class="game-badge badge-sale">-75%</span>
              <span class="thumb-label">CP2077</span>
            </div>
            <div class="game-info">
              <div class="game-name">Cyberpunk 2077</div>
              <div class="game-sub">CD Projekt Red · RPG</div>
              <div class="game-price-row">
                <span class="price-disc">-75%</span>
                <span class="price-cur">Rp 149.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#292524,#78350f);">
              <span class="game-badge badge-sale">-60%</span>
              <span class="thumb-label">ELDEN</span>
            </div>
            <div class="game-info">
              <div class="game-name">Elden Ring</div>
              <div class="game-sub">FromSoftware · Action</div>
              <div class="game-price-row">
                <span class="price-disc">-60%</span>
                <span class="price-cur">Rp 279.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#0c1a35,#1d3461);">
              <span class="game-badge badge-sale">-40%</span>
              <span class="thumb-label" style="font-size:16px;">RDR2</span>
            </div>
            <div class="game-info">
              <div class="game-name">Red Dead Redemption 2</div>
              <div class="game-sub">Rockstar · Open World</div>
              <div class="game-price-row">
                <span class="price-disc">-40%</span>
                <span class="price-cur">Rp 299.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#3b0764,#6d28d9);">
              <span class="game-badge badge-sale">-50%</span>
              <span class="thumb-label" style="font-size:16px;">BG3</span>
            </div>
            <div class="game-info">
              <div class="game-name">Baldur's Gate 3</div>
              <div class="game-sub">Larian Studios · RPG</div>
              <div class="game-price-row">
                <span class="price-disc">-50%</span>
                <span class="price-cur">Rp 399.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#0f172a,#0369a1);">
              <span class="game-badge badge-sale">-30%</span>
              <span class="thumb-label" style="font-size:16px;">GTA V</span>
            </div>
            <div class="game-info">
              <div class="game-name">Grand Theft Auto V</div>
              <div class="game-sub">Rockstar · Open World</div>
              <div class="game-price-row">
                <span class="price-disc">-30%</span>
                <span class="price-cur">Rp 139.999</span>
              </div>
            </div>
          </div>
          <div class="game-card">
            <div class="game-thumb" style="background:linear-gradient(135deg,#1c0a0a,#7f1d1d);">
              <span class="game-badge badge-sale">-35%</span>
              <span class="thumb-label" style="font-size:16px;">DEAD SPACE</span>
            </div>
            <div class="game-info">
              <div class="game-name">Dead Space</div>
              <div class="game-sub">Motive Studio · Horror</div>
              <div class="game-price-row">
                <span class="price-disc">-35%</span>
                <span class="price-cur">Rp 324.999</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PROMO BANNER -->
      <div class="promo-banner">
        <div class="promo-text">
          <h3>Epic Games Reward</h3>
          <p>Kumpulkan poin dari setiap pembelian dan tukar dengan diskon eksklusif!</p>
        </div>
        <button class="promo-cta">Pelajari Lebih Lanjut</button>
      </div>

      <!-- COLLECTIONS -->
      <div class="section">
        <div class="section-header">
          <span class="section-title">Koleksi Pilihan</span>
          <a class="see-all" href="#">Lihat Semua →</a>
        </div>
        <div class="collections-row">
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#134e4a,#0f766e);"></div>
            <div class="coll-overlay"><span class="coll-name">Game Open World</span></div>
          </div>
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#3b0764,#7e22ce);"></div>
            <div class="coll-overlay"><span class="coll-name">RPG Terbaik</span></div>
          </div>
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#7f1d1d,#dc2626);"></div>
            <div class="coll-overlay"><span class="coll-name">Action & Petualangan</span></div>
          </div>
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#0c1a35,#1e40af);"></div>
            <div class="coll-overlay"><span class="coll-name">Multiplayer Online</span></div>
          </div>
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#1a2e05,#365314);"></div>
            <div class="coll-overlay"><span class="coll-name">Indie Gems</span></div>
          </div>
          <div class="coll-card">
            <div class="coll-bg" style="background:linear-gradient(135deg,#2d1b00,#92400e);"></div>
            <div class="coll-overlay"><span class="coll-name">Simulasi & Strategi</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar">
      <div class="sidebar-label" style="margin-bottom:16px;">Filter</div>

      <div class="sidebar-section">
        <div class="sidebar-label">Genre</div>
        <div class="filter-item active" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Semua
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Action
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>RPG
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Adventure
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Strategi
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Simulasi
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Horror
        </div>
        <div class="filter-item" onclick="setFilter(this, 'genre')">
          <div class="filter-dot"></div>Sports
        </div>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-label">Harga</div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb checked"></div>Gratis
        </div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb"></div>Di Bawah Rp 100K
        </div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb"></div>Rp 100K – 300K
        </div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb"></div>Rp 300K – 500K
        </div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb"></div>Di Atas Rp 500K
        </div>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-label">Platform</div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb checked"></div>Windows
        </div>
        <div class="filter-item" onclick="toggleCb(this)">
          <div class="filter-cb"></div>Mac
        </div>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-label">Rating Usia</div>
        <div class="filter-item active" onclick="setFilter(this, 'rating')">
          <div class="filter-dot"></div>Semua Rating
        </div>
        <div class="filter-item" onclick="setFilter(this, 'rating')">
          <div class="filter-dot"></div>Semua Umur
        </div>
        <div class="filter-item" onclick="setFilter(this, 'rating')">
          <div class="filter-dot"></div>Remaja
        </div>
        <div class="filter-item" onclick="setFilter(this, 'rating')">
          <div class="filter-dot"></div>Dewasa
        </div>
      </div>

      <!-- UPCOMING FREE -->
      <div class="upcoming-card">
        <div class="sidebar-label" style="margin-bottom:12px;">Gratis Berikutnya</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <div class="upcoming-item">
            <div class="upcoming-thumb" style="background:linear-gradient(135deg,#1e3a5f,#2563eb);">GAME</div>
            <div>
              <div class="upcoming-title">The Callisto Protocol</div>
              <div class="upcoming-date">01 Mei – 08 Mei</div>
            </div>
          </div>
          <div class="upcoming-item">
            <div class="upcoming-thumb" style="background:linear-gradient(135deg,#1a0a2e,#6d28d9);">GAME</div>
            <div>
              <div class="upcoming-title">A Plague Tale: Requiem</div>
              <div class="upcoming-date">08 Mei – 15 Mei</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-logo">
      <svg viewBox="0 0 24 28" fill="none" width="20" height="20">
        <path d="M0 0h24v18.5L12 28 0 18.5V0z" fill="white" />
        <path d="M4 4h16v12.5L12 23 4 16.5V4z" fill="#121212" />
        <path d="M7 8h10v3H7V8zm0 4.5h7v3H7v-3z" fill="white" />
      </svg>
      <span>© 2026 Epic Games, Inc. Semua hak dilindungi.</span>
    </div>
    <div class="footer-links">
      <span class="footer-link">Kebijakan Privasi</span>
      <span class="footer-link">Syarat Layanan</span>
      <span class="footer-link">Aksesibilitas</span>
      <span class="footer-link">Bantuan</span>
      <span class="footer-link">Karir</span>
    </div>
  </footer>

  <script>
    // HERO SLIDER
    let cur = 0;
    let timer = setInterval(() => goSlide((cur + 1) % 3), 5000);

    function goSlide(n) {
      document.querySelectorAll('.hero-slide').forEach((s, i) => s.classList.toggle('active', i === n));
      document.querySelectorAll('.dot').forEach((d, i) => d.classList.toggle('active', i === n));
      document.querySelectorAll('.hero-thumb').forEach((t, i) => t.classList.toggle('active', i === n));
      cur = n;
      clearInterval(timer);
      timer = setInterval(() => goSlide((cur + 1) % 3), 5000);
    }

    // TABS
    function setTab(el) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      el.classList.add('active');
    }

    // FILTER - radio style (per group via data attribute)
    function setFilter(el, group) {
      el.closest('.sidebar-section').querySelectorAll('.filter-item').forEach(f => f.classList.remove('active'));
      el.classList.add('active');
    }

    // CHECKBOX TOGGLE
    function toggleCb(el) {
      const cb = el.querySelector('.filter-cb');
      if (cb) cb.classList.toggle('checked');
    }
  </script>
</body>

</html>