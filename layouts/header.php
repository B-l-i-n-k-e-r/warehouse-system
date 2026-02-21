<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "MoonLit Warehouse System";?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />

    <style>
      body { font-family: 'Inter', sans-serif; background: #f4f7f6; }
      
      /* Modern Header Styling */
      #header {
        background: #ffffff;
        height: 70px;
        position: fixed;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      /* Logo container updated for Image + Text */
      .logo {
        background: #1a1d21;
        color: #fff;
        font-weight: 700;
        font-size: 14px; /* Slightly smaller to fit both */
        padding: 10px 10px;
        height: 100px; /* Increased height for stack */
        width: 250px;
        margin-left: -20px;
        text-align: center;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        line-height: normal;
      }

      /* Logo Image styled like Profile Picture */
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
        margin-left: 20px;
      }

      /* Profile Dropdown Styling */
      .info-menu { margin: 0; padding-right: 20px; }
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

      .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 12px;
        padding: 10px;
        margin-top: 15px;
      }

      .dropdown-menu li a {
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.2s;
      }

      .dropdown-menu li a:hover {
        background: #f0f7ff;
        color: #3498db;
      }

      /* Page Content Adjustment */
      .page {
        padding-top: 110px; /* Increased to account for taller logo box */
        padding-left: 250px;
        transition: all .3s;
      }

      .sidebar {
        width: 250px;
        position: fixed;
        top: 100px; /* Adjusted to match logo height */
        height: 100%;
        background: #1a1d21;
        z-index: 999;
      }

      @media (max-width: 768px) {
        .sidebar { left: -250px; }
        .page { padding-left: 0; }
      }
    </style>
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header" style="height: 100px;"> <div class="logo pull-left">
        <img src="libs/images/logo.jpg" alt="Logo" class="logo-img-circle">
        <div>MoonLit <span style="color:#3498db;">Warehouse</span></div>
      </div>
      
      <div class="header-content">
        <div class="header-date pull-left">
          <i class="glyphicon glyphicon-time" style="margin-right: 5px;"></i>
          <strong><?php echo date("F j, Y, g:i a");?></strong>
        </div>

        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                    <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                        <i class="glyphicon glyphicon-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="edit_account.php">
                        <i class="glyphicon glyphicon-cog"></i> Settings
                    </a>
                </li>
                <li class="divider"></li>
                <li class="last">
                    <a href="logout.php" style="color: #e74c3c;">
                        <i class="glyphicon glyphicon-off"></i> Logout
                    </a>
                </li>
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
<?php endif;?>

<div class="page">
  <div class="container-fluid">