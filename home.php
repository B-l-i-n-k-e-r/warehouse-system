<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  $page_title = 'MoonLit Dashboard';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}

  $current_user = current_user();
  $user_level = (int)$current_user['user_level'];
?>
<?php include_once('layouts/header.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  :root {
    --primary: #0099ff;
    --primary-dark: #0066ff;
    --surface: rgba(255, 255, 255, 0.03);
    --glass-border: rgba(255, 255, 255, 0.1);
    --text-main: #ffffff;
    --text-muted: #94a3b8;
    --bg-deep: #0f172a;
    --success: #10b981;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
    min-height: 100vh;
  }

  /* --- Fit Content Requirement --- */
  .dashboard-container {
    padding: 40px 20px;
    max-width: 1300px;
    animation: fadeIn 0.8s ease-out;
  }

  /* Force table columns to fit content */
  .table { width: auto !important; }
  .table td, .table th { white-space: nowrap !important; width: 1% !important; }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .card-modern {
    background: var(--surface);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid var(--glass-border);
    border-radius: 28px;
    padding: 45px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
  }

  .admin-card {
    background: linear-gradient(135deg, rgba(0, 153, 255, 0.15), rgba(0, 102, 255, 0.05));
    border: 1px solid rgba(0, 153, 255, 0.3);
  }

  .stat-box {
    padding: 30px;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--glass-border);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 22px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .stat-icon {
    width: 64px; height: 64px;
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    background: rgba(0, 153, 255, 0.1);
    color: var(--primary);
  }

  .stat-number { font-size: 2.2rem; font-weight: 800; color: #fff; letter-spacing: -1px; }
  .stat-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; }

  /* --- Fixed Status Badge Styling --- */
  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 16px;
    border-radius: 50px;
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 20px;
    border: 1px solid rgba(16, 185, 129, 0.2);
  }

  .pulse {
    width: 8px; height: 8px;
    background: var(--success);
    border-radius: 50%;
    margin-right: 8px;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: pulse-green 2s infinite;
  }

  @keyframes pulse-green {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
  }
</style>

<div class="container dashboard-container">
  <?php echo display_msg($msg); ?>

  <?php if($user_level === 1): ?>
    <div class="card-modern admin-card">
      <div class="welcome-text">
        <div class="status-badge"><span class="pulse"></span> System Operational</div>
        <h2 style="font-weight: 800; font-size: 2.5rem; color: #fff;">Admin Control Center</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px;">
          Welcome back, Commander. You have full oversight of inventory flow and system access.
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-cubes"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('products')); ?></div>
            <div class="stat-label">Stock Units</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('sales')); ?></div>
            <div class="stat-label">Total Sales</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">
          <div class="stat-icon"><i class="fas fa-user-gear"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('users')); ?></div>
            <div class="stat-label">System Users</div>
          </div>
        </div>
      </div>
    </div>

  <?php else: ?>
    <div class="card-modern">
      <div class="welcome-text">
        <div class="status-badge">
            <span class="pulse"></span> Session Active
        </div>
        <h2 style="font-weight: 800; font-size: 2.5rem; color: #fff;">Hello, <?php echo remove_junk(ucfirst($current_user['name'])); ?>!</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Your workspace is ready. Here's a summary of your recent activity.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="stat-box">
          <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-cart-shopping"></i></div>
          <div>
            <div class="stat-number"><?php echo count(find_all('sales')); ?></div>
            <div class="stat-label">Orders Processed</div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="stat-box">
          <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-clock-rotate-left"></i></div>
          <div>
            <div class="stat-number"><?php echo date('d M'); ?></div>
            <div class="stat-label">Daily Log - <?php echo date('Y'); ?></div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include_once('layouts/footer.php'); ?>