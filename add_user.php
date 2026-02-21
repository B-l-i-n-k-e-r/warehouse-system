<?php
  $page_title = 'Add User';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $groups = find_all('user_groups');
?>
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('full-name','username','password','level' );
   validate_fields($req_fields);

   if(empty($errors)){
           $name   = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $password   = remove_junk($db->escape($_POST['password']));
       $user_level = (int)$db->escape($_POST['level']);
       $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,user_level,status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}','1'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"User account has been created! ");
          redirect('add_user.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create account!');
          redirect('add_user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_user.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .form-wrapper {
    max-width: 650px;
    margin: 40px auto;
  }
  .user-add-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
  }
  .card-header-accent {
    background: #f8fafc;
    padding: 25px 30px;
    border-bottom: 1px solid #f1f5f9;
  }
  .card-header-accent h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
  }
  .form-body {
    padding: 30px;
  }
  .input-group-modern {
    margin-bottom: 20px;
  }
  .input-group-modern label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    margin-bottom: 8px;
  }
  .form-control-modern {
    display: block;
    width: 100%;
    height: 48px;
    padding: 10px 16px;
    font-size: 15px;
    color: #334155;
    background-color: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.2s ease-in-out;
  }
  .form-control-modern:focus {
    border-color: #6366f1;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
  }
  .btn-create-user {
    background: #6366f1;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 14px 24px;
    font-weight: 600;
    font-size: 16px;
    width: 100%;
    transition: all 0.2s;
    margin-top: 10px;
  }
  .btn-create-user:hover {
    background: #4f46e5;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    color: #fff;
  }
  .back-btn {
    display: inline-block;
    margin-top: 20px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
  }
  .back-btn:hover {
    color: #6366f1;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
       <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="form-wrapper">
    <div class="user-add-card">
      <div class="card-header-accent">
        <h3><i class="glyphicon glyphicon-user" style="color: #6366f1; margin-right: 12px;"></i> Add New System User</h3>
      </div>
      
      <div class="form-body">
        <form method="post" action="add_user.php">
          
          <div class="row">
            <div class="col-md-6">
              <div class="input-group-modern">
                <label for="name">Full Name</label>
                <input type="text" class="form-control-modern" name="full-name" placeholder="e.g. John Doe">
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group-modern">
                <label for="username">Username</label>
                <input type="text" class="form-control-modern" name="username" placeholder="johndoe88">
              </div>
            </div>
          </div>

          <div class="input-group-modern">
            <label for="password">Password</label>
            <input type="password" class="form-control-modern" name="password" placeholder="••••••••">
          </div>

          <div class="input-group-modern">
            <label for="level">Assign User Role</label>
            <select class="form-control-modern" name="level">
              <?php foreach ($groups as $group ):?>
               <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
            <?php endforeach;?>
            </select>
            <p class="help-block" style="font-size: 12px; margin-top: 5px; color: #94a3b8;">Determines what parts of the system this user can access.</p>
          </div>

          <div class="form-group clearfix">
            <button type="submit" name="add_user" class="btn-create-user">Create Account</button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center">
      <a href="users.php" class="back-btn"><i class="glyphicon glyphicon-arrow-left"></i> Return to User Management</a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>