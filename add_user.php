<?php
  $page_title = 'Add User';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $groups = find_all('user_groups');
?>
<?php
  if(isset($_POST['add_user'])){

   // Added 'email' to the required fields array
   $req_fields = array('full-name','username','email','password','level' );
   validate_fields($req_fields);

   if(empty($errors)){
       $name       = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $email      = remove_junk($db->escape($_POST['email'])); // Capture Email
       $password   = remove_junk($db->escape($_POST['password']));
       $user_level = (int)$db->escape($_POST['level']);
       $password   = sha1($password);

        $query  = "INSERT INTO users (";
        $query .="name,username,email,password,user_level,status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$email}', '{$password}', '{$user_level}','1'";
        $query .=")";

        if($db->query($query)){
          // success
          $session->msg('s',"User account has been created! ");
          redirect('add_user.php', false);
        } else {
          // failed
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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-bg: rgba(15, 23, 42, 0.5);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .form-wrapper {
    max-width: 650px;
    margin: 40px auto;
  }

  .user-add-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    overflow: hidden;
  }

  .card-header-accent {
    padding: 30px;
    border-bottom: 1px solid var(--glass-border);
  }

  .card-header-accent h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: var(--text-main);
    letter-spacing: -0.5px;
  }

  .form-body {
    padding: 40px;
  }

  .input-group-modern {
    margin-bottom: 25px;
  }

  .input-group-modern label {
    display: block;
    font-weight: 700;
    font-size: 11px;
    color: var(--neon-blue);
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 10px;
  }

  .form-control-modern {
    display: block;
    width: 100%;
    height: 50px;
    padding: 12px 18px;
    font-size: 15px;
    color: #fff !important;
    background-color: var(--input-bg) !important;
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    transition: all 0.3s ease;
  }

  .form-control-modern:focus {
    border-color: var(--neon-blue) !important;
    outline: 0;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important;
    transform: translateY(-1px);
  }

  /* Dropdown arrow styling */
  select.form-control-modern {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
  }

  select.form-control-modern option {
    background: #1e293b;
    color: #fff;
  }

  .btn-create-user {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px 24px;
    font-weight: 700;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    transition: all 0.3s;
    margin-top: 15px;
    box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
  }

  .btn-create-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px rgba(14, 165, 233, 0.4);
    filter: brightness(1.1);
    color: #fff;
  }

  .back-btn {
    display: inline-block;
    margin-top: 30px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
  }

  .back-btn:hover {
    color: var(--neon-blue);
    text-decoration: none;
    transform: translateX(-5px);
  }

  .help-text {
    font-size: 11px;
    margin-top: 8px;
    color: var(--text-muted);
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
        <h3><i class="glyphicon glyphicon-user" style="color: var(--neon-blue); margin-right: 12px;"></i> Add New System User</h3>
      </div>
      
      <div class="form-body">
        <form method="post" action="add_user.php">
          
          <div class="row">
            <div class="col-md-6">
              <div class="input-group-modern">
                <label for="name">Full Name</label>
                <input type="text" class="form-control-modern" name="full-name" placeholder="e.g. Mariba" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group-modern">
                <label for="username">Username</label>
                <input type="text" class="form-control-modern" name="username" placeholder="blinker09" required>
              </div>
            </div>
          </div>

          <div class="input-group-modern">
            <label for="email">Email Address</label>
            <input type="email" class="form-control-modern" name="email" placeholder="user@gmail.com" required>
          </div>

          <div class="input-group-modern">
            <label for="password">Password</label>
            <input type="password" class="form-control-modern" name="password" placeholder="••••••••" required>
          </div>

          <div class="input-group-modern">
            <label for="level">Assign User Role</label>
            <select class="form-control-modern" name="level">
              <?php foreach ($groups as $group ):?>
               <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
            <?php endforeach;?>
            </select>
            <p class="help-text">Determines system access permissions.</p>
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