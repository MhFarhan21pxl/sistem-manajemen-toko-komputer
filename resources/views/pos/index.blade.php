@extends('layouts.app')

@section('content')
<style>
  .pos-wrapper {
    display: flex; width: 100%; height: calc(100vh - 130px); background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; position: relative;
  }

  /* KIRI: Grid Produk & Kategori */
  .product-section { flex: 1 1 65%; display: flex; flex-direction: column; background: #f8fafc; padding: 1.5rem; overflow-y: auto; }
  .pos-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
  .pos-header h2 { font-weight: 700; font-size: 1.3rem; color: #0f172a; }
  .search-bar { display: flex; align-items: center; background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.5rem 1rem; gap: 0.5rem; width: 250px; }
  .search-bar input { border: none; outline: none; background: transparent; font-size: 0.9rem; width: 100%; font-weight: 500; }

  .category-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.2rem; flex-wrap: wrap; }
  .cat-tab { padding: 0.4rem 1.1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: white; border: 1px solid #e2e8f0; color: #475569; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;}
  .cat-tab.active { background: #0f172a; color: white; border-color: #0f172a; }
  .cat-tab:hover:not(.active) { background: #f1f5f9; }

  .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; flex: 1; align-content: start; }
  .product-card { background: white; border-radius: 14px; padding: 1rem 0.8rem; border: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: center; text-align: center; transition: all 0.2s; cursor: pointer; }
  .product-card:hover { border-color: #cbd5e1; transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
  .product-img { background: #f1f5f9; border-radius: 10px; width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; margin-bottom: 0.7rem; font-size: 2.2rem; color: #94a3b8; overflow: hidden; }
  .product-img img { width: 100%; height: 100%; object-fit: cover; }
  .product-name { font-weight: 700; font-size: 0.85rem; color: #0f172a; margin-bottom: 0.2rem; line-height: 1.2; height: 30px; display: flex; align-items: center; }
  .product-price { font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 0.3rem; }
  .stock-info { font-size: 0.7rem; color: #64748b; font-weight: 600; background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 20px;}

  /* KANAN: Keranjang Kasir */
  .cart-section { flex: 0 0 350px; background: #ffffff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; padding: 1.5rem; }
  .cart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;}
  .cart-header h3 { font-weight: 700; font-size: 1.1rem; color: #0f172a; }
  .clear-cart { color: #ef4444; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: none; border: none; }
  
  .cart-items { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1rem; padding-right: 0.3rem; }
  .cart-item { display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border-radius: 10px; padding: 0.6rem 0.8rem; border: 1px solid #f1f5f9;}
  .item-info { display: flex; flex-direction: column; width: 45%; }
  .item-name { font-weight: 700; font-size: 0.8rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .item-price { font-size: 0.75rem; color: #64748b; font-weight: 500; }
  
  .qty-control { display: flex; align-items: center; gap: 0.2rem; }
  .qty-btn { background: white; border: 1px solid #e2e8f0; border-radius: 6px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-weight: 700; cursor: pointer; color: #0f172a; font-size: 0.8rem; }
  .qty-input { width: 40px; height: 24px; text-align: center; border: 1px solid #e2e8f0; border-radius: 6px; font-weight: 700; font-size: 0.85rem; outline: none; font-family: 'Inter', sans-serif;}
  .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  
  .item-total { font-weight: 700; font-size: 0.85rem; color: #0f172a; text-align: right; width: 70px;}
  .remove-item { color: #cbd5e1; cursor: pointer; font-size: 0.8rem; padding-left: 0.5rem;}
  .remove-item:hover { color: #ef4444; }

  .cart-summary { border-top: 2px dashed #e2e8f0; padding-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
  .summary-row { display: flex; justify-content: space-between; font-size: 0.9rem; color: #475569; font-weight: 500; }
  .summary-row.total { font-weight: 800; font-size: 1.3rem; color: #0f172a; margin-top: 0.2rem; border-top: 1px solid #f1f5f9; padding-top: 0.7rem; }
  .pay-btn { background: #111827; color: white; border: none; border-radius: 12px; padding: 0.9rem; font-weight: 700; font-size: 1rem; margin-top: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s;}
  .pay-btn:hover { background: #1f2937; }

  /* ========== STYLE SEMUA MODAL KUSTOM (SUCCESS & CONFIRMATION) ========== */
  .modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; display: none;
  }
  .modal-card {
    background: #ffffff; width: 100%; max-width: 450px; border-radius: 24px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; border: 1px solid #f1f5f9; animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes scaleUp { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  
  /* Khusus Modal Success */
  .success-icon { font-size: 3.5rem; color: #16a34a; margin-bottom: 0.8rem; }
  .modal-card h3 { font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 0.2rem; }
  .invoice-code { font-size: 0.85rem; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 0.3rem 1rem; border-radius: 20px; display: inline-block; margin-bottom: 1.5rem; }
  .receipt-details { border-top: 1px dashed #e2e8f0; border-bottom: 1px dashed #e2e8f0; padding: 1rem 0; margin-bottom: 1.5rem; text-align: left; max-height: 150px; overflow-y: auto; }
  .receipt-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #475569; margin-bottom: 0.4rem; font-weight: 500; }
  .btn-close-modal { background: #0f172a; color: white; border: none; width: 100%; padding: 0.8rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; }
  .btn-close-modal:hover { background: #1e293b; }

  /* Khusus Modal Clear Cart */
  .modal-card.warning-card { max-width: 400px; }
  .warning-icon { font-size: 3.5rem; color: #ef4444; margin-bottom: 0.8rem; }
  .modal-card.warning-card h3 { font-size: 1.4rem; }
  .modal-card.warning-card p { font-size: 0.95rem; color: #475569; margin-bottom: 0.3rem; margin-top: 0.5rem; }
  .modal-actions { display: flex; gap: 1rem; margin-top: 1.8rem; }
  .btn-cancel { flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; font-family: 'Inter', sans-serif;}
  .btn-cancel:hover { background: #e2e8f0; }
  .btn-confirm-delete { flex: 1; background: #ef4444; color: white; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); font-family: 'Inter', sans-serif;}
  .btn-confirm-delete:hover { background: #dc2626; }
</style>

<div class="pos-wrapper">
  <section class="product-section">
    <div class="pos-header">
      <h2><i class="fas fa-microchip" style="color: #2563eb; margin-right: 0.4rem;"></i> ProTech POS Kasir</h2>
      <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari komponen...">
      </div>
    </div>

    <div class="category-tabs">
      <button class="cat-tab active" onclick="filterCategory('all', this)">Semua</button>
      @foreach($categories as $category)
        <button class="cat-tab" onclick="filterCategory('{{ $category->name }}', this)">{{ $category->name }}</button>
      @endforeach
    </div>

    <div class="product-grid" id="productGrid"></div>
  </section>

  <aside class="cart-section">
    <div class="cart-header">
      <h3><i class="fas fa-shopping-cart" style="color: #64748b;"></i> Keranjang</h3>
      <button class="clear-cart" id="clearCartBtn"><i class="fas fa-trash-alt"></i> Kosongkan</button>
    </div>

    <div class="cart-items" id="cartItemsContainer"></div>

    <div class="cart-summary">
      <div class="summary-row"><span>Subtotal</span><span id="subtotalDisplay">Rp 0</span></div>
      <div class="summary-row"><span>PPN (11%)</span><span id="taxDisplay">Rp 0</span></div>
      <div class="summary-row total"><span>Total</span><span id="totalDisplay">Rp 0</span></div>
    </div>

    <button class="pay-btn" id="payNowBtn"><i class="fas fa-lock"></i> Proses Pembayaran</button>
  </aside>
</div>

<div class="modal-overlay" id="successModal">
  <div class="modal-card">
    <i class="fas fa-check-circle success-icon"></i>
    <h3>Transaksi Berhasil</h3>
    <span class="invoice-code" id="modalInvoice">PRO-XXXXX</span>
    
    <div class="receipt-details" id="modalReceiptDetails"></div>
    
    <div style="display:flex; justify-content:space-between; margin-bottom: 1.5rem; font-weight:800; font-size:1.1rem; color:#0f172a;">
      <span>Total Bayar:</span>
      <span id="modalTotalCost">Rp 0</span>
    </div>
    
    <button class="btn-close-modal" onclick="closeSuccessModal()">Selesai & Sediakan Sesi Baru</button>
  </div>
</div>

<div class="modal-overlay" id="clearCartModal">
  <div class="modal-card warning-card">
    <i class="fas fa-exclamation-triangle warning-icon"></i>
    <h3>Kosongkan Keranjang?</h3>
    <p>Semua barang yang sudah dipilih akan dihapus dari daftar belanja.</p>
    
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeClearCartModal()">Batal</button>
      <button class="btn-confirm-delete" id="confirmClearCartBtn">Ya, Kosongkan</button>
    </div>
  </div>
</div>

<script>
  const csrfToken = '{{ csrf_token() }}';
  const dbProducts = @json($products);
  const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

  let cart = [];
  let currentCategory = 'all';

  const productGrid = document.getElementById('productGrid');
  const cartItemsContainer = document.getElementById('cartItemsContainer');
  const searchInput = document.getElementById('searchInput');

  function renderProducts(productsToRender) {
    if(productsToRender.length === 0) {
        productGrid.innerHTML = `<div style="grid-column:1/-1; text-align:center; color:#94a3b8; margin-top:2rem;">Barang tidak tersedia.</div>`;
        return;
    }
    productGrid.innerHTML = productsToRender.map(prod => `
      <div class="product-card" onclick="addToCart(${prod.id})">
        <div class="product-img">
          ${prod.image ? `<img src="/storage/products/${prod.image}" alt="${prod.name}">` : `<i class="fas fa-box"></i>`}
        </div>
        <div class="product-name">${prod.name}</div>
        <div class="product-price">${formatRupiah(prod.price)}</div>
        <div class="stock-info">Stok: ${prod.stock}</div>
      </div>
    `).join('');
  }

  function applyFilters() {
    const term = searchInput.value.toLowerCase();
    const filtered = dbProducts.filter(p => {
      const matchSearch = p.name.toLowerCase().includes(term);
      const matchCategory = currentCategory === 'all' || p.category.name === currentCategory;
      return matchSearch && matchCategory;
    });
    renderProducts(filtered);
  }

  window.filterCategory = function(catName, btnElement) {
    currentCategory = catName;
    document.querySelectorAll('.cat-tab').forEach(btn => btn.classList.remove('active'));
    btnElement.classList.add('active');
    applyFilters();
  };

  searchInput.addEventListener('input', applyFilters);

  window.addToCart = function(productId) {
    const product = dbProducts.find(p => p.id === productId);
    if (!product) return;

    const existing = cart.find(item => item.id === productId);
    if (existing) {
      if (existing.qty >= product.stock) {
        alert('Stok gudang tidak cukup!');
        return;
      }
      existing.qty += 1;
    } else {
      cart.push({ id: product.id, name: product.name, price: product.price, qty: 1, maxStock: product.stock });
    }
    renderCart();
  };

  window.updateQty = function(productId, delta) {
    const item = cart.find(i => i.id === productId);
    if (!item) return;
    const newQty = item.qty + delta;
    if (newQty > item.maxStock) {
      alert('Stok gudang terbatas!');
      return;
    }
    item.qty = newQty;
    if (item.qty <= 0) cart = cart.filter(i => i.id !== productId);
    renderCart();
  };

  window.handleManualQty = function(productId, inputElement) {
    const item = cart.find(i => i.id === productId);
    if (!item) return;
    let typedQty = parseInt(inputElement.value);
    if (isNaN(typedQty) || typedQty <= 0) typedQty = 1;
    if (typedQty > item.maxStock) {
      alert('Stok maksimal di gudang hanya ' + item.maxStock);
      typedQty = item.maxStock;
    }
    item.qty = typedQty;
    renderCart();
  };

  window.removeFromCart = function(productId) {
    cart = cart.filter(item => item.id !== productId);
    renderCart();
  };

  function renderCart() {
    if (cart.length === 0) {
      cartItemsContainer.innerHTML = `
        <div style="text-align: center; color: #94a3b8; margin-top: 3rem;">
          <i class="fas fa-cart-arrow-down" style="font-size: 2rem; display:block; margin-bottom:0.5rem;"></i> Keranjang kosong
        </div>`;
    } else {
      cartItemsContainer.innerHTML = cart.map(item => `
        <div class="cart-item">
          <div class="item-info">
            <span class="item-name" title="${item.name}">${item.name}</span>
            <span class="item-price">${formatRupiah(item.price)}</span>
          </div>
          <div class="qty-control">
            <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
            <input type="number" class="qty-input" value="${item.qty}" onchange="handleManualQty(${item.id}, this)">
            <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
          </div>
          <span class="item-total">${formatRupiah(item.price * item.qty)}</span>
          <i class="fas fa-times remove-item" onclick="removeFromCart(${item.id})"></i>
        </div>
      `).join('');
    }
    calculateTotals();
  }

  function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const tax = subtotal * 0.11;
    const total = subtotal + tax;
    document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
    document.getElementById('taxDisplay').textContent = formatRupiah(tax);
    document.getElementById('totalDisplay').textContent = formatRupiah(total);
  }

  // ========== LOGIKA MODAL CLEAR CART ==========
  document.getElementById('clearCartBtn').addEventListener('click', () => {
    if(cart.length > 0) {
      document.getElementById('clearCartModal').style.display = 'flex';
    }
  });

  window.closeClearCartModal = function() {
    document.getElementById('clearCartModal').style.display = 'none';
  };

  document.getElementById('confirmClearCartBtn').addEventListener('click', () => {
    cart = [];
    renderCart();
    closeClearCartModal();
  });

  // ========== LOGIKA CHECKOUT & MODAL SUCCESS ==========
  document.getElementById('payNowBtn').addEventListener('click', () => {
    if (cart.length === 0) {
      alert('Keranjang belanja Anda masih kosong!');
      return;
    }

    fetch("{{ route('pos.checkout') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken
      },
      body: JSON.stringify({ cart: cart })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('modalInvoice').textContent = data.invoice;
        document.getElementById('modalTotalCost').textContent = formatRupiah(data.total);
        document.getElementById('modalReceiptDetails').innerHTML = data.items.map(item => `
          <div class="receipt-row">
            <span>${item.name} (x${item.qty})</span>
            <span>${formatRupiah(item.price * item.qty)}</span>
          </div>
        `).join('');

        document.getElementById('successModal').style.display = 'flex';
      } else {
        alert('Gagal: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan sistem saat memproses pembayaran.');
    });
  });

  window.closeSuccessModal = function() {
    document.getElementById('successModal').style.display = 'none';
    window.location.reload(); 
  };

  // Tutup modal jika user klik area gelap
  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this && this.id === 'clearCartModal') {
        closeClearCartModal();
      }
    });
  });

  renderProducts(dbProducts);
  renderCart();
</script>
@endsection