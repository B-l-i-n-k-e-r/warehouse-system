<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ob_start();
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once('includes/load.php');
  
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
      $expires = date("Y-m-d H:i:s", strtotime("+24 hours")); 
      $user_id = $user[0]['id'];

      $sql = "UPDATE users SET reset_token='{$token}', reset_token_expires='{$expires}' WHERE id='{$user_id}'";
      
      if($db->query($sql)){
        $mail = new PHPMailer(true);
        // Tip: Ensure this URL matches your actual production/local environment
        $reset_link = "http://localhost:8000/reset-password.php?token=" . $token;

        try {
          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'vinniemariba2004@gmail.com'; 
          $mail->Password   = 'fadw zury olur hkpq';    
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port       = 587;

          $mail->setFrom('no-reply@moonlit.com', 'MoonLit System');
          $mail->addAddress($email); 

          $mail->isHTML(true);
          $mail->Subject = 'Password Reset - MoonLit Warehouse';
          
          // Enhanced MoonLit Branded Email Template
          $mail->Body = "
            <div style='background-color: #f8fafc; padding: 40px 20px; font-family: sans-serif;'>
              <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
                <div style='background: #0f172a; padding: 30px; text-align: center;'>
                  <h1 style='color: #0099ff; margin: 0; font-size: 24px; letter-spacing: 2px;'>MOONLIT</h1>
                </div>
                <div style='padding: 40px 30px;'>
                  <h2 style='color: #1e293b; margin-top: 0;'>Password Reset Request</h2>
                  <p style='color: #64748b; line-height: 1.6;'>We received a request to access your account. If you made this request, click the button below to set a new password:</p>
                  <div style='text-align: center; margin: 35px 0;'>
                    <a href='{$reset_link}' style='background: #0099ff; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 12px; font-weight: bold; display: inline-block;'>Reset My Password</a>
                  </div>
                  <p style='color: #94a3b8; font-size: 13px; border-top: 1px solid #f1f5f9; padding-top: 20px;'>This link is valid for 24 hours. If you didn't request this, you can safely ignore this email.</p>
                </div>
              </div>
            </div>";

          $mail->send();
          $session->msg('s', "Success! Check your email for the recovery link.");
        } catch (Exception $e) {
          $session->msg('d', "Email failed to send. Error: {$mail->ErrorInfo}");
        }
      }
    } else {
      $session->msg('d', "That email isn't registered in our system.");
    }
    redirect('forgot-password.php', false);
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  *{margin:0;padding:0;box-sizing:border-box;}

  body {
    background: linear-gradient(rgba(10, 15, 30, 0.85), rgba(10, 15, 30, 0.85)), 
                url('libs/images/warehouse.jpg') no-repeat center center fixed; 
    background-size: cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
  }

  .forgot-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 32px;
    padding: 55px 45px;
    width: 100%;
    max-width: 440px;
    color: #ffffff;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease;
  }

  .forgot-card:hover {
    transform: translateY(-5px);
  }

  .icon-box {
    width: 90px; height: 90px;
    background: rgba(0, 153, 255, 0.1);
    border: 1px solid rgba(0, 153, 255, 0.2);
    border-radius: 28px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 30px;
    color: #0099ff; font-size: 36px;
    position: relative;
  }

  /* Cool Pulse Effect */
  .icon-box::after {
    content: '';
    position: absolute;
    width: 100%; height: 100%;
    border-radius: 28px;
    border: 2px solid #0099ff;
    animation: pulse 2s infinite;
    opacity: 0;
  }

  @keyframes pulse {
    0% { transform: scale(1); opacity: 0.5; }
    100% { transform: scale(1.3); opacity: 0; }
  }

  .brand-title {
    font-size: 30px; font-weight: 900; margin-bottom: 10px;
    background: linear-gradient(135deg, #fff 0%, #0099ff 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.4) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 16px !important;
    color: #fff !important;
    padding: 12px 20px 12px 50px !important;
    height: 60px !important;
    width: 100%;
    font-size: 16px !important;
    transition: all 0.3s ease !important;
  }

  .form-control:focus {
    border-color: #0099ff !important;
    background: rgba(15, 23, 42, 0.6) !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.15) !important;
    outline: none;
  }

  .btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #0099ff, #0066ff) !important;
    border: none !important;
    padding: 18px !important;
    border-radius: 16px !important;
    color: white !important;
    font-size: 16px !important;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease !important;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
  }

  .btn-submit:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 25px -5px rgba(0, 153, 255, 0.5);
  }

  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 30px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
  }

  .back-link:hover {
    color: #0099ff;
  }

  /* Custom Alert */
  .alert {
    border-radius: 14px;
    padding: 15px;
    font-size: 14px;
    margin-bottom: 25px;
    text-align: left;
    border: 1px solid rgba(255,255,255,0.1);
  }
</style>

<div class="forgot-card">
    <div class="icon-box"><i class="fas fa-user-shield"></i></div>
    <div class="brand-title">RECOVER ACCESS</div>
    <p style="color: #94a3b8; font-size: 15px; margin-bottom: 35px; line-height: 1.5;">
        Enter your registered email below and we'll send you a secure link to reset your password.
    </p>

    <div class="msg-container">
      <?php echo display_msg($msg); ?>
    </div>

    <form method="post" action="forgot-password.php">
        <div style="position:relative; margin-bottom: 25px;">
            <i class="fas fa-envelope" style="position:absolute; left:18px; top:21px; color:#64748b; font-size: 18px;"></i>
            <input type="email" class="form-control" name="email" placeholder="Email Address" required autofocus>
        </div>
        <button type="submit" name="submit" class="btn-submit">
            Send Reset Link <i class="fas fa-paper-plane"></i>
        </button>
    </form>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Login
    </a>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>