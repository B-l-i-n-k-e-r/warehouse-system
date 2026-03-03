<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // Set Timezone to East Africa (Kenya)
  date_default_timezone_set('Africa/Nairobi');

  $page_title = 'Home Page';
  require_once('includes/load.php');
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);

  // 1. Count users waiting for approval
  $count_sql = "SELECT COUNT(id) as total FROM users WHERE status = '0'";
  $res = $db->query($count_sql);
  $data = $db->fetch_assoc($res);
  $pending_users = $data['total'];

  // 2. Fetch Dashboard Stats
  $c_categorie     = count_by_id('categories');
  $c_product       = count_by_id('products');
  $c_sale          = count_by_id('sales');
  $c_active_user   = find_by_sql("SELECT COUNT(id) as total FROM users WHERE status='1'");
  $active_count    = $c_active_user[0]['total'];

  // 3. Fetch Activity Data
  $products_sold   = find_higest_saleing_product('10');
  $recent_products = find_recent_product_added('5');
  $recent_sales    = find_recent_sale_added('5');
  $low_stock_list  = find_low_stock_products(5); 
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --bg-deep: #0f172a;
    --glass-card: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-green: #10b981;
    --neon-red: #f43f5e;
    --neon-yellow: #fbbf24;
    --text-primary: #f8fafc;
    --text-muted: #94a3b8;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-primary);
    min-height: 100vh;
  }

  /* --- Dashboard Cards --- */
  .dashboard-card {
    background: var(--glass-card);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
    border-color: var(--neon-blue);
    box-shadow: 0 15px 35px rgba(56, 189, 248, 0.1);
  }

  /* --- Glowing Stat Icons --- */
  .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 10px;
  }

  .bg-soft-green { background: rgba(16, 185, 129, 0.15); color: var(--neon-green); box-shadow: 0 0 15px rgba(16, 185, 129, 0.2); }
  .bg-soft-red { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); box-shadow: 0 0 15px rgba(244, 63, 94, 0.2); }
  .bg-soft-blue { background: rgba(56, 189, 248, 0.15); color: var(--neon-blue); box-shadow: 0 0 15px rgba(56, 189, 248, 0.2); }
  .bg-soft-yellow { background: rgba(251, 191, 36, 0.15); color: var(--neon-yellow); box-shadow: 0 0 15px rgba(251, 191, 36, 0.2); }

  /* --- Notifications --- */
  .modern-alert {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(8px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--text-primary);
  }
  .alert-warning { border-left: 4px solid var(--neon-yellow); }
  .alert-danger { border-left: 4px solid var(--neon-red); }

  /* --- Table Fit-Content & Transparency --- */
  .table-modern {
    width: auto !important;
    min-width: 100%;
    margin-bottom: 0;
    background-color: transparent !important;
  }

  .table-modern thead th {
    background: rgba(255, 255, 255, 0.05) !important;
    color: var(--neon-blue) !important;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 1px;
    border: none !important;
    padding: 12px !important;
  }

  .table-modern tbody tr {
    background: transparent !important;
    transition: background 0.2s;
  }

  .table-modern tbody tr td {
    border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
    padding: 10px !important;
    vertical-align: middle !important;
    color: var(--text-primary) !important;
    background: transparent !important;
  }

  .table-modern tbody tr:hover {
    background: rgba(255, 255, 255, 0.03) !important;
  }

  /* --- Typography & Links --- */
  h3, h4 { color: var(--text-primary); font-weight: 700; margin-top: 5px; }
  .text-muted { color: var(--text-muted) !important; }
  
  .table-modern a, .list-group-item a { 
    color: var(--text-primary) !important; 
    text-decoration: none; 
    font-weight: 600;
  }
  
  .table-modern a:hover, .list-group-item a:hover { 
    color: var(--neon-blue) !important; 
  }

  hr { border-top: 1px solid var(--glass-border); opacity: 0.3; }

  .label-success { background: var(--neon-green); color: #fff; border: none; }
  .text-success { color: var(--neon-green) !important; font-weight: bold; }

  .img-avatar-small {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: 1px solid var(--glass-border);
    object-fit: cover;
  }

  .list-group-item {
    background: transparent !important;
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    padding: 12px 0;
    color: var(--text-primary) !important;
  }
  .list-group-item:last-child { border-bottom: none !important; }
</style>

<div class="container-fluid">
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>

  <?php if($pending_users > 0): ?>
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-warning modern-alert" role="alert">
        <span><i class="glyphicon glyphicon-user" style="margin-right:10px;"></i> <strong>Attention:</strong> <?php echo $pending_users; ?> new users are waiting for activation.</span>
        <a href="users.php?status=0" class="btn btn-warning btn-sm" style="border-radius:20px; font-weight:bold;">Review Now</a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if(!empty($low_stock_list)): ?>
  <div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger modern-alert">
          <span><i class="glyphicon glyphicon-warning-sign" style="margin-right:10px;"></i> <strong>Warehouse:</strong> <?php echo count($low_stock_list); ?> items are running low on stock.</span>
          <a href="low_stock_report.php" class="btn btn-danger btn-sm" style="border-radius:20px; font-weight:bold;">Check Inventory</a>
        </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="row">
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-green"><i class="glyphicon glyphicon-user"></i></div>
            <h3><?php echo $active_count; ?></h3>
            <p class="text-muted text-uppercase small">Active Users</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-red"><i class="glyphicon glyphicon-list"></i></div>
            <h3><?php echo $c_categorie['total']; ?></h3>
            <p class="text-muted text-uppercase small">Categories</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-blue"><i class="glyphicon glyphicon-shopping-cart"></i></div>
            <h3><?php echo $c_product['total']; ?></h3>
            <p class="text-muted text-uppercase small">Products</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-yellow"><i class="glyphicon glyphicon-usd"></i></div>
            <h3><?php echo $c_sale['total']; ?></h3>
            <p class="text-muted text-uppercase small">Total Sales</p>
         </div>
      </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="dashboard-card">
        <h4><i class="glyphicon glyphicon-fire" style="color:var(--neon-red)"></i> Top Selling</h4>
        <hr>
        <table class="table table-modern">
          <thead>
            <tr>
              <th>Title</th>
              <th class="text-right">Sold</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td class="text-right"><span class="label label-success"><?php echo (int)$product_sold['totalSold']; ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-4">
      <div class="dashboard-card">
        <h4><i class="glyphicon glyphicon-time" style="color:var(--neon-blue)"></i> Latest Sales</h4>
        <hr>
        <table class="table table-modern">
          <thead>
            <tr>
              <th>Product</th>
              <th class="text-right">Price</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_sales as $recent_sale): ?>
            <tr>
              <td>
               <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>">
                <?php echo remove_junk(first_character($recent_sale['name'])); ?>
               </a>
               <div class="small text-muted"><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></div>
              </td>
              <td class="text-right"><strong style="color:var(--neon-green)">Ksh <?php echo remove_junk(first_character($recent_sale['price'])); ?></strong></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-4">
      <div class="dashboard-card">
        <h4><i class="glyphicon glyphicon-plus" style="color:var(--neon-green)"></i> Recently Added</h4>
        <hr>
        <div class="list-group list-group-flush">
          <?php foreach ($recent_products as $recent_product): ?>
            <div class="list-group-item">
                <div class="pull-left">
                  <?php if($recent_product['media_id'] === '0'): ?>
                    <img class="img-avatar-small" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                    <img class="img-avatar-small" src="uploads/products/<?php echo $recent_product['image'];?>" alt="" />
                  <?php endif;?>
                </div>
                <div class="pull-left" style="margin-left: 10px;">
                  <a href="edit_product.php?id=<?php echo (int)$recent_product['id'];?>">
                    <h5 style="margin:0;"><?php echo remove_junk(first_character($recent_product['name']));?></h5>
                  </a>
                  <small class="text-muted"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></small>
                </div>
                <span class="pull-right text-success">
                  Ksh <?php echo (int)$recent_product['sale_price']; ?>
                </span>
                <div class="clearfix"></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>