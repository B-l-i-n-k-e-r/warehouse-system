<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ob_start();
    require_once('includes/load.php');
    if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  body {
    background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), 
                url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 20px;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  .login-card {
    /* Advanced Glassmorphism */
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    
    padding: 50px 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(0, 153, 255, 0.15); /* Subtle brand glow */
    color: #ffffff;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8),
                0 0 30px rgba(0, 153, 255, 0.25);
  }

  .login-logo {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 24px;
    margin-bottom: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    border: 2px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease;
  }

  .login-logo:hover {
    transform: scale(1.05) rotate(5deg);
  }

  .brand-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: 2px;
    background: linear-gradient(135deg, #fff, #94a3b8, #0099ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradientShift 3s ease infinite;
    background-size: 200% 200%;
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .brand-subtitle {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 35px;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    display: inline-block;
  }

  .brand-subtitle::before,
  .brand-subtitle::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 30px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #0099ff, transparent);
  }

  .brand-subtitle::before {
    left: -40px;
  }

  .brand-subtitle::after {
    right: -40px;
  }

  /* Message container styling */
  .msg-container {
    margin-bottom: 25px;
  }

  .alert {
    padding: 15px 20px;
    border-radius: 14px;
    font-size: 14px;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideIn 0.3s ease;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .alert-danger {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fff;
  }

  .alert-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #fff;
  }

  .alert i {
    font-size: 18px;
  }

  .form-group {
    position: relative;
    margin-bottom: 20px;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 20px 12px 45px !important;
    height: 55px !important;
    width: 100%;
    transition: all 0.3s ease !important;
    font-size: 16px !important;
  }

  .form-control:hover {
    background: rgba(15, 23, 42, 0.8) !important;
    border-color: rgba(0, 153, 255, 0.5) !important;
  }

  .form-control:focus {
    background: rgba(15, 23, 42, 0.9) !important;
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.2) !important;
    outline: none;
  }

  .form-control::placeholder {
    color: rgba(148, 163, 184, 0.6);
    font-weight: 300;
  }

  /* Input icons */
  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 1;
    font-size: 18px;
    transition: color 0.3s ease;
  }

  .form-group:focus-within .input-icon {
    color: #0099ff;
  }

  .btn-login {
    width: 100%;
    background: linear-gradient(135deg, #0099ff 0%, #007acc 50%, #005c99 100%) !important;
    border: none !important;
    padding: 16px !important;
    border-radius: 14px !important;
    color: white !important;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 15px;
    box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
  }

  .btn-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
  }

  .btn-login:hover::before {
    left: 100%;
  }

  .btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px -5px rgba(0, 153, 255, 0.6);
    filter: brightness(1.1);
  }

  .btn-login:active {
    transform: translateY(-1px);
  }

  .btn-login i {
    margin-right: 8px;
  }

  .options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    margin: 20px 0;
    color: #94a3b8;
  }

  .checkbox-wrapper {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
  }

  .checkbox-wrapper input[type="checkbox"] {
    accent-color: #0099ff;
    width: 18px;
    height: 18px;
    margin-right: 8px;
    cursor: pointer;
  }

  .forgot-password {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .forgot-password:hover {
    color: #0099ff;
    text-decoration: underline;
  }

  .register-link {
    margin-top: 30px;
    font-size: 14px;
    color: #94a3b8;
    padding: 15px 0 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }

  .register-link a {
    color: #0099ff;
    text-decoration: none;
    font-weight: 700;
    margin-left: 5px;
    transition: color 0.2s, text-decoration 0.2s;
    position: relative;
  }

  .register-link a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: #0099ff;
    transition: width 0.3s ease;
  }

  .register-link a:hover {
    color: #33adff;
  }

  .register-link a:hover::after {
    width: 100%;
  }

  /* Password strength indicator (optional) */
  .password-strength {
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    margin-top: 5px;
    overflow: hidden;
  }

  .strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s ease, background 0.3s ease;
  }

  /* Loading state */
  .btn-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.8;
  }

  .btn-loading .btn-text {
    opacity: 0;
  }

  .btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid transparent;
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* Responsive Adjustments */
  @media (max-width: 480px) {
    .login-card {
      padding: 40px 25px;
      border-radius: 24px;
      background: rgba(15, 23, 42, 0.9);
      backdrop-filter: blur(10px);
    }
    
    body { 
      padding: 10px; 
    }

    .brand-title {
      font-size: 24px;
    }

    .brand-subtitle::before,
    .brand-subtitle::after {
      width: 20px;
    }

    .brand-subtitle::before {
      left: -30px;
    }

    .brand-subtitle::after {
      right: -30px;
    }
  }

  /* Dark mode support */
  @media (prefers-color-scheme: dark) {
    .login-card {
      background: rgba(15, 23, 42, 0.8);
    }
  }
</style>

<div class="login-card">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="login-logo" onerror="this.src='libs/images/default-logo.jpg'">
    <div class="brand-title">MOONLIT</div>
    <div class="brand-subtitle">Warehouse Management System</div>

    <div class="msg-container">
        <?php echo display_msg($msg); ?>
    </div>
    
    <form method="post" action="auth.php" id="loginForm">
        <div class="form-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="username" autofocus>
        </div>
        
        <div class="form-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" class="form-control" name="password" placeholder="Password" required autocomplete="current-password">
            <!-- Optional password strength indicator -->
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bar" id="strengthBar"></div>
            </div>
        </div>
        
        <div class="options-row">
            <label class="checkbox-wrapper">
                <input type="checkbox" id="show" onclick="togglePass()">
                <span>Show Password</span>
            </label>
            <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i>
            <span class="btn-text">Login to Dashboard</span>
        </button>
    </form>

    <div class="register-link">
        <span>New to the system?</span>
        <a href="register.php">Create Account →</a>
    </div>
</div>

<script>
// Toggle password visibility
function togglePass() {
    var passwordInput = document.getElementById("password");
    var showCheckbox = document.getElementById("show");
    passwordInput.type = showCheckbox.checked ? "text" : "password";
}

// Optional: Password strength indicator
document.getElementById('password')?.addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    let strength = 0;
    
    if (password.length > 0) {
        // Length check
        if (password.length >= 8) strength += 25;
        // Contains number
        if (/\d/.test(password)) strength += 25;
        // Contains lowercase
        if (/[a-z]/.test(password)) strength += 25;
        // Contains uppercase or special char
        if (/[A-Z]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
    }
    
    strengthBar.style.width = strength + '%';
    
    // Color based on strength
    if (strength <= 25) {
        strengthBar.style.background = '#ef4444';
    } else if (strength <= 50) {
        strengthBar.style.background = '#f59e0b';
    } else if (strength <= 75) {
        strengthBar.style.background = '#3b82f6';
    } else {
        strengthBar.style.background = '#10b981';
    }
});

// Form submission with loading state
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    const loginBtn = document.getElementById('loginBtn');
    loginBtn.classList.add('btn-loading');
    
    // Remove loading state after 3 seconds (in case of slow response)
    setTimeout(() => {
        loginBtn.classList.remove('btn-loading');
    }, 3000);
});

// Auto-hide messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include_once('layouts/footer.php'); ?>