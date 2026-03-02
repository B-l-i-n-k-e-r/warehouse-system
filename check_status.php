<?php
require_once('includes/load.php');

if(isset($_POST['username'])){
  $username = $db->escape($_POST['username']);
  $sql = "SELECT status FROM users WHERE username = '{$username}' LIMIT 1";
  $result = $db->query($sql);
  $user = $db->fetch_assoc($result);

  if($user){
    // trim ensures no extra spaces or newlines are sent
    echo trim($user['status']); 
  } else {
    echo "not_found";
  }
}
?>