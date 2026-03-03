<?php
  $page_title = 'All Group';
  require_once('includes/load.php');
  page_require_level(1);
  $all_groups = find_all('user_groups');
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-green: #10b981;
    --neon-red: #f43f5e;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .modern-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }

  .modern-header {
    padding: 20px 25px;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .modern-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
  }

  /* Table Fit Content & Glass Style */
  .table-clean {
    margin-bottom: 0;
    width: auto !important;
    min-width: 100%;
    background: transparent !important;
  }

  .table-clean thead th {
    background-color: rgba(255, 255, 255, 0.03) !important;
    border: none !important;
    color: var(--neon-blue);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
    padding: 15px !important;
  }

  .table-clean tbody td {
    padding: 15px !important;
    vertical-align: middle !important;
    border-top: 1px solid var(--glass-border) !important;
    color: var(--text-main);
    background: transparent !important;
  }

  .table-clean tbody tr:hover {
    background: rgba(255, 255, 255, 0.02) !important;
  }

  /* Status Badges */
  .badge-pill {
    padding: 5px 12px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 10px;
    text-transform: uppercase;
  }
  .badge-active { 
    background: rgba(16, 185, 129, 0.15); 
    color: var(--neon-green); 
    border: 1px solid rgba(16, 185, 129, 0.3);
  }
  .badge-deactive { 
    background: rgba(244, 63, 94, 0.15); 
    color: var(--neon-red); 
    border: 1px solid rgba(244, 63, 94, 0.3);
  }

  /* Group Level Label */
  .level-tag {
    background: rgba(56, 189, 248, 0.1) !important;
    color: var(--neon-blue) !important;
    border: 1px solid rgba(56, 189, 248, 0.2);
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
  }

  /* Buttons */
  .action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .btn-warning.action-btn { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
  .btn-danger.action-btn { background: rgba(244, 63, 94, 0.2); color: var(--neon-red); }
  .action-btn:hover { transform: scale(1.1); filter: brightness(1.2); }

  .btn-add {
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(48, 33, 250, 0.42);
  }
  .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(56, 189, 248, 0.4); }

  .back-link {
    display: block;
    text-align: center;
    margin-top: 30px;
    color: var(--text-muted);
    font-weight: 600;
    transition: 0.3s;
  }
  .back-link:hover { color: var(--neon-blue); text-decoration: none; }
</style>

<div class="container-fluid">
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="modern-card">
        <div class="modern-header">
          <h2><i class="glyphicon glyphicon-th-list" style="margin-right: 10px; color: var(--neon-blue);"></i> User Groups</h2>
          <a href="add_group.php" class="btn btn-add">
            <i class="glyphicon glyphicon-plus"></i> Add New Group
          </a>
        </div>
        
        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-clean">
              <thead>
                <tr>
                  <th class="text-center" style="width: 70px;">#</th>
                  <th>Group Name</th>
                  <th class="text-center">Group Level</th>
                  <th class="text-center">Status</th>
                  <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($all_groups as $a_group): ?>
                  <tr>
                    <td class="text-center text-muted" style="font-family: monospace;"><?php echo count_id();?></td>
                    <td><strong style="color: #fff;"><?php echo remove_junk(ucwords($a_group['group_name']))?></strong></td>
                    <td class="text-center">
                      <span class="level-tag">
                        Level <?php echo remove_junk(ucwords($a_group['group_level']))?>
                      </span>
                    </td>
                    <td class="text-center">
                      <?php if($a_group['group_status'] === '1'): ?>
                        <span class="badge-pill badge-active">Active</span>
                      <?php else: ?>
                        <span class="badge-pill badge-deactive">Deactive</span>
                      <?php endif;?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-warning action-btn" data-toggle="tooltip" title="Edit">
                          <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                        <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-danger action-btn" style="margin-left: 8px;" data-toggle="tooltip" title="Remove">
                          <i class="glyphicon glyphicon-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <a href="admin.php" class="back-link">
        <i class="glyphicon glyphicon-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>