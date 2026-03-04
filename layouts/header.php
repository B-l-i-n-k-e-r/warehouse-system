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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
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
      /* ===== MOONLIT AUTO-FIT RESPONSIVE ENGINE ===== */
      
      :root {
        --sidebar-bg: #0f172a;
        --header-bg: #1e293b;
        --accent: #38bdf8;
        --primary-glow: rgba(56, 189, 248, 0.15);
        --header-height: 60px; 
        --sidebar-width: 240px; 
        --text-main: #f8fafc;
        --border: rgba(99, 102, 241, 0.2);
      }

      * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

      body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background: #0f172a;
        margin: 0;
        padding: 0;
        overflow-x: hidden; 
        color: var(--text-main);
        width: 100%;
      }

      /* 1. Universal Table & Column Auto-Fit */
      .table-responsive {
        border: none !important;
        overflow-x: auto !important;
        width: 100% !important;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 20px !important;
      }

      table.table {
        width: 100% !important;
        table-layout: auto !important; /* Forces columns to fit content */
        margin-bottom: 0;
        background: rgba(30, 41, 59, 0.4) !important;
      }
      
      .table th, .table td {
        white-space: nowrap !important; /* Prevents awkward multi-line numbers */
        width: 1% !important; /* Initial shrink to content */
        vertical-align: middle !important;
        padding: 10px 8px !important;
        border-color: var(--border) !important;
        font-size: 13px;
      }
      
      /* Primary Expandable Column (Product Name) */
      .table td:nth-child(2), 
      .table th:nth-child(2),
      .col-expand {
        width: auto !important; 
        white-space: normal !important; /* Allows wrap for long names */
        min-width: 140px;
        word-break: break-word;
      }

      /* 2. Responsive Layout Containers */
      .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        padding: 10px !important;
      }

      .panel {
        background: #1e293b !important;
        border: 1px solid var(--border) !important;
        border-radius: 16px !important;
        width: 100% !important;
        margin-bottom: 15px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
      }

      /* 3. Header & Navigation (Mobile Responsive) */
      #header {
        background: rgba(30, 41, 59, 0.95);
        backdrop-filter: blur(15px);
        height: var(--header-height);
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 15px;
      }

      .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .logo-img-circle {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 2px solid var(--accent);
      }

      .brand-name {
        color: #fff;
        font-weight: 800;
        font-size: 17px;
        letter-spacing: -0.5px;
      }

      /* Desktop sidebar */
      .sidebar {
        width: var(--sidebar-width);
        position: fixed;
        top: var(--header-height);
        height: calc(100vh - var(--header-height));
        background: var(--sidebar-bg);
        z-index: 999;
        transition: all 0.3s ease;
        left: 0;
        overflow-y: auto;
        border-right: 1px solid rgba(56, 189, 248, 0.05);
      }

      .page {
        padding-top: calc(var(--header-height) + 10px);
        padding-left: 0; 
        transition: all 0.3s ease;
        min-height: 100vh;
      }

      /* Logic for desktop view */
      @media (min-width: 769px) {
        .page { padding-left: var(--sidebar-width); }
        body.sidebar-collapsed .page { padding-left: 0; }
        body.sidebar-collapsed .sidebar { left: calc(var(--sidebar-width) * -1); }
      }

      /* Mobile Slide-in Sidebar Logic */
      @media (max-width: 768px) {
        .sidebar { left: calc(var(--sidebar-width) * -1); }
        body.sidebar-visible .sidebar { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        body.sidebar-visible::after {
          content: '';
          position: fixed; top: 0; left: 0; right: 0; bottom: 0;
          background: rgba(0,0,0,0.6); z-index: 998; backdrop-filter: blur(3px);
        }
      }

      .toggle-sidebar {
        font-size: 20px;
        cursor: pointer;
        color: var(--accent);
        padding: 8px;
      }

      /* 4. Action Button Engine */
      .flex-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
      }

      .flex-actions .btn {
        flex: 1 1 auto;
        min-width: 140px;
        border-radius: 12px !important;
        padding: 12px !important;
        font-weight: 700;
      }
        
        /* --- SIDEBAR CONTENT AUTO-FIT --- */

