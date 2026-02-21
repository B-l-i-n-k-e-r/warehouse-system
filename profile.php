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
  body {
    background-color: #f8fafc;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .profile-container {
    padding-top: 50px;
  }

  .profile-card {
    background: #ffffff;
    border-radius: 24px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s ease;
  }

  /* Modern Gradient Header */
  .profile-header-banner {
    background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
    height: 140px;
    position: relative;
  }

  .profile-avatar-wrapper {
    margin-top: -60px;
    position: relative;
    display: inline-block;
  }

  .profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid #ffffff;
    object-fit: cover;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    background: #f1f5f9;
  }

  .profile-info {
    padding: 30px 20px;
  }

  .profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
  }

  .profile-role {
    font-size: 0.85rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
  }

  .profile-actions {
    padding: 0 30px 30px;
  }

  .btn-edit-profile {
    background: #f1f5f9;
    color: #475569;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
  }

  .btn-edit-profile:hover {
    background: #e2e8f0;
    color: #1e293b;
    transform: translateY(-2px);
  }

  .btn-edit-profile i {
    font-size: 1rem;
    color: #ef4444;
  }
</style>

<div class="container profile-container">
  <div class="row justify-content-center">
    <div class="col-md-4 col-sm-8">
      
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
          <p class="profile-role">
            <i class="glyphicon glyphicon-user" style="margin-right: 5px; font-size: 10px;"></i>
            Internal Team Member
          </p>
        </div>

        <?php if( $user_p['id'] === $user['id']):?>
          <div class="profile-actions">
            <a href="edit_account.php" class="btn-edit-profile">
              <i class="glyphicon glyphicon-edit"></i> 
              Edit Account Settings
            </a>
          </div>
        <?php endif;?>
      </div>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>