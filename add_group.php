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
  .form-container {
    max-width: 550px;
    margin: 40px auto;
  }
  .form-card {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
  }
  .form-card h3 {
    margin-top: 0;
    margin-bottom: 25px;
    font-weight: 700;
    color: #334155;
    font-size: 20px;
  }
  .form-group label {
    font-weight: 600;
    color: #64748b;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }
  .form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    height: 45px;
    box-shadow: none;
    transition: all 0.2s;
  }
  .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
  }
  .btn-submit {
    background: #4f46e5;
    border: none;
    border-radius: 8px;
    padding: 12px 25px;
    font-weight: 600;
    width: 100%;
    margin-top: 10px;
    transition: all 0.3s;
  }
  .btn-submit:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
  }
  .back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 500;
  }
  .back-link:hover { color: #4f46e5; }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="form-container">
    <div class="form-card">
      <h3><i class="glyphicon glyphicon-plus-sign" style="color: #4f46e5; margin-right: 10px;"></i> Add User Group</h3>
      
      <form method="post" action="add_group.php">
        <div class="form-group">
          <label for="name">Group Name</label>
          <input type="text" class="form-control" name="group-name" placeholder="e.g. Sales Team">
        </div>

        <div class="form-group">
          <label for="level">Permission Level</label>
          <input type="number" class="form-control" name="group-level" placeholder="e.g. 2">
          <small class="text-muted">Unique numeric identifier for the group.</small>
        </div>

        <div class="form-group">
          <label for="status">Account Status</label>
          <select class="form-control" name="status">
            <option value="1">Active</option>
            <option value="0">Deactive</option>
          </select>
        </div>

        <button type="submit" name="add" class="btn btn-info btn-submit">Create Group</button>
      </form>
    </div>
    <a href="group.php" class="back-link"><i class="glyphicon glyphicon-arrow-left"></i> Back to All Groups</a>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>