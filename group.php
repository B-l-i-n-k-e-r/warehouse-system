<?php
  $page_title = 'All Group';
  require_once('includes/load.php');
  page_require_level(1);
  $all_groups = find_all('user_groups');
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .modern-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 30px;
  }
  .modern-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modern-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #333;
  }
  .table-clean {
    margin-bottom: 0;
  }
  .table-clean thead th {
    background-color: #fafafa;
    border-top: none !important;
    border-bottom: 2px solid #eee !important;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding: 15px !important;
  }
  .table-clean tbody td {
    padding: 15px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f5f5f5 !important;
  }
  .badge-pill {
    padding: 5px 12px;
    border-radius: 50px;
    font-weight: 500;
    font-size: 11px;
  }
  .badge-active { background-color: #e8f5e9; color: #2e7d32; }
  .badge-deactive { background-color: #ffebee; color: #c62828; }
  
  .action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    line-height: 32px;
    border-radius: 8px;
    transition: all 0.2s;
    border: none;
  }
  .btn-add {
    background: #4f46e5;
    color: #fff;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    transition: all 0.3s;
  }
  .btn-add:hover { background: #4338ca; color: #fff; transform: translateY(-1px); }

  /* Back Link Style from Add Group Page */
  .back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 500;
  }
  .back-link:hover { 
    color: #4f46e5; 
    text-decoration: none;
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
      <div class="modern-card">
        <div class="modern-header">
          <h2><i class="glyphicon glyphicon-th-list" style="margin-right: 10px; color: #4f46e5;"></i> User Groups</h2>
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
                    <td class="text-center text-muted"><?php echo count_id();?></td>
                    <td><strong><?php echo remove_junk(ucwords($a_group['group_name']))?></strong></td>
                    <td class="text-center">
                      <span class="label label-default" style="background:#f0f0f0; color:#666; border-radius:4px;">
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
                        <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-danger action-btn" style="margin-left: 5px;" data-toggle="tooltip" title="Remove">
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
      <a href="home.php" class="back-link">
        <i class="glyphicon glyphicon-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>