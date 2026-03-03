<?php
  $page_title = 'All Warehouse Locations';
  require_once('includes/load.php');
  page_require_level(1);

  // --- Pagination Logic ---
  $limit = 10; 
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  // Get total count for pagination
  $total_res = $db->query("SELECT COUNT(id) as total FROM locations");
  $total_count = $db->fetch_assoc($total_res);
  $total_records = (int)$total_count['total'];
  $total_pages = ceil($total_records / $limit);

  // Fetch only 10 locations for the current page
  $locations = find_by_sql("SELECT * FROM locations ORDER BY location_name ASC LIMIT {$limit} OFFSET {$offset}");
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-green: #10b981;
    --neon-red: #f43f5e;
    --neon-orange: #fb923c;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .location-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 1px solid var(--glass-border);
    overflow: hidden;
    margin-bottom: 30px;
  }

  .card-header-flex {
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: transparent;
    border-bottom: 1px solid var(--glass-border);
  }

  .card-header-flex h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
  }

  .btn-modern-add {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    color: #fff;
    border-radius: 8px;
    padding: 8px 18px;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    border: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2);
  }

  .btn-modern-add:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 8px 20px rgba(56, 189, 248, 0.4);
    color: #fff;
  }
  
  /* Fit Content Table Rule */
  .table-modern {
    width: auto !important;
    min-width: 100%;
    background: transparent !important;
  }

  .table-modern thead th {
    background: rgba(255, 255, 255, 0.05) !important;
    color: var(--neon-blue) !important;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 1px;
    padding: 15px 20px !important;
    border: none !important;
    white-space: nowrap;
    width: 1%;
  }

  .table-modern thead th:nth-child(2) { width: auto; } /* Bin Code column takes space */

  .table-modern tbody td {
    padding: 15px 20px !important;
    vertical-align: middle !important;
    border-top: 1px solid var(--glass-border) !important;
    white-space: nowrap;
    color: var(--text-main);
  }

  .table-modern tbody tr:hover {
    background: rgba(255, 255, 255, 0.02) !important;
  }

  .bin-code {
    font-family: 'Monaco', 'Consolas', monospace;
    background: rgba(56, 189, 248, 0.1);
    padding: 4px 8px;
    border-radius: 6px;
    color: var(--neon-blue);
    border: 1px solid rgba(56, 189, 248, 0.2);
    font-weight: 600;
  }

  .zone-badge {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid var(--glass-border);
  }

  .status-pill {
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
  }

  .status-pill-success { background: rgba(16, 185, 129, 0.15); color: var(--neon-green); border: 1px solid rgba(16, 185, 129, 0.3); }
  .status-pill-danger { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); border: 1px solid rgba(244, 63, 94, 0.3); }
  
  .btn-action {
    width: 32px;
    height: 32px;
    line-height: 32px;
    display: inline-block;
    border-radius: 8px;
    text-align: center;
    transition: 0.3s;
  }

  .btn-action-edit { background: rgba(251, 146, 60, 0.15); color: var(--neon-orange); }
  .btn-action-delete { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); }
  .btn-action:hover { transform: scale(1.1); filter: brightness(1.2); }

  /* --- Pagination Styles --- */
  .pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid var(--glass-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: transparent;
  }

  .btn-pagination {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    color: var(--text-muted);
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    transition: 0.3s;
    text-decoration: none;
  }

  .btn-pagination:hover:not(.disabled) {
    background: var(--neon-blue);
    border-color: var(--neon-blue);
    color: #fff;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
  }

  .btn-pagination.disabled {
    opacity: 0.3;
    cursor: not-allowed;
  }
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
            <i class="glyphicon glyphicon-map-marker" style="color:var(--neon-blue); margin-right: 10px;"></i>
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
                  <th class="text-center">#</th>
                  <th>Bin Code / Name</th>
                  <th>Warehouse Zone</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = $offset + 1;
                foreach($locations as $loc): 
                ?>
                  <tr>
                    <td class="text-center" style="color: var(--text-muted); font-family: monospace;"><?php echo $count++; ?></td>
                    <td>
                      <span class="bin-code"><?php echo remove_junk(ucfirst($loc['location_name'])); ?></span>
                    </td>
                    <td>
                      <span class="zone-badge">
                        <i class="glyphicon glyphicon-tags" style="font-size: 10px; margin-right: 6px; color: var(--neon-blue);"></i>
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
                    <td colspan="5" class="text-center" style="padding: 60px !important;">
                      <i class="glyphicon glyphicon-info-sign" style="font-size: 40px; color: var(--glass-border); display: block; margin-bottom: 15px;"></i>
                      <p style="color: var(--text-muted); font-weight: 600;">No warehouse locations found in this sector.</p>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-wrapper">
            <div class="small" style="color: var(--text-muted);">
              Showing <span style="color: var(--text-main); font-weight: 700;"><?php echo min($offset + 1, $total_records); ?>-<?php echo min($offset + $limit, $total_records); ?></span> of <span style="color: var(--neon-blue); font-weight: 700;"><?php echo $total_records; ?></span> Bins
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo $page - 1; ?>" 
                 class="btn btn-pagination <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <i class="glyphicon glyphicon-chevron-left" style="font-size: 10px;"></i> Prev
              </a>
              <a href="?page=<?php echo $page + 1; ?>" 
                 class="btn btn-pagination <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>" 
                 style="margin-left: 8px;">
                Next <i class="glyphicon glyphicon-chevron-right" style="font-size: 10px;"></i>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>