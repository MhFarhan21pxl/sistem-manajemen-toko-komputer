@extends('layouts.app')

@section('content')
<style>
  /* CSS Khusus Tombol Edit dan Hapus di dalam Card */
  .card-actions { 
      display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.8rem; border-top: 1px solid #f1f5f9; padding-top: 0.9rem; 
  }
  .btn-action { 
      display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; font-family: 'Inter', sans-serif;
  }
  .btn-edit { background: #eff6ff; color: #2563eb; }
  .btn-edit:hover { background: #dbeafe; }
  .btn-delete { background: #fef2f2; color: #dc2626; }
  .btn-delete:hover { background: #fee2e2; }

  /* ========== STYLE MODAL KONFIRMASI HAPUS ========== */
  .modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; display: none;
  }
  .delete-card {
    background: #ffffff; width: 100%; max-width: 400px; border-radius: 24px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; border: 1px solid #f1f5f9; animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes scaleUp { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  
  .warning-icon { font-size: 3.5rem; color: #ef4444; margin-bottom: 0.8rem; }
  .delete-card h3 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
  .delete-card p { font-size: 0.95rem; color: #475569; margin-bottom: 0.3rem; }
  .product-name-highlight { font-weight: 700; color: #dc2626; background: #fee2e2; padding: 0.1rem 0.5rem; border-radius: 6px; }
  
  .modal-actions { display: flex; gap: 1rem; margin-top: 1.8rem; }
  .btn-cancel { flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; }
  .btn-cancel:hover { background: #e2e8f0; }
  .btn-confirm-delete { flex: 1; background: #ef4444; color: white; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }
  .btn-confirm-delete:hover { background: #dc2626; }
</style>

<main class="main-content">
  <div class="page-header">
    <div class="page-title">
      <h1>Katalog Produk</h1>
      <p>Kelola data komponen dan periferal toko Anda</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Produk
    </a>
  </div>

  @if(session('success'))
    <div style="background: #dcfce7; color: #15803d; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; border: 1px solid #bbf7d0;">
      <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
  @endif

  <div class="product-grid">
    
    @foreach ($products as $product)
    <div class="product-card">
      <div class="product-image">
        @if($product->image)
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
        @else
            <i class="fas fa-box-open" style="font-size: 3rem; color: #cbd5e1;"></i>
        @endif
      </div>
      
      <div class="product-info">
        <span class="product-category">{{ $product->category->name }}</span>
        <div class="product-name">{{ $product->name }}</div>
        
        <div class="product-meta">
          <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
          <span class="stock-badge">
             <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Stok: {{ $product->stock }}
          </span>
        </div>
      </div>

      <div class="card-actions">
        <a href="{{ route('products.edit', $product->id) }}" class="btn-action btn-edit">
          <i class="fas fa-edit"></i> Edit
        </a>
        
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="margin: 0;" class="form-delete-{{ $product->id }}">
          @csrf
          @method('DELETE')
          <button type="button" class="btn-action btn-delete" onclick="openDeleteModal('{{ $product->id }}', '{{ addslashes($product->name) }}')">
            <i class="fas fa-trash-alt"></i> Hapus
          </button>
        </form>
      </div>

    </div>
    @endforeach

  </div>
</main>

<div class="modal-overlay" id="deleteModal">
  <div class="delete-card">
    <i class="fas fa-exclamation-triangle warning-icon"></i>
    <h3>Konfirmasi Hapus</h3>
    <p>Apakah Anda yakin ingin menghapus produk ini?</p>
    <p class="product-name-highlight" id="modalProductName">Nama Produk</p>
    <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 1rem;">*Tindakan ini tidak dapat dibatalkan dan data akan hilang permanen dari database.</p>
    
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
      <button class="btn-confirm-delete" id="confirmDeleteBtn">Ya, Hapus!</button>
    </div>
  </div>
</div>

<script>
  let formIdToSubmit = null;

  // Fungsi untuk membuka modal dan menyiapkan form mana yang akan disubmit
  function openDeleteModal(productId, productName) {
    document.getElementById('modalProductName').innerText = productName;
    formIdToSubmit = 'form-delete-' + productId;
    document.getElementById('deleteModal').style.display = 'flex';
  }

  // Fungsi menutup modal
  function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    formIdToSubmit = null;
  }

  // Fungsi eksekusi ketika tombol "Ya, Hapus!" ditekan
  document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (formIdToSubmit) {
      // Cari elemen form berdasarkan class spesifik, lalu submit form tersebut
      document.querySelector('.' + formIdToSubmit).submit();
    }
  });

  // Tutup modal jika user nge-klik area gelap di luar kartu
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeDeleteModal();
    }
  });
</script>
@endsection