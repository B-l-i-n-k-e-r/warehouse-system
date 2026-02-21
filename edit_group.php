<?php
  $page_title = 'Edit Group';
  require_once('includes/load.php');
  page_require_level(1);
?>

<?php
  $e_group = find_by_id('user_groups',(int)$_GET['id']);
  if(!$e_group){
    $session->msg("d","Missing Group id.");
    redirect('group.php');
  }
?>

<?php
  if(isset($_POST['update'])){

   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);

   if(empty($errors)){
      $name   = remove_junk($db->escape($_POST['group-name']));
      $level  = remove_junk($db->escape($_POST['group-level']));
      $status = remove_junk($db->escape($_POST['status']));

      $query  = "UPDATE user_groups SET ";
      $query .= "group_name='{$name}',group_level='{$level}',group_status='{$status}'";
      $query .= "WHERE ID='{$db->escape($e_group['id'])}'";

      $result = $db->query($query);

      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Group has been updated! ");
        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      } else {
        $session->msg('d',' Sorry failed to updated Group!');
        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      }

   } else {
     $session->msg("d", $errors);
     redirect('edit_group.php?id='.(int)$e_group['id'], false);
   }
 }
?>

<?php include_once('layouts/header.php'); ?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  body {
    background: linear-gradient(135deg,#f8fafc,#eef2ff);
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .edit-container {
    padding: 60px 20px;
  }

  .glass-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.6);
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    padding: 40px;
    transition: 0.3s ease;
  }

  .glass-card:hover {
    transform: translateY(-4px);
  }

  .page-title {
    font-weight: 800;
    font-size: 1.8rem;
    margin-bottom: 30px;
    color: #0f172a;
  }

  .form-label-modern {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 8px;
    display: block;
  }

  .form-control-modern {
    height: 50px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    padding: 12px 16px;
    transition: 0.2s;
    width: 100%;
  }

  .form-control-modern:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
    outline: none;
  }

  .btn-modern {
    height: 52px;
    border-radius: 14px;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    color: white;
    box-shadow: 0 8px 20px rgba(99,102,241,0.3);
    transition: 0.3s;
    width: 100%;
  }

  .btn-modern:hover {
    transform: translateY(-2px);
  }

</style>

<div class="container edit-container">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="glass-card">

        <div class="text-center">
          <div class="page-title">Edit Group</div>
        </div>

        <?php echo display_msg($msg); ?>

        <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id'];?>">

          <div class="form-group">
            <label class="form-label-modern">Group Name</label>
            <input 
              type="text"
              class="form-control-modern"
              name="group-name"
              value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
          </div>

          <div class="form-group" style="margin-top:20px;">
            <label class="form-label-modern">Group Level</label>
            <input 
              type="number"
              class="form-control-modern"
              name="group-level"
              value="<?php echo (int)$e_group['group_level']; ?>">
          </div>

          <div class="form-group" style="margin-top:20px;">
            <label class="form-label-modern">Status</label>
            <select class="form-control-modern" name="status">
              <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"';?> value="1">Active</option>
              <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
            </select>
          </div>

          <div style="margin-top:35px;">
            <button type="submit" name="update" class="btn-modern">
              Update Group
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>