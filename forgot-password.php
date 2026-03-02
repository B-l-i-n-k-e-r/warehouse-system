<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ob_start();
  
  // Import PHPMailer classes into the global namespace
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once('includes/load.php');
  
  // Load PHPMailer files
  require 'libs/phpmailer/Exception.php';
  require 'libs/phpmailer/PHPMailer.php';
  require 'libs/phpmailer/SMTP.php';

  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}

  $page_title = 'Reset Access';

  if(isset($_POST['submit'])){
    $email = $db->escape($_POST['email']);
    $user = find_by_sql("SELECT id, username FROM users WHERE email='{$email}' LIMIT 1");

    if($user){
      $token = bin2hex(random_bytes(32));
      // Increased to 24 hours to ensure the link stays valid for the user
      $expires = date("Y-m-d H:i:s", strtotime("+24 hours")); 
      $user_id = $user[0]['id'];

      $sql = "UPDATE users SET reset_token='{$token}', reset_token_expires='{$expires}' WHERE id='{$user_id}'";
      
      if($db->query($sql)){
        
        // --- AUTOMATED EMAIL LOGIC ---
        $mail = new PHPMailer(true);
        $reset_link = "http://localhost:8000/reset-password.php?token=" . $token;

        try {
          // Server settings
          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'vinniemariba2004@gmail.com'; 
          $mail->Password   = 'fadw zury olur hkpq';    
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port       = 587;

          // Recipients
          $mail->setFrom('no-reply@moonlit.com', 'MoonLit System');
          $mail->addAddress($email); 

          // Content
          $mail->isHTML(true);
          $mail->Subject = 'Password Reset - MoonLit Warehouse';
          $mail->Body    = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px; max-width: 500px;'>
              <h2 style='color: #0099ff;'>MoonLit Access</h2>
              <p>We received a request to reset your password. Click the button below to secure your account:</p>
              <div style='text-align: center; margin: 30px 0;'>
                <a href='{$reset_link}' style='background: #0099ff; color: #fff; padding: 15px 25px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Reset Password</a>
              </div>
              <p style='font-size: 12px; color: #777;'>This link expires in 24 hours. If you did not request this, please ignore this email.</p>
            </div>";

          $mail->send();
          $session->msg('s', "Success! A secure reset link has been sent to your email.");
        } catch (Exception $e) {
          $session->msg('d', "Database updated, but email failed. Contact Admin. Error: {$mail->ErrorInfo}");
        }
      }
    } else {
      $session->msg('d', "Email address not found in our system.");
    }
    redirect('forgot-password.php', false);
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
    padding: 20px;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  .forgot-card {
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    padding: 50px 40px;
    width: 100%;
    max-width: 420px;
    color: #ffffff;
    text-align: center;
  }

  .icon-box {
    width: 80px; height: 80px;
    background: rgba(0, 153, 255, 0.1);
    border: 1px solid rgba(0, 153, 255, 0.3);
    border-radius: 22px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 25px;
    color: #0099ff; font-size: 32px;
  }

  .brand-title {
    font-size: 26px; font-weight: 800; margin-bottom: 8px;
    background: linear-gradient(135deg, #fff, #94a3b8, #0099ff);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 20px 12px 45px !important;
    height: 55px !important;
    width: 100%;
  }

  .btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #0099ff 0%, #007acc 100%) !important;
    border: none !important;
    padding: 16px !important;
    border-radius: 14px !important;
    color: white !important;
    font-weight: 700;
    text-transform: uppercase;
    cursor: pointer;
    margin-top: 10px;
  }

  .alert-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #fff; padding: 15px; border-radius: 14px;
    font-size: 13px; margin-bottom: 20px; text-align: left;
  }
</style>

<div class="forgot-card">
    <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
    <div class="brand-title">RECOVER ACCESS</div>
    <p style="color: #94a3b8; font-size: 14px; margin-bottom: 30px;">
        Enter your email to receive an automated secure reset link.
    </p>

    <div class="msg-container">
      <?php echo display_msg($msg); ?>
    </div>

    <form method="post" action="forgot-password.php">
        <div style="position:relative; margin-bottom: 25px;">
            <i class="fas fa-envelope" style="position:absolute; left:16px; top:18px; color:#94a3b8;"></i>
            <input type="email" class="form-control" name="email" placeholder="Email Address" required autofocus>
        </div>
        <button type="submit" name="submit" class="btn-submit">Request Reset</button>
    </form>

    <div style="margin-top:25px;">
        <a href="index.php" style="color:#94a3b8; text-decoration:none; font-size:14px;">
            <i class="fas fa-chevron-left"></i> Back to Login
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>