<aside class="sidebar">
  <div class="sidebar-section-title">Menu Utama</div>
  
  <a href="{{ route('dashboard.index') }}" class="sidebar-item {{ Route::is('dashboard.*') ? 'active' : '' }}">
    <i class="fas fa-tachometer-alt"></i> Dashboard
  </a>

  <div class="sidebar-section-title">Master Data</div>
  <a href="{{ route('products.index') }}" class="sidebar-item {{ Route::is('products.*') ? 'active' : '' }}">
    <i class="fas fa-box-open"></i> Data Produk
  </a>
  <a href="#" class="sidebar-item">
    <i class="fas fa-tags"></i> Kategori Produk
  </a>

  <div class="sidebar-section-title">Transaksi</div>
  <a href="{{ route('pos.index') }}" class="sidebar-item {{ Route::is('pos.*') ? 'active' : '' }}">
    <i class="fas fa-desktop"></i> Kasir POS
  </a>
  <a href="{{ route('transactions.index') }}" class="sidebar-item {{ Route::is('transactions.*') ? 'active' : '' }}">
    <i class="fas fa-file-invoice-dollar"></i> Laporan Transaksi
  </a>
</aside>