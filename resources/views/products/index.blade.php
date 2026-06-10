@extends('layouts.app')

@section('content')
<main class="main-content">
  <div class="page-header">
    <div class="page-title">
      <h1>Katalog Produk</h1>
      <p>Kelola data barang dan stok toko Anda</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Produk
    </a>
  </div>

  <div class="product-grid">
    
    @foreach ($products as $product)
    <div class="product-card">
      <div class="product-image">
        @if($product->image)
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
        @else
            <i class="fas fa-image" style="font-size: 3rem; color: #cbd5e1;"></i>
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
    </div>
    @endforeach

  </div>
</main>
@endsection