<?php
  $page_title = 'My Profile';
  require_once('includes/load.php');
  page_require_level(3);

  $user_id = (int)$_GET['id'];
  if(empty($user_id)):
    redirect('home.php',false);
  else:
    $user_p = find_by_id('users',$user_id);
  endif;
?>
<?php include_once('layouts/header.php'); ?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root {
    --primary: #38bdf8; 
    --accent: #22c55e;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --text-main: #f8fafc;
    --text-dim: #94a3b8;
    --border: rgba(56, 189, 248, 0.2);
  }

  body {
    background-color: var(--dark-bg);
    background-image: radial-gradient(circle at 2px 2px, rgba(56, 189, 248, 0.05) 1px, transparent 0);
    background-size: 40px 40px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
  }

  .profile-container {
    padding-top: 80px;
  }

  .profile-card {
    background: var(--card-bg);
    border-radius: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    text-align: center;
    transition: all 0.3s ease;
  }

  .profile-card:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
  }

  /* MoonLit Gradient Header */
  .profile-header-banner {
    background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    height: 140px;
    position: relative;
    opacity: 0.8;
  }

  .profile-avatar-wrapper {
    margin-top: -60px;
    position: relative;
    display: inline-block;
  }

  .profile-avatar {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    border: 6px solid var(--card-bg);
    object-fit: cover;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    background: var(--dark-bg);
  }

  .profile-info {
    padding: 30px 20px;
  }

  .profile-name {
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
  }

  .profile-role {
    font-size: 0.75rem;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-weight: 800;
    background: rgba(56, 189, 248, 0.1);
    padding: 6px 16px;
    border-radius: 100px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .profile-details {
    margin-top: 25px;
    padding: 20px;
    background: rgba(15, 23, 42, 0.3);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  .detail-label {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    color: var(--text-dim);
    letter-spacing: 1px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .detail-value {
    display: block;
    font-size: 0.95rem;
    color: #fff;
    font-weight: 600;
  }

  .profile-actions {
    padding: 0 30px 40px;
  }

  .btn-edit-profile {
    background: var(--primary);
    color: var(--dark-bg);
    border: none;
    border-radius: 12px;
    padding: 14px 28px;
    font-weight: 800;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s;
    text-decoration: none;
    text-transform: uppercase;
  }

  .btn-edit-profile:hover {
    background: #fff;
    color: var(--dark-bg);
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
    transform: translateY(-2px);
  }
</style>

<div class="container profile-container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      
      <?php echo display_msg($msg); ?>

      <div class="profile-card">
        <div class="profile-header-banner"></div>

        <div class="profile-avatar-wrapper">
          <img class="profile-avatar" 
               src="uploads/users/<?php echo $user_p['image'];?>" 
               alt="User Profile">
        </div>

        <div class="profile-info">
          <h3 class="profile-name">
            <?php echo remove_junk(ucwords($user_p['name'])); ?>
          </h3>
          <span class="profile-role">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Internal Team Member
          </span>
          
          <div class="profile-details">
            <div class="row">
                <div class="col-xs-12">
                    <span class="detail-label">Last Activity Detected</span>
                    <span class="detail-value">
                        <?php echo !empty($user_p['last_login']) ? read_date($user_p['last_login']) : "First Session"; ?>
                    </span>
                </div>
            </div>
          </div>
        </div>

        <?php if( (int)$user_p['id'] === (int)$user['id']):?>
          <div class="profile-actions">
            <a href="edit_account.php" class="btn-edit-profile">
              <i class="glyphicon glyphicon-cog"></i> 
              Account Settings
            </a>
          </div>
        <?php endif;?>
      </div>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>