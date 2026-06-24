@extends('admin.layouts.admin')
@section('title', 'Tambah Kuliner')
@section('page-title', 'Tambah Kuliner')
@section('page-sub', 'Isi form berikut untuk menambahkan kuliner khas baru')

@section('content')
<div class="form-page">
    <form method="POST" action="{{ route('admin.culinaries.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-card">
            <div class="form-card-title">🍽️ Informasi Kuliner</div>
            <div class="form-grid-2">

                <div class="form-group full">
                    <label class="form-label">Nama Kuliner <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="cth: Seruit" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi <span>*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" placeholder="Tulis deskripsi singkat tentang kuliner ini..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span>*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Makanan','Minuman','Camilan'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Outlet <span>*</span></label>
                    <select name="outlet_type" class="form-select" required>
                        @foreach(['warung','kafe','restoran'] as $type)
                            <option value="{{ $type }}" {{ old('outlet_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    @error('outlet_type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Outlet Terdekat</label>
                    <input type="number" name="outlet_count" class="form-input" value="{{ old('outlet_count', 0) }}" min="0" placeholder="cth: 12">
                    @error('outlet_count')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tingkat Kepedasan (0–5) <span>*</span></label>
                    <div style="display:flex; align-items:center; gap:16px; margin-top:4px;">
                        <input type="range" name="spice_level" id="spiceRange"
                               min="0" max="5" step="1" value="{{ old('spice_level', 0) }}"
                               style="flex:1; accent-color: var(--coral);"
                               oninput="updateSpice(this.value)">
                        <div style="display:flex; align-items:center; gap:8px; min-width:100px;">
                            <div id="spiceDots" style="display:flex;gap:3px;">
                                @for($i=1;$i<=5;$i++)
                                <div class="spice-dot" data-i="{{ $i }}" style="width:12px;height:12px;border-radius:3px;background:var(--border);transition:background 0.2s;"></div>
                                @endfor
                            </div>
                            <span id="spiceVal" style="font-size:13px;font-weight:500;color:var(--coral);min-width:24px;">0/5</span>
                        </div>
                    </div>
                    @error('spice_level')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">📷 Foto Kuliner</div>
            <div class="form-group">
                <label class="form-label">Upload Gambar <span>*</span></label>
                <div class="img-upload-area">
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp" required onchange="previewImage(this)">
                    <div class="img-upload-icon">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <div class="img-upload-text"><strong>Klik untuk upload</strong> atau drag & drop<br><small>JPG, PNG, WEBP — maks. 5MB</small></div>
                </div>
                <img id="imgPreview" class="img-preview" src="#" alt="Preview">
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Simpan Kuliner</button>
            <a href="{{ route('admin.culinaries.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
function updateSpice(val) {
    document.getElementById('spiceVal').textContent = val + '/5';
    document.querySelectorAll('.spice-dot').forEach(dot => {
        dot.style.background = parseInt(dot.dataset.i) <= parseInt(val) ? 'var(--coral)' : 'var(--border)';
    });
}
// init on load
updateSpice(document.getElementById('spiceRange').value);
</script>
@endpush
@endsection
