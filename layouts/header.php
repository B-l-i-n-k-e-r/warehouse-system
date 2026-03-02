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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />

    <style>
      body { 
        font-family: 'Inter', sans-serif; 
        background: #f4f7f6; 
        margin: 0;
        padding: 0;
      }

      /* --- Custom Instruction: Columns fit content --- */
      .container-fluid, .row, .col-fixed {
        display: table;
        width: 100%;
        table-layout: auto; /* Forces columns to respect content size */
      }
      
      /* --- Notification Badge Styles --- */
      .notif-badge {
        background-color: #e74c3c !important;
        color: white !important;
        font-size: 10px !important;
        padding: 2px 7px !important;
        border-radius: 10px !important;
        font-weight: bold !important;
        position: absolute !important;
        right: 40px; 
        top: 50%;
        transform: translateY(-50%);
        box-shadow: 0 0 8px rgba(231, 76, 60, 0.5);
        z-index: 10;
      }

      /* --- Header & Layout Styling --- */
      #header {
        background: #ffffff;
        height: 100px;
        position: fixed;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .header-left {
        display: flex;
        align-items: center;
      }

      .logo {
        background: #1a1d21;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        height: 100px;
        width: fit-content; 
        min-width: 250px;
        text-align: center;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
        gap: 5px;
        transition: all 0.3s ease;
      }

      .toggle-sidebar {
        font-size: 24px;
        cursor: pointer;
        color: #3498db;
        margin-left: 15px;
        padding: 10px;
        transition: transform 0.3s;
      }
      .toggle-sidebar:hover { transform: scale(1.1); }

      .logo-img-circle {
        width: 45px;
        height: 45px;
        border: 2px solid #3498db;
        padding: 2px;
        border-radius: 50%;
        object-fit: cover;
        background: #fff;
      }

      .header-date {
        color: #888;
        font-size: 13px;
        font-weight: 400;
        margin-left: 15px;
      }

      body.sidebar-collapsed .sidebar { left: -250px; }
      body.sidebar-collapsed .page { padding-left: 0; }
      body.sidebar-collapsed .logo { width: 0; overflow: hidden; padding: 0; }

      .page {
        padding-top: 110px;
        padding-left: 250px;
        transition: all .3s ease;
      }

      .sidebar {
        width: 250px;
        position: fixed;
        top: 100px;
        height: 100%;
        background: #1a1d21;
        z-index: 999;
        transition: all 0.3s ease;
        left: 0;
        overflow-y: auto;
      }

      @media (max-width: 768px) {
        .sidebar { left: -250px; }
        .page { padding-left: 0; }
        body.sidebar-open .sidebar { left: 0; }
      }

      .profile { padding-right: 25px; }
      .profile a.toggle {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .img-circle.img-inline {
        width: 40px;
        height: 40px;
        border: 2px solid #3498db;
        padding: 2px;
        object-fit: cover;
      }
    </style>
  </head>
  <body>
  <?php if ($session->isUserLoggedIn(true) && is_array($user)): ?>
    <header id="header">
      <div class="header-left">
        <div class="logo">
          <img src="libs/images/logo.jpg" alt="Logo" class="logo-img-circle">
          <div>MoonLit <span style="color:#3498db;">Warehouse</span></div>
        </div>
        
        <div class="toggle-sidebar" onclick="toggleSidebar()">
          <i class="glyphicon glyphicon-menu-hamburger"></i>
        </div>

        <div class="header-date">
          <i class="glyphicon glyphicon-time" style="margin-right: 5px;"></i>
          <strong><?php echo date("F j, Y, g:i a");?></strong>
        </div>
      </div>
      
      <div class="header-content">
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="profile.php?id=<?php echo (int)$user['id'];?>"><i class="glyphicon glyphicon-user"></i> Profile</a></li>
                <li><a href="edit_account.php"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
                <li class="divider"></li>
                <li class="last"><a href="logout.php" style="color: #e74c3c;"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
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
        document.body.classList.toggle('sidebar-collapsed');
        if(window.innerWidth <= 768) {
          document.body.classList.toggle('sidebar-open');
        }
      }
    </script>
  <?php endif;?>

  <div class="page">
    <div class="container-fluid">