<?php
  ob_start();
  require_once('includes/load.php');
  // Redirect if already logged in
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  body {
    /* Matching the high-end dark warehouse theme */
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

  .register-page {
    /* High-end Glassmorphism effects */
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    
    padding: 45px 35px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(0, 153, 255, 0.1);
    color: #fff;
    text-align: center;
    animation: fadeIn 0.8s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .register-logo {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 18px;
    margin-bottom: 15px;
    border: 2px solid rgba(255, 255, 255, 0.1);
  }

  h2 {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
    background: linear-gradient(to right, #fff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .subtitle {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
  }

  .form-group {
    margin-bottom: 18px;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 20px !important;
    height: 52px !important;
    transition: all 0.3s ease !important;
    font-size: 16px !important; /* Critical for mobile responsiveness */
  }

  .form-control:focus {
    background: rgba(15, 23, 42, 0.8) !important;
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.2) !important;
    outline: none;
  }

  .form-control::placeholder {
    color: #64748b;
  }

  .btn-register {
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
    margin-top: 10px;
    box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(0, 153, 255, 0.5);
    filter: brightness(1.1);
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
  }

  .footer-links a:hover {
    color: #33adff;
  }

  /* Landscape Mobile Optimization */
  @media (max-height: 650px) {
    body { padding: 40px 10px; align-items: flex-start; }
    .register-page { padding: 30px 25px; }
  }
</style>

<div class="register-page">
    <img src="libs/images/logo.jpg" alt="MoonLit Logo" class="register-logo">
    <h2>Create Account</h2>
    <div class="subtitle">MoonLit WMS Registration</div>

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
        
        <button type="submit" class="btn-register">SIGNUP</button>
    </form>

    <div class="footer-links">
        Already registered? <a href="index.php">Login here</a>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>