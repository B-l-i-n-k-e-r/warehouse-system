<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)){
  $user_id = authenticate($username, $password);
  if($user_id){
    //create session with id
     $session->login($user_id);
    //Update Sign in time
     updateLastLogIn($user_id);
     $session->msg("s", "Welcome to MoonLit Warehouse");
     redirect('home.php',false);

  } else {
    $session->msg("d", "Sorry Username/Password incorrect.");
    redirect('index.php',false);
  }

} else {
   $session->msg("d", $errors);
   redirect('index.php',false);
}
// Inside your auth.php logic
$user = authenticate($username, $password);

if($user){
    if($user['status'] === '0'){
        $session->msg("d", "Your account is pending admin approval.");
        redirect('index.php', false);
    } else {
        $session->login($user['id']);
        updateLastLogIn($user['id']);
        $session->msg("s", "Welcome to MoonLit WMS.");
        redirect('home.php', false);
    }
}

?>
