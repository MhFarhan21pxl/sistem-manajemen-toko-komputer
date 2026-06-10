<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Manajemen Toko Komputer</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* CSS Bawaan DeepSeek yang sudah dirapikan */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; color: #1e293b; height: 100vh; display: flex; flex-direction: column; }
    .navbar { background-color: #0f172a; color: #f1f5f9; padding: 0 2rem; height: 68px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); flex-shrink: 0; }
    .logo-area { display: flex; align-items: center; gap: 0.75rem; }
    .logo-icon { font-size: 1.8rem; color: #38bdf8; }
    .logo-text { font-weight: 700; font-size: 1.4rem; color: white; }
    .logo-text span { font-weight: 500; color: #94a3b8; font-size: 0.9rem; margin-left: 0.3rem; }
    .admin-profile { display: flex; align-items: center; gap: 1.2rem; }
    .avatar { background-color: #334155; border-radius: 40px; padding: 0.4rem 0.9rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 500; font-size: 0.9rem; color: #e2e8f0; }
    .avatar i { color: #38bdf8; font-size: 1.1rem; }
    
    .dashboard-container { display: flex; flex: 1; overflow: hidden; }
    .sidebar { width: 240px; background-color: #ffffff; border-right: 1px solid #e9eef2; padding: 1.8rem 1.2rem; display: flex; flex-direction: column; gap: 0.3rem; flex-shrink: 0; }
    .sidebar-item { display: flex; align-items: center; gap: 0.9rem; padding: 0.7rem 1rem; border-radius: 10px; color: #475569; font-weight: 500; font-size: 0.95rem; text-decoration: none; transition: all 0.2s; }
    .sidebar-item i { width: 20px; font-size: 1.1rem; text-align: center; }
    .sidebar-item.active { background-color: #f1f5f9; color: #0f172a; font-weight: 600; }
    .sidebar-item:hover:not(.active) { background-color: #f8fafc; color: #0f172a; }
    .sidebar-section-title { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin: 1.2rem 0 0.2rem 1rem; }
    
    .main-content { flex: 1; padding: 1.8rem 2rem; overflow-y: auto; background-color: #f4f6f9; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title h1 { font-weight: 700; font-size: 1.8rem; color: #0f172a; }
    .page-title p { color: #64748b; font-size: 0.9rem; margin-top: 0.2rem; font-weight: 500; }
    .btn-primary { background-color: #0f172a; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; text-decoration: none; }
    .btn-primary:hover { background-color: #1e293b; color: white; }
    
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.8rem; }
    .product-card { background: #ffffff; border-radius: 20px; padding: 1.3rem; box-shadow: 0 8px 20px rgba(0,0,0,0.04); display: flex; flex-direction: column; border: 1px solid #f1f5f9; }
    .product-image { background: #f8fafc; border-radius: 16px; padding: 1rem; height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.2rem; overflow: hidden; }
    .product-image img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .product-category { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #38bdf8; background: #f0f9ff; padding: 0.2rem 0.7rem; border-radius: 20px; align-self: flex-start; }
    .product-name { font-weight: 700; font-size: 1.2rem; color: #0f172a; margin: 0.5rem 0; }
    .product-meta { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    .price { font-weight: 700; font-size: 1.3rem; color: #0f172a; }
    .stock-badge { font-weight: 600; font-size: 0.8rem; padding: 0.3rem 0.7rem; border-radius: 30px; background: #dcfce7; color: #15803d; }
  </style>
</head>
<body>

  @include('components.navbar')

  <div class="dashboard-container">
    @include('components.sidebar')

    @yield('content')
  </div>

</body>
</html>