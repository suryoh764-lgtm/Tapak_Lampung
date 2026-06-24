<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Tapak Lampung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
    <div class="login-container" style="padding-top: 40px; padding-bottom: 40px;">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-mark">
                    <svg viewBox="0 0 24 24"><path d="M3 20L12 4L21 20"/><circle cx="12" cy="14" r="2.5"/><line x1="7" y1="17" x2="17" y2="17"/></svg>
                </div>
                <h1 class="login-title">Tapak Lampung</h1>
                <p class="login-sub">Buat akun baru</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Anda" required autofocus class="form-input">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="role" class="form-label">Tipe Akun</label>
                    <select id="role" name="role" required class="form-input" style="appearance: auto; background-color: var(--surface); color: var(--text);">
                        <option value="user">Wisatawan</option>
                        <option value="trip_admin">Pemilik Trip</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required class="form-input">
                </div>
                
                <button type="submit" class="login-btn" style="margin-top: 10px;">
                    Daftar Sekarang
                </button>
            </form>

            <div class="login-footer">
                <p>Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 500;">Masuk di sini</a></p>
                <div style="margin-top: 15px;">
                    <a href="{{ route('home') }}">← Kembali ke website</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const html = document.documentElement;
        const theme = localStorage.getItem('tapak-admin-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        html.setAttribute('data-theme', theme);
    </script>
</body>
</html>
