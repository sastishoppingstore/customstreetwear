<?php
/**
 * Custom Streetwear v2 - Premium Admin Login
 * Animated 3D login page with enhanced security
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

if (isAdminLoggedIn()) {
    redirect(ADMIN_URL . '/dashboard.php');
}

$error = '';
$show2FA = false;
$tempToken = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    
    if (isset($_POST['step']) && $_POST['step'] === '2fa') {
        $tempToken = $_SESSION['2fa_temp_token'] ?? '';
        $code = trim($_POST['2fa_code'] ?? '');
        
        if (verify2FACode($tempToken, $code)) {
            $admin = dbFetchOne("SELECT * FROM admins WHERE id = ?", [$_SESSION['2fa_admin_id']]);
            if ($admin) {
                completeAdminLogin($admin);
                redirect(ADMIN_URL . '/dashboard.php');
            }
        } else {
            $error = 'Invalid verification code. Please try again.';
        }
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        $result = adminLoginV2($email, $password, $remember);
        
        if ($result['success']) {
            if ($result['2fa_required']) {
                $show2FA = true;
                $tempToken = $result['temp_token'];
            } else {
                redirect(ADMIN_URL . '/dashboard.php');
            }
        } else {
            $error = $result['error'];
        }
    }
}

$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo e(getSetting('site_name', 'Custom Streetwear')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0a;
            --bg-alt: #111;
            --card: #161616;
            --card-hover: #1c1c1c;
            --border: #2a2a2a;
            --text: #fff;
            --text-muted: #888;
            --accent: #39ff14;
            --accent-dark: #2dd410;
            --accent-glow: rgba(57,255,20,0.15);
            --danger: #ff4444;
            --font: 'Inter', -apple-system, sans-serif;
            --font-display: 'Oswald', sans-serif;
        }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        /* Animated Background */
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .login-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(57,255,20,0.03) 0%, transparent 70%);
            animation: bgPulse 8s ease-in-out infinite;
        }
        .login-bg-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(57,255,20,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(57,255,20,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }
        .login-bg-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 8s ease-in-out infinite;
        }
        .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; animation-duration: 7s; }
        .particle:nth-child(2) { left: 85%; top: 30%; animation-delay: 1s; animation-duration: 9s; }
        .particle:nth-child(3) { left: 20%; top: 70%; animation-delay: 2s; animation-duration: 8s; }
        .particle:nth-child(4) { left: 70%; top: 10%; animation-delay: 3s; animation-duration: 10s; }
        .particle:nth-child(5) { left: 40%; top: 80%; animation-delay: 4s; animation-duration: 6s; }
        .particle:nth-child(6) { left: 90%; top: 60%; animation-delay: 0.5s; animation-duration: 9s; }
        .particle:nth-child(7) { left: 50%; top: 40%; animation-delay: 1.5s; animation-duration: 7.5s; }
        .particle:nth-child(8) { left: 30%; top: 50%; animation-delay: 3.5s; animation-duration: 8.5s; }
        .particle:nth-child(9) { left: 60%; top: 25%; animation-delay: 2.5s; animation-duration: 11s; }
        .particle:nth-child(10) { left: 5%; top: 85%; animation-delay: 4.5s; animation-duration: 7s; }
        .particle:nth-child(11) { left: 75%; top: 75%; animation-delay: 0.8s; animation-duration: 9.5s; }
        .particle:nth-child(12) { left: 15%; top: 45%; animation-delay: 2.2s; animation-duration: 6.5s; }

        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }
        @keyframes particleFloat {
            0%, 100% { opacity: 0; transform: translateY(0) scale(0); }
            20% { opacity: 0.8; transform: translateY(-20px) scale(1); }
            80% { opacity: 0.4; transform: translateY(-60px) scale(0.5); }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: containerEnter 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }
        @keyframes containerEnter {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo .logo-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: #000;
            box-shadow: 0 0 30px var(--accent-glow);
        }
        .login-logo h1 {
            font-family: var(--font-display);
            font-size: 22px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .login-logo p {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 4px;
        }

        /* Login Card */
        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px 32px;
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
        }
        .login-card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .login-card-title svg {
            width: 20px;
            height: 20px;
            color: var(--accent);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .form-input-wrapper {
            position: relative;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .form-input::placeholder { color: #444; }
        .form-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            opacity: 0.5;
        }
        .form-input:focus + .form-input-icon { opacity: 1; color: var(--accent); }

        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        .toggle-password:hover { opacity: 1; }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
        }
        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: var(--accent); }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #000;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            box-shadow: 0 0 30px var(--accent-glow);
            transform: translateY(-1px);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }
        .btn-login:hover::after {
            transform: translateX(100%);
        }
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .btn-loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(0,0,0,0.2);
            border-top-color: #000;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid;
            animation: alertSlide 0.3s ease;
        }
        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-error {
            background: rgba(255,68,68,0.08);
            border-color: rgba(255,68,68,0.2);
            color: var(--danger);
        }
        .alert-info {
            background: rgba(57,255,20,0.08);
            border-color: rgba(57,255,20,0.2);
            color: var(--accent);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 24px;
        }
        .login-footer p {
            color: var(--text-muted);
            font-size: 12px;
        }
        .login-footer .default-cred {
            display: inline-block;
            padding: 6px 12px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: monospace;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* 2FA Input */
        .otp-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px 0;
        }
        .otp-input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            outline: none;
            transition: all 0.3s;
            font-family: inherit;
        }
        .otp-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        /* Shake animation */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .shake { animation: shake 0.5s ease; }

        @media (max-width: 480px) {
            .login-card { padding: 24px 20px; }
            .otp-input { width: 40px; height: 48px; font-size: 20px; }
        }

        /* Page transition */
        .page-exit {
            animation: pageExit 0.3s ease both;
        }
        @keyframes pageExit {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="login-bg">
        <div class="login-bg-grid"></div>
        <div class="login-bg-particles">
            <?php for ($i = 0; $i < 12; $i++): ?>
            <div class="particle"></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Login Container -->
    <div class="login-container" id="loginContainer">
        <div class="login-logo">
            <div class="logo-icon">CS</div>
            <h1>Custom Streetwear</h1>
            <p>Administration Panel</p>
        </div>

        <div class="login-card">
            <div class="login-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <?php echo $show2FA ? 'Two-Factor Authentication' : 'Sign In'; ?>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error" id="errorAlert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <?php echo e($error); ?>
            </div>
            <?php endif; ?>

            <?php if (!$show2FA): ?>
            <!-- Step 1: Login Form -->
            <form method="POST" action="" id="loginForm" onsubmit="return handleLogin(event)">
                <?php echo csrfField(); ?>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="form-input-wrapper">
                        <input type="email" name="email" class="form-input" required autofocus placeholder="admin@example.com" id="loginEmail" autocomplete="email">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <input type="password" name="password" class="form-input" required placeholder="••••••••" id="loginPassword" autocomplete="current-password">
                        <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="eyeIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="loginBtnText">Sign In</span>
                    <div class="btn-loader" id="loginBtnLoader"></div>
                </button>
            </form>
            <?php else: ?>
            <!-- Step 2: 2FA -->
            <form method="POST" action="" id="twoFAForm">
                <?php echo csrfField(); ?>
                <input type="hidden" name="step" value="2fa">
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:16px;">Enter the verification code from your authenticator app.</p>
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)" autofocus>
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="otpMove(this, event)" onkeydown="otpBack(this, event)">
                </div>
                <input type="hidden" name="2fa_code" id="2faCode">
                <button type="submit" class="btn-login">Verify & Sign In</button>
            </form>
            <?php endif; ?>
        </div>

        <div class="login-footer">
            <p>&copy; <?php echo $currentYear; ?> Custom Streetwear. All rights reserved.</p>
            <div class="default-cred">v2.0 | Secure Admin Panel</div>
        </div>
    </div>

    <script>
    // Toggle password visibility
    function togglePassword() {
        const pw = document.getElementById('loginPassword');
        const icon = document.getElementById('eyeIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            pw.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Login form handler
    function handleLogin(e) {
        const btn = document.getElementById('loginBtn');
        const text = document.getElementById('loginBtnText');
        const loader = document.getElementById('loginBtnLoader');
        
        btn.disabled = true;
        text.style.display = 'none';
        loader.style.display = 'block';
        
        return true;
    }

    // OTP input handling
    function otpMove(input, event) {
        if (input.value.length >= input.maxLength) {
            const next = input.nextElementSibling;
            if (next && next.classList.contains('otp-input')) {
                next.focus();
                next.select();
            }
        }
        updateOtpCode();
    }
    
    function otpBack(input, event) {
        if (event.key === 'Backspace' && input.value.length === 0) {
            const prev = input.previousElementSibling;
            if (prev && prev.classList.contains('otp-input')) {
                prev.focus();
                prev.select();
            }
        }
    }
    
    function updateOtpCode() {
        const inputs = document.querySelectorAll('.otp-input');
        let code = '';
        inputs.forEach(inp => { code += inp.value; });
        document.getElementById('2faCode').value = code;
    }

    // Auto-submit OTP when all filled
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('otp-input')) {
            const allFilled = Array.from(document.querySelectorAll('.otp-input')).every(inp => inp.value.length === 1);
            if (allFilled) {
                document.getElementById('twoFAForm').submit();
            }
        }
    });

    // Shake animation on error
    <?php if ($error): ?>
    document.getElementById('loginContainer')?.classList.add('shake');
    setTimeout(() => {
        document.getElementById('loginContainer')?.classList.remove('shake');
    }, 500);
    <?php endif; ?>
    </script>
</body>
</html>
