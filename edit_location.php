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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-orange: #fb923c;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-bg: rgba(15, 23, 42, 0.6);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .edit-wrapper {
    max-width: 600px;
    margin: 40px auto;
  }

  .edit-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    overflow: hidden;
  }

  .card-header-orange {
    background: linear-gradient(135deg, rgba(251, 146, 60, 0.1), rgba(249, 115, 22, 0.1));
    padding: 30px;
    border-bottom: 1px solid var(--glass-border);
  }

  .card-header-orange h3 { 
    margin: 0; 
    font-size: 20px; 
    font-weight: 800; 
    color: var(--text-main);
    letter-spacing: -0.5px;
  }

  .form-content { padding: 40px; }

  .notice-box {
    background: rgba(251, 146, 60, 0.05);
    border-left: 4px solid var(--neon-orange);
    padding: 15px 20px;
    margin-bottom: 30px;
    border-radius: 4px 12px 12px 4px;
  }

  .notice-box p { 
    margin: 0; 
    font-size: 13px; 
    color: var(--text-main); 
    font-weight: 600;
    line-height: 1.5;
  }

  .form-group label {
    font-weight: 700;
    color: var(--neon-orange);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 10px;
    display: block;
  }

  .modern-input {
    background: var(--input-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 12px;
    height: 50px;
    color: #fff !important;
    padding: 10px 18px;
    transition: all 0.3s ease;
  }

  .modern-input:focus {
    border-color: var(--neon-orange) !important;
    box-shadow: 0 0 15px rgba(251, 146, 60, 0.2) !important;
    outline: none;
    transform: translateY(-1px);
  }

  select.modern-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23fb923c'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
  }

  select.modern-input option {
    background: #1e293b;
    color: #fff;
  }

  .btn-update-loc {
    background: linear-gradient(135deg, var(--neon-orange), #f97316);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 15px 25px;
    font-weight: 800;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    margin-top: 20px;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(249, 115, 22, 0.2);
  }

  .btn-update-loc:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 15px 25px rgba(249, 115, 22, 0.4);
    filter: brightness(1.1);
    color: #fff;
  }

  .back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: 0.3s;
  }

  .back-link:hover { 
    color: var(--neon-orange); 
    transform: translateX(-3px);
  }
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
        <h3>
          <i class="glyphicon glyphicon-edit" style="color: var(--neon-orange); margin-right: 12px;"></i> 
          Edit Bin: <?php echo remove_junk(ucfirst($location['location_name'])); ?>
        </h3>
      </div>

      <div class="form-content">
        <div class="notice-box">
          <p><i class="glyphicon glyphicon-exclamation-sign"></i> System Warning: Renaming this bin will automatically re-map all inventory items currently linked to this code.</p>
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
              <select class="form-control modern-input" name="status">
                <option <?php if($location['status'] === '1') echo 'selected'; ?> value="1">Active (Available)</option>
                <option <?php if($location['status'] === '0') echo 'selected'; ?> value="0">Inactive (Blocked)</option>
              </select>
          </div>

          <button type="submit" name="edit_loc" class="btn-update-loc">
            Apply Sector Changes
          </button>
        </form>
      </div>
    </div>
    
    <a href="locations.php" class="back-link">
      <i class="glyphicon glyphicon-arrow-left"></i> Cancel and Return to Locations
    </a>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>