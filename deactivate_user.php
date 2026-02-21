<?php
  require_once('includes/load.php');
  page_require_level(1);
  $user_id = (int)$_GET['id'];
  
  if($user_id){
    // Don't let admin deactivate themselves
    if($user_id === (int)$_SESSION['user_id']){
      $session->msg('d',"You cannot deactivate your own admin account!");
      redirect('users.php');
    }

    $sql = "UPDATE users SET status='0' WHERE id='{$user_id}'";
    if($db->query($sql)){
        $session->msg('s',"User account has been deactivated.");
        redirect('users.php');
    } else {
        $session->msg('d',"Failed to deactivate user.");
        redirect('users.php');
    }
  }
?>