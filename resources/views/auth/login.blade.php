<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sales Management System - Sign In">
    <title>Sign In | Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
        }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated gradient background ── */
        .bg-wrap {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg,
                #e8d5f5 0%,
                #d4c5f0 15%,
                #c8d8f8 30%,
                #e0d0f8 45%,
                #f5d0e8 60%,
                #f8e0d5 75%,
                #edd5f8 90%,
                #d8e0fc 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            z-index: 0;
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── World map SVG overlay ── */
        .world-map-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            opacity: 0.18;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 810'%3E%3Cdefs%3E%3Cpattern id='dots' patternUnits='userSpaceOnUse' width='24' height='24'%3E%3Ccircle cx='4' cy='4' r='2' fill='%238b5cf6'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='1440' height='810' fill='url(%23dots)'/%3E%3C/svg%3E");
            background-size: cover;
        }

        /* ── Diagonal accent shapes ── */
        .accent-top {
            position: fixed;
            top: -60px;
            left: 50%;
            transform: translateX(-30%);
            width: 220px;
            height: 380px;
            background: linear-gradient(160deg, #7c3aed 0%, #db2777 100%);
            clip-path: polygon(40% 0%, 100% 0%, 60% 100%, 0% 100%);
            opacity: 0.75;
            z-index: 2;
            border-radius: 8px;
        }

        .accent-bottom {
            position: fixed;
            bottom: -60px;
            left: 50%;
            transform: translateX(-60%);
            width: 220px;
            height: 380px;
            background: linear-gradient(160deg, #4f46e5 0%, #9333ea 100%);
            clip-path: polygon(40% 0%, 100% 0%, 60% 100%, 0% 100%);
            opacity: 0.70;
            z-index: 2;
            border-radius: 8px;
        }

        /* ── Wavy left decoration ── */
        .wave-left {
            position: fixed;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 180px;
            height: 500px;
            z-index: 2;
            opacity: 0.25;
        }

        .wave-left svg { width: 100%; height: 100%; }

        /* ── Login card ── */
        .card-wrap {
            position: fixed;
            inset: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            pointer-events: none;
        }
        .login-card { pointer-events: all; }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 48px 44px;
            width: 100%;
            max-width: 420px;
            box-shadow:
                0 25px 60px rgba(124, 58, 237, 0.12),
                0 8px 30px rgba(0,0,0,0.08);
            animation: cardIn 0.5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-title {
            text-align: center;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .card-sub {
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 32px;
        }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #374151;
            background: #f9fafb;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: inherit;
        }

        .form-input::placeholder { color: #9ca3af; }

        .form-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
            background: #fff;
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            display: flex;
            align-items: center;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-pw:hover { color: #7c3aed; }

        /* ── Alert ── */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            color: #dc2626;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: none;
        }

        .alert-error.show { display: block; animation: shake 0.4s; }

        @keyframes shake {
            0%,100%{ transform: translateX(0); }
            25%{ transform: translateX(-6px); }
            75%{ transform: translateX(6px); }
        }

        /* ── Sign In button ── */
        .btn-signin {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #fff;
            background: linear-gradient(90deg, #ec4899 0%, #8b5cf6 50%, #4f46e5 100%);
            background-size: 200% 100%;
            cursor: pointer;
            transition: background-position 0.4s ease, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(139,92,246,0.35);
            margin-top: 8px;
            font-family: inherit;
        }

        .btn-signin:hover {
            background-position: 100% 0;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(139,92,246,0.45);
        }

        .btn-signin:active { transform: translateY(0); }

        .btn-signin.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        /* ── Spinner ── */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: 0 auto;
        }

        .btn-signin.loading .btn-text { display: none; }
        .btn-signin.loading .spinner { display: inline-block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 520px) {
            body {
                overflow-y: auto;
                overflow-x: hidden;
            }

            .card-wrap {
                align-items: flex-start;
                padding: max(16px, env(safe-area-inset-top)) 14px 24px;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .login-card {
                padding: 32px 22px;
                border-radius: 16px;
                max-width: 100%;
            }

            .card-title {
                font-size: 1.4rem;
            }

            .card-sub {
                margin-bottom: 24px;
            }

            .accent-top,
            .accent-bottom {
                opacity: 0.35;
            }

            .wave-left {
                opacity: 0.12;
            }
        }
    </style>
</head>
<body>

    <!-- Gradient background -->
    <div class="bg-wrap"></div>

    <!-- Dotted world-map overlay -->
    <div class="world-map-overlay"></div>

    <!-- Accent diagonal strips -->
    <div class="accent-top"></div>
    <div class="accent-bottom"></div>

    <!-- Wavy left decoration -->
    <div class="wave-left">
        <svg viewBox="0 0 180 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M90 0 Q130 80 70 160 Q10 240 90 320 Q170 400 80 500"
                  stroke="#7c3aed" stroke-width="3" fill="none" opacity="0.6"/>
            <path d="M60 0 Q100 80 40 160 Q-20 240 60 320 Q140 400 50 500"
                  stroke="#9333ea" stroke-width="2" fill="none" opacity="0.4"/>
            <path d="M120 0 Q160 80 100 160 Q40 240 120 320 Q200 400 110 500"
                  stroke="#4f46e5" stroke-width="2" fill="none" opacity="0.35"/>
        </svg>
    </div>

    <!-- Login Card -->
    <div class="card-wrap">
        <div class="login-card">
            <h1 class="card-title">SIGN IN</h1>
            <p class="card-sub">Enter your username and password to login</p>

            <div id="login-alert" class="alert-error">
                ⚠ Invalid username or password. Please try again.
            </div>

            <form id="login-form" method="POST" action="{{ route('login.post') }}" onsubmit="handleSubmit(event)">
                @csrf

                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-4 7a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
                            </svg>
                        </span>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            class="form-input"
                            placeholder="Enter Username"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-input"
                            placeholder="Enter Password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-pw" id="toggle-pw-btn" aria-label="Toggle password visibility">
                            <!-- Eye icon -->
                            <svg id="eye-open" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <!-- Eye-off icon -->
                            <svg id="eye-closed" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" id="signin-btn" class="btn-signin">
                    <span class="btn-text">SIGN IN</span>
                    <div class="spinner"></div>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('toggle-pw-btn');
        const pwInput   = document.getElementById('password');
        const eyeOpen   = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        toggleBtn.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type      = isHidden ? 'text' : 'password';
            eyeOpen.style.display   = isHidden ? 'none'  : 'block';
            eyeClosed.style.display = isHidden ? 'block' : 'none';
        });

        // Submit handler with loading state
        function handleSubmit(e) {
            const btn   = document.getElementById('signin-btn');
            const alert = document.getElementById('login-alert');
            alert.classList.remove('show');
            btn.classList.add('loading');
            // Let the form submit normally (remove this timeout for real backend)
            // For demo: revert after 2s
        }

        // Show error if session has errors
        @if(session('error'))
            document.getElementById('login-alert').classList.add('show');
            document.getElementById('login-alert').textContent = '⚠ {{ session("error") }}';
        @endif

        @if($errors->any())
            document.getElementById('login-alert').classList.add('show');
        @endif
    </script>
</body>
</html>
