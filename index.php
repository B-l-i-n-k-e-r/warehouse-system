<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
/* Modern Reset & Base */
*{margin:0;padding:0;box-sizing:border-box;}

body{
  background: linear-gradient(rgba(10, 15, 30, 0.85), rgba(10, 15, 30, 0.85)),
              url('libs/images/warehouse.jpg') no-repeat center center fixed;
  background-size: cover;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.auth-card{
  background: rgba(255, 255, 255, 0.03);
  backdrop-filter: blur(25px) saturate(180%);
  -webkit-backdrop-filter: blur(25px) saturate(180%);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 40px; /* Slightly rounder for the larger width */
  padding: 70px 80px; /* Generous padding for the 640px width */
  width: 100%;
  max-width: 640px; /* Increased Width matched to Registration */
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
              0 0 40px rgba(0, 153, 255, 0.1);
  color: #fff;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.auth-card:hover{
  transform: translateY(-8px);
  border-color: rgba(0, 153, 255, 0.3);
  box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.6),
              0 0 60px rgba(0, 153, 255, 0.2);
}

.auth-logo-wrapper { 
  margin-bottom: 30px; 
}

.auth-logo{ 
  width: 110px; 
  height: 110px; 
  border-radius: 30px; 
  border: 2px solid rgba(255, 255, 255, 0.15);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.auth-title{
  font-size: 42px; /* Large, bold branding */
  font-weight: 900;
  margin-bottom: 10px;
  letter-spacing: -1px;
  background: linear-gradient(135deg, #ffffff 0%, #0099ff 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.auth-subtitle{
  font-size: 13px;
  color: #94a3b8;
  margin-bottom: 50px;
  text-transform: uppercase;
  letter-spacing: 6px; /* Elegant wide tracking */
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
}

.auth-subtitle::before, .auth-subtitle::after {
  content: '';
  height: 1px;
  width: 30px;
  background: rgba(0, 153, 255, 0.3);
}

.form-group{ position: relative; margin-bottom: 25px; }
.input-icon{ position: absolute; left: 22px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 20px; transition: color 0.3s ease; }

.form-control{
  background: rgba(15, 23, 42, 0.4) !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  border-radius: 18px !important;
  color: #fff !important;
  padding: 12px 20px 12px 60px !important;
  height: 65px !important;
  width: 100%;
  font-size: 17px !important;
  transition: all 0.3s ease !important;
}

.form-control:focus{ 
  border-color: #0099ff !important; 
  background: rgba(15, 23, 42, 0.6) !important;
  box-shadow: 0 0 0 5px rgba(0, 153, 255, 0.15) !important; 
  outline: none; 
}

.form-group:focus-within .input-icon { color: #0099ff; }

.options-row{ 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  font-size: 15px; 
  margin-bottom: 35px; 
  padding: 0 5px;
  color: #94a3b8; 
}

.checkbox-wrapper{ display: flex; align-items: center; cursor: pointer; user-select: none; }
.checkbox-wrapper input{ accent-color: #0099ff; margin-right: 12px; width: 19px; height: 19px; cursor: pointer; }

.btn-primary-auth{
  width: 100%;
  background: linear-gradient(135deg, #0099ff, #0066ff);
  border: none;
  padding: 22px;
  border-radius: 18px;
  color: #fff;
  font-size: 17px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
  transition: all 0.3s ease;
}

.btn-primary-auth:hover {
  transform: scale(1.02);
  box-shadow: 0 15px 30px -5px rgba(0, 153, 255, 0.5);
}

.auth-footer{ 
  margin-top: 50px; 
  font-size: 15px; 
  color: #64748b; 
  border-top: 1px solid rgba(255, 255, 255, 0.05); 
  padding-top: 30px; 
}

.auth-footer a{ color: #0099ff; text-decoration: none; font-weight: 600; transition: 0.3s; }
.auth-footer a:hover { text-decoration: underline; }

/* Responsive adjustments */
@media (max-width: 640px) {
  .auth-card { padding: 50px 30px; }
  .auth-title { font-size: 32px; }
}
</style>

<div class="auth-card">
  <div class="auth-logo-wrapper">
    <img src="libs/images/logo.jpg" class="auth-logo" alt="Logo" onerror="this.src='libs/images/default-logo.jpg'">
  </div>
  
  <div class="auth-title">MOONLIT</div>
  <div class="auth-subtitle">Warehouse Management</div>
  
  <div class="msg-container">
    <?php echo display_msg($msg); ?>
  </div>

  <form method="post" action="auth.php">
    <div class="form-group">
      <i class="fas fa-user-astronaut input-icon"></i>
      <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
    </div>
    
    <div class="form-group">
      <i class="fas fa-key input-icon"></i>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
    </div>
    
    <div class="options-row">
      <label class="checkbox-wrapper">
        <input type="checkbox" onclick="togglePass()">
        <span>Show Password</span>
      </label>
      <a href="forgot-password.php" style="color:#0099ff; text-decoration:none; font-weight: 500;">Forgot Password?</a>
    </div>
    
    <button type="submit" class="btn-primary-auth">
      Access Dashboard <i class="fas fa-sign-in-alt"></i>
    </button>
  </form>
  
  <div class="auth-footer">
    Not part of the fleet? <a href="register.php">Create Account</a>
  </div>
</div>

<script>
function togglePass(){ 
  const x = document.getElementById("password"); 
  x.type = (x.type === "password") ? "text" : "password"; 
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>