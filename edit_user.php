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
    // Added 'email' to required fields
    $req_fields = array('name','username','email','level');
    validate_fields($req_fields);
    
    if(empty($errors)){
      $id       = (int)$e_user['id'];
      $name     = remove_junk($db->escape($_POST['name']));
      $username = remove_junk($db->escape($_POST['username']));
      $email    = remove_junk($db->escape($_POST['email'])); // Capture Email
      $level    = (int)$db->escape($_POST['level']);
      $status   = remove_junk($db->escape($_POST['status']));
      
      // Updated SQL query to include email
      $sql = "UPDATE users SET name ='{$name}', username ='{$username}', email='{$email}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";
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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-red: #f43f5e;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-bg: rgba(15, 23, 42, 0.6);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .user-card { 
    background: var(--glass-bg); 
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    border: 1px solid var(--glass-border); 
    margin-bottom: 20px;
    width: auto !important; /* Fit content rule */
  }

  .user-card-header { 
    padding: 20px 25px; 
    border-bottom: 1px solid var(--glass-border); 
    font-weight: 700; 
    font-size: 16px;
    color: var(--text-main); 
    display: flex;
    align-items: center;
  }

  .form-label { 
    font-size: 10px; 
    font-weight: 700; 
    color: var(--neon-blue); 
    text-transform: uppercase; 
    letter-spacing: 1.2px;
    margin-bottom: 10px; 
    display: block;
  }

  .modern-input { 
    background: var(--input-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 10px; 
    height: 45px; 
    color: #fff !important;
    transition: 0.3s;
  }

  .modern-input:focus {
    border-color: var(--neon-blue) !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important;
    outline: none;
  }

  .btn-action { 
    border-radius: 10px; 
    font-weight: 700; 
    padding: 12px 25px; 
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s; 
    border: none;
  }

  .btn-update {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    color: #fff;
  }

  .btn-password {
    background: linear-gradient(135deg, var(--neon-red), #e11d48);
    color: #fff;
  }

  .btn-action:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }

  .access-guide { 
    background: rgba(15, 23, 42, 0.4); 
    border-radius: 10px; 
    padding: 15px; 
    margin-top: 20px; 
    border: 1px solid var(--glass-border); 
  }

  .input-group-addon {
    background: rgba(255,255,255,0.05) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--neon-blue) !important;
    width: auto;
  }

  select.modern-input option {
    background: #1e293b;
    color: #fff;
  }
</style>

<div class="container-fluid" style="padding-top: 20px;">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-7">
      <div class="user-card">
        <div class="user-card-header">
          <i class="glyphicon glyphicon-user" style="color:var(--neon-blue); margin-right:12px;"></i> Profile Configuration
        </div>
        <div class="panel-body" style="padding: 25px;">
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
               <div class="col-md-12">
                 <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                      <input type="email" class="form-control modern-input" name="email" value="<?php echo remove_junk($e_user['email']); ?>" placeholder="email@example.com" required>
                    </div>
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
              <span class="form-label" style="color: var(--text-muted); margin-bottom: 8px;">Role Reference</span>
              <div style="font-size: 11px; color: var(--text-muted); line-height: 1.6;">
                <span style="color:var(--neon-blue)">● Admin:</span> Full system & user control.<br>
                <span style="color:var(--neon-blue)">● Manager:</span> Inventory & warehouse ops.<br>
                <span style="color:var(--neon-blue)">● Staff:</span> Sales & basic reporting.
              </div>
            </div>

            <div style="margin-top: 30px; border-top: 1px solid var(--glass-border); padding-top: 20px;">
              <button type="submit" name="update" class="btn btn-action btn-update">Update Profile</button>
              <a href="users.php" class="btn btn-default btn-action" style="background: rgba(255,255,255,0.05); color: #fff; margin-left:10px;">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="user-card">
        <div class="user-card-header" style="border-left: 4px solid var(--neon-red);">
          <i class="glyphicon glyphicon-lock" style="color:var(--neon-red); margin-right:12px;"></i> Security & Password
        </div>
        <div class="panel-body" style="padding: 25px;">
          <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post">
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control modern-input" name="password" placeholder="Enter new strong password">
              <small style="color: var(--text-muted); font-size: 10px; margin-top: 10px; display: block;">
                Directly overrides current password. Use with caution.
              </small>
            </div>
            <div style="margin-top: 25px; text-align: right;">
              <button type="submit" name="update-pass" class="btn btn-action btn-password">Apply New Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>