<?php
  $page_title = 'Change Password';
  require_once('includes/load.php');
  page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
  // ... [Keep your existing PHP logic here] ...
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --security-blue: #2563eb;
    --slate-50: #f8fafc;
    --slate-200: #e2e8f0;
    --slate-800: #1e293b;
    /* Typography Overrides */
    --font-base: 16px;
    --font-lg: 1.25rem; /* 20px */
    --font-xl: 2rem;    /* 32px */
  }

  body { 
    background-color: var(--slate-50); 
    font-size: var(--font-base); /* Increased base font */
  }

  .security-card {
    max-width: 500px; /* Slightly wider to accommodate larger text */
    margin: 60px auto;
    background: #ffffff;
    padding: 50px; /* More breathing room */
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
  }

  .security-header h3 {
    font-weight: 800;
    color: var(--slate-800);
    margin-bottom: 12px;
    font-size: var(--font-xl); /* Big, clear heading (32px) */
    letter-spacing: -0.02em;
  }

  .security-header p {
    color: #64748b;
    font-size: 1.1rem; /* Clearer sub-text (17.6px) */
    margin-bottom: 40px;
    line-height: 1.5;
  }

  .form-group { position: relative; margin-bottom: 25px; }

  .form-label {
    font-size: 0.95rem; /* Bold, readable labels (15px) */
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.075em;
    margin-bottom: 12px;
    display: block;
  }

  .form-control {
    height: 60px; /* Taller inputs for easier touch/click */
    border-radius: 15px;
    border: 2px solid var(--slate-200); /* Thicker borders */
    padding: 12px 20px;
    font-size: 1.1rem; /* Larger typing text (17.6px) */
    font-weight: 500;
  }

  /* Adjust icon position for taller inputs */
  .password-toggle {
    position: absolute;
    right: 20px;
    top: 48px; 
    font-size: 1.2rem;
    cursor: pointer;
    color: #94a3b8;
    z-index: 10;
  }

  .btn-change {
    background: var(--security-blue);
    color: white;
    border: none;
    width: 100%;
    height: 60px; /* Matching input height */
    border-radius: 15px;
    font-weight: 700;
    font-size: 1.15rem; /* Bold button text (18px) */
    margin-top: 15px;
    letter-spacing: 0.02em;
  }

  .btn-change:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 15px 20px -5px rgba(37, 99, 235, 0.4);
  }

  .back-link {
    font-size: 1rem;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
</style>

<div class="container">
  <div class="security-card text-center">
    
    <div class="security-header">
      <div class="security-icon-circle" style="width: 70px; height: 70px; font-size: 30px; margin: 0 auto 25px;">
        <i class="glyphicon glyphicon-lock"></i>
      </div>
      <h3>Update Password</h3>
      <p>Ensure your account stays secure with a strong, updated password.</p>
    </div>

    <?php echo display_msg($msg); ?>

    <form method="post" action="change_password.php" class="text-left">
      
      <div class="form-group">
        <label class="form-label">Old Password</label>
        <input type="password" class="form-control" name="old-password" id="old-pass" placeholder="Current password" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('old-pass', this)"></i>
      </div>

      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control" name="new-password" id="new-pass" placeholder="New password" required>
        <i class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePass('new-pass', this)"></i>
      </div>

      <div class="form-group">
        <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
        <button type="submit" name="update" class="btn btn-change">
          Update Credentials
        </button>
      </div>

      <div class="text-center" style="margin-top: 30px;">
        <a href="edit_account.php" class="back-link">
          <i class="glyphicon glyphicon-arrow-left"></i> Back to Settings
        </a>
      </div>

    </form>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>