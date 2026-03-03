<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  /* Base Styles synchronized with MoonLit Ecosystem */
  * { margin: 0; padding: 0; box-sizing: border-box; }
  
  body {
    background: linear-gradient(rgba(10, 15, 30, 0.85), rgba(10, 15, 30, 0.85)), 
                url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 20px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    overflow-x: hidden;
  }

  .register-page {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 32px;
    padding: 60px 50px;
    width: 100%;
    max-width: 640px; /* Increased Width as requested */
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    color: #fff;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    animation: slideUp 0.6s ease-out;
  }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .register-page:hover {
    transform: translateY(-5px);
    border-color: rgba(0, 153, 255, 0.3);
    box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.7),
                0 0 40px rgba(0, 153, 255, 0.15);
  }

  /* Grid Layout for larger width */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    text-align: left;
  }

  .full-width {
    grid-column: span 2;
  }

  .register-logo {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 24px;
    border: 2px solid rgba(255, 255, 255, 0.15);
    margin-bottom: 20px;
  }

  h2 {
    font-size: 32px;
    font-weight: 900;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #fff 0%, #0099ff 50%, #fff 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shine 4s linear infinite;
  }

  @keyframes shine {
    to { background-position: 200% center; }
  }

  .subtitle {
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 4px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
  }

  .subtitle::before, .subtitle::after {
    content: '';
    height: 1px;
    width: 30px;
    background: rgba(0, 153, 255, 0.4);
  }

  .form-group { margin-bottom: 5px; position: relative; }

  .input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 16px;
    transition: color 0.3s ease;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.4) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 16px !important;
    color: #fff !important;
    padding: 12px 20px 12px 50px !important;
    height: 60px !important;
    width: 100%;
    transition: all 0.3s ease !important;
    font-size: 15px !important;
  }

  .form-control:focus {
    background: rgba(15, 23, 42, 0.7) !important;
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.15) !important;
    outline: none;
  }

  .form-group:focus-within .input-icon { color: #0099ff; }

  .options-row {
    grid-column: span 2;
    display: flex;
    align-items: center;
    font-size: 13px;
    margin: 5px 0 15px 5px;
    color: #94a3b8;
  }

  .checkbox-wrapper { 
    display: flex; 
    align-items: center; 
    cursor: pointer; 
  }
  
  .checkbox-wrapper input { 
    accent-color: #0099ff; 
    margin-right: 10px; 
    width: 17px; height: 17px;
  }

  .btn-register {
    grid-column: span 2;
    width: 100%;
    background: linear-gradient(135deg, #0099ff, #0066ff) !important;
    border: none !important;
    padding: 20px !important;
    border-radius: 18px !important;
    color: white !important;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 153, 255, 0.4);
    margin-top: 10px;
  }

  .btn-register:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 35px -5px rgba(0, 153, 255, 0.5);
  }

  .footer-links {
    margin-top: 35px;
    font-size: 14px;
    color: #64748b;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding-top: 25px;
  }

  .footer-links a { color: #0099ff; text-decoration: none; font-weight: 600; }

  /* Responsive Fix for Mobile */
  @media (max-width: 600px) {
    .form-grid { grid-template-columns: 1fr; }
    .full-width { grid-column: span 1; }
    .options-row { grid-column: span 1; }
    .btn-register { grid-column: span 1; }
    .register-page { padding: 40px 25px; }
  }
</style>

<div class="register-page">
    <div class="register-logo-wrapper">
        <img src="libs/images/logo.jpg" alt="Logo" class="register-logo" onerror="this.src='libs/images/default-logo.jpg'">
    </div>
    
    <h2>Create Account</h2>
    <div class="subtitle">Join MoonLit WMS</div>

    <div class="msg-container">
        <?php echo display_msg($msg); ?>
    </div>
    
    <form method="post" action="auth_signup.php" id="registerForm" class="form-grid">
        <div class="form-group">
            <i class="fas fa-id-card input-icon"></i>
            <input type="text" class="form-control" name="full_name" placeholder="Full Name" required autocomplete="name" autofocus>
        </div>
        
        <div class="form-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="username">
        </div>

        <div class="form-group full-width">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" class="form-control" name="email" placeholder="Email Address" required autocomplete="email">
        </div>
        
        <div class="form-group full-width">
            <i class="fas fa-key input-icon"></i>
            <input type="password" class="form-control" name="password" id="password" placeholder="Create Password" required autocomplete="new-password">
        </div>

        <div class="options-row">
            <label class="checkbox-wrapper">
                <input type="checkbox" id="show" onclick="togglePass()">
                <span>Show Password</span>
            </label>
        </div>
        
        <button type="submit" class="btn-register" id="registerBtn">
            <span class="btn-text">Initialize System Access</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </form>

    <div class="footer-links">
        Already have access? <a href="index.php">Sign In</a>
    </div>
</div>

<script>
function togglePass() {
    const p = document.getElementById("password");
    const c = document.getElementById("show");
    p.type = c.checked ? "text" : "password";
}

document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('registerBtn');
    const text = btn.querySelector('.btn-text');
    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.8';
    text.innerText = 'Provisioning...';
    btn.querySelector('i').className = 'fas fa-circle-notch fa-spin';
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>