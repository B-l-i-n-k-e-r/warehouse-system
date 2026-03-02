<?php
require_once('includes/load.php');

$errors = array();
$username = $db->escape($_POST['username']);
$password = $db->escape($_POST['password']);

if(empty($username) || empty($password)){
  $session->msg("d", "Username/Password fields empty.");
  redirect('index.php',false);
}

// 1. First, check if the user exists at all
$user = find_by_username($username);

if($user){
    // 2. Check Status (0 = Pending, 2 = Deactivated)
    if($user['status'] === '0'){
        $session->msg("w", "<strong>Pending:</strong> Your account is awaiting admin approval.");
        redirect('index.php',false);
    } elseif($user['status'] === '2'){
        $session->msg("d", "<strong>Access Denied:</strong> Your account has been deactivated.");
        redirect('index.php',false);
    }

    // 3. If Status is 1 (Active), attempt login
    $user_id = authenticate($username, $password);
    if($user_id){
        $session->login($user_id);
        updateLastLogIn($user_id);
        $session->msg("s", "Welcome to MoonLit Warehouse.");
        redirect('home.php', false);
    } else {
        $session->msg("d", "Sorry, Username/Password incorrect.");
        redirect('index.php',false);
    }
} else {
    $session->msg("d", "Sorry, Username/Password incorrect.");
    redirect('index.php',false);
}
?>