.sidebar {
  /* Allows the sidebar itself to scroll if you have many menu items */
  overflow-y: auto;
  overflow-x: hidden;
}

.sidebar-menu {
  list-style: none;
  padding: 10px 0;
  margin: 0;
  width: 100%; /* Ensures it fills the sidebar width */
}

.sidebar-menu li {
  width: 100%;
  display: block;
}

.sidebar-menu li a {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: #94a3b8;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap; /* Prevents text from jumping to next line */
  gap: 12px;
}

/* Hide text when sidebar is collapsed (Desktop) */
body.sidebar-collapsed .sidebar-menu li a span,
body.sidebar-collapsed .menu-label {
  display: none;
}

/* On Mobile, always show text because it's a slide-out drawer */
@media (max-width: 768px) {
  .sidebar-menu li a span {
    display: inline-block !important;
  }
  .sidebar-menu li a {
    padding: 15px 25px; /* Bigger tap targets for fingers */
    font-size: 16px;
  }
}

/* Hover Effect: MoonLit Glow */
.sidebar-menu li a:hover {
  background: rgba(56, 189, 248, 0.1);
  color: var(--accent);
}

.sidebar-menu li a i {
  min-width: 20px;
  text-align: center;
  font-size: 18px;
}
    </style>
  </head>
  <body class="<?php echo (isset($_COOKIE['sidebar_state']) && $_COOKIE['sidebar_state'] == 'collapsed') ? 'sidebar-collapsed' : ''; ?>">
  
  <?php if ($session->isUserLoggedIn(true) && is_array($user)): ?>
    <header id="header">
      <div class="logo-container">
        <div class="toggle-sidebar visible-xs" onclick="toggleMobileSidebar()">
          <i class="glyphicon glyphicon-menu-hamburger"></i>
        </div>
        
        <img src="libs/images/logo.jpg" alt="L" class="logo-img-circle">
        <span class="brand-name">MoonLit<span style="color:var(--accent);">WMS</span></span>
        
        <div class="toggle-sidebar hidden-xs" onclick="toggleSidebar()" style="margin-left: 15px;">
          <i class="glyphicon <?php echo (isset($_COOKIE['sidebar_state']) && $_COOKIE['sidebar_state'] == 'collapsed') ? 'glyphicon-menu-right' : 'glyphicon-menu-left'; ?>"></i>
        </div>
      </div>
      
      <div class="pull-right">
        <div class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <img src="uploads/users/<?php echo $user['image'];?>" style="width:32px; height:32px; border-radius:50%; border:2px solid var(--accent);">
            <i class="caret" style="color: var(--accent);"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-right" style="background:#1e293b; border: 1px solid var(--border); border-radius:12px;">
            <li><a href="profile.php?id=<?php echo (int)$user['id'];?>" style="color:#fff;"><i class="glyphicon glyphicon-user"></i> Profile</a></li>
            <li><a href="edit_account.php" style="color:#fff;"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
            <li class="divider" style="background:rgba(255,255,255,0.05);"></li>
            <li><a href="logout.php" style="color:#ef4444;"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <div class="sidebar">
      <?php 
        if($user['user_level'] === '1') include_once('admin_menu.php');
        elseif($user['user_level'] === '2') include_once('special_menu.php');
        else include_once('user_menu.php');
      ?>
    </div>

    <script>
      function toggleSidebar() {
        const body = document.body;
        body.classList.toggle('sidebar-collapsed');
        const state = body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded';
        document.cookie = "sidebar_state=" + state + "; path=/";
        location.reload(); // Refresh to update icon state
      }

      function toggleMobileSidebar() {
        document.body.classList.toggle('sidebar-visible');
      }

      // Close mobile sidebar on backdrop click
      document.addEventListener('click', function(e) {
        if (document.body.classList.contains('sidebar-visible') && e.clientX > 240) {
          document.body.classList.remove('sidebar-visible');
        }
      });
    </script>
  <?php endif;?>

  <div class="page">
    <div class="container-fluid">