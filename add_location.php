<?php
  $page_title = 'Add Warehouse Location';
  require_once('includes/load.php');
  page_require_level(1);
?>
<?php
  if(isset($_POST['add_location'])){
    $req_fields = array('location-name');
    validate_fields($req_fields);

    if(empty($errors)){
      $name   = remove_junk($db->escape($_POST['location-name']));
      $zone   = remove_junk($db->escape($_POST['zone']));
      $status = remove_junk($db->escape($_POST['status']));

      $query  = "INSERT INTO locations (";
      $query .=" location_name, zone, status";
      $query .=") VALUES (";
      $query .=" '{$name}', '{$zone}', '{$status}'";
      $query .=")";
      
      if($db->query($query)){
        $session->msg('s',"Location has been created successfully!");
        redirect('add_location.php', false);
      } else {
        $session->msg('d',' Sorry, failed to create location!');
        redirect('add_location.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_location.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .location-wrapper {
    max-width: 600px;
    margin: 30px auto;
  }
  .config-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border: none;
  }
  .card-banner {
    background: #4f46e5;
    padding: 25px;
    border-radius: 12px 12px 0 0;
    color: #fff;
  }
  .card-banner h3 { margin: 0; font-size: 18px; font-weight: 700; }
  
  .form-content { padding: 30px; }
  
  .helper-box {
    background: #f8fafc;
    border-left: 4px solid #6366f1;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 0 8px 8px 0;
  }
  .helper-box p { margin-bottom: 5px; font-size: 13px; color: #475569; }
  
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
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    outline: none;
  }
  .btn-save-loc {
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 600;
    width: 100%;
    margin-top: 15px;
    transition: all 0.2s;
  }
  .btn-save-loc:hover { background: #4338ca; transform: translateY(-1px); }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
       <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="location-wrapper">
    <div class="config-card">
      <div class="card-banner">
        <h3><i class="glyphicon glyphicon-map-marker" style="margin-right: 10px;"></i> Define New Stock Location</h3>
      </div>

      <div class="form-content">
        <div class="helper-box">
          <p><strong>Pro Tip:</strong> Use a hierarchical naming system for better tracking.</p>
          <code style="color: #6366f1;">[Aisle]-[Shelf]-[Bin]</code> (e.g., <strong>A1-S2-B05</strong>)
        </div>

        <form method="post" action="add_location.php">
          <div class="form-group">
              <label>Location Name / Bin Code</label>
              <input type="text" class="form-control modern-input" name="location-name" placeholder="e.g. A1-S1-B01" required>
          </div>

          <div class="form-group">
              <label>Warehouse Zone</label>
              <input type="text" class="form-control modern-input" name="zone" placeholder="e.g. Main Floor / Receiving">
          </div>

          <div class="form-group">
            <label>Current Availability</label>
              <select class="form-control modern-input" name="status" style="height: 45px;">
                <option value="1">Active (Available for Stock)</option>
                <option value="0">Inactive (Reserved/Maintenance)</option>
              </select>
          </div>

          <button type="submit" name="add_location" class="btn-save-loc">
            Confirm and Create Location
          </button>
        </form>
      </div>
    </div>
    
    <div class="text-center" style="margin-top: 20px;">
      <a href="locations.php" style="color: #64748b; text-decoration: none; font-weight: 500;">
        <i class="glyphicon glyphicon-list"></i> View All Locations
      </a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>