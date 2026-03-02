<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  /* Base Styles synchronized with Login */
  * { margin: 0; padding: 0; box-sizing: border-box; }
  
  body {
    background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), 
                url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 20px;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    overflow-x: hidden;
  }

  .register-page {
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    padding: 45px 35px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(0, 153, 255, 0.15);
    color: #fff;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeIn 0.8s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .register-page:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8),
                0 0 30px rgba(0, 153, 255, 0.25);
  }

  .register-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 20px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
  }

  h2 {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 5px;
    letter-spacing: 1px;
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

  .subtitle::before, .subtitle::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 30px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #0099ff, transparent);
  }
  .subtitle::before { left: -40px; }
  .subtitle::after { right: -40px; }

  .msg-container { margin-bottom: 20px; }

  .form-group { margin-bottom: 18px; position: relative; }

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

  .form-control:focus {
    background: rgba(15, 23, 42, 0.9) !important;
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.2) !important;
    outline: none;
  }

  .form-group:focus-within .input-icon { color: #0099ff; }

  /* Show Password Row Styling */
  .options-row {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    font-size: 14px;
    margin: -5px 0 15px 5px;
    color: #94a3b8;
  }

  .checkbox-wrapper { 
    display: flex; 
    align-items: center; 
    cursor: pointer; 
    user-select: none;
  }
  
  .checkbox-wrapper input { 
    accent-color: #0099ff; 
    margin-right: 8px; 
    width: 16px;
    height: 16px;
    cursor: pointer;
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
  }

  .btn-register:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px -5px rgba(0, 153, 255, 0.6);
  }

  .footer-links {
    margin-top: 25px;
    font-size: 14px;
    color: #94a3b8;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
  }

  .footer-links a { color: #0099ff; text-decoration: none; font-weight: 700; }

  @media (max-width: 480px) {
    .register-page { padding: 35px 25px; }
  }
</style>

<div class="register-page">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="register-logo" onerror="this.src='libs/images/default-logo.jpg'">
    <h2>Create Account</h2>
    <div class="subtitle">MoonLit WMS Registration</div>

    <div class="msg-container">
        <?php echo display_msg($msg); ?>
    </div>
    
    <form method="post" action="auth_signup.php" id="registerForm">
        <div class="form-group">
            <i class="fas fa-user-circle input-icon"></i>
            <input type="text" class="form-control" name="full_name" placeholder="Full Name" required autocomplete="name" autofocus>
        </div>
        
        <div class="form-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="username">
        </div>

        <div class="form-group">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" class="form-control" name="email" placeholder="Email Address" required autocomplete="email">
        </div>
        
        <div class="form-group" style="margin-bottom: 10px;">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autocomplete="new-password">
        </div>

        <div class="options-row">
            <label class="checkbox-wrapper">
                <input type="checkbox" id="show" onclick="togglePass()">
                <span>Show Password</span>
            </label>
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

<script>
// Toggle password visibility
function togglePass() {
    var passwordInput = document.getElementById("password");
    var showCheckbox = document.getElementById("show");
    passwordInput.type = showCheckbox.checked ? "text" : "password";
}

document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const registerBtn = document.getElementById('registerBtn');
    registerBtn.style.opacity = '0.7';
    registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>