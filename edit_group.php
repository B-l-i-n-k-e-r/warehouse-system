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

  .edit-container {
    padding: 60px 20px;
  }

  .glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    padding: 40px;
    max-width: 550px;
    margin: 0 auto;
    width: auto !important; /* Fit content rule */
  }

  .page-title {
    font-weight: 800;
    font-size: 1.8rem;
    margin-bottom: 30px;
    color: var(--text-main);
  }

  .form-label-modern {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--neon-blue);
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 10px;
    display: block;
  }

  .form-control-modern {
    background: var(--input-bg) !important;
    height: 50px;
    border-radius: 12px;
    border: 1px solid var(--glass-border);
    padding: 12px 16px;
    color: #fff !important;
    transition: 0.3s ease;
    width: 100%;
  }

  .form-control-modern:focus {
    border-color: var(--neon-blue) !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important;
    outline: none;
    transform: translateY(-1px);
  }

  .btn-modern {
    height: 52px;
    border-radius: 14px;
    border: none;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    color: white;
    box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
    transition: 0.3s;
    width: 100%;
    margin-top: 20px;
  }

  .btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(14, 165, 233, 0.5);
    filter: brightness(1.1);
  }

  .back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    color: var(--text-muted);
    font-weight: 600;
    transition: 0.3s;
  }

  .back-link:hover {
    color: var(--neon-blue);
    text-decoration: none;
    transform: translateX(-5px);
  }

  select.form-control-modern option {
    background: #1e293b;
    color: #fff;
  }
</style>

<div class="container edit-container">
  <div class="row justify-content-center">
    <div class="col-md-12">

      <div class="glass-card">

        <div class="text-center">
          <div class="page-title">
            <i class="glyphicon glyphicon-pencil" style="color: var(--neon-blue); font-size: 22px; margin-right: 10px;"></i>
            Edit Group
          </div>
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

      <a href="group.php" class="back-link">
        <i class="glyphicon glyphicon-arrow-left"></i> Back to All Groups
      </a>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>