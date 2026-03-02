<?php
  require_once('includes/load.php');

  // Check if all required fields are present
  if(isset($_POST['full_name']) && isset($_POST['username']) && isset($_POST['email'])){
    
    // Capture and escape all inputs
    $name     = remove_junk($db->escape($_POST['full_name']));
    $username = remove_junk($db->escape($_POST['username']));
    $email    = remove_junk($db->escape($_POST['email'])); // <--- Added this
    $password = remove_junk($db->escape($_POST['password']));
    
    // Hash password & set default values
    $password   = sha1($password); // Matches your current system encryption
    $user_level = '3';             // Set as "Default User"
    $status     = '0';             // 0 means Deactivated/Pending
    $image      = 'no_image.jpg';  // Default placeholder

    // Check if username already exists
    $sql_check = "SELECT id FROM users WHERE username='{$username}' LIMIT 1";
    if($db->query($sql_check)->num_rows > 0){
      $session->msg('d','Username already taken.');
      redirect('register.php', false);
    }

    // Check if email already exists (Optional but recommended)
    $email_check = "SELECT id FROM users WHERE email='{$email}' LIMIT 1";
    if($db->query($email_check)->num_rows > 0){
      $session->msg('d','Email is already registered.');
      redirect('register.php', false);
    }

    // Updated Query to include 'email' column
    $query  = "INSERT INTO users (name, username, email, password, user_level, image, status) ";
    $query .= "VALUES ('{$name}', '{$username}', '{$email}', '{$password}', '{$user_level}', '{$image}', '{$status}')";

    if($db->query($query)){
      $session->msg('s',"Registration successful! Please wait for Admin approval.");
      redirect('index.php', false);
    } else {
      $session->msg('d','Sorry, registration failed.');
      redirect('register.php', false);
    }
  } else {
    // If someone tries to access this script directly without POST data
    $session->msg('d','Registration data missing.');
    redirect('register.php', false);
  }
?>