@extends('admin.layouts.admin')
@section('title', 'Edit Open Trip')
@section('page-title', 'Edit Open Trip')
@section('page-sub', 'Perbarui informasi paket open trip')

@section('content')
<div class="form-page">
    <form method="POST" action="{{ route('admin.trips.update', $trip) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-card">
            <div class="form-card-title">🗺️ Informasi Trip</div>
            <div class="form-grid-2">

                <div class="form-group full">
                    <label class="form-label">Nama Paket Trip <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $trip->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi <span>*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $trip->description) }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tags (pisah koma)</label>
                    <input type="text" name="tags" class="form-input" value="{{ old('tags', $trip->tags->pluck('tag')->join(', ')) }}" placeholder="cth: Snorkeling, 3D2N">
                    <div class="form-hint">Pisahkan dengan koma</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi <span>*</span></label>
                    <input type="text" name="duration" class="form-input" value="{{ old('duration', $trip->duration) }}" required>
                    @error('duration')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">👤 Organizer & Jadwal</div>
            <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">Nama Organizer <span>*</span></label>
                    <input type="text" name="organizer_name" class="form-input" value="{{ old('organizer_name', $trip->organizer_name) }}" required>
                    @error('organizer_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Inisial Avatar <span>*</span></label>
                    <input type="text" name="organizer_avatar" class="form-input" value="{{ old('organizer_avatar', $trip->organizer_avatar) }}" maxlength="2" required style="text-transform:uppercase;">
                    @error('organizer_avatar')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Jadwal <span>*</span></label>
                    <input type="date" name="schedule_date" class="form-input" value="{{ old('schedule_date', $trip->schedule_date->format('Y-m-d')) }}" required>
                    @error('schedule_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga per Orang (Rp) <span>*</span></label>
                    <input type="number" name="price" class="form-input" value="{{ old('price', $trip->price) }}" min="0" step="1000" required>
                    @error('price')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota Maksimum <span>*</span></label>
                    <input type="number" name="max_quota" class="form-input" value="{{ old('max_quota', $trip->max_quota) }}" min="1" required>
                    @error('max_quota')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Rating (0–5)</label>
                    <input type="number" name="rating" class="form-input" value="{{ old('rating', $trip->rating) }}" min="0" max="5" step="0.1">
                    @error('rating')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-title">📷 Foto Trip</div>
            <div class="form-group">
                <label class="form-label">Gambar Saat Ini</label>
                @php
                    $imgUrl = str_starts_with($trip->image_path ?? '', 'images/') || str_starts_with($trip->image_path ?? '', 'http')
                        ? asset($trip->image_path)
                        : asset('storage/' . $trip->image_path);
                @endphp
                <img src="{{ $imgUrl }}" class="img-current" alt="{{ $trip->name }}">
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
