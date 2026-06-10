@extends('layouts.app')

@section('content')
<style>
  /* CSS Komponen Statistik Keuangan */
  .mini-stats { display: flex; gap: 1rem; margin-bottom: 1.6rem; flex-wrap: wrap; }
  .mini-stat { background: white; border-radius: 14px; padding: 1rem 1.5rem; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 200px; box-shadow: 0 2px 5px rgba(0,0,0,0.01); }
  .mini-stat i { font-size: 1.4rem; color: #2563eb; background: #dbeafe; padding: 0.6rem; border-radius: 10px; }
  .mini-stat .stat-title { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
  .mini-stat .stat-number { font-weight: 700; font-size: 1.3rem; color: #0f172a; margin-top: 0.1rem; }
  
  /* Filter Bar Struktur Kompak ProTech */
  .filter-bar { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.6rem; width: 100%; }
  .filter-form { display: flex; gap: 0.8rem; width: 100%; flex-wrap: wrap; align-items: center; }
  
  .search-box { display: flex; align-items: center; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.65rem 1rem; gap: 0.5rem; flex: 2; min-width: 250px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02); }
  .search-box i { color: #94a3b8; font-size: 0.9rem; }
  .search-box input { border: none; outline: none; background: transparent; font-size: 0.95rem; width: 100%; font-weight: 500; color: #0f172a; font-family: 'Inter', sans-serif; }
  
  .sort-dropdown { display: flex; align-items: center; gap: 0.5rem; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.65rem 1rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02); min-width: 180px; }
  .sort-dropdown i { color: #64748b; font-size: 0.95rem; }
  .sort-dropdown select { border: none; background: transparent; width: 100%; font-weight: 600; font-size: 0.9rem; color: #0f172a; outline: none; cursor: pointer; font-family: 'Inter', sans-serif; }
  
  /* Input Khusus Penyeleksi Tanggal/Bulan/Tahun */
  .dynamic-input-box { display: flex; align-items: center; gap: 0.5rem; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.65rem 1rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02); min-width: 180px; }
  .dynamic-input-box i { color: #2563eb; font-size: 0.95rem; }
  .dynamic-input-box input { border: none; background: transparent; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.9rem; color: #0f172a; outline: none; width: 100%; cursor: pointer; }

  .btn-protech { background-color: #0f172a; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; text-decoration: none; transition: background 0.2s; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.1); font-family: 'Inter', sans-serif; }
  .btn-protech:hover { background-color: #1e293b; color: white; }

  /* Gaya Tabel Utama */
  .table-card { background: white; border-radius: 16px; padding: 0.5rem 0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
  .table-wrapper { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; min-width: 700px; }
  th { text-align: left; padding: 1rem 1.4rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
  td { padding: 1rem 1.4rem; font-size: 0.9rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9; }
  tr:hover td { background: #f8fafc; }
  .badge { background: #f1f5f9; padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; color: #0f172a; }
  .status-success { color: #16a34a; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.3rem;}
  
  .view-btn { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; color: #2563eb; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.3rem; }
  .view-btn:hover { background: #eff6ff; border-color: #bfdbfe; }
  .pagination-container { padding: 1rem 1.4rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; }

  /* OVERLAY MODAL RINCIAN */
  .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; display: none; }
  .receipt-card { background: #ffffff; width: 100%; max-width: 450px; border-radius: 24px; padding: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
  @keyframes scaleUp { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .receipt-title { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.5rem; }
  .receipt-list { border-top: 2px dashed #e2e8f0; border-bottom: 2px dashed #e2e8f0; padding: 1rem 0; margin: 1rem 0; max-height: 200px; overflow-y: auto; }
  .receipt-item { display: flex; justify-content: space-between; font-size: 0.9rem; color: #475569; margin-bottom: 0.5rem; }

  @media print {
    body * { visibility: hidden; }
    .table-card, .table-card * { visibility: visible; }
    .table-card { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
    .pagination-container, .filter-bar, .page-header, .mini-stats { display: none !important; }
    .no-print { display: none !important; }
  }
</style>

<main class="main-content">
  <div class="page-header">
    <div class="page-title">
      <h1>Riwayat Transaksi</h1>
      <p>Laporan keuangan dan rekap penjualan toko ProTech</p>
    </div>
    <button class="btn-protech" onclick="window.print()">
      <i class="fas fa-print"></i> Cetak Laporan
    </button>
  </div>

  <div class="filter-bar">
    <form action="{{ route('transactions.index') }}" method="GET" class="filter-form" id="filterForm">
      
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari nomor nota (PRO-xxx)..." value="{{ $search }}">
        @if($search)
          <a href="{{ route('transactions.index') }}" style="color: #64748b; text-decoration:none; font-size:0.85rem; margin-right:0.5rem; font-weight:600;">Clear</a>
        @endif
      </div>

      <div class="sort-dropdown">
        <i class="fas fa-filter"></i>
        <select name="period" id="periodSelect" onchange="togglePeriodInputs()">
          <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
          <option value="date" {{ $period === 'date' ? 'selected' : '' }}>Harian (Pilih Tanggal)</option>
          <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulanan (Pilih Bulan)</option>
          <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahunan (Pilih Tahun)</option>
        </select>
      </div>

      <div class="dynamic-input-box" id="dateInputGroup" style="display: none;">
        <i class="fas fa-calendar-day"></i>
        <input type="date" name="date_val" value="{{ $dateVal }}" onchange="document.getElementById('filterForm').submit()">
      </div>

      <div class="dynamic-input-box" id="monthInputGroup" style="display: none;">
        <i class="fas fa-calendar-alt"></i>
        <input type="month" name="month_val" value="{{ $monthVal }}" onchange="document.getElementById('filterForm').submit()">
      </div>

      <div class="sort-dropdown" id="yearInputGroup" style="display: none;">
        <i class="fas fa-calendar"></i>
        <select name="year_val" onchange="document.getElementById('filterForm').submit()">
          @for($y = date('Y'); $y >= date('Y') - 4; $y--)
            <option value="{{ $y }}" {{ (int)$yearVal === $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
          @endfor
        </select>
      </div>

      <div class="sort-dropdown">
        <i class="fas fa-sort-amount-down-alt"></i>
        <select name="sort" onchange="document.getElementById('filterForm').submit()">
          <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Terbaru ke Terlama</option>
          <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Terlama ke Terbaru</option>
        </select>
      </div>

    </form>
  </div>

  <div class="mini-stats">
    <div class="mini-stat">
      <i class="fas fa-receipt"></i>
      <div>
        <div class="stat-title">Total Nota Terbit</div>
        <div class="stat-number">{{ $totalTransactions }} Transaksi</div>
      </div>
    </div>
    <div class="mini-stat">
      <i class="fas fa-wallet"></i>
      <div>
        <div class="stat-title">Total Pendapatan (Omzet)</div>
        <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      </div>
    </div>
    <div class="mini-stat">
      <i class="fas fa-shopping-bag"></i>
      <div>
        <div class="stat-title font-bold">Rata-rata Pembelian</div>
        <div class="stat-number">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
      </div>
    </div>
  </div>

  <div class="table-card">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Tanggal Pembelian</th>
            <th>Kode Invoice</th>
            <th>Kasir / Petugas</th>
            <th>Total Bayar (Inc PPN)</th>
            <th>Status Keamanan</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $tx)
          <tr>
            <td><i class="far fa-calendar-alt" style="color:#94a3b8; margin-right:0.4rem;"></i> {{ $tx->created_at->format('d M Y - H:i') }} WIB</td>
            <td><span class="badge">{{ $tx->transaction_code }}</span></td>
            <td><i class="far fa-user-circle" style="color:#94a3b8; margin-right:0.2rem;"></i> Admin Utama</td>
            <td style="font-weight:700; color:#0f172a;">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
            <td><span class="status-success"><i class="fas fa-check-circle"></i> Selesai (Lunas)</span></td>
            <td class="no-print">
              <button class="view-btn" onclick="viewDetails('{{ $tx->id }}', '{{ $tx->transaction_code }}', '{{ number_format($tx->total_price, 0, ',', '.') }}')">
                <i class="fas fa-eye"></i> Rincian Item
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align: center; color:#64748b; padding: 3rem;">
              <i class="fas fa-folder-open" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> Tidak ada rekaman data transaksi pada kriteria filter ini.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination-container">
      <span style="font-size: 0.85rem; color:#64748b; font-weight:500;">Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} nota</span>
      <div>
        {{ $transactions->links() }}
      </div>
    </div>
  </div>
</main>

<div class="modal-overlay" id="detailsModal">
  <div class="receipt-card">
    <div class="receipt-title"><i class="fas fa-file-invoice" style="color:#2563eb;"></i> Rincian Nota Belanja</div>
    <div style="font-size:0.85rem; font-weight:700; color:#64748b; margin-bottom:0.5rem;" id="modalInvoiceLabel">PRO-XXXX</div>
    
    <div class="receipt-list" id="modalItemsContainer"></div>
    
    <div style="display:flex; justify-content:space-between; font-weight:800; font-size:1.1rem; color:#0f172a; margin-bottom:1.5rem;">
      <span>Total Akhir:</span>
      <span id="modalTotalLabel">Rp 0</span>
    </div>
    
    <button class="btn-protech" style="width: 100%; justify-content: center; padding: 0.8rem;" onclick="closeDetailsModal()">
      Tutup Rincian
    </button>
  </div>
</div>

<script>
  // JAVASCRIPT REVISI UTAMA: Pengendali Sembunyi/Muncul Elemen Kalender Kustom
  function togglePeriodInputs() {
    const period = document.getElementById('periodSelect').value;
    
    // Sembunyikan semua grup input terlebih dahulu
    document.getElementById('dateInputGroup').style.display = 'none';
    document.getElementById('monthInputGroup').style.display = 'none';
    document.getElementById('yearInputGroup').style.display = 'none';
    
    // Tampilkan kotak input yang sesuai pilihan tipe periode
    if (period === 'date') {
      document.getElementById('dateInputGroup').style.display = 'flex';
    } else if (period === 'month') {
      document.getElementById('monthInputGroup').style.display = 'flex';
    } else if (period === 'year') {
      document.getElementById('yearInputGroup').style.display = 'flex';
    }

    // Jika admin pindah ke opsi "Semua Waktu", langsung lempar kirim form agar mereset kueri
    if (period === 'all') {
      document.getElementById('filterForm').submit();
    }
  }

  // Jalankan fungsi di atas sekali saat halaman pertama dimuat agar posisi input tetap terjaga
  window.addEventListener('DOMContentLoaded', togglePeriodInputs);

  // Fungsi memanggil Rincian Item (AJAX)
  function viewDetails(transactionId, invoiceCode, totalCost) {
    document.getElementById('modalInvoiceLabel').innerText = 'Nomor Nota: ' + invoiceCode;
    document.getElementById('modalTotalLabel').innerText = 'Rp ' + totalCost;
    const container = document.getElementById('modalItemsContainer');
    container.innerHTML = '<p style="text-align:center; color:#64748b; font-size:0.9rem;">Memuat rincian item...</p>';
    document.getElementById('detailsModal').style.display = 'flex';

    fetch('/transactions/' + transactionId)
      .then(response => response.json())
      .then(data => {
        if(data.length === 0) {
          container.innerHTML = '<p style="text-align:center; color:#64748b;">Gagal memuat item.</p>';
          return;
        }
        container.innerHTML = data.map(item => `
          <div class="receipt-item">
            <span style="font-weight:600; color:#0f172a; font-size:0.9rem;">${item.product.name} <span style="color:#64748b; font-weight:500;">(x${item.quantity})</span></span>
            <span style="font-weight:700; color:#0f172a; font-size:0.9rem;">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
          </div>
        `).join('');
      })
      .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<p style="text-align:center; color:#ef4444; font-size:0.9rem;">Terjadi gangguan jaringan.</p>';
      });
  }

  function closeDetailsModal() { document.getElementById('detailsModal').style.display = 'none'; }
  document.getElementById('detailsModal').addEventListener('click', function(e) { if (e.target === this) closeDetailsModal(); });
</script>
@endsection