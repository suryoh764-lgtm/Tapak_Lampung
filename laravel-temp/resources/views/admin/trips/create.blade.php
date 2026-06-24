@extends('admin.layouts.admin')
@section('title', 'Tambah Open Trip')
@section('page-title', 'Tambah Open Trip')
@section('page-sub', 'Isi form berikut untuk menambahkan paket open trip baru')

@section('content')
<div class="form-page">
    <form method="POST" action="{{ route('admin.trips.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Info Utama --}}
        <div class="form-card">
            <div class="form-card-title">🗺️ Informasi Trip</div>
            <div class="form-grid-2">

                <div class="form-group full">
                    <label class="form-label">Nama Paket Trip <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="cth: Pahawang & Kelagian 3D2N" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi <span>*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" placeholder="Tulis deskripsi menarik tentang paket trip ini..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tags (pisah koma)</label>
                    <input type="text" name="tags" class="form-input" value="{{ old('tags') }}" placeholder="cth: Snorkeling, 3D2N, Pantai">
                    <div class="form-hint">Gunakan koma untuk memisahkan tag</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi <span>*</span></label>
                    <input type="text" name="duration" class="form-input" value="{{ old('duration') }}" placeholder="cth: 3 Hari 2 Malam" required>
                    @error('duration')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Organizer & Jadwal --}}
        <div class="form-card">
            <div class="form-card-title">👤 Organizer & Jadwal</div>
            <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">Nama Organizer <span>*</span></label>
                    <input type="text" name="organizer_name" class="form-input" value="{{ old('organizer_name') }}" placeholder="cth: Dewi Trip" required>
                    @error('organizer_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Inisial Avatar (maks. 2 huruf) <span>*</span></label>
                    <input type="text" name="organizer_avatar" class="form-input" value="{{ old('organizer_avatar') }}" maxlength="2" placeholder="cth: DL" required style="text-transform:uppercase;">
                    <div class="form-hint">Akan tampil sebagai avatar organizer di kartu trip</div>
                    @error('organizer_avatar')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Jadwal <span>*</span></label>
                    <input type="date" name="schedule_date" class="form-input" value="{{ old('schedule_date') }}" required>
                    @error('schedule_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga per Orang (Rp) <span>*</span></label>
                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" min="0" step="1000" placeholder="cth: 485000" required>
                    @error('price')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota Maksimum <span>*</span></label>
                    <input type="number" name="max_quota" class="form-input" value="{{ old('max_quota') }}" min="1" placeholder="cth: 20" required>
                    @error('max_quota')<div class="form-error">{{ $message }}</div>@enderror
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
            <div class="form-card-title">📷 Foto Trip</div>
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
            <button type="submit" class="btn-submit">Simpan Open Trip</button>
            <a href="{{ route('admin.trips.index') }}" class="btn-cancel">Batal</a>
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
