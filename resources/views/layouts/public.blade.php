<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProTech • Komponen & Periferal Komputer</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* CSS DeepSeek Khusus Publik */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #0f172a; line-height: 1.5; }
    .navbar { display: flex; align-items: center; justify-content: space-between; padding: 0 2.5rem; height: 72px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid #f1f5f9; position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    .logo { display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 1.5rem; letter-spacing: -0.5px; color: #0f172a; text-decoration: none; }
    .logo i { color: #3b82f6; font-size: 1.7rem; }
    .nav-links { display: flex; gap: 2.2rem; align-items: center; }
    .nav-links a { text-decoration: none; color: #334155; font-weight: 500; font-size: 0.95rem; transition: color 0.2s; position: relative; }
    .nav-links a:hover, .nav-links a.active { color: #0f172a; }
    .nav-links a.active::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 100%; height: 2px; background: #3b82f6; border-radius: 4px; }
    .nav-actions { display: flex; align-items: center; gap: 1.5rem; }
    .nav-actions i { font-size: 1.2rem; color: #475569; cursor: pointer; transition: color 0.2s; }
    .nav-actions i:hover { color: #0f172a; }
    .cart-badge { background: #0f172a; color: white; border-radius: 20px; padding: 0.2rem 0.6rem; font-size: 0.7rem; font-weight: 600; margin-left: -0.4rem; }
    .footer-note { text-align: center; color: #94a3b8; font-size: 0.8rem; padding: 1.5rem; border-top: 1px solid #f1f5f9; margin-top: 1rem; }
    @media (max-width: 700px) { .navbar { padding: 0 1.2rem; } .nav-links { display: none; } }
  </style>
  
  @stack('styles')
</head>
<body>

  <nav class="navbar">
    <a href="{{ route('home') }}" class="logo">
      <i class="fas fa-microchip"></i> KeyTech
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}" class="active">Home</a>
      <a href="#">Katalog</a>
      <a href="#">Kategori</a>
      <a href="{{ route('products.index') }}">Admin Panel</a> </div>
    <div class="nav-actions">
      <i class="fas fa-search"></i>
      <i class="fas fa-user"></i>
      <i class="fas fa-shopping-bag"></i>
      <span class="cart-badge">0</span>
    </div>
  </nav>

  @yield('content')

  <div class="footer-note">
    © ProTech — Pusat penyedia perangkat komputer, komponen PC, laptop, dan periferal.
  </div>

</body>
</html>