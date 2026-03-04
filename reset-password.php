<?php
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}

  // Check if token exists in URL
  if(!isset($_GET['token'])){
    $session->msg('d', "Token is missing from the request.");
    redirect('index.php', false);
  }

  $token = $_GET['token'];
  // Verify token and expiry in database
  $user = find_by_sql("SELECT * FROM users WHERE reset_token='{$db->escape($token)}' AND reset_token_expires > NOW() LIMIT 1");

  if(!$user){
    $session->msg('d', "This reset link is invalid or has expired. Please request a new one.");
    redirect('index.php', false);
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

  .reset-card {
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    padding: 50px 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(0, 153, 255, 0.15);
    color: #ffffff;
    text-align: center;
    transition: transform 0.3s ease;
  }

  .reset-card:hover {
    transform: translateY(-5px);
  }

  .icon-box {
    width: 80px;
    height: 80px;
    background: rgba(0, 153, 255, 0.1);
    border: 1px solid rgba(0, 153, 255, 0.3);
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: #0099ff;
    font-size: 32px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
  }

  .brand-title {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: 1px;
    background: linear-gradient(135deg, #fff, #94a3b8, #0099ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradientShift 3s ease infinite;
    background-size: 200% 200%;
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .instruction-text {
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 30px;
    line-height: 1.6;
  }

  .form-group {
    position: relative;
    margin-bottom: 20px;
  }

  .form-control {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    color: #fff !important;
    padding: 12px 45px 12px 45px !important; /* Adjusted for both icons */
    height: 55px !important;
    width: 100%;
    transition: all 0.3s ease !important;
  }

  .form-control:focus {
    border-color: #0099ff !important;
    box-shadow: 0 0 0 4px rgba(0, 153, 255, 0.2) !important;
    outline: none;
  }

  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
    z-index: 2;
  }

  /* Show Password Eye Icon Style */
  .toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    cursor: pointer;
    z-index: 3;
    font-size: 16px;
    transition: color 0.3s;
  }

  .toggle-password:hover {
    color: #0099ff;
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
    letter-spacing: 1px;
    cursor: pointer;
    box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
    transition: all 0.3s ease;
    margin-top: 10px;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -5px rgba(0, 153, 255, 0.6);
  }

  .match-indicator {
    font-size: 12px;
    display: block;
    margin-top: 8px;
    text-align: left;
    font-weight: 500;
  }

  .alert {
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(15, 23, 42, 0.4);
    color: #fff;
  }
</style>

<div class="reset-card">
    <div class="icon-box">
        <i class="fas fa-key"></i>
    </div>
    
    <div class="brand-title">MOONLIT</div>
    <p class="instruction-text">
        Please create a strong new password to secure your account access.
    </p>

    <div class="msg-container">
      <?php echo display_msg($msg); ?>
    </div>

    <form method="post" action="update-password.php">
        <input type="hidden" name="token" value="<?php echo remove_junk($token); ?>">
        
        <div class="form-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" class="form-control" name="new_password" placeholder="New Password" required autofocus>
            <i class="fas fa-eye toggle-password" data-target="password"></i>
        </div>

        <div class="form-group">
            <i class="fas fa-shield-alt input-icon"></i>
            <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm New Password" required>
            <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
            <span id="match_msg" class="match-indicator"></span>
        </div>

        <button type="submit" name="submit_reset" class="btn btn-submit" id="resetBtn">
            Update Password
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Toggle Password Visibility
$('.toggle-password').on('click', function() {
  const targetId = $(this).data('target');
  const input = $('#' + targetId);
  const icon = $(this);

  if (input.attr('type') === 'password') {
    input.attr('type', 'text');
    icon.removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    input.attr('type', 'password');
    icon.removeClass('fa-eye-slash').addClass('fa-eye');
  }
});

// Match Logic
$('#confirm_password, #password').on('keyup', function () {
  const p1 = $('#password').val();
  const p2 = $('#confirm_password').val();

  if (p1 === "" && p2 === "") {
    $('#match_msg').html('');
    return;
  }

  if (p1 === p2) {
    $('#match_msg').html('<i class="fas fa-check-circle"></i> Passwords Match').css('color', '#10b981');
    $('#resetBtn').prop('disabled', false).css('opacity', '1').css('cursor', 'pointer');
  } else {
    $('#match_msg').html('<i class="fas fa-times-circle"></i> Passwords do not match').css('color', '#ef4444');
    $('#resetBtn').prop('disabled', true).css('opacity', '0.6').css('cursor', 'not-allowed');
  }
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include_once('layouts/footer.php'); ?>