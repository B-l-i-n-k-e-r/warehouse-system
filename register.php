<?php
  ob_start();
  require_once('includes/load.php');
  // Redirect if already logged in
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  /* Reset and Base Styles */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  html, body {
    height: 100%;
    width: 100%;
  }

  body {
    /* High-end dark warehouse theme */
    background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), 
                url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    
    /* Perfect Centering */
    display: flex;
    align-items: center;
    justify-content: center;
    
    min-height: 100vh;
    margin: 0;
    padding: 20px;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    
    /* Prevent any unwanted scrollbars */
    overflow-x: hidden;
  }

  /* Centering container for extra safety */
  .center-wrapper {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .register-page {
    /* High-end Glassmorphism effects */
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    
    padding: 45px 35px;
    width: 100%;
    max-width: 440px;
    
    /* Ensure card stays centered */
    margin: auto;
    
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(0, 153, 255, 0.15);
    color: #fff;
    text-align: center;
    animation: fadeIn 0.8s ease-out;
    
    /* Prevent overflow on small screens */
    max-height: calc(100vh - 40px);
    overflow-y: auto;
  }

  /* Add hover effect for premium feel */
  .register-page:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8),
                0 0 30px rgba(0, 153, 255, 0.25);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  @keyframes fadeIn {
    from { 
      opacity: 0; 
      transform: translateY(20px); 
    }
    to { 
      opacity: 1; 
      transform: translateY(0); 
    }
  }

  .register-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 20px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    transition: transform 0.3s ease;
  }

  .register-logo:hover {
    transform: scale(1.05) rotate(5deg);
  }

  h2 {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
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

  .subtitle {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    display: inline-block;
  }

  .subtitle::before,
  .subtitle::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 30px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #0099ff, transparent);
  }

  .subtitle::before {
    left: -40px;
  }

  .subtitle::after {
    right: -40px;
  }

  /* Message container styling */
  .msg-container {
    margin-bottom: 20px;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.3s ease;
    margin-bottom: 10px;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-10px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
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
    font-size: 16px;
  }

  .form-group {
    margin-bottom: 18px;
    position: relative;
  }

  /* Input with icons */
  .input-wrapper {
    position: relative;
  }

  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    z-index: 1;
    font-size: 16px;
    transition: color 0.3s ease;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 20px 12px 45px !important;
    height: 52px !important;
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

  .form-group:focus-within .input-icon {
    color: #0099ff;
  }

  .form-control::placeholder {
    color: #64748b;
    font-weight: 300;
  }

  /* Password strength indicator */
  .password-strength {
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    margin-top: 6px;
    overflow: hidden;
  }

  .strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s ease, background 0.3s ease;
  }

  /* Terms and conditions */
  .terms-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 15px 0;
    color: #94a3b8;
    font-size: 13px;
    text-align: left;
  }

  .terms-checkbox input[type="checkbox"] {
    accent-color: #0099ff;
    width: 16px;
    height: 16px;
    cursor: pointer;
  }

  .terms-checkbox label {
    cursor: pointer;
  }

  .terms-checkbox a {
    color: #0099ff;
    text-decoration: none;
  }

  .terms-checkbox a:hover {
    text-decoration: underline;
  }

  .btn-register {
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
    margin-top: 10px;
    box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
  }

  .btn-register::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
  }

  .btn-register:hover::before {
    left: 100%;
  }

  .btn-register:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px -5px rgba(0, 153, 255, 0.6);
    filter: brightness(1.1);
  }

  .btn-register:active {
    transform: translateY(-1px);
  }

  .btn-register i {
    margin-right: 8px;
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

  .footer-links {
    margin-top: 25px;
    font-size: 14px;
    color: #94a3b8;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
  }

  .footer-links a {
    color: #0099ff;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.2s;
    position: relative;
  }

  .footer-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: #0099ff;
    transition: width 0.3s ease;
  }

  .footer-links a:hover {
    color: #33adff;
  }

  .footer-links a:hover::after {
    width: 100%;
  }

  /* Responsive adjustments */
  @media (max-width: 480px) {
    body {
      padding: 10px;
    }
    
    .register-page {
      padding: 30px 20px;
      border-radius: 24px;
      background: rgba(15, 23, 42, 0.9);
    }
    
    h2 {
      font-size: 24px;
    }
    
    .subtitle::before,
    .subtitle::after {
      width: 20px;
    }
    
    .subtitle::before {
      left: -30px;
    }
    
    .subtitle::after {
      right: -30px;
    }
  }

  /* Landscape mode for phones */
  @media (max-height: 650px) and (orientation: landscape) {
    body {
      padding: 20px 10px;
      align-items: flex-start;
    }
    
    .register-page {
      padding: 25px 20px;
      max-height: calc(100vh - 40px);
      overflow-y: auto;
    }
    
    .register-logo {
      width: 50px;
      height: 50px;
      margin-bottom: 10px;
    }
    
    h2 {
      font-size: 20px;
      margin-bottom: 2px;
    }
    
    .subtitle {
      margin-bottom: 15px;
    }
    
    .form-group {
      margin-bottom: 12px;
    }
    
    .form-control {
      height: 45px !important;
      padding: 8px 20px 8px 40px !important;
    }
  }

  /* Dark mode support */
  @media (prefers-color-scheme: dark) {
    .register-page {
      background: rgba(15, 23, 42, 0.8);
    }
  }

  /* Ensure footer links are always visible */
  .footer-links {
    position: relative;
    z-index: 1;
  }
