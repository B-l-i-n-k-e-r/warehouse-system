<?php
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(1);
?>
<?php
  // Find the location by ID from the GET request
  $location = find_by_id('locations', (int)$_GET['id']);
  
  // If location doesn't exist in the database
  if(!$location){
    $session->msg("d", "Missing location ID.");
    redirect('locations.php');
  }
?>
<?php
  // Perform the delete query
  $delete_id = delete_by_id('locations', (int)$location['id']);
  
  if($delete_id){
      $session->msg("s", "Warehouse bin deleted successfully.");
      redirect('locations.php');
  } else {
      $session->msg("d", "Location deletion failed or record not found.");
      redirect('locations.php');
  }
?>