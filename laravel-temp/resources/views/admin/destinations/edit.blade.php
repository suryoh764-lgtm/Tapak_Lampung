@extends('admin.layouts.admin')
@section('title', 'Edit Destinasi')
@section('page-title', 'Edit Destinasi')
@section('page-sub', 'Perbarui informasi destinasi')

@section('content')
<div class="form-page">
    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-card">
            <div class="form-card-title">📍 Informasi Utama</div>
            <div class="form-grid-2">

                <div class="form-group full">
                    <label class="form-label">Nama Destinasi <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $destination->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span>*</span></label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $destination->location) }}" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span>*</span></label>
                    <select name="category" class="form-select" required>
                        @foreach(['Pantai','Teluk','Air Terjun','Danau','Pulau','Gunung','Hutan'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $destination->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Label <span>*</span></label>
                    <select name="label" class="form-select" required>
                        @foreach(['Hidden Gem','Populer','Surfing'] as $lbl)
                            <option value="{{ $lbl }}" {{ old('label', $destination->label) === $lbl ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    @error('label')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi <span>*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $destination->description) }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">🗺️ Detail Informasi</div>
            <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">Jarak / Rute</label>
                    <input type="text" name="distance_km" class="form-input" value="{{ old('distance_km', $destination->distance_km) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Tempuh</label>
                    <input type="text" name="travel_time" class="form-input" value="{{ old('travel_time', $destination->travel_time) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tiket Masuk</label>
                    <input type="text" name="entrance_fee" class="form-input" value="{{ old('entrance_fee', $destination->entrance_fee) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Terbaik Berkunjung</label>
                    <input type="text" name="best_time" class="form-input" value="{{ old('best_time', $destination->best_time) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Rating (0–5)</label>
                    <input type="number" name="rating" class="form-input" value="{{ old('rating', $destination->rating) }}" min="0" max="5" step="0.1">
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">📷 Foto Destinasi</div>
            <div class="form-group">
                <label class="form-label">Gambar Saat Ini</label>
                @php
                    $imgUrl = str_starts_with($destination->image_path ?? '', 'images/') || str_starts_with($destination->image_path ?? '', 'http')
                        ? asset($destination->image_path)
                        : asset('storage/' . $destination->image_path);
                @endphp
                <img src="{{ $imgUrl }}" class="img-current" alt="{{ $destination->name }}">
                <label class="form-label" style="margin-top:4px;">Ganti Gambar (opsional)</label>
                <div class="img-upload-area">
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp" onchange="previewImage(this)">
                    <div class="img-upload-icon">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <div class="img-upload-text"><strong>Klik untuk upload</strong> — JPG, PNG, WEBP maks. 5MB</div>
                </div>
                <img id="imgPreview" class="img-preview" src="#" alt="Preview">
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
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
