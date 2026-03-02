<?php
require_once('includes/load.php');

// Ensure the timezone matches your configuration
date_default_timezone_set('Africa/Nairobi');

$success = false; // Initialize the success flag

if(isset($_POST['submit_reset'])){
  $token = $db->escape($_POST['token']);
  $password = $db->escape($_POST['new_password']);
  
  // 1. Double check token validity
  $user = find_by_sql("SELECT id FROM users WHERE reset_token='{$token}' AND reset_token_expires > NOW() LIMIT 1");
  
  if($user){
    $user_id = $user[0]['id'];
    
    // 2. Hash new password 
    $hashed_password = sha1($password); 
    
    // 3. Update DB, clear the token, and reset the expiry to NULL
    $query  = "UPDATE users SET ";
    $query .= "password='{$hashed_password}', reset_token=NULL, reset_token_expires=NULL ";
    $query .= "WHERE id='{$user_id}'";
    
    if($db->query($query)){
      // Set success to true to trigger the success UI
      $success = true; 
    } else {
      $session->msg('d', "Failed to update password. Please try again.");
      redirect('reset-password.php?token='.$token, false);
    }
  } else {
    $session->msg('d', "Invalid or expired token.");
    redirect('forgot-password.php', false);
  }
} else {
  // Prevent direct access without POST data
  if(!$success) { redirect('index.php', false); }
}
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
  }

  .forgot-card {
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    padding: 50px 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    color: #ffffff;
    text-align: center;
    animation: fadeIn 0.6s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .success-icon {
    font-size: 64px;
    color: #22c55e;
    margin-bottom: 20px;
    filter: drop-shadow(0 0 10px rgba(34, 197, 94, 0.4));
  }

  .brand-title {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 15px;
    background: linear-gradient(135deg, #fff, #0099ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .btn-login {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #0099ff 0%, #007acc 100%);
    border: none;
    padding: 16px;
    border-radius: 14px;
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    margin-top: 30px;
    transition: all 0.3s ease;
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 153, 255, 0.4);
    color: white;
  }
</style>

<div class="forgot-card">
    <?php if($success): ?>
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="brand-title">SUCCESSFUL!</div>
        <p style="color: #94a3b8; line-height: 1.6;">
            Your password has been updated successfully. You can now use your new credentials to sign in to the system.
        </p>
        
        <a href="index.php" class="btn-login">Back to Login</a>

    <?php else: ?>
        <div class="brand-title">ERROR</div>
        <p style="color: #ef4444;">Something went wrong. Please try the reset link again.</p>
        <a href="index.php" class="btn-login">Return Home</a>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>