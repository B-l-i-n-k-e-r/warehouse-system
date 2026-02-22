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
        
        // FIX: We check if the query executed ($result). 
        // We removed "&& $db->affected_rows() === 1" because if the user 
        // types the same password, MySQL reports 0 rows changed, causing a false failure.
        if($result){
          $session->msg('s',"Password successfully updated.");
          redirect('change_password.php', false);
        } else {
          $session->msg('d','Database Error: Failed to update password.');
          redirect('change_password.php', false);
        }

      } else {
        $session->msg('d','Old password does not match.');
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
    --security-blue: #2563eb;
    --slate-50: #f8fafc;
    --slate-200: #e2e8f0;
    --slate-800: #1e293b;
  }
  body { background-color: var(--slate-50); }
  .security-card {
    max-width: 500px;
    margin: 60px auto;
    background: #ffffff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  }
  .form-group { position: relative; margin-bottom: 20px; }
  .form-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    display: block;
    margin-bottom: 8px;
  }
  .form-control {
    height: 50px;
    border-radius: 10px;
    border: 2px solid var(--slate-200);
    padding: 10px 15px;
    width: 100%;
  }
  .password-toggle {
    position: absolute;
    right: 15px;
    top: 38px;
    cursor: pointer;
    color: #94a3b8;
  }
  .btn-change {
    background: var(--security-blue);
    color: white;
    border: none;
    width: 100%;
    height: 50px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
  }
</style>

<div class="container">
  <div class="security-card">
    <div class="text-center" style="margin-bottom: 30px;">
      <i class="glyphicon glyphicon-lock" style="font-size: 40px; color: var(--security-blue);"></i>
      <h3 style="font-weight: 800;">Update Password</h3>
    </div>

    <?php echo display_msg($msg); ?>

    <form method="post" action="change_password.php">
      <div class="form-group">
        <label class="form-label">Old Password</label>
        <input type="password" class="form-control" name="old-password" id="old-pass" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('old-pass', this)"></i>
      </div>

      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control" name="new-password" id="new-pass" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('new-pass', this)"></i>
      </div>

      <div class="form-group">
        <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
        <button type="submit" name="update" class="btn btn-change">Update Credentials</button>
      </div>
      
      <div class="text-center">
        <a href="edit_account.php" style="color: #64748b; text-decoration: none;">&larr; Back to Settings</a>
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