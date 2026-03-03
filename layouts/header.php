<?php 
  // Ensure your load file is included to prevent "Undefined Variable" errors
  require_once('includes/load.php'); 

  // 1. Fetch the user data
  $user = current_user(); 

  // 2. SECURITY CHECK: If session exists but user is NOT found in DB
  if($session->isUserLoggedIn(true) && empty($user)){
      $session->logout();
      redirect("index.php", false);
  }

  // Set Timezone to East Africa (Kenya)
  date_default_timezone_set('Africa/Nairobi');

  // Logic to fetch counts for the sidebar notifications
  $pending_count = 0;
  $low_stock_count = 0;

  if(is_array($user) && isset($user['user_level']) && $user['user_level'] === '1'){
    // Count pending users
    $notif_user = $db->query("SELECT COUNT(id) as total FROM users WHERE status = '0'");
    $pending_data = $db->fetch_assoc($notif_user);
    $pending_count = $pending_data['total'];

    // Count low stock items (Threshold: 5)
    $low_stock_query = $db->query("SELECT COUNT(id) as total FROM products WHERE quantity <= 5");
    $low_stock_data = $db->fetch_assoc($low_stock_query);
    $low_stock_count = $low_stock_data['total'];
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user) && isset($user['name']))
           echo ucfirst($user['name']);
            else echo "MoonLit Warehouse System";?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />

    <style>
      :root {
        --sidebar-bg: #0f172a;
        --header-bg: #1e293b;
        --accent: #38bdf8;
        --primary-glow: rgba(56, 189, 248, 0.15);
        --header-height: 70px;
        --sidebar-width: 260px;
      }

      body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background: #0f172a; /* Matched to MoonLit dark theme */
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        color: #f8fafc;
      }

      /* --- Global Table Content Fitting & Glow --- */
      /* Ensures columns fit content regardless of window size */
      table.table {
        width: 100% !important;
        margin-bottom: 0;
      }
      
      .table th, .table td {
        white-space: nowrap !important;
        width: 1% !important; /* Force content-based width */
      }
      
      /* Expand the primary column (usually the name/label) */
      .table td:nth-child(2) {
        width: auto !important;
      }

      .table-hover tbody tr:hover {
        background-color: rgba(56, 189, 248, 0.08) !important;
        transition: background-color 0.2s ease;
      }
      
      /* Modern Notification Badges */
      .notif-badge {
        background: #ef4444 !important;
        color: white !important;
        font-size: 10px !important;
        padding: 2px 7px !important;
        border-radius: 6px !important;
        font-weight: 800 !important;
        position: absolute !important;
        right: 15px; 
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
      }

      /* Glassmorphism Header */
      #header {
        background: rgba(30, 41, 59, 0.8);
        backdrop-filter: blur(15px);
        height: var(--header-height);
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        border-bottom: 1px solid rgba(56, 189, 248, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 30px;
      }

      .logo-container {
        height: var(--header-height);
        width: var(--sidebar-width);
        display: flex;
        align-items: center;
        padding: 0 20px;
        gap: 12px;
        transition: all 0.3s ease;
      }

      .logo-img-circle {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 2px solid var(--accent);
        box-shadow: 0 0 15px var(--primary-glow);
      }

      .brand-name {
        color: #fff;
        font-weight: 800;
        font-size: 18px;
        letter-spacing: -0.5px;
      }

      .toggle-sidebar {
        font-size: 18px;
        cursor: pointer;
        color: #94a3b8;
        margin-left: 10px;
        padding: 8px;
        border-radius: 8px;
        transition: 0.3s;
      }
      .toggle-sidebar:hover { 
        background: var(--primary-glow);
        color: var(--accent); 
      }

      .header-date {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
        margin-left: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(15, 23, 42, 0.5);
        padding: 6px 15px;
        border-radius: 50px;
        border: 1px solid rgba(56, 189, 248, 0.05);
      }

      /* Sidebar Layout */
      .sidebar {
        width: var(--sidebar-width);
        position: fixed;
        top: var(--header-height);
        height: calc(100vh - var(--header-height));
        background: var(--sidebar-bg);
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        left: 0;
        overflow-y: auto;
        border-right: 1px solid rgba(56, 189, 248, 0.05);
      }

      .page {
        padding-top: calc(var(--header-height) + 20px);
        padding-left: var(--sidebar-width);
        padding-right: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 100vh;
      }

      /* Collapsed States */
      body.sidebar-collapsed .sidebar { left: calc(var(--sidebar-width) * -1); }
      body.sidebar-collapsed .page { padding-left: 20px; }

      /* User Profile Dropdown */
      .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid var(--accent);
        object-fit: cover;
        box-shadow: 0 0 10px var(--primary-glow);
      }

      .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
        color: #f8fafc;
        font-weight: 700;
        font-size: 13px;
      }
      
      .dropdown-menu {
        background: #1e293b !important;
        border: 1px solid rgba(56, 189, 248, 0.1) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.4) !important;
        margin-top: 10px;
      }
      
      .dropdown-menu li a {
        color: #cbd5e1 !important;
        font-weight: 600;
        padding: 10px 20px !important;
      }
      
      .dropdown-menu li a:hover {
        background: var(--primary-glow) !important;
        color: var(--accent) !important;
      }
    </style>
  </head>
  <body class="<?php echo (isset($_COOKIE['sidebar_state']) && $_COOKIE['sidebar_state'] == 'collapsed') ? 'sidebar-collapsed' : ''; ?>">
  <?php if ($session->isUserLoggedIn(true) && is_array($user)): ?>
    <header id="header">
      <div style="display: flex; align-items: center;">
        <div class="logo-container">
          <img src="libs/images/logo.jpg" alt="L" class="logo-img-circle">
          <span class="brand-name">MoonLit<span style="color:var(--accent);">WMS</span></span>
        </div>
        
        <div class="toggle-sidebar" onclick="toggleSidebar()">
          <i class="glyphicon glyphicon-menu-left"></i>
        </div>

        <div class="header-date">
          <i class="glyphicon glyphicon-time" style="color: var(--accent);"></i>
          <span><?php echo date("D, M j, Y | g:i a");?></span>
        </div>
      </div>
      
      <div class="header-content">
        <div class="pull-right">
          <ul class="list-inline list-unstyled" style="margin:0;">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                <img src="uploads/users/<?php echo $user['image'];?>" alt="P" class="profile-img">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret" style="color: var(--accent);"></i></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right" style="border-radius:12px; padding: 8px;">
                <li><a href="profile.php?id=<?php echo (int)$user['id'];?>"><i class="glyphicon glyphicon-user"></i> My Profile</a></li>
                <li><a href="edit_account.php"><i class="glyphicon glyphicon-lock"></i> Settings</a></li>
                <li class="divider" style="background: rgba(255,255,255,0.05);"></li>
                <li><a href="logout.php" style="color: #ef4444 !important;"><i class="glyphicon glyphicon-log-out"></i> LogOut</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <?php include_once('admin_menu.php');?>
      <?php elseif($user['user_level'] === '2'): ?>
        <?php include_once('special_menu.php');?>
      <?php elseif($user['user_level'] === '3'): ?>
        <?php include_once('user_menu.php');?>
      <?php endif;?>
    </div>

    <script>
      function toggleSidebar() {
        const body = document.body;
        const icon = document.querySelector('.toggle-sidebar i');
        body.classList.toggle('sidebar-collapsed');
        
        const state = body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded';
        document.cookie = "sidebar_state=" + state + "; path=/";

        // Flip arrow icon based on state
        if(state === 'collapsed') {
            icon.classList.replace('glyphicon-menu-left', 'glyphicon-menu-right');
        } else {
            icon.classList.replace('glyphicon-menu-right', 'glyphicon-menu-left');
        }
      }
    </script>
  <?php endif;?>

  <div class="page">
    <div class="container-fluid">