<?php
require_once('includes/load.php');

if(isset($_POST['request_submit'])){
  $email = $db->escape($_POST['email']);
  
  // Check if email exists
  $user = find_by_sql("SELECT id FROM users WHERE email='{$email}' LIMIT 1");
  
  if($user){
    $user_id = $user[0]['id'];
    // Generate random secure token
    $token = bin2hex(random_bytes(32));
    // Set expiry for 1 hour from now
    $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
    
    $query  = "UPDATE users SET ";
    $query .= "reset_token='{$token}', reset_token_expires='{$expires}' ";
    $query .= "WHERE id='{$user_id}'";
    
    if($db->query($query)){
      $session->msg('s', "Reset request received. Please contact your System Administrator to receive your secure reset link.");
      redirect('forgot-password.php', false);
    } else {
      $session->msg('d', "Failed to process request.");
      redirect('forgot-password.php', false);
    }
  } else {
    $session->msg('d', "Email address not found in our records.");
    redirect('forgot-password.php', false);
  }
}
?>