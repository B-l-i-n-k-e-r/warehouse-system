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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-green: #10b981;
    --neon-red: #f43f5e;
    --neon-yellow: #fbbf24;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .user-card { 
    background: var(--glass-bg); 
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    border: 1px solid var(--glass-border); 
    overflow: hidden; 
  }

  .card-header-main { 
    padding: 20px 25px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    background: transparent;
    border-bottom: 1px solid var(--glass-border);
  }

  .filter-bar { 
    background: rgba(255, 255, 255, 0.02); 
    padding: 12px 25px; 
    border-bottom: 1px solid var(--glass-border); 
  }

  .nav-pills-custom .btn { 
    border-radius: 20px; 
    padding: 6px 18px; 
    font-size: 11px; 
    font-weight: 700; 
    margin-right: 8px; 
    border: 1px solid var(--glass-border); 
    color: var(--text-muted); 
    background: transparent; 
    text-transform: uppercase;
    transition: 0.3s;
  }

  .nav-pills-custom .btn.active { 
    background: var(--neon-blue); 
    color: #fff; 
    border-color: var(--neon-blue); 
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
  }
  
  /* Applying "Fit Content" rule to columns */
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
  
  .table-modern thead th:nth-child(3) { width: auto; }

  .table-modern tbody td { 
    padding: 12px 20px !important; 
    vertical-align: middle !important; 
    border-top: 1px solid var(--glass-border) !important; 
    white-space: nowrap; 
    color: var(--text-main) !important;
    background: transparent !important;
  }

  .table-modern tbody tr:hover {
    background: rgba(255, 255, 255, 0.03) !important;
  }
  
  .badge-pill { padding: 4px 12px; border-radius: 50px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
  .badge-active { background: rgba(16, 185, 129, 0.15); color: var(--neon-green); border: 1px solid rgba(16, 185, 129, 0.3); }
  .badge-deactive { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); border: 1px solid rgba(244, 63, 94, 0.3); }
  .badge-pending { background: rgba(251, 191, 36, 0.15); color: var(--neon-yellow); border: 1px solid rgba(251, 191, 36, 0.3); }
  
  .user-meta { font-size: 12px; color: var(--text-muted); }
  .btn-action { width: 32px; height: 32px; padding: 0; line-height: 32px; border-radius: 8px; margin: 0 2px; border: none; transition: 0.2s; }
  .btn-action:hover { transform: scale(1.1); filter: brightness(1.2); }
  
  .user-avatar { width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 1px solid var(--glass-border); }
  
  .btn-copy-token { 
    background: rgba(56, 189, 248, 0.1); 
    color: var(--neon-blue); 
    border: 1px solid rgba(56, 189, 248, 0.2); 
    font-size: 10px; 
    font-weight: 700; 
    border-radius: 6px; 
    padding: 4px 10px; 
    cursor: pointer; 
  }
  .btn-copy-token:hover { background: var(--neon-blue); color: #fff; }

  .label-role {
    background: rgba(148, 163, 184, 0.1);
    color: var(--text-muted);
    border: 1px solid var(--glass-border);
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
  }
</style>

<div class="container-fluid">
  <div class="row"><div class="col-md-12"><?php echo display_msg($msg); ?></div></div>
  <div class="row">
    <div class="col-md-12">
      <div class="user-card">
        <div class="card-header-main">
          <h3 style="margin:0; font-weight:700; color:var(--text-main);">
            <i class="glyphicon glyphicon-user" style="color:var(--neon-blue); margin-right:10px;"></i> Users
          </h3>
          <a href="add_user.php" class="btn btn-primary" style="background: linear-gradient(135deg, var(--neon-blue), #0ea5e9); border:none; border-radius:8px; font-weight:700;">
            <i class="glyphicon glyphicon-plus"></i> Add New User
          </a>
        </div>

        <div class="filter-bar">
          <div class="nav-pills-custom">
            <a href="users.php" class="btn <?php if(!isset($_GET['status'])) echo 'active'; ?>">All Directory</a>
            <a href="users.php?status=0" class="btn <?php if(isset($_GET['status']) && $_GET['status'] == '0') echo 'active'; ?>">
              Pending Approval <?php if($pending_count > 0) echo '<span class="label label-danger" style="border-radius:10px; margin-left:5px;">'.$pending_count.'</span>'; ?>
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
                  <td class="text-center text-muted" style="font-family:monospace;"><?php echo count_id();?></td>
                  <td class="text-center">
                    <img class="user-avatar" src="uploads/users/<?php echo (empty($a_user['image'])) ? 'no_image.jpg' : $a_user['image']; ?>" alt="User Image">
                  </td>
                  <td>
                    <div style="font-weight:700; color:#fff; font-size:14px;"><?php echo remove_junk(ucwords($a_user['name']))?></div>
                    <div class="user-meta">
                        <span style="color:var(--neon-blue)">@<?php echo remove_junk($a_user['username'])?></span> • 
                        <span style="<?php echo empty($a_user['email']) ? 'color:var(--neon-red); font-weight:bold;' : ''; ?>">
                            <?php echo !empty($a_user['email']) ? remove_junk($a_user['email']) : 'No Email Recorded'; ?>
                        </span>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="label-role">
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
                       <span style="color:var(--glass-border); font-size:10px;">None</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="user-meta">
                      <i class="glyphicon glyphicon-time" style="font-size:10px; color:var(--neon-blue)"></i> 
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
                        <a href="deactivate_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-default btn-action" style="background:rgba(255,255,255,0.1); color:#fff;" data-toggle="tooltip" title="Deactivate">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                        </a>
                      <?php endif; ?>
                      <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-warning btn-action" style="background:rgba(251,191,36,0.2); color:#fbbf24;" data-toggle="tooltip" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-danger btn-action" style="background:rgba(244,63,94,0.2); color:var(--neon-red);" data-toggle="tooltip" title="Remove"><i class="glyphicon glyphicon-trash"></i></a>
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
        alert("Reset Link Copied to Clipboard!");
    }).catch(err => {
        console.error('Copy failed', err);
    });
}
</script>

<?php include_once('layouts/footer.php'); ?>