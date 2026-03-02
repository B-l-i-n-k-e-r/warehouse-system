<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
  background:linear-gradient(rgba(15,23,42,0.8),rgba(15,23,42,0.8)),
             url('libs/images/warehouse.jpg') no-repeat center center fixed;
  background-size:cover;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:20px;
  font-family:'Inter','Segoe UI',system-ui,sans-serif;
}

.auth-card{
  background:rgba(255,255,255,0.07);
  backdrop-filter:blur(25px) saturate(180%);
  border:1px solid rgba(255,255,255,0.1);
  border-radius:28px;
  padding:50px 40px;
  width:100%;
  max-width:430px;
  box-shadow:0 25px 50px -12px rgba(0,0,0,0.7),
             0 0 20px rgba(0,153,255,0.15);
  color:#fff;
  text-align:center;
  transition:all .3s ease;
}

.auth-card:hover{
  transform:translateY(-5px);
  box-shadow:0 30px 60px -12px rgba(0,0,0,0.8),
             0 0 30px rgba(0,153,255,0.25);
}

.auth-logo{
  width:90px;height:90px;object-fit:cover;
  border-radius:24px;margin-bottom:20px;
  border:2px solid rgba(255,255,255,0.1);
  box-shadow:0 10px 20px rgba(0,0,0,0.3);
}

.auth-title{
  font-size:28px;font-weight:800;margin-bottom:5px;
  background:linear-gradient(135deg,#fff,#94a3b8,#0099ff);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}

.auth-subtitle{
  font-size:13px;color:#94a3b8;margin-bottom:30px;
  text-transform:uppercase;letter-spacing:2px;
}

.form-group{position:relative;margin-bottom:18px;}

.input-icon{
  position:absolute;left:16px;top:50%;
  transform:translateY(-50%);
  color:#94a3b8;font-size:18px;
}

.form-control{
  background:rgba(15,23,42,0.6)!important;
  border:1px solid rgba(255,255,255,0.1)!important;
  border-radius:14px!important;
  color:#fff!important;
  padding:12px 20px 12px 45px!important;
  height:55px!important;width:100%;
  font-size:16px!important;
}

.form-control:focus{
  border-color:#0099ff!important;
  box-shadow:0 0 0 4px rgba(0,153,255,0.2)!important;
  outline:none;
}

.options-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  font-size:14px;
  margin:5px 0 10px 5px;
  color:#94a3b8;
}

.checkbox-wrapper{
  display:flex;
  align-items:center;
  cursor:pointer;
}

.checkbox-wrapper input{
  accent-color:#0099ff;
  margin-right:6px;
  cursor:pointer;
}

.forgot-link{
  color:#0099ff;
  text-decoration:none;
  font-weight:600;
  transition:.3s;
}

.forgot-link:hover{
  text-decoration:underline;
  color:#33adff;
}

.btn-primary-auth{
  width:100%;
  background:linear-gradient(135deg,#0099ff,#005c99);
  border:none;padding:16px;
  border-radius:14px;color:#fff;
  font-size:16px;font-weight:700;
  margin-top:10px;cursor:pointer;
  transition:.3s ease;
}

.btn-primary-auth:hover{
  transform:translateY(-3px);
  box-shadow:0 15px 30px -5px rgba(0,153,255,0.6);
}

.auth-footer{
  margin-top:25px;font-size:14px;
  color:#94a3b8;
  border-top:1px solid rgba(255,255,255,0.1);
  padding-top:20px;
}

.auth-footer a{
  color:#0099ff;text-decoration:none;font-weight:700;
}
</style>

<div class="auth-card">
  <img src="libs/images/logo.jpg" class="auth-logo" alt="Logo">
  <div class="auth-title">MOONLIT</div>
  <div class="auth-subtitle">Warehouse Management System</div>

  <?php echo display_msg($msg); ?>

  <form method="post" action="auth.php" id="loginForm">
    
    <div class="form-group">
      <i class="fas fa-user input-icon"></i>
      <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>

    <div class="form-group">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
    </div>

    <div class="options-row">
      <label class="checkbox-wrapper">
        <input type="checkbox" onclick="togglePass()">
        Show Password
      </label>

      <a href="forgot-password.php" class="forgot-link">
        Forgot Password?
      </a>
    </div>

    <button type="submit" class="btn-primary-auth">
      <i class="fas fa-sign-in-alt"></i> Login to Dashboard
    </button>

  </form>

  <div class="auth-footer">
    New to the system? <a href="register.php">Create Account →</a>
  </div>
</div>

<script>
function togglePass(){
  var x=document.getElementById("password");
  x.type=(x.type==="password")?"text":"password";
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>