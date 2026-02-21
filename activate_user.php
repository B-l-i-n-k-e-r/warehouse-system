<?php
  require_once('includes/load.php');
  // Check what level user has permission to perform this action
  page_require_level(1);

  // Get the user ID from the URL
  $user_id = (int)$_GET['id'];
  
  if(!$user_id){
    $session->msg("d","Missing User ID.");
    redirect('users.php');
  }

  // Update status to 1 (Active)
  $query = "UPDATE users SET status = '1' WHERE id = '{$db->escape($user_id)}'";
  
  if($db->query($query)){
      $session->msg('s',"User account has been approved and activated!");
      redirect('users.php');
  } else {
      $session->msg('d',"Failed to activate user. Please try again.");
      redirect('users.php');
  }
?>