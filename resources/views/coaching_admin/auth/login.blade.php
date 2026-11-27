<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaching Institute Admin Portal | Findstudyroom</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, #090d16 0%, #030712 90%);
            min-height: 100vh;
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow */
        .ambient-glow-1 {
            position: absolute;
            top: -120px;
            right: -120px;
            width: 450px;
            height: 450px;
            background: rgba(13, 148, 136, 0.15);
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }
        .ambient-glow-2 {
            position: absolute;
            bottom: -120px;
            left: -120px;
            width: 450px;
            height: 450px;
            background: rgba(249, 115, 22, 0.1);
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }

        .login-card {
            background: rgba(19, 29, 50, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 440px;
            padding: 40px;
            position: relative;
            z-index: 10;
        }

        .portal-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.3), rgba(20, 184, 166, 0.15));
            border: 1px solid rgba(13, 148, 136, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #14b8a6;
            margin: 0 auto 20px;
        }

        .portal-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 24px;
            color: #fff;
            text-align: center;
            margin-bottom: 6px;
        }

        .portal-desc {
            font-size: 13px;
            color: #94a3b8;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-right: none;
            color: #64748b;
            border-radius: 12px 0 0 12px;
            padding-left: 14px;
            padding-right: 14px;
        }

        .form-control-dark {
            background-color: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-left: none;
            color: #fff !important;
            border-radius: 0 12px 12px 0;
            padding: 12px 14px;
            font-size: 14px;
        }

        .form-control-dark:focus {
            background-color: #111c33;
            border-color: #14b8a6;
            box-shadow: none;
            color: #fff;
        }

        .input-group:focus-within .input-group-text {
            border-color: #14b8a6;
            color: #14b8a6;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            padding: 13px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px -4px rgba(13, 148, 136, 0.4);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(13, 148, 136, 0.6);
            color: #fff;
        }

        .demo-pill {
            background: rgba(255, 255, 255, 0.04);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 24px;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="login-card">
        <!-- Icon -->
        <div class="portal-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <h1 class="portal-title">Coaching Admin Portal</h1>
        <p class="portal-desc">Sign in with your institute credentials to manage profile &amp; leads</p>

        <!-- Flash alerts -->
        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 rounded-3 text-xs mb-3 d-flex align-items-center" style="font-size: 12px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;">
                <i class="fas fa-exclamation-circle me-2 fs-6"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 rounded-3 text-xs mb-3 d-flex align-items-center" style="font-size: 12px; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #86efac;">
                <i class="fas fa-check-circle me-2 fs-6"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('coaching.login.post') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Institute Admin Email / User</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-dark @error('email') is-invalid @enderror" placeholder="e.g. themovement@findstudyroom.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0">Password</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control form-control-dark @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0 border-top border-bottom border-end border-white/10 text-muted" type="button" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label text-muted small" for="rememberMe" style="font-size: 12px;">
                        Remember my session
                    </label>
                </div>
                <span class="badge bg-dark text-teal-400 border border-teal-500/30" style="font-size: 11px;">Encrypted 256-bit</span>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-sign-in-alt me-2"></i> Enter Coaching Dashboard
            </button>
        </form>

        <div class="demo-pill text-center">
            <div class="fw-bold text-white mb-1"><i class="fas fa-shield-alt text-teal-400 me-1"></i> Isolated Multi-Tenant Portal</div>
            <div>Coaching admins can only view their own leads and profile. Credentials are provided by the Main Administrator.</div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-muted text-decoration-none small hover-underline" style="font-size: 12px;">
                <i class="fas fa-arrow-left me-1"></i> Back to Findstudyroom
            </a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
