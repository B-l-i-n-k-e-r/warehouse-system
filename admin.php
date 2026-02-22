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
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --card-shadow: 0 4px 20px 0 rgba(0,0,0,0.05);
  }
  .dashboard-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: var(--card-shadow);
    padding: 20px;
    margin-bottom: 20px;
    transition: transform 0.2s;
    border: none;
  }
  .dashboard-card:hover { transform: translateY(-5px); }
  .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 10px;
  }
  .bg-soft-green { background: #e8f5e9; color: #2e7d32; }
  .bg-soft-red { background: #ffebee; color: #c62828; }
  .bg-soft-blue { background: #e3f2fd; color: #1565c0; }
  .bg-soft-yellow { background: #fffde7; color: #f9a825; }
  
  .modern-alert {
    border-radius: 8px;
    border: none;
    box-shadow: var(--card-shadow);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .table-modern thead th {
    background: #f8f9fa;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
    border-bottom: 2px solid #eee !important;
  }
  .img-avatar-small {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
  }
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
        <a href="users.php?status=0" class="btn btn-warning btn-sm">Review Now</a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if(!empty($low_stock_list)): ?>
  <div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger modern-alert">
          <span><i class="glyphicon glyphicon-warning-sign" style="margin-right:10px;"></i> <strong>Warehouse:</strong> <?php echo count($low_stock_list); ?> items are running low on stock.</span>
          <a href="low_stock_report.php" class="btn btn-danger btn-sm">Check Inventory</a>
        </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="row">
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-green center-block"><i class="glyphicon glyphicon-user"></i></div>
            <h3 class="margin-top"><?php echo $active_count; ?></h3>
            <p class="text-muted text-uppercase small">Active Users</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-red center-block"><i class="glyphicon glyphicon-list"></i></div>
            <h3 class="margin-top"><?php echo $c_categorie['total']; ?></h3>
            <p class="text-muted text-uppercase small">Categories</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-blue center-block"><i class="glyphicon glyphicon-shopping-cart"></i></div>
            <h3 class="margin-top"><?php echo $c_product['total']; ?></h3>
            <p class="text-muted text-uppercase small">Products</p>
         </div>
      </div>
      <div class="col-md-3">
         <div class="dashboard-card text-center">
            <div class="stat-icon bg-soft-yellow center-block"><i class="glyphicon glyphicon-usd"></i></div>
            <h3 class="margin-top"><?php echo $c_sale['total']; ?></h3>
            <p class="text-muted text-uppercase small">Total Sales</p>
         </div>
      </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="dashboard-card">
        <h4 class="margin-top"><i class="glyphicon glyphicon-fire" style="color:#ff5722"></i> Top Selling</h4>
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
        <h4 class="margin-top"><i class="glyphicon glyphicon-time" style="color:#2196f3"></i> Latest Sales</h4>
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
               <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>" class="text-dark">
                <?php echo remove_junk(first_character($recent_sale['name'])); ?>
               </a>
               <div class="small text-muted"><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></div>
              </td>
              <td class="text-right"><strong>Ksh <?php echo remove_junk(first_character($recent_sale['price'])); ?></strong></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-4">
      <div class="dashboard-card">
        <h4 class="margin-top"><i class="glyphicon glyphicon-plus" style="color:#4caf50"></i> Recently Added</h4>
        <hr>
        <div class="list-group list-group-flush">
          <?php foreach ($recent_products as $recent_product): ?>
            <a class="list-group-item" href="edit_product.php?id=<?php echo (int)$recent_product['id'];?>" style="border:none; padding: 10px 0;">
                <div class="pull-left">
                  <?php if($recent_product['media_id'] === '0'): ?>
                    <img class="img-avatar-small" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                    <img class="img-avatar-small" src="uploads/products/<?php echo $recent_product['image'];?>" alt="" />
                  <?php endif;?>
                </div>
                <div class="pull-left">
                  <h5 class="list-group-item-heading" style="margin:0; font-weight:600;">
                    <?php echo remove_junk(first_character($recent_product['name']));?>
                  </h5>
                  <small class="text-muted"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></small>
                </div>
                <span class="pull-right text-success" style="font-weight: bold;">
                  Ksh <?php echo (int)$recent_product['sale_price']; ?>
                </span>
                <div class="clearfix"></div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>