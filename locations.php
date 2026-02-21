<?php
  $page_title = 'All Warehouse Locations';
  require_once('includes/load.php');
  page_require_level(1);
  $locations = find_all_locations();
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .location-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: none;
    overflow: hidden;
  }
  .card-header-flex {
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
  }
  .card-header-flex h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #334155;
  }
  .btn-modern-add {
    background: #4f46e5;
    color: #fff;
    border-radius: 8px;
    padding: 8px 18px;
    font-weight: 600;
    border: none;
    transition: all 0.2s;
  }
  .btn-modern-add:hover { background: #4338ca; color: #fff; transform: translateY(-1px); }
  
  .table-modern { margin-bottom: 0; }
  .table-modern thead th {
    background: #f8fafc;
    color: #64748b;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.05em;
    padding: 15px 20px !important;
    border-bottom: 1px solid #edf2f7 !important;
    border-top: none !important;
  }
  .table-modern tbody td {
    padding: 15px 20px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .bin-code {
    font-family: 'Monaco', 'Consolas', monospace;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    color: #475569;
    font-size: 13px;
  }
  .zone-badge {
    color: #6366f1;
    background: #eef2ff;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }
  .status-pill {
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
  }
  .status-pill-success { background: #dcfce7; color: #166534; }
  .status-pill-danger { background: #fee2e2; color: #991b1b; }
  
  .btn-action {
    width: 32px;
    height: 32px;
    line-height: 32px;
    display: inline-block;
    border-radius: 8px;
    text-align: center;
    transition: all 0.2s;
  }
  .btn-action-edit { background: #fff7ed; color: #f97316; }
  .btn-action-delete { background: #fef2f2; color: #ef4444; }
</style>

<div class="container-fluid">
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="location-card">
        <div class="card-header-flex">
          <h2>
            <i class="glyphicon glyphicon-map-marker" style="color:#4f46e5; margin-right: 10px;"></i>
            Warehouse Layout: Aisles & Bins
          </h2>
          <a href="add_location.php" class="btn-modern-add">
            <i class="glyphicon glyphicon-plus"></i> Add New Bin
          </a>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th class="text-center" style="width: 60px;">#</th>
                  <th>Bin Code / Name</th>
                  <th>Warehouse Zone</th>
                  <th class="text-center">Status</th>
                  <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($locations as $loc): ?>
                  <tr>
                    <td class="text-center text-muted"><?php echo count_id();?></td>
                    <td>
                      <span class="bin-code"><?php echo remove_junk(ucfirst($loc['location_name'])); ?></span>
                    </td>
                    <td>
                      <span class="zone-badge">
                        <i class="glyphicon glyphicon-tags" style="font-size: 10px; margin-right: 4px;"></i>
                        <?php echo remove_junk(ucfirst($loc['zone'])); ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <?php if($loc['status'] == '1'): ?>
                        <span class="status-pill status-pill-success">Ready for Stock</span>
                      <?php else: ?>
                        <span class="status-pill status-pill-danger">Blocked / Full</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_location.php?id=<?php echo (int)$loc['id'];?>" class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Bin">
                          <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="delete_location.php?id=<?php echo (int)$loc['id'];?>" class="btn-action btn-action-delete" style="margin-left: 8px;" data-toggle="tooltip" title="Remove Bin" onclick="return confirm('Are you sure?');">
                          <i class="glyphicon glyphicon-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
                
                <?php if(empty($locations)): ?>
                  <tr>
                    <td colspan="5" class="text-center" style="padding: 40px !important;">
                      <i class="glyphicon glyphicon-info-sign" style="font-size: 30px; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                      <p class="text-muted">No warehouse locations defined yet.</p>
                      <a href="add_location.php" class="btn btn-sm btn-default">Create First Bin</a>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>