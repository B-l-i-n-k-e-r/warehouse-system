<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .inventory-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 30px;
  }
  .inventory-header {
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
  }
  .inventory-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
  }
  .product-img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  .table-inventory { margin: 0; }
  .table-inventory thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 15px 12px !important;
    border: none !important;
  }
  .table-inventory tbody td {
    padding: 15px 12px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .stock-count {
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
  }
  .stock-low { background: #fee2e2; color: #ef4444; }
  .stock-ok { background: #dcfce7; color: #166534; }
  
  .price-tag { font-family: 'Inter', sans-serif; font-weight: 600; }
  .price-buy { color: #94a3b8; font-size: 12px; }
  .price-sale { color: #0f172a; font-size: 14px; }
  
  .btn-action-group .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    line-height: 32px;
    border-radius: 8px;
    margin: 0 2px;
    border: none;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="inventory-card">
        <div class="inventory-header">
          <h2><i class="glyphicon glyphicon-shopping-cart" style="color: #6366f1; margin-right: 12px;"></i> Master Inventory</h2>
          <a href="add_product.php" class="btn btn-primary" style="background:#6366f1; border:none; border-radius:8px; padding: 10px 20px; font-weight:600;">
            <i class="glyphicon glyphicon-plus"></i> Add New Product
          </a>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-inventory table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th style="width: 60px;">Photo</th>
                  <th>Product Details</th>
                  <th class="text-center">Category</th>
                  <th class="text-center">Location</th>
                  <th class="text-center">In Stock</th>
                  <th class="text-center">Pricing (Buy/Sell)</th>
                  <th class="text-center">Added Date</th>
                  <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $product):?>
                <tr>
                  <td class="text-center text-muted"><?php echo count_id();?></td>
                  <td>
                    <?php if($product['media_id'] === '0'): ?>
                      <img class="product-img" src="uploads/products/no_image.jpg" alt="">
                    <?php else: ?>
                      <img class="product-img" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="font-weight: 700; color: #334155;"><?php echo remove_junk($product['name']); ?></div>
                    <small class="text-muted">SKU: PROD-<?php echo (int)$product['id']; ?></small>
                  </td>
                  <td class="text-center">
                    <span class="label label-default" style="background:#f1f5f9; color:#475569;"><?php echo remove_junk($product['categorie']); ?></span>
                  </td>
                  <td class="text-center">
                    <?php if(!empty($product['location_name'])): ?>
                      <span style="color: #6366f1; font-weight: 600; font-size: 12px;">
                        <i class="glyphicon glyphicon-map-marker"></i> <?php echo remove_junk($product['location_name']); ?>
                      </span>
                    <?php else: ?>
                      <span class="text-muted" style="font-size: 11px;">Not Set</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php 
                      $q = (int)$product['quantity'];
                      $stock_class = ($q <= 5) ? 'stock-low' : 'stock-ok';
                    ?>
                    <span class="stock-count <?php echo $stock_class; ?>">
                      <?php echo $q; ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="price-buy">$<?php echo remove_junk($product['buy_price']); ?></div>
                    <div class="price-sale">$<?php echo remove_junk($product['sale_price']); ?></div>
                  </td>
                  <td class="text-center text-muted" style="font-size: 12px;">
                    <?php echo read_date($product['date']); ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-action-group">
                      <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info" title="Edit" data-toggle="tooltip">
                        <i class="glyphicon glyphicon-pencil"></i>
                      </a>
                      <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger" title="Delete" data-toggle="tooltip">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>