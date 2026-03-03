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
  }

  .settings-card {
    background: var(--card-bg);
    border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    margin-bottom: 30px;
    padding: 30px;
    transition: transform 0.3s ease;
  }

  .settings-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* Custom File Upload Styling */
  .custom-file-upload {
    display: block;
    padding: 15px;
    cursor: pointer;
    background: rgba(15, 23, 42, 0.5);
    border-radius: 12px;
    border: 2px dashed var(--border);
    color: var(--text-dim);
    font-weight: 600;
    transition: all 0.2s;
    text-align: center;
    margin-bottom: 15px;
  }
  
  .custom-file-upload:hover { 
    background: rgba(99, 102, 241, 0.1); 
    border-color: var(--primary);
    color: #fff;
  }

  .profile-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--card-bg);
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    background: var(--dark-bg);
  }

  .form-group label {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--text-dim);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
  }

  .form-control {
    background: var(--dark-bg) !important;
    border: 1px solid var(--border);
    border-radius: 10px;
    height: 50px;
    color: #fff !important;
    transition: all 0.3s;
  }

  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    outline: none;
  }

  .btn-update {
    background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
    border: none;
    border-radius: 10px;
    font-weight: 800;
    padding: 12px 30px;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s;
  }
  
  .btn-update:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
    filter: brightness(1.1);
  }

  .btn-upload {
    background: var(--accent);
    color: var(--dark-bg);
    font-weight: 800;
    border-radius: 10px;
    border: none;
    padding: 10px;
    transition: 0.3s;
  }

  .btn-upload:hover {
    background: #fff;
    box-shadow: 0 0 15px var(--accent);
  }

  hr { border-top: 1px solid var(--border); }
</style>

<div class="container-fluid" style="padding: 40px;">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="settings-card text-center">
        <div class="settings-title">
          <i class="glyphicon glyphicon-camera"></i> 
          Identity
        </div>
        
        <img class="profile-preview mb-4" src="uploads/users/<?php echo $user['image'];?>" alt="Current Profile">
        
        <form action="edit_account.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file_upload" class="custom-file-upload">
              <i class="glyphicon glyphicon-picture"></i> Choose New Photo
            </label>
            <input type="file" name="file_upload" id="file_upload" style="display:none;"/>
          </div>
          <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
          <button type="submit" name="submit" class="btn btn-block btn-upload">
            Apply New Image
          </button>
        </form>
      </div>
    </div>

    <div class="col-md-8">
      <div class="settings-card">
        <div class="settings-title">
          <i class="glyphicon glyphicon-edit"></i> 
          Profile Credentials
        </div>
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id'];?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Full Display Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>System Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo remove_junk($user['username']); ?>">
              </div>
            </div>
          </div>
          
          <hr style="margin: 30px 0;">
          
          <div class="d-flex justify-content-between align-items-center">
            <button type="submit" name="update" class="btn btn-update">Save Profile Changes</button>
            <a href="change_password.php" class="text-danger fw-bold" style="text-decoration:none; font-size: 0.85rem; display: flex; align-items: center; gap: 5px;">
              <i class="glyphicon glyphicon-lock"></i> UPDATE PASSWORD
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // UI Interaction for File Selection
  document.getElementById('file_upload').onchange = function () {
    const label = document.querySelector('.custom-file-upload');
    const fileName = this.files[0].name;
    label.innerHTML = `<i class="glyphicon glyphicon-ok" style="color:var(--accent)"></i> Ready: ` + fileName;
    label.style.borderColor = "var(--accent)";
    label.style.color = "#fff";
    label.style.background = "rgba(56, 189, 248, 0.1)";
  };
</script>

<?php include_once('layouts/footer.php'); ?>