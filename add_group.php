<?php
  $page_title = 'Add Group';
  require_once('includes/load.php');
  page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);

   if(find_by_groupName($_POST['group-name']) === false ){
     $session->msg('d','<b>Sorry!</b> Entered Group Name already in database!');
     redirect('add_group.php', false);
   }elseif(find_by_groupLevel($_POST['group-level']) === false) {
     $session->msg('d','<b>Sorry!</b> Entered Group Level already in database!');
     redirect('add_group.php', false);
   }
   if(empty($errors)){
           $name = remove_junk($db->escape($_POST['group-name']));
          $level = remove_junk($db->escape($_POST['group-level']));
         $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .="group_name,group_level,group_status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$level}','{$status}'";
        $query .=")";
        if($db->query($query)){
          $session->msg('s',"Group has been created! ");
          redirect('add_group.php', false);
        } else {
          $session->msg('d',' Sorry failed to create Group!');
          redirect('add_group.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_group.php',false);
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

  .form-container {
    max-width: 550px;
    margin: 40px auto;
    width: auto !important; /* Fit content alignment */
  }

  .form-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 40px;
    border-radius: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
  }

  .form-card h3 {
    margin-top: 0;
    margin-bottom: 30px;
    font-weight: 700;
    color: var(--text-main);
    font-size: 22px;
    letter-spacing: -0.5px;
  }

  .form-group label {
    font-weight: 600;
    color: var(--neon-blue);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 10px;
    display: block;
  }

  .form-control {
    background: var(--input-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 12px;
    height: 50px;
    color: #fff !important;
    box-shadow: none;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: var(--neon-blue) !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important;
    transform: translateY(-1px);
  }

  /* Styling placeholder text */
  .form-control::placeholder {
    color: #475569;
  }

  .btn-submit {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    margin-top: 20px;
    color: white;
    box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
    transition: all 0.3s;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px rgba(14, 165, 233, 0.4);
    filter: brightness(1.1);
  }

  .back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
  }

  .back-link:hover { 
    color: var(--neon-blue); 
    text-decoration: none;
    transform: translateX(-5px);
  }

  select.form-control option {
    background: #1e293b;
    color: #fff;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="form-container">
    <div class="form-card">
      <h3>
        <i class="glyphicon glyphicon-plus-sign" style="color: var(--neon-blue); margin-right: 12px;"></i> 
        Add User Group
      </h3>
      
      <form method="post" action="add_group.php">
        <div class="form-group">
          <label for="name">Group Name</label>
          <input type="text" class="form-control" name="group-name" placeholder="e.g. Sales Team">
        </div>

        <div class="form-group">
          <label for="level">Permission Level</label>
          <input type="number" class="form-control" name="group-level" placeholder="e.g. 2">
          <small style="color: var(--text-muted); font-size: 10px; margin-top: 5px; display: block;">
            Assign a unique numeric level (1 is usually Admin).
          </small>
        </div>

        <div class="form-group">
          <label for="status">Account Status</label>
          <select class="form-control" name="status">
            <option value="1">Active</option>
            <option value="0">Deactive</option>
          </select>
        </div>

        <button type="submit" name="add" class="btn btn-submit">Create Group</button>
      </form>
    </div>
    <a href="group.php" class="back-link">
        <i class="glyphicon glyphicon-arrow-left"></i> Back to All Groups
    </a>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>