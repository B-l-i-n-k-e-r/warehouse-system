<?php
    error_reporting(E_ALL);
  ini_set('display_errors', 1);

  $page_title = 'Home Page';
  require_once('includes/load.php');
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}

  $current_user = current_user();
  $user_level = (int)$current_user['user_level'];
?>
<?php include_once('layouts/header.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  :root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --bg-surface: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
  }

  body {
    background: radial-gradient(at top left, #f8fafc, #f1f5f9);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
  }

  .dashboard-container {
    padding: 40px 20px;
    max-width: 1200px;
  }

  /* Modern Card Styling */
  .card-modern {
    background: var(--bg-surface);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    margin-bottom: 30px;
    transition: transform 0.2s ease;
  }

  .admin-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    position: relative;
    overflow: hidden;
  }

  .admin-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  /* Stat Boxes */
  .stat-box {
    padding: 30px;
    border-radius: 20px;
    background: white;
    border: 1px solid #f1f5f9;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: #eef2ff;
    color: var(--primary);
  }

  .stat-number {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    color: var(--text-main);
  }

  .stat-label {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 500;
  }

  .welcome-text h2 {
    font-weight: 800;
    letter-spacing: -0.02em;
    margin-bottom: 10px;
  }

</style>

<div class="container dashboard-container">
  <?php echo display_msg($msg); ?>

  <?php if($user_level === 1): ?>
    <div class="card-modern admin-card">
      <div class="welcome-text">
        <h2>Admin Overview <i class="fas fa-shield-halved" style="margin-left:10px; opacity:0.8"></i></h2>
        <p style="opacity: 0.9; font-size: 1.1rem;">Full system control active. Monitoring inventory, users, and performance.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-box-open"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('products')); ?></div>
            <div class="stat-label">Inventory Items</div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('sales')); ?></div>
            <div class="stat-label">Sales Transactions</div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('users')); ?></div>
            <div class="stat-label">Active Users</div>
          </div>
        </div>
      </div>
    </div>

  <?php else: ?>
    <div class="card-modern">
      <div class="welcome-text">
        <h2>Hello, <?php echo remove_junk(ucfirst($current_user['name'])); ?>! 👋</h2>
        <p style="color: var(--text-muted);">Welcome back to your workspace. Here’s what’s happening today.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="stat-box">
          <div class="stat-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-shopping-cart"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('sales')); ?></div>
            <div class="stat-label">Sales Recorded</div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="stat-box">
          <div class="stat-icon" style="background: #fff7ed; color: #f59e0b;"><i class="fas fa-calendar-day"></i></div>
          <div>
            <div class="stat-number"><?php echo date('M d'); ?></div>
            <div class="stat-label">Current Session</div>
          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>

</div>

<?php include_once('layouts/footer.php'); ?>