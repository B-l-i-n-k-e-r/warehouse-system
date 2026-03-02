<?php
  $page_title = 'All User';
  require_once('includes/load.php');
  page_require_level(1);
  date_default_timezone_set('Africa/Nairobi');
  $current_time = date("d M Y, h:i A");

  // Calculate Pending Count for the notification badge (Status 0 only)
  $count_sql = "SELECT COUNT(id) as total FROM users WHERE status='0'";
  $result = find_by_sql($count_sql);
  $pending_count = ($result) ? $result[0]['total'] : 0;

  // Updated SQL query to include email and reset token fields
  $sql  = "SELECT u.id, u.name, u.username, u.email, u.user_level, u.status, u.last_login, u.image, ";
  $sql .= "u.reset_token, u.reset_token_expires, g.group_name ";
  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN user_groups g ON g.group_level = u.user_level ";
  
  if(isset($_GET['status']) && $_GET['status'] == '0'){
      $sql .= "WHERE u.status='0' ";
  }
  
  $sql .= "ORDER BY u.name ASC";
  
  $all_users = find_by_sql($sql);
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .user-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: none; overflow: hidden; }
  .card-header-main { padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; background: #fff; }
  .filter-bar { background: #fcfcfd; padding: 10px 25px; border-top: 1px solid #f1f1f1; border-bottom: 1px solid #f1f1f1; }
  .nav-pills-custom .btn { border-radius: 20px; padding: 5px 15px; font-size: 12px; font-weight: 600; margin-right: 5px; border: 1px solid #ddd; color: #666; background: #fff; }
  .nav-pills-custom .btn.active { background: #4f46e5; color: #fff; border-color: #4f46e5; }
  
  /* Applying "Fit Content" rule to columns */
  .table-modern thead th { 
    background: #f8fafc; 
    color: #64748b; 
    text-transform: uppercase; 
    font-size: 11px; 
    letter-spacing: 0.05em; 
    padding: 15px 20px !important; 
    border-bottom: 1px solid #edf2f7 !important;
    white-space: nowrap;
    width: 1%; 
  }
  
  /* Allowing the details column to take remaining space */
  .table-modern thead th:nth-child(3) { width: auto; }

  .table-modern tbody td { 
    padding: 15px 20px !important; 
    vertical-align: middle !important; 
    border-top: 1px solid #f1f5f9 !important; 
    white-space: nowrap; 
  }
  
  .badge-pill { padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; }
  .badge-active { background: #dcfce7; color: #166534; }
  .badge-deactive { background: #fee2e2; color: #991b1b; }
  .badge-pending { background: #fef9c3; color: #854d0e; }
  .user-meta { font-size: 12px; color: #94a3b8; }
  .btn-action { width: 30px; height: 30px; padding: 0; line-height: 30px; border-radius: 6px; margin: 0 2px; }
  .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #eeeff2; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
  
  .btn-copy-token { 
    background: #e0e7ff; 
    color: #4338ca; 
    border: 1px solid #c7d2fe; 
    font-size: 10px; 
    font-weight: 700; 
    border-radius: 4px; 
    padding: 2px 8px; 
    cursor: pointer; 
    transition: all 0.2s;
  }
  .btn-copy-token:hover { background: #4338ca; color: #fff; }
</style>

<div class="container-fluid">
  <div class="row"><div class="col-md-12"><?php echo display_msg($msg); ?></div></div>
  <div class="row">
    <div class="col-md-12">
      <div class="user-card">
        <div class="card-header-main">
          <h3 style="margin:0; font-weight:700; color:#1e293b;">
            <i class="glyphicon glyphicon-user" style="color:#4f46e5; margin-right:10px;"></i> Users
          </h3>
          <a href="add_user.php" class="btn btn-primary" style="background:#4f46e5; border:none; border-radius:8px;">
            <i class="glyphicon glyphicon-plus"></i> Add New User
          </a>
        </div>

        <div class="filter-bar">
          <div class="nav-pills-custom">
            <a href="users.php" class="btn <?php if(!isset($_GET['status'])) echo 'active'; ?>">All Directory</a>
            <a href="users.php?status=0" class="btn <?php if(isset($_GET['status']) && $_GET['status'] == '0') echo 'active'; ?>">
              Pending Approval <?php if($pending_count > 0) echo '<span class="label label-danger">'.$pending_count.'</span>'; ?>
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding:0;">
          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Photo</th>
                  <th>User Details</th>
                  <th class="text-center">Role</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Reset Link</th>
                  <th>Last Login</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($all_users as $a_user): ?>
                <tr>
                  <td class="text-center text-muted"><?php echo count_id();?></td>
                  <td class="text-center">
                    <img class="user-avatar" src="uploads/users/<?php echo (empty($a_user['image'])) ? 'no_image.jpg' : $a_user['image']; ?>" alt="User Image">
                  </td>
                  <td>
                    <div style="font-weight:600; color:#334155;"><?php echo remove_junk(ucwords($a_user['name']))?></div>
                    <div class="user-meta">
                        @<?php echo remove_junk($a_user['username'])?> • 
                        <span style="<?php echo empty($a_user['email']) ? 'color:#ef4444; font-weight:bold;' : ''; ?>">
                            <?php echo !empty($a_user['email']) ? remove_junk($a_user['email']) : 'No Email Recorded'; ?>
                        </span>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="label label-default" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                      <?php echo remove_junk(ucwords($a_user['group_name']))?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php if($a_user['status'] === '1'): ?>
                      <span class="badge-pill badge-active">Active</span>
                    <?php elseif($a_user['status'] === '0'): ?>
                      <span class="badge-pill badge-pending">Pending</span>
                    <?php else: ?>
                      <span class="badge-pill badge-deactive">Deactivated</span>
                    <?php endif;?>
                  </td>
                  <td class="text-center">
                    <?php if(!empty($a_user['reset_token']) && strtotime($a_user['reset_token_expires']) > time()): ?>
                       <button class="btn-copy-token" onclick="copyResetLink('<?php echo $a_user['reset_token']; ?>')">
                         <i class="glyphicon glyphicon-link"></i> COPY
                       </button>
                    <?php else: ?>
                       <span style="color:#cbd5e1; font-size:10px;">None</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="user-meta">
                      <i class="glyphicon glyphicon-time" style="font-size:10px;"></i> 
                      <?php echo read_date($a_user['last_login'])?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <?php if($a_user['status'] === '0' || $a_user['status'] === '2'): ?>
                        <a href="activate_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-success btn-action" data-toggle="tooltip" title="Activate">
                          <i class="glyphicon glyphicon-ok"></i>
                        </a>
                      <?php else: ?>
                        <a href="deactivate_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-default btn-action" data-toggle="tooltip" title="Deactivate">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                        </a>
                      <?php endif; ?>
                      <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-danger btn-action" data-toggle="tooltip" title="Remove"><i class="glyphicon glyphicon-trash"></i></a>
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
</div>

<script>
function copyResetLink(token) {
    const baseUrl = window.location.origin + window.location.pathname.replace('users.php', '');
    const fullLink = baseUrl + "reset-password.php?token=" + token;
    
    navigator.clipboard.writeText(fullLink).then(() => {
        alert("✅ Reset Link Copied to Clipboard!");
    }).catch(err => {
        console.error('Copy failed', err);
    });
}
</script>

<?php include_once('layouts/footer.php'); ?>