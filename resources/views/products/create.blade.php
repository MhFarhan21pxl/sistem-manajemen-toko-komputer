@extends('layouts.app')

@section('content')
<style>
  /* CSS Khusus untuk Form Tambah Data */
  .form-card { background: #ffffff; border-radius: 20px; padding: 2.2rem 2.4rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03); max-width: 780px; width: 100%; border: 1px solid #f0f0f0; margin-top: 1rem; }
  .form-grid { display: flex; flex-direction: column; gap: 1.8rem; }
  .input-group { display: flex; flex-direction: column; gap: 0.4rem; }
  .input-group label { font-weight: 600; font-size: 0.85rem; color: #374151; }
  .input-field { display: flex; align-items: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 0.65rem 1rem; transition: all 0.2s; }
  .input-field i { color: #9ca3af; font-size: 1rem; margin-right: 0.7rem; }
  .input-field input, .input-field select { background: transparent; border: none; outline: none; width: 100%; font-size: 0.95rem; font-weight: 500; color: #111827; }
  .input-field:focus-within { border-color: #2563eb; background: #ffffff; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
  .row-dual { display: flex; gap: 1.5rem; flex-wrap: wrap; }
  .row-dual .input-group { flex: 1; min-width: 150px; }
  .upload-area { border: 2px dashed #d1d5db; border-radius: 18px; padding: 2rem 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: #fafbfc; cursor: pointer; transition: all 0.2s; }
  .upload-area:hover { border-color: #2563eb; background: #f0f7ff; }
  .upload-text { font-weight: 600; font-size: 0.95rem; color: #1f2937; margin-bottom: 0.2rem; }
  .upload-hint { font-size: 0.8rem; color: #6b7280; }
  .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; border-top: 1px solid #f3f4f6; padding-top: 1.8rem; }
  .btn-outline { background: white; border: 1px solid #e5e7eb; color: #374151; padding: 0.7rem 1.8rem; border-radius: 12px; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
  .btn-outline:hover { background: #f9fafb; }
  .text-danger { color: #dc3545; font-size: 0.8rem; margin-top: 0.3rem; font-weight: 500; }
</style>

<main class="main-content">
  <div class="page-header" style="margin-bottom: 0;">
    <div class="page-title">
      <h1>Tambah Produk Baru</h1>
      <p>Masukkan detail informasi produk ke dalam sistem gudang.</p>
    </div>
  </div>

  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
    @csrf <div class="form-grid">
      
      <div class="input-group">
        <label>Nama Produk</label>
        <div class="input-field">
          <i class="fas fa-tag"></i>
          <input type="text" name="name" placeholder="Contoh: Rexus SH600 68-Key" required>
        </div>
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
      </div>

      <div class="input-group">
        <label>Kategori</label>
        <div class="input-field">
          <i class="fas fa-layer-group"></i>
          <select name="category_id" required>
            <option value="" disabled selected>Pilih kategori produk</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
      </div>

      <div class="row-dual">
        <div class="input-group">
          <label>Harga Jual (Rp)</label>
          <div class="input-field">
            <i class="fas fa-wallet"></i>
            <input type="number" name="price" placeholder="0" required>
          </div>
          @error('price') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <div class="input-group">
          <label>Jumlah Stok</label>
          <div class="input-field">
            <i class="fas fa-cubes"></i>
            <input type="number" name="stock" placeholder="0" required>
          </div>
          @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="input-group">
        <label>Foto Produk (Opsional)</label>
        <div class="upload-area" id="uploadTrigger">
          <i class="fas fa-camera" style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;"></i>
          <div class="upload-text">Klik atau seret gambar ke sini</div>
          <div class="upload-hint">Format PNG/JPG maksimal 2MB</div>
        </div>
        <input type="file" name="image" id="fileInput" accept="image/*" style="display: none;">
        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('products.index') }}" class="btn-outline">
        <i class="fas fa-times"></i> Batal
      </a>
      <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i> Simpan Produk
      </button>
    </div>
  </form>
</main>

<script>
  const uploadArea = document.getElementById('uploadTrigger');
  const fileInput = document.getElementById('fileInput');

  if (uploadArea && fileInput) {
    uploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function(e) {
      if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        uploadArea.querySelector('.upload-text').innerText = `📎 ${fileName}`;
        uploadArea.querySelector('.upload-hint').innerText = 'File siap diunggah.';
        uploadArea.style.borderColor = '#15803d'; // Warna hijau tandanya berhasil
      }
    });

    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.style.borderColor = '#2563eb'; });
    uploadArea.addEventListener('dragleave', (e) => { e.preventDefault(); uploadArea.style.borderColor = '#d1d5db'; });
    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        const fileName = e.dataTransfer.files[0].name;
        uploadArea.querySelector('.upload-text').innerText = `📎 ${fileName}`;
        uploadArea.style.borderColor = '#15803d';
      }
    });
  }
</script>
@endsection