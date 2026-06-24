@extends('admin.layouts.admin')
@section('title', 'Tambah Destinasi')
@section('page-title', 'Tambah Destinasi')
@section('page-sub', 'Isi form berikut untuk menambahkan destinasi baru')

@section('content')
<div class="form-page">
    <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Info Utama --}}
        <div class="form-card">
            <div class="form-card-title">📍 Informasi Utama</div>
            <div class="form-grid-2">

                <div class="form-group full">
                    <label class="form-label">Nama Destinasi <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="cth: Pulau Pahawang Kecil" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span>*</span></label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}" placeholder="cth: Pesawaran, Lampung" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span>*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Pantai','Teluk','Air Terjun','Danau','Pulau','Gunung','Hutan'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Label <span>*</span></label>
                    <select name="label" class="form-select" required>
                        <option value="">-- Pilih Label --</option>
                        @foreach(['Hidden Gem','Populer','Surfing'] as $lbl)
                            <option value="{{ $lbl }}" {{ old('label') === $lbl ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    @error('label')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi <span>*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" placeholder="Tulis deskripsi menarik tentang destinasi ini..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Detail Info --}}
        <div class="form-card">
            <div class="form-card-title">🗺️ Detail Informasi</div>
            <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">Jarak / Rute</label>
                    <input type="text" name="distance_km" class="form-input" value="{{ old('distance_km') }}" placeholder="cth: 25 km (darat) + 30 mnt perahu">
                    @error('distance_km')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu Tempuh</label>
                    <input type="text" name="travel_time" class="form-input" value="{{ old('travel_time') }}" placeholder="cth: 1.5 - 2 Jam">
                    @error('travel_time')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tiket Masuk</label>
                    <input type="text" name="entrance_fee" class="form-input" value="{{ old('entrance_fee') }}" placeholder="cth: Rp 10.000">
                    @error('entrance_fee')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu Terbaik Berkunjung</label>
                    <input type="text" name="best_time" class="form-input" value="{{ old('best_time') }}" placeholder="cth: April - September">
                    @error('best_time')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Rating Awal (0–5)</label>
                    <input type="number" name="rating" class="form-input" value="{{ old('rating', '0.00') }}" min="0" max="5" step="0.1" placeholder="cth: 4.8">
                    @error('rating')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="form-card">
            <div class="form-card-title">📷 Foto Destinasi</div>
            <div class="form-group">
                <label class="form-label">Upload Gambar <span>*</span></label>
                <div class="img-upload-area" id="uploadArea">
                    <input type="file" name="image" id="imageInput" accept="image/jpg,image/jpeg,image/png,image/webp" required onchange="previewImage(this)">
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
            <button type="submit" class="btn-submit">Simpan Destinasi</button>
            <a href="{{ route('admin.destinations.index') }}" class="btn-cancel">Batal</a>
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
</script>
@endpush
@endsection
