<?php
  $page_title = 'Edit User';
  require_once('includes/load.php');
  page_require_level(1);

  $e_user = find_by_id('users',(int)$_GET['id']);
  $groups = find_all('user_groups');
  
  if(!$e_user){
    $session->msg("d","Missing user id.");
    redirect('users.php');
  }

  // Update User Basic Info
  if(isset($_POST['update'])) {
    $req_fields = array('name','username','level');
    validate_fields($req_fields);
    if(empty($errors)){
      $id       = (int)$e_user['id'];
      $name     = remove_junk($db->escape($_POST['name']));
      $username = remove_junk($db->escape($_POST['username']));
      $level    = (int)$db->escape($_POST['level']);
      $status   = remove_junk($db->escape($_POST['status']));
      
      $sql = "UPDATE users SET name ='{$name}', username ='{$username}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
      
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Account settings updated successfully.");
        redirect('users.php', false);
      } else {
        $session->msg('d','No changes detected or update failed.');
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }
  }

  // Update User Password
  if(isset($_POST['update-pass'])) {
    $req_fields = array('password');
    validate_fields($req_fields);
    if(empty($errors)){
      $id       = (int)$e_user['id'];
      $password = remove_junk($db->escape($_POST['password']));
      $h_pass   = sha1($password);
      
      $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
      
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Password successfully reset.");
        redirect('users.php', false);
      } else {
        $session->msg('d','Failed to update password.');
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<style>
  .user-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none; }
  .user-card-header { padding: 20px; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; }
  .form-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 8px; }
  .modern-input { border-radius: 8px; border: 1px solid #e2e8f0; height: 42px; }
  .btn-action { border-radius: 8px; font-weight: 600; padding: 10px 20px; transition: 0.3s; }
  .access-guide { background: #f8fafc; border-radius: 8px; padding: 15px; margin-top: 20px; border: 1px solid #e2e8f0; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-7">
      <div class="user-card">
        <div class="user-card-header">
          <i class="glyphicon glyphicon-user" style="color:#6366f1;"></i> Profile Configuration
        </div>
        <div class="panel-body">
          <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control modern-input" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control modern-input" name="username" value="<?php echo remove_junk($e_user['username']); ?>">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Assigned Role</label>
                  <select class="form-control modern-input" name="level">
                    <?php foreach ($groups as $group): ?>
                      <option <?php if($group['group_level'] === $e_user['user_level']) echo 'selected="selected"';?> value="<?php echo $group['group_level'];?>">
                        <?php echo ucwords($group['group_name']);?>
                      </option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Account Status</label>
                  <select class="form-control modern-input" name="status">
                    <option <?php if($e_user['status'] === '1') echo 'selected="selected"';?> value="1">Active (Allow Access)</option>
                    <option <?php if($e_user['status'] === '0') echo 'selected="selected"';?> value="0">Disabled (Revoke Access)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="access-guide">
              <span class="form-label" style="margin-bottom: 5px;">Permissions Quick-Reference:</span>
              <ul style="padding-left: 20px; font-size: 12px; color: #475569; margin:0;">
                <li><strong>Admin (L1):</strong> Full system access and user management.</li>
                <li><strong>Special (L2):</strong> Inventory edits and location management.</li>
                <li><strong>User (L3):</strong> Sales processing and report printing only.</li>
              </ul>
            </div>

            <hr>
            <button type="submit" name="update" class="btn btn-info btn-action">Update User Profile</button>
            <a href="users.php" class="btn btn-default btn-action" style="margin-left:10px;">Cancel</a>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="user-card">
        <div class="user-card-header" style="border-left: 4px solid #ef4444;">
          <i class="glyphicon glyphicon-lock" style="color:#ef4444;"></i> Security & Password
        </div>
        <div class="panel-body">
          <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post">
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control modern-input" name="password" placeholder="Create a strong password">
              <p class="help-block" style="font-size: 11px;">Password will be encrypted using SHA-1.</p>
            </div>
            <button type="submit" name="update-pass" class="btn btn-danger btn-action pull-right">Apply New Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>