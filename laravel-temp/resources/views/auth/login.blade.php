<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Tapak Lampung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-mark">
                    <svg viewBox="0 0 24 24"><path d="M3 20L12 4L21 20"/><circle cx="12" cy="14" r="2.5"/><line x1="7" y1="17" x2="17" y2="17"/></svg>
                </div>
                <h1 class="login-title">Tapak Lampung</h1>
                <p class="login-sub">Selamat datang kembali</p>
            </div>

            @if(session('info'))
                <div class="alert" style="background: rgba(56,189,248,0.12); border: 1px solid rgba(56,189,248,0.3); color: #38bdf8; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus class="form-input">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required class="form-input">
                </div>
                
                <div class="form-row">
                    <label class="form-check">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                </div>
                
                <button type="submit" class="login-btn">
                    Masuk
                    <svg viewBox="0 0 24 24" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}" style="color: var(--accent); font-weight: 500;">Daftar di sini</a></p>
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
