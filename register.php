<?php
  ob_start();
  require_once('includes/load.php');
  // Redirect if already logged in
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  body {
    /* Background image path based on your folder structure */
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .register-page {
    background: rgba(255, 255, 255, 0.1); 
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 35px;
    border-radius: 20px;
    width: 400px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
    color: #fff;
    text-align: center;
  }

  .register-logo {
    width: 80px;
    margin-bottom: 10px;
    border-radius: 10px;
  }

  h2 {
    font-weight: 600;
    margin-bottom: 5px;
  }

  p {
    font-size: 14px;
    margin-bottom: 25px;
    opacity: 0.8;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-control {
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 10px !important;
    color: #fff !important;
    padding: 12px 15px !important;
    height: auto !important;
  }

  .form-control::placeholder {
    color: #ddd;
  }

  /* Bright blue button matching your login page */
  .btn-register {
    width: 100%;
    background-color: #0099ff !important;
    border: none !important;
    padding: 12px !important;
    border-radius: 10px !important;
    color: white !important;
    font-weight: bold !important;
    margin-top: 10px;
    transition: 0.3s;
  }

  .btn-register:hover {
    background-color: #007acc !important;
    transform: translateY(-1px);
  }

  .footer-links {
    margin-top: 20px;
    font-size: 14px;
  }

  .footer-links a {
    color: #0099ff;
    text-decoration: none;
    font-weight: bold;
  }
</style>

<div class="register-page">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="register-logo">
    <h2>Create Account</h2>
    <p>Join MoonLit - Warehouse Management</p>

    <?php echo display_msg($msg); ?>
    
    <form method="post" action="auth_signup.php">
        <div class="form-group">
            <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        
        <button type="submit" class="btn-register">Sign Up</button>
    </form>

    <div class="footer-links">
        Already have an account? <a href="index.php">Login here</a>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>