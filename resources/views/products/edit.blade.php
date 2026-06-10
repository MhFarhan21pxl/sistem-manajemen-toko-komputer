@extends('layouts.app')

@section('content')
<style>
  /* CSS Form sama persis dengan create */
  .form-card { background: #ffffff; border-radius: 20px; padding: 2.2rem 2.4rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03); max-width: 780px; width: 100%; border: 1px solid #f0f0f0; margin-top: 1rem; }
  .form-grid { display: flex; flex-direction: column; gap: 1.8rem; }
  .input-group { display: flex; flex-direction: column; gap: 0.4rem; }
  .input-group label { font-weight: 600; font-size: 0.85rem; color: #374151; }
  .input-field { display: flex; align-items: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 0.65rem 1rem; }
  .input-field i { color: #9ca3af; font-size: 1rem; margin-right: 0.7rem; }
  .input-field input, .input-field select { background: transparent; border: none; outline: none; width: 100%; font-size: 0.95rem; font-weight: 500; color: #111827; }
  .row-dual { display: flex; gap: 1.5rem; flex-wrap: wrap; }
  .row-dual .input-group { flex: 1; min-width: 150px; }
  .upload-area { border: 2px dashed #d1d5db; border-radius: 18px; padding: 2rem 1.5rem; display: flex; flex-direction: column; align-items: center; text-align: center; background: #fafbfc; cursor: pointer; }
  .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; border-top: 1px solid #f3f4f6; padding-top: 1.8rem; }
  .btn-outline { background: white; border: 1px solid #e5e7eb; color: #374151; padding: 0.7rem 1.8rem; border-radius: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
  .btn-primary { background-color: #0f172a; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 600; cursor: pointer; }
</style>

<main class="main-content">
  <div class="page-header" style="margin-bottom: 0;">
    <div class="page-title">
      <h1>Edit Produk</h1>
      <p>Perbarui informasi komponen atau periferal ini.</p>
    </div>
  </div>

  <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="form-card">
    @csrf
    @method('PUT') <div class="form-grid">
      <div class="input-group">
        <label>Nama Produk</label>
        <div class="input-field">
          <i class="fas fa-tag"></i>
          <input type="text" name="name" value="{{ $product->name }}" required>
        </div>
      </div>

      <div class="input-group">
        <label>Kategori</label>
        <div class="input-field">
          <i class="fas fa-layer-group"></i>
          <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="row-dual">
        <div class="input-group">
          <label>Harga Jual (Rp)</label>
          <div class="input-field">
            <i class="fas fa-wallet"></i>
            <input type="number" name="price" value="{{ $product->price }}" required>
          </div>
        </div>
        
        <div class="input-group">
          <label>Jumlah Stok</label>
          <div class="input-field">
            <i class="fas fa-cubes"></i>
            <input type="number" name="stock" value="{{ $product->stock }}" required>
          </div>
        </div>
      </div>

      <div class="input-group">
        <label>Foto Produk (Biarkan kosong jika tidak ingin mengubah foto)</label>
        <div class="upload-area" id="uploadTrigger" style="{{ $product->image ? 'border-color: #15803d;' : '' }}">
          <i class="fas fa-camera" style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;"></i>
          <div class="upload-text">
            {{ $product->image ? 'Ganti foto lama?' : 'Klik atau seret gambar ke sini' }}
          </div>
        </div>
        <input type="file" name="image" id="fileInput" accept="image/*" style="display: none;">
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('products.index') }}" class="btn-outline">Batal</a>
      <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui Produk</button>
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
        uploadArea.querySelector('.upload-text').innerText = `📎 ${e.target.files[0].name}`;
        uploadArea.style.borderColor = '#15803d';
      }
    });
  }
</script>
@endsection