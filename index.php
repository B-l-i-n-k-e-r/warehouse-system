<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  body {
    /* Use the path to your warehouse image here */
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  }

  .login-card {
    background: rgba(255, 255, 255, 0.12); 
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 40px 30px;
    border-radius: 20px;
    width: 380px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    color: #ffffff;
    text-align: center;
  }

  .login-logo {
    width: 90px;
    margin-bottom: 5px;
  }

  .brand-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 25px;
    letter-spacing: 0.5px;
  }

  .form-control {
    background: rgba(50, 50, 50, 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 8px !important;
    color: #fff !important;
    padding: 12px 15px !important;
    height: 45px !important;
    margin-bottom: 15px;
  }

  .form-control::placeholder {
    color: #bbb;
  }

  /* The bright blue button from your photo */
  .btn-login {
    width: 100%;
    background-color: #0099ff !important;
    border: none !important;
    padding: 12px !important;
    border-radius: 8px !important;
    color: white !important;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 10px;
  }

  .btn-login:hover {
    background-color: #007acc !important;
  }

  .options-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    margin-bottom: 15px;
    padding: 0 5px;
  }

  /* Register Link Styling */
  .register-link {
    margin-top: 25px;
    font-size: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
  }

  .register-link a {
    color: #0099ff;
    text-decoration: none;
    font-weight: 600;
  }

  .register-link a:hover {
    text-decoration: underline;
    color: #33adff;
  }
</style>

<div class="login-card">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="login-logo">
    <div class="brand-title">MoonLit - WMS</div>

    <?php echo display_msg($msg); ?>
    
    <form method="post" action="auth.php">
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
        
        <div class="options-row">
            <div>
              <input type="checkbox" id="show" onclick="togglePass()">
              <label for="show" style="cursor:pointer">Show Password</label>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="glyphicon glyphicon-log-in"></i> Login
        </button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>

<script>
function togglePass() {
  var p = document.getElementById("password");
  p.type = p.type === "password" ? "text" : "password";
}
</script>

<?php include_once('layouts/footer.php'); ?>