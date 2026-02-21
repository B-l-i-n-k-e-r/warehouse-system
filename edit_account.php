<?php
  $page_title = 'Edit Account';
  require_once('includes/load.php');
  page_require_level(3);
?>
<?php
  // Update user image logic
  if(isset($_POST['submit'])) {
    $photo = new Media();
    $user_id = (int)$_POST['user_id'];
    $photo->upload($_FILES['file_upload']);
    if($photo->process_user($user_id)){
      $session->msg('s','Profile photo updated successfully.');
      redirect('edit_account.php');
    } else {
      $session->msg('d',join($photo->errors));
      redirect('edit_account.php');
    }
  }

  // Update user info logic
  if(isset($_POST['update'])){
    $req_fields = array('name','username' );
    validate_fields($req_fields);
    if(empty($errors)){
      $id = (int)$_SESSION['user_id'];
      $name = remove_junk($db->escape($_POST['name']));
      $username = remove_junk($db->escape($_POST['username']));
      $sql = "UPDATE users SET name ='{$name}', username ='{$username}' WHERE id='{$id}'";
      $result = $db->query($sql);
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Account settings updated.");
        redirect('edit_account.php', false);
      } else {
        $session->msg('d','No changes were made.');
        redirect('edit_account.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_account.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --primary: #6366f1;
    --slate-100: #f1f5f9;
    --slate-700: #334155;
  }

  .settings-card {
    background: #ffffff;
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    padding: 25px;
  }

  .settings-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--slate-700);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* Custom File Upload Styling */
  .custom-file-upload {
    display: inline-block;
    padding: 8px 16px;
    cursor: pointer;
    background: var(--slate-100);
    border-radius: 8px;
    border: 1px dashed #cbd5e1;
    color: #64748b;
    font-weight: 500;
    transition: all 0.2s;
    width: 100%;
    text-align: center;
  }
  .custom-file-upload:hover { background: #e2e8f0; }

  .profile-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  .form-control {
    border-radius: 10px;
    height: 45px;
    border: 1.5px solid #e2e8f0;
  }

  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
  }

  .btn-update {
    background: var(--primary);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    padding: 10px 25px;
    transition: all 0.3s;
  }
  .btn-update:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }
</style>

<div class="container-fluid" style="padding: 30px;">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-5">
      <div class="settings-card">
        <div class="settings-title">
          <i class="glyphicon glyphicon-camera" style="color: var(--primary);"></i> 
          Profile Image
        </div>
        <div class="text-center">
          <img class="profile-preview mb-4" src="uploads/users/<?php echo $user['image'];?>" alt="Current Profile">
          
          <form action="edit_account.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="file_upload" class="custom-file-upload">
                <i class="glyphicon glyphicon-cloud-upload"></i> Choose New Photo
              </label>
              <input type="file" name="file_upload" id="file_upload" style="display:none;"/>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
            <button type="submit" name="submit" class="btn btn-warning btn-block" style="border-radius:10px; font-weight:600;">
              Upload & Update
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="settings-card">
        <div class="settings-title">
          <i class="glyphicon glyphicon-user" style="color: var(--primary);"></i> 
          Account Details
        </div>
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id'];?>">
          <div class="form-group">
            <label class="text-muted small fw-bold text-uppercase">Display Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
          </div>
          <div class="form-group">
            <label class="text-muted small fw-bold text-uppercase">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk($user['username']); ?>">
          </div>
          
          <hr style="border-top: 1px solid #f1f5f9; margin: 25px 0;">
          
          <div class="d-flex justify-content-between align-items-center">
            <button type="submit" name="update" class="btn btn-primary btn-update">Save Changes</button>
            <a href="change_password.php" class="btn btn-link text-danger fw-bold" style="text-decoration:none;">
              <i class="glyphicon glyphicon-lock"></i> Change Password
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Simple JS to show filename after selection
  document.getElementById('file_upload').onchange = function () {
    const label = document.querySelector('.custom-file-upload');
    label.innerHTML = `<i class="glyphicon glyphicon-check"></i> File Selected: ` + this.files[0].name;
    label.style.borderColor = "#10b981";
    label.style.color = "#10b981";
  };
</script>

<?php include_once('layouts/footer.php'); ?>