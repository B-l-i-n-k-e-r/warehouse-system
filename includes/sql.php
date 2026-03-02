<?php
// Circular dependency removed. This file is called by load.php.

/*--------------------------------------------------------------*/
/* Basic CRUD and Query Functions
/*--------------------------------------------------------------*/

function find_all($table) {
   global $db;
   if(tableExists($table)) {
       return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
   return [];
}

function find_by_sql($sql) {
   global $db;
   $result = $db->query($sql);
   if($result) {
       return $db->while_loop($result);
   }
   return [];
}

function find_by_id($table, $id) {
   global $db;
   $id = (int)$id;
   if(tableExists($table)) {
       $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
       if($result = $db->fetch_assoc($sql))
           return $result;
   }
   return null;
}

function delete_by_id($table, $id) {
   global $db;
   if(tableExists($table)) {
       $sql = "DELETE FROM ".$db->escape($table)." WHERE id=".$db->escape((int)$id)." LIMIT 1";
       $db->query($sql);
       return ($db->affected_rows() === 1);
   }
   return false;
}

function count_by_id($table) {
   global $db;
   if(tableExists($table)) {
       $sql = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
       $result = $db->query($sql);
       return $db->fetch_assoc($result);
   }
   return ['total' => 0];
}

function tableExists($table) {
   global $db;
   $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
   return ($table_exit && $db->num_rows($table_exit) > 0);
}

/*--------------------------------------------------------------*/
/* User & Session Functions
/*--------------------------------------------------------------*/

function current_user() {
   static $current_user;
   if(!$current_user && isset($_SESSION['user_id'])) {
       $current_user = find_by_id('users', intval($_SESSION['user_id']));
   }
   return $current_user ?? null;
}

function page_require_level($require_level) {
   global $session;
   $current_user = current_user();
   
   if(!$session->isUserLoggedIn(true)) {
       $session->msg('d','Please login...');
       redirect('index.php', false);
   } 
   
   if(($current_user['user_level'] ?? 99) <= (int)$require_level) {
       return true;
   } else {
       $session->msg("d", "Sorry! You don't have permission to view the page.");
       redirect('home.php', false);
   }
}

/*--------------------------------------------------------------*/
/* Product Functions (Updated for Locations)
/*--------------------------------------------------------------*/

function join_product_table(){
  global $db;
  $sql  =" SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.media_id, p.date, ";
  $sql  .=" c.name AS categorie, m.file_name AS image, l.location_name"; //
  $sql  .=" FROM products p";
  $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
  $sql  .=" LEFT JOIN locations l ON l.id = p.location_id"; // Joined locations table
  $sql  .=" ORDER BY p.id ASC";
  return find_by_sql($sql);
}

function find_recent_product_added($limit){
   global $db;
   $sql = "SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,m.file_name AS image
           FROM products p
           LEFT JOIN categories c ON c.id=p.categorie_id
           LEFT JOIN media m ON m.id=p.media_id
           ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
}

function update_product_qty($qty,$p_id){
   global $db;
   $sql = "UPDATE products SET quantity=quantity-{$db->escape((int)$qty)} WHERE id='{$db->escape((int)$p_id)}'";
   $db->query($sql);
   return ($db->affected_rows() === 1);
}

function find_low_stock_products($limit){
  global $db;
  // Joins the locations table to get location_name and zone for alerts
  $sql  = "SELECT p.id, p.name, p.quantity, l.location_name, l.zone ";
  $sql .= "FROM products p ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "WHERE p.quantity < 10 "; // Threshold set to 10
  $sql .= "ORDER BY p.quantity ASC LIMIT " . (int)$limit;
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Sales Functions
/*--------------------------------------------------------------*/

function find_all_sale() {
  return find_by_sql("SELECT s.id, s.qty, s.price, s.date, p.name, l.location_name
                      FROM sales s
                      LEFT JOIN products p ON s.product_id = p.id
                      LEFT JOIN locations l ON p.location_id = l.id
                      ORDER BY s.date DESC"); //
}

function find_higest_saleing_product($limit){
   global $db;
   $sql = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty
           FROM sales s
           LEFT JOIN products p ON p.id = s.product_id
           GROUP BY s.product_id
           ORDER BY SUM(s.qty) DESC
           LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
}

function find_recent_sale_added($limit){
   global $db;
   $sql = "SELECT s.id,s.qty,s.price,s.date,p.name
           FROM sales s
           LEFT JOIN products p ON s.product_id = p.id
           ORDER BY s.date DESC
           LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
}

function find_sale_by_dates($start_date, $end_date){
   global $db;
   $start_date  = date("Y-m-d", strtotime($start_date));
   $end_date    = date("Y-m-d", strtotime($end_date));
   $sql  = "SELECT s.date, p.name, p.sale_price, p.buy_price,";
   $sql .= " COUNT(s.product_id) AS total_records,";
   $sql .= " SUM(s.qty) AS total_sales,";
   $sql .= " SUM(p.sale_price * s.qty) AS total_saleing_price,";
   $sql .= " SUM(p.buy_price * s.qty) AS total_buying_price";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
   $sql .= " GROUP BY DATE(s.date),p.name";
   $sql .= " ORDER BY DATE(s.date) DESC";
   return find_by_sql($sql);
}

function dailySales($year, $month){
   $sql  = "SELECT s.qty, DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name,";
   $sql .= " SUM(p.sale_price * s.qty) AS total_saleing_price";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m') = '{$year}-{$month}'";
   $sql .= " GROUP BY DATE_FORMAT(s.date, '%e'), s.product_id";
   return find_by_sql($sql);
}

function monthlySales($year){
   $sql  = "SELECT s.qty, DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name,";
   $sql .= " SUM(p.sale_price * s.qty) AS total_saleing_price";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " WHERE DATE_FORMAT(s.date, '%Y') = '{$year}'";
   $sql .= " GROUP BY DATE_FORMAT(s.date, '%c'), s.product_id";
   $sql .= " ORDER BY DATE_FORMAT(s.date, '%c') ASC";
   return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Warehouse Location Functions
/*--------------------------------------------------------------*/

function find_all_locations(){
  return find_by_sql("SELECT * FROM locations WHERE status='1' ORDER BY location_name ASC"); //
}

function find_product_location($product_id){
  global $db;
  $sql  = "SELECT l.location_name, l.zone FROM locations l ";
  $sql .= "JOIN products p ON p.location_id = l.id ";
  $sql .= "WHERE p.id = '{$db->escape($product_id)}' LIMIT 1"; //
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Authenticate user (Fixed for Status)
/*--------------------------------------------------------------*/
function authenticate($username='', $password='') {
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' AND status='1' LIMIT 1", $username); //
  
  $result = $db->query($sql);
  if($result && $db->num_rows($result)){
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if($password_request === $user['password'] ){
      return $user['id'];
    }
  }
  return false;
}

/*--------------------------------------------------------------*/
/* Find all users
/*--------------------------------------------------------------*/
function find_all_user(){
  global $db;
  $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
  $sql .="g.group_name ";
  $sql .="FROM users u ";
  $sql .="LEFT JOIN user_groups g ON g.group_level=u.user_level ";
  $sql .="ORDER BY u.name ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Update last login
/*--------------------------------------------------------------*/
function updateLastLogIn($user_id) {
  global $db;
  $date = make_date();
  $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$db->escape((int)$user_id)}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1);
}
/*--------------------------------------------------------------*/
/* Function for Finding Product by title
/*--------------------------------------------------------------*/
function find_product_by_title($product_name){
  global $db;
  $p_name = remove_junk($db->escape($product_name));
  $sql = "SELECT name FROM products WHERE name LIKE '%$p_name%' LIMIT 5";
  $result = find_by_sql($sql);
  return $result;
}

/*--------------------------------------------------------------*/
/* Function for Finding all product info by title (Updated for Locations)
/*--------------------------------------------------------------*/
function find_all_product_info_by_title($title){
  global $db;
  $sql  = "SELECT p.*, l.location_name "; // Fetching location name too
  $sql .= "FROM products p ";
  $sql .= "LEFT JOIN locations l ON l.id = p.location_id "; // Joining locations
  $sql .= "WHERE p.name ='{$db->escape($title)}' LIMIT 1";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Find user by username
/*--------------------------------------------------------------*/
function find_by_username($username) {
  global $db;
  $username = $db->escape($username);
  $sql = "SELECT * FROM users WHERE username = '{$username}' LIMIT 1";
  $result = find_by_sql($sql);
  return !empty($result) ? array_shift($result) : false;
}
?>