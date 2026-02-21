<?php
  require_once('includes/load.php');

  if(isset($_POST['full_name']) && isset($_POST['username'])){
    $name       = remove_junk($db->escape($_POST['full_name']));
    $username   = remove_junk($db->escape($_POST['username']));
    $password   = remove_junk($db->escape($_POST['password']));
    
    // Hash password & set default values
    $password   = sha1($password); // Use the same encryption as your current system
    $user_level = '3';             // Set as "Default User"
    $status     = '0';             // 0 means Deactivated/Pending
    $image      = 'no_image.jpg';  // Default placeholder

    // Check if username already exists
    $sql_check = "SELECT id FROM users WHERE username='{$username}' LIMIT 1";
    if($db->query($sql_check)->num_rows > 0){
      $session->msg('d','Username already taken.');
      redirect('register.php', false);
    }

    $query  = "INSERT INTO users (name, username, password, user_level, image, status) ";
    $query .= "VALUES ('{$name}', '{$username}', '{$password}', '{$user_level}', '{$image}', '{$status}')";

    if($db->query($query)){
      $session->msg('s',"Registration successful! Please wait for Admin approval.");
      redirect('index.php', false);
    } else {
      $session->msg('d','Sorry, registration failed.');
      redirect('register.php', false);
    }
  }
?>