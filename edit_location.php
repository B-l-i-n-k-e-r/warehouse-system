<?php
  $page_title = 'Edit Location';
  require_once('includes/load.php');
  page_require_level(1);
?>
<?php
  $location = find_by_id('locations',(int)$_GET['id']);
  if(!$location){
    $session->msg("d","Missing location id.");
    redirect('locations.php');
  }
?>
<?php
  if(isset($_POST['edit_loc'])){
    $req_fields = array('location-name');
    validate_fields($req_fields);
    if(empty($errors)){
      $name   = remove_junk($db->escape($_POST['location-name']));
      $zone   = remove_junk($db->escape($_POST['zone']));
      $status = remove_junk($db->escape($_POST['status']));

      $query  = "UPDATE locations SET";
      $query .= " location_name='{$name}', zone='{$zone}', status='{$status}'";
      $query .= " WHERE id='{$location['id']}'";
      
      if($db->query($query)){
        $session->msg('s',"Location updated successfully! ");
        redirect('locations.php', false);
      } else {
        $session->msg('d',' Sorry, failed to update location!');
        redirect('edit_location.php?id='.(int)$location['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_location.php?id='.(int)$location['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .edit-wrapper {
    max-width: 600px;
    margin: 30px auto;
  }
  .edit-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border: none;
    overflow: hidden;
  }
  .card-header-orange {
    background: #f59e0b; /* Professional Warning Orange */
    padding: 25px;
    color: #fff;
  }
  .card-header-orange h3 { margin: 0; font-size: 18px; font-weight: 700; }
  
  .form-content { padding: 30px; }
  
  .notice-box {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 0 8px 8px 0;
  }
  .notice-box p { margin: 0; font-size: 13px; color: #92400e; }
  
  .form-group label {
    font-weight: 600;
    color: #334155;
    font-size: 13px;
    margin-bottom: 8px;
    display: block;
  }
  .modern-input {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    height: 45px;
    padding: 10px 15px;
    transition: all 0.2s;
  }
  .modern-input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    outline: none;
  }
  .btn-update-loc {
    background: #f59e0b;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 600;
    width: 100%;
    margin-top: 15px;
    transition: all 0.2s;
  }
  .btn-update-loc:hover { 
    background: #d97706; 
    transform: translateY(-1px);
    color: #fff;
  }
  .back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
  }
  .back-link:hover { color: #f59e0b; }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
       <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="edit-wrapper">
    <div class="edit-card">
      <div class="card-header-orange">
        <h3><i class="glyphicon glyphicon-edit" style="margin-right: 10px;"></i> Edit Bin: <?php echo remove_junk(ucfirst($location['location_name'])); ?></h3>
      </div>

      <div class="form-content">
        <div class="notice-box">
          <p><strong>Note:</strong> Renaming a bin will update the location label for all products currently assigned to this code.</p>
        </div>

        <form method="post" action="edit_location.php?id=<?php echo (int)$location['id']; ?>">
          <div class="form-group">
              <label>Location Name / Bin Code</label>
              <input type="text" class="form-control modern-input" name="location-name" value="<?php echo remove_junk($location['location_name']); ?>">
          </div>

          <div class="form-group">
              <label>Warehouse Zone</label>
              <input type="text" class="form-control modern-input" name="zone" value="<?php echo remove_junk($location['zone']); ?>">
          </div>

          <div class="form-group">
            <label>Current Status</label>
              <select class="form-control modern-input" name="status" style="height: 45px;">
                <option <?php if($location['status'] === '1') echo 'selected'; ?> value="1">Active (Available)</option>
                <option <?php if($location['status'] === '0') echo 'selected'; ?> value="0">Inactive (Blocked)</option>
              </select>
          </div>

          <button type="submit" name="edit_loc" class="btn-update-loc">
            Apply Changes
          </button>
        </form>
      </div>
    </div>
    
    <a href="locations.php" class="back-link">
      <i class="glyphicon glyphicon-arrow-left"></i> Cancel and Return
    </a>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>