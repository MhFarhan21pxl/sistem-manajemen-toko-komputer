@extends('layouts.public')

@push('styles')
<style>
  .hero { display: flex; align-items: center; justify-content: space-between; padding: 3rem 3rem 3rem 4rem; background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); margin: 1.2rem 2rem 2rem 2rem; border-radius: 32px; min-height: 380px; gap: 2rem; flex-wrap: wrap; }
  .hero-content { flex: 1 1 320px; }
  .hero-badge { background: #dbeafe; color: #1e40af; font-weight: 600; font-size: 0.8rem; padding: 0.3rem 1rem; border-radius: 30px; display: inline-block; margin-bottom: 1.2rem; }
  .hero h1 { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 2.8rem; line-height: 1.2; letter-spacing: -0.8px; color: #0f172a; margin-bottom: 0.8rem; }
  .hero-highlight { color: #2563eb; }
  .hero p { color: #475569; font-size: 1.1rem; max-width: 450px; margin-bottom: 2rem; font-weight: 500; }
  .hero-btn { background: #0f172a; color: white; border: none; padding: 0.8rem 2rem; border-radius: 14px; font-weight: 600; font-size: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: background 0.2s; box-shadow: 0 8px 18px rgba(15,23,42,0.15); }
  .hero-image { flex: 1 1 300px; display: flex; justify-content: center; align-items: center; background: #ffffff; border-radius: 28px; padding: 1.8rem; box-shadow: 0 20px 35px rgba(0,0,0,0.05); border: 1px solid #e9eef2; }
  .products-section { padding: 1.5rem 2.5rem 4rem; }
  .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
  .section-header h2 { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.9rem; color: #0f172a; }
  .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.8rem; }
  .product-card { background: white; border-radius: 24px; padding: 1.2rem 1.1rem 1.4rem; transition: all 0.25s ease; border: 1px solid #f1f5f9; box-shadow: 0 5px 15px rgba(0,0,0,0.02); display: flex; flex-direction: column; }
  .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px rgba(0,0,0,0.06); border-color: #e2e8f0; }
  .product-img { background: #f8fafc; border-radius: 18px; aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative; }
  .product-img img { width: 100%; height: 100%; object-fit: cover; }
  .product-category { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #6366f1; margin-bottom: 0.3rem; }
  .product-name { font-weight: 700; font-size: 1.1rem; color: #0f172a; margin-bottom: 0.2rem; }
  .product-price { font-weight: 700; font-size: 1.2rem; margin-top: 0.5rem; }
  .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 0.8rem; }
  .rating { color: #f59e0b; font-size: 0.8rem; }
  .add-cart { background: #f1f5f9; border: none; border-radius: 12px; padding: 0.5rem 0.9rem; font-weight: 600; font-size: 0.8rem; color: #0f172a; cursor: pointer; display: flex; align-items: center; gap: 0.3rem; transition: background 0.2s; }
  .add-cart:hover { background: #e2e8f0; }
  @media (max-width: 700px) { .hero { margin: 0.8rem 1rem; padding: 2rem 1.5rem; flex-direction: column; } .products-section { padding: 1rem 1.2rem; } }
</style>
@endpush

@section('content')
  <section class="hero">
    <div class="hero-content">
      <span class="hero-badge">✦ Terlengkap & Terpercaya</span>
      <h1>Pusat <span class="hero-highlight">Komponen PC & Periferal</span> Premium</h1>
      <p>Tingkatkan performa *setup* Anda. Dari rakitan PC *high-end*, laptop produktivitas, hingga *keyboard mechanical* kelas atas.</p>
      <button class="hero-btn">
        <i class="fas fa-arrow-right"></i> Belanja Sekarang
      </button>
    </div>
    <div class="hero-image">
      <img src="https://images.unsplash.com/photo-1587202372634-32705e3bf49c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="PC Build Setup" style="border-radius: 15px; width: 100%;">
    </div>
  </section>

  <div class="products-section">
    <div class="section-header">
      <h2>Katalog Etalase</h2>
      <a href="#" style="color: #2563eb; font-weight: 600; text-decoration: none;">Lihat Semua <i class="fas fa-chevron-right"></i></a>
    </div>

    <div class="product-grid">
      @forelse($products as $product)
      <div class="product-card">
        <div class="product-img">
          @if($product->image)
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
          @else
            <i class="fas fa-box-open" style="font-size: 3rem; color: #94a3b8;"></i>
          @endif
        </div>
        <span class="product-category">{{ $product->category->name }}</span>
        <div class="product-name">{{ $product->name }}</div>
        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        
        <div class="card-footer">
          <span class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i> 4.8</span>
          <button class="add-cart"><i class="fas fa-cart-plus"></i> Beli</button>
        </div>
      </div>
      @empty
      <div style="grid-column: 1 / -1; text-align: center; color: #64748b; padding: 2rem;">
        Belum ada produk di etalase. Tambahkan produk melalui panel Admin.
      </div>
      @endforelse
    </div>
  </div>
@endsection
