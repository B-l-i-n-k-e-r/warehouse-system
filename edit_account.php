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
    --accent: #38bdf8;
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
    overflow-x: hidden;
  }

  .container-fluid {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
  }

  .settings-card {
    background: var(--card-bg);
    border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
    padding: 25px;
    height: 100%; /* Ensures cards in the same row match height */
    display: flex;
    flex-direction: column;
  }

  .settings-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .profile-preview {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--dark-bg);
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    margin: 0 auto 20px;
  }

  /* Responsive Form elements */
  .form-control {
    background: var(--dark-bg) !important;
    border: 1px solid var(--border);
    border-radius: 10px;
    height: 48px;
    color: #fff !important;
    width: 100%;
  }

  /* Custom File Upload */
  .custom-file-upload {
    display: block;
    padding: 12px;
    cursor: pointer;
    background: rgba(15, 23, 42, 0.5);
    border-radius: 10px;
    border: 2px dashed var(--border);
    color: var(--text-dim);
    font-size: 0.85rem;
    transition: 0.2s;
    margin-bottom: 10px;
  }

  /* BUTTONS - AUTO FIT LOGIC */
  .flex-actions {
    display: flex;
    flex-wrap: wrap; /* Allows stacking on small screens */
    gap: 15px;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 20px;
  }

  .btn-update {
    background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
    border: none;
    border-radius: 10px;
    font-weight: 800;
    padding: 14px 24px;
    color: #fff;
    flex: 1 1 auto; /* Grow and shrink to fit */
    min-width: 200px;
    text-transform: uppercase;
    transition: 0.3s;
  }

  .btn-pass-large {
    background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
    color: #fff !important;
    font-weight: 800;
    padding: 14px 24px;
    border-radius: 10px;
    text-decoration: none !important;
    flex: 1 1 auto; /* Grow and shrink to fit */
    min-width: 200px;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
  }

  .btn-pass-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    filter: brightness(1.1);
  }

  .btn-upload {
    background: var(--accent);
    color: var(--dark-bg);
    font-weight: 800;
    border-radius: 10px;
    width: 100%;
    padding: 12px;
    border: none;
  }

  @media (max-width: 768px) {
    .flex-actions {
        flex-direction: column;
    }
    .btn-update, .btn-pass-large {
        width: 100%;
    }
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4 col-md-5">
      <div class="settings-card text-center">
        <div class="settings-title">
          <i class="glyphicon glyphicon-camera"></i> Identity
        </div>
        
        <img class="profile-preview" src="uploads/users/<?php echo $user['image'];?>" alt="Profile">
        
        <form action="edit_account.php" method="POST" enctype="multipart/form-data" style="width: 100%;">
          <div class="form-group">
            <label for="file_upload" class="custom-file-upload">
              <i class="glyphicon glyphicon-picture"></i> Choose Photo
            </label>
            <input type="file" name="file_upload" id="file_upload" style="display:none;"/>
          </div>
          <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
          <button type="submit" name="submit" class="btn btn-upload">
            Apply New Image
          </button>
        </form>
      </div>
    </div>

    <div class="col-lg-8 col-md-7">
      <div class="settings-card">
        <div class="settings-title">
          <i class="glyphicon glyphicon-edit"></i> Profile Credentials
        </div>
        
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id'];?>" style="width: 100%;">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="small fw-bold text-uppercase opacity-50">Display Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="small fw-bold text-uppercase opacity-50">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo remove_junk($user['username']); ?>">
              </div>
            </div>
          </div>
          
          <hr style="border-color: var(--border); margin: 30px 0;">
          
          <div class="flex-actions">
            <button type="submit" name="update" class="btn btn-update">
                Save Changes
            </button>
            
            <a href="change_password.php" class="btn-pass-large">
              <i class="glyphicon glyphicon-lock"></i> Update Password
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('file_upload').onchange = function () {
    const label = document.querySelector('.custom-file-upload');
    const fileName = this.files[0].name;
    label.innerHTML = `<i class="glyphicon glyphicon-ok" style="color:var(--accent)"></i> Ready: ` + fileName;
    label.style.borderColor = "var(--accent)";
  };
</script>

<?php include_once('layouts/footer.php'); ?>