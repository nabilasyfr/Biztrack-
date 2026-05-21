<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — BizTrack UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%; max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,.4);
        }
        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #1a56db, #0ea5e9);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff;
            margin: 0 auto 16px;
        }
        .brand-name { font-size: 22px; font-weight: 800; color: #0f172a; }
        .brand-tagline { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        .form-control { border-radius: 10px; border-color: #e2e8f0; padding: 12px 14px; font-size: 14px; }
        .form-control:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,.12); }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; }
        .btn-login { background: linear-gradient(135deg, #1a56db, #0ea5e9); border: none; border-radius: 10px; padding: 13px; font-size: 15px; font-weight: 700; letter-spacing: .02em; }
        .btn-login:hover { opacity: .92; }
        .demo-box { background: #f8fafc; border-radius: 10px; padding: 14px; border: 1px solid #e2e8f0; }
        .demo-box p { font-size: 12px; margin: 0; color: #64748b; }
        .demo-box strong { color: #0f172a; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-28">
            <div class="brand-icon"><i class="bi bi-shop"></i></div>
            <div class="brand-name">BizTrack UMKM</div>
            <div class="brand-tagline">Smart Retail, Inventory & Accounting System</div>
        </div>

        <hr class="my-3">

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3" style="font-size:13px; border-radius:10px;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px; border-color:#e2e8f0; background:#f8fafc;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com"
                           value="{{ old('email') }}" required autofocus style="border-radius:0 10px 10px 0;">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px; border-color:#e2e8f0; background:#f8fafc;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required
                           style="border-radius:0 10px 10px 0;" id="pwdField">
                    <button type="button" class="btn btn-outline-secondary" style="border-radius:0 10px 10px 0; border-color:#e2e8f0;"
                        onclick="var f=document.getElementById('pwdField'); f.type=f.type==='password'?'text':'password'">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
            </button>
        </form>

        <div class="demo-box mt-4">
            <p class="mb-1"><i class="bi bi-info-circle me-1"></i><strong>Demo Akun:</strong></p>
            <p>Owner: <strong>owner@biztrack.com</strong> / <strong>password</strong></p>
            <p>Kasir: <strong>cashier@biztrack.com</strong> / <strong>password</strong></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
