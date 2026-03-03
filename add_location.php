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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-bg: rgba(15, 23, 42, 0.6);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .location-wrapper {
    max-width: 600px;
    margin: 40px auto;
  }

  .config-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    overflow: hidden;
  }

  .card-banner {
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.1), rgba(14, 165, 233, 0.1));
    padding: 30px;
    border-bottom: 1px solid var(--glass-border);
    text-align: center;
  }

  .card-banner h3 { 
    margin: 0; 
    font-size: 20px; 
    font-weight: 800; 
    letter-spacing: -0.5px;
    color: var(--text-main);
  }

  .form-content { padding: 40px; }

  .helper-box {
    background: rgba(56, 189, 248, 0.05);
    border-left: 4px solid var(--neon-blue);
    padding: 15px 20px;
    margin-bottom: 30px;
    border-radius: 4px 12px 12px 4px;
  }

  .helper-box p { 
    margin-bottom: 5px; 
    font-size: 13px; 
    color: var(--text-main); 
    font-weight: 600;
  }

  .form-group label {
    font-weight: 700;
    color: var(--neon-blue);
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
    border-color: var(--neon-blue) !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important;
    outline: none;
    transform: translateY(-1px);
  }

  /* Style for Select dropdown */
  select.modern-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 15px;
  }

  select.modern-input option {
    background: #1e293b;
    color: #fff;
  }

  .btn-save-loc {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
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
    box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
  }

  .btn-save-loc:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 15px 25px rgba(14, 165, 233, 0.4);
    filter: brightness(1.1);
  }

  .back-link {
    display: inline-block;
    margin-top: 25px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: 0.3s;
  }

  .back-link:hover {
    color: var(--neon-blue);
    transform: translateX(-3px);
  }
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
        <h3><i class="glyphicon glyphicon-map-marker" style="color: var(--neon-blue); margin-right: 12px;"></i> Define New Stock Location</h3>
      </div>

      <div class="form-content">
        <div class="helper-box">
          <p><i class="glyphicon glyphicon-info-sign"></i> Optimization Strategy</p>
          <code style="background: transparent; color: var(--neon-blue); font-size: 14px; padding: 0;">[Aisle]-[Shelf]-[Bin]</code>
          <div style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">Example: <strong>A1-S2-B05</strong></div>
        </div>

        <form method="post" action="add_location.php">
          <div class="form-group">
              <label>Location Name / Bin Code</label>
              <input type="text" class="form-control modern-input" name="location-name" placeholder="e.g. A1-S1-B01" required>
          </div>

          <div class="form-group">
              <label>Warehouse Zone</label>
              <input type="text" class="form-control modern-input" name="zone" placeholder="e.g. Main Floor / Cold Storage">
          </div>

          <div class="form-group">
            <label>Current Availability</label>
              <select class="form-control modern-input" name="status">
                <option value="1">Active (Ready for Stock)</option>
                <option value="0">Inactive (Reserved/Maintenance)</option>
              </select>
          </div>

          <button type="submit" name="add_location" class="btn-save-loc">
            Initialize Location
          </button>
        </form>
      </div>
    </div>
    
    <div class="text-center">
      <a href="locations.php" class="back-link">
        <i class="glyphicon glyphicon-arrow-left"></i> Return to Locations
      </a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>