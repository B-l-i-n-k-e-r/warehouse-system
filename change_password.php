<?php
  $page_title = 'Change Password';
  require_once('includes/load.php');
  page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
  // Update Password logic
  if(isset($_POST['update'])){
    $req_fields = array('old-password','new-password','id' );
    validate_fields($req_fields);

    if(empty($errors)){
      $id          = (int)$_POST['id'];
      $old_pass    = $_POST['old-password'];
      $new_pass    = $_POST['new-password'];
      
      // Hash the passwords
      $old_pass_hashed = sha1($old_pass);
      $new_pass_hashed = sha1($new_pass);

      $user_data = find_by_id('users', $id);
      
      // Step 1: Check if old password is correct
      if($user_data['password'] === $old_pass_hashed){
        
        // Step 2: Run the update
        $sql = "UPDATE users SET password ='{$db->escape($new_pass_hashed)}' WHERE id='{$id}'";
        $result = $db->query($sql);
        
        if($result){
          $session->msg('s',"Credentials updated successfully.");
          redirect('change_password.php', false);
        } else {
          $session->msg('d','Database Error: Cryptography update failed.');
          redirect('change_password.php', false);
        }

      } else {
        $session->msg('d','Verification failed: Old password does not match.');
        redirect('change_password.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('change_password.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --primary: #38bdf8;
    --security-accent: #6366f1;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --text-main: #f8fafc;
    --text-dim: #94a3b8;
    --border: rgba(99, 102, 241, 0.2);
  }

  body {
    background-color: var(--dark-bg);
    background-image: radial-gradient(circle at 2px 2px, rgba(99, 102, 241, 0.05) 1px, transparent 0);
    background-size: 40px 40px;
    color: var(--text-main);
  }

  .security-card {
    max-width: 480px;
    margin: 80px auto;
    background: var(--card-bg);
    padding: 40px;
    border-radius: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
  }

  .security-title-text {
    color: var(--primary) !important; /* Visible Color Fix */
    font-weight: 900;
    letter-spacing: -1px;
    margin-bottom: 5px;
    text-transform: uppercase;
  }

  .security-subtitle {
    color: var(--text-main); /* Brightened for readability */
    font-size: 0.85rem;
    margin-bottom: 30px;
    opacity: 0.8;
  }

  .form-group { position: relative; margin-bottom: 25px; z-index: 1; }

  .form-label {
    font-size: 0.7rem;
    font-weight: 800;
    color: var(--primary);
    text-transform: uppercase;
    display: block;
    margin-bottom: 10px;
    letter-spacing: 1.5px;
  }

  .form-control {
    height: 55px;
    background: var(--dark-bg) !important;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    padding: 10px 45px 10px 15px;
    width: 100%;
    color: #fff !important;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: var(--security-accent);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    outline: none;
  }

  .password-toggle {
    position: absolute;
    right: 15px;
    top: 40px;
    cursor: pointer;
    color: var(--text-dim);
    transition: 0.2s;
    font-size: 1.2rem;
  }

  .btn-change {
    background: linear-gradient(135deg, var(--security-accent) 0%, #4f46e5 100%);
    color: white;
    border: none;
    width: 100%;
    height: 55px;
    border-radius: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
  }

  .btn-change:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
  }

  .security-icon-circle {
    width: 70px;
    height: 70px;
    background: rgba(56, 189, 248, 0.1);
    border: 2px solid var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }
</style>

<div class="container">
  <div class="security-card">
    <div class="text-center">
      <div class="security-icon-circle">
        <i class="glyphicon glyphicon-lock" style="font-size: 28px; color: var(--primary);"></i>
      </div>
      <h3 class="security-title-text">Security Vault</h3>
      <p class="security-subtitle">Update your access credentials</p>
    </div>

    <?php echo display_msg($msg); ?>

    <form method="post" action="change_password.php">
      <div class="form-group">
        <label class="form-label">Current Password</label>
        <input type="password" class="form-control" name="old-password" id="old-pass" placeholder="••••••••" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('old-pass', this)"></i>
      </div>

      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control" name="new-password" id="new-pass" placeholder="••••••••" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('new-pass', this)"></i>
      </div>

      <div class="form-group">
        <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
        <button type="submit" name="update" class="btn btn-change">Change Password</button>
      </div>
      
      <div class="text-center">
        <a href="edit_account.php" style="color: var(--text-dim); text-decoration: none; font-size: 1.2rem; font-weight: 700;">&larr; Return to Settings</a>
      </div>
    </form>
  </div>
</div>

<script>
function togglePass(id, icon) {
  const input = document.getElementById(id);
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove('glyphicon-eye-open');
    icon.classList.add('glyphicon-eye-close');
  } else {
    input.type = "password";
    icon.classList.remove('glyphicon-eye-close');
    icon.classList.add('glyphicon-eye-open');
  }
}
</script>

<?php include_once('layouts/footer.php'); ?>