</style>

<div class="center-wrapper">
    <div class="register-page">
        <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="register-logo" onerror="this.src='libs/images/default-logo.jpg'">
        <h2>Create Account</h2>
        <div class="subtitle">MoonLit WMS Registration</div>

        <div class="msg-container">
            <?php echo display_msg($msg); ?>
        </div>
        
        <form method="post" action="auth_signup.php" id="registerForm">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" name="full_name" placeholder="Full Name" required autocomplete="name" autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-at input-icon"></i>
                    <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="username">
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autocomplete="new-password">
                </div>
                <!-- Password strength indicator -->
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>
            
            <!-- Optional: Confirm Password field -->
            <!-- <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required autocomplete="new-password">
                </div>
            </div> -->
            
            <!-- Terms and conditions -->
            <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and <a href="privacy.php" target="_blank">Privacy Policy</a></label>
            </div>
            
            <button type="submit" class="btn-register" id="registerBtn">
                <i class="fas fa-user-plus"></i>
                <span class="btn-text">Create Account</span>
            </button>
        </form>

        <div class="footer-links">
            Already registered? <a href="index.php">Sign in →</a>
        </div>
    </div>
</div>

<script>
// Password strength indicator
document.getElementById('password')?.addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    let strength = 0;
    
    if (password.length > 0) {
        // Length check (min 8 characters)
        if (password.length >= 8) strength += 25;
        // Contains number
        if (/\d/.test(password)) strength += 25;
        // Contains lowercase
        if (/[a-z]/.test(password)) strength += 25;
        // Contains uppercase or special character
        if (/[A-Z]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
    }
    
    strengthBar.style.width = strength + '%';
    
    // Color based on strength
    if (strength <= 25) {
        strengthBar.style.background = '#ef4444'; // Weak - Red
    } else if (strength <= 50) {
        strengthBar.style.background = '#f59e0b'; // Fair - Orange
    } else if (strength <= 75) {
        strengthBar.style.background = '#3b82f6'; // Good - Blue
    } else {
        strengthBar.style.background = '#10b981'; // Strong - Green
    }
});

// Form submission with loading state
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const registerBtn = document.getElementById('registerBtn');
    
    // Add loading state
    registerBtn.classList.add('btn-loading');
    registerBtn.disabled = true;
    
    // Remove loading state after 5 seconds (in case of timeout)
    setTimeout(() => {
        registerBtn.classList.remove('btn-loading');
        registerBtn.disabled = false;
    }, 5000);
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

// Optional: Confirm password validation
// document.getElementById('confirm_password')?.addEventListener('input', function(e) {
//     const password = document.getElementById('password').value;
//     const confirm = e.target.value;
//     
//     if (confirm.length > 0) {
//         if (password === confirm) {
//             e.target.style.borderColor = '#10b981';
//         } else {
//             e.target.style.borderColor = '#ef4444';
//         }
//     } else {
//         e.target.style.borderColor = '';
//     }
// });
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include_once('layouts/footer.php'); ?>