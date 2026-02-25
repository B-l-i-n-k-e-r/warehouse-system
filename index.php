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
    transition: transform 0.3s ease;
  }

  .login-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    border: 2px solid rgba(255, 255, 255, 0.1);
  }

  .brand-title {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: 1px;
    background: linear-gradient(to right, #fff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .brand-subtitle {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 35px;
    text-transform: uppercase;
    letter-spacing: 2px;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 20px !important;
    height: 55px !important;
    margin-bottom: 20px;
    transition: all 0.3s ease !important;
    font-size: 16px !important;
  }

  .form-control:focus {
    background: rgba(15, 23, 42, 0.8) !important;
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.2) !important;
    outline: none;
  }

  .btn-login {
    width: 100%;
    background: linear-gradient(135deg, #0099ff 0%, #007acc 100%) !important;
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
  }

  .btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px -5px rgba(0, 153, 255, 0.5);
    filter: brightness(1.1);
  }

  .options-row {
    display: flex;
    align-items: center;
    font-size: 14px;
    margin-bottom: 20px;
    color: #94a3b8;
  }

  .options-row input[type="checkbox"] {
    accent-color: #0099ff;
    width: 18px;
    height: 18px;
    margin-right: 10px;
  }

  .register-link {
    margin-top: 30px;
    font-size: 14px;
    color: #94a3b8;
  }

  .register-link a {
    color: #0099ff;
    text-decoration: none;
    font-weight: 700;
    margin-left: 5px;
    transition: color 0.2s;
  }

  .register-link a:hover {
    color: #33adff;
  }

  /* Responsive Adjustments */
  @media (max-width: 480px) {
    .login-card {
      padding: 40px 25px;
      border-radius: 0; /* Full screen feel on very small mobile */
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(10px);
    }
    body { padding: 0; }
  }
</style>

<div class="login-card">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="login-logo">
    <div class="brand-title">MOONLIT</div>
    <div class="brand-subtitle">Warehouse Management</div>

    <?php echo display_msg($msg); ?>
    
    <form method="post" action="auth.php">
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
        
        <div class="options-row">
            <input type="checkbox" id="show" onclick="togglePass()">
            <label for="show" style="cursor:pointer; user-select: none;">Show Password</label>
        </div>

        <button type="submit" class="btn-login">
           Login
        </button>
    </form>

    <div class="register-link">
        New to the system? <a href="register.php">Create Account</a>
    </div>
</div>

<script>
function togglePass() {
  var p = document.getElementById("password");
  p.type = p.type === "password" ? "text" : "password";
}
</script>

<?php include_once('layouts/footer.php'); ?>