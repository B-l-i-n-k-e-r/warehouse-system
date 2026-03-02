<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  page_require_level(2);

  // 1. Set Timezone
  date_default_timezone_set('Africa/Nairobi');
  $current_time = date("d M Y, h:i A");

  // 2. Fetch Data with "Total Sold" calculation
  // We use a custom query here to join the sales table and sum the quantities
  $sql  = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.media_id, p.date, ";
  $sql .= " c.name AS categorie, m.file_name AS image, l.location_name, ";
  $sql .= " SUM(s.qty) AS total_sold "; // Summing the sales
  $sql .= " FROM products p ";
  $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id ";
  $sql .= " LEFT JOIN media m ON m.id = p.media_id ";
  $sql .= " LEFT JOIN locations l ON l.id = p.location_id ";
  $sql .= " LEFT JOIN sales s ON s.product_id = p.id ";
  $sql .= " GROUP BY p.id";

  $all_products = find_by_sql($sql);

  // 3. Sorting Logic
  $sort_option = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'newest';
  switch ($sort_option) {
    case 'oldest':
      usort($all_products, function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); });
      break;
    case 'stock_low':
      usort($all_products, function($a, $b) { return $a['quantity'] - $b['quantity']; });
      break;
    case 'most_sold':
      usort($all_products, function($a, $b) { return $b['total_sold'] - $a['total_sold']; });
      break;
    case 'newest':
    default:
      usort($all_products, function($a, $b) { return strtotime($b['date']) - strtotime($a['date']); });
      break;
  }

  // 4. Pagination Logic
  $limit = 10;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  $total_products = count($all_products);
  $total_pages = ceil($total_products / $limit);
  $products = array_slice($all_products, $offset, $limit);
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .inventory-card { background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: none; margin-bottom: 30px; }
  .inventory-header { padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 15px; }
  .inventory-header h2 { margin: 0; font-size: 20px; font-weight: 700; color: #1e293b; }
  
  .sync-status { font-size: 11px; color: #94a3b8; display: block; margin-top: 4px; }
  .sync-dot { height: 8px; width: 8px; background-color: #10b981; border-radius: 50%; display: inline-block; margin-right: 5px; }

  .sort-form .form-control { height: 38px; border-radius: 8px; font-size: 13px; border: 1px solid #e2e8f0; color: #64748b; width: 180px; display: inline-block; }

  .product-img { width: 45px; height: 45px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
  .table-inventory { margin: 0; }
  .table-inventory thead th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; padding: 15px 12px !important; border: none !important; }
  .table-inventory tbody td { padding: 15px 12px !important; vertical-align: middle !important; border-top: 1px solid #f1f5f9 !important; }
  
  .stock-count { font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 12px; display: inline-block; min-width: 45px; text-align: center;}
  .stock-low { background: #fee2e2; color: #ef4444; }
  .stock-ok { background: #dcfce7; color: #166534; }
  .stock-out-badge { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

  .pagination-container { padding: 20px; display: flex; justify-content: center; align-items: center; border-top: 1px solid #f1f5f9; }
  .btn-pagination { background: #fff; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; color: #6366f1; font-weight: 600; text-decoration: none; margin: 0 5px; }
  .btn-pagination.disabled { color: #cbd5e1; cursor: not-allowed; pointer-events: none; }
  
  .back-link { display: block; text-align: center; margin-top: 10px; margin-bottom: 30px; color: #94a3b8; text-decoration: none; font-weight: 500; }
  .back-link:hover { color: #6366f1; text-decoration: none; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="inventory-card">
        <div class="inventory-header">
          <div>
            <h2><i class="glyphicon glyphicon-shopping-cart" style="color: #6366f1; margin-right: 12px;"></i> Master Inventory</h2>
            <span class="sync-status"><span class="sync-dot"></span> Last updated: <?php echo $current_time; ?></span>
          </div>

          <div style="display: flex; gap: 10px; align-items: center;">
            <form class="sort-form" method="get" action="product.php" id="sortForm">
              <select name="sort_by" class="form-control" onchange="document.getElementById('sortForm').submit();">
                <option value="newest" <?php if($sort_option == 'newest') echo 'selected'; ?>>Latest Added</option>
                <option value="oldest" <?php if($sort_option == 'oldest') echo 'selected'; ?>>Oldest Added</option>
                <option value="most_sold" <?php if($sort_option == 'most_sold') echo 'selected'; ?>>Most Sold Items</option>
                <option value="stock_low" <?php if($sort_option == 'stock_low') echo 'selected'; ?>>Low Stock First</option>
              </select>
            </form>

            <a href="add_product.php" class="btn btn-primary" style="background:#6366f1; border:none; border-radius:8px; padding: 9px 18px; font-weight:600;">
              <i class="glyphicon glyphicon-plus"></i> Add Product
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-inventory table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th style="width: 60px;">Photo</th>
                  <th>Product Details</th>
                  <th class="text-center">Location</th>
                  <th class="text-center">Stock In</th>
                  <th class="text-center">Stock Out</th>
                  <th class="text-center">Pricing</th>
                  <th class="text-center">Date Added</th>
                  <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $index => $product):?>
                <tr>
                  <td class="text-center text-muted"><?php echo $offset + ($index + 1);?></td>
                  <td>
                    <img class="product-img" src="uploads/products/<?php echo ($product['media_id'] === '0') ? 'no_image.jpg' : $product['image']; ?>">
                  </td>
                  <td>
                    <div style="font-weight: 700; color: #334155;"><?php echo remove_junk($product['name']); ?></div>
                    <small class="text-muted">SKU: #<?php echo (int)$product['id']; ?></small>
                  </td>
                  <td class="text-center">
                    <span style="color: #6366f1; font-weight: 600; font-size: 11px;">
                      <i class="glyphicon glyphicon-map-marker"></i> <?php echo remove_junk($product['location_name'] ?: 'N/A'); ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="stock-count <?php echo ($product['quantity'] <= 5) ? 'stock-low' : 'stock-ok'; ?>">
                      <?php echo (int)$product['quantity']; ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="stock-count stock-out-badge">
                      <?php echo (int)$product['total_sold'] ?: '0'; ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div style="color: #0f172a; font-size: 13px; font-weight: 700;">Ksh <?php echo number_format($product['sale_price'], 2); ?></div>
                  </td>
                  <td class="text-center text-muted" style="font-size: 11px;">
                    <?php echo read_date($product['date']); ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-xs btn-warning" style="border-radius:4px; margin-right:2px;"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-xs btn-danger" style="border-radius:4px;"><i class="glyphicon glyphicon-trash"></i></a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-container">
            <a href="?page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_option; ?>" class="btn-pagination <?php if($page <= 1) echo 'disabled'; ?>">
              <i class="glyphicon glyphicon-chevron-left"></i> Previous
            </a>
            <span class="page-info" style="margin: 0 15px; color: #64748b;">Page <b><?php echo $page; ?></b> of <b><?php echo $total_pages; ?></b></span>
            <a href="?page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_option; ?>" class="btn-pagination <?php if($page >= $total_pages) echo 'disabled'; ?>">
              Next <i class="glyphicon glyphicon-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
      <a href="home.php" class="back-link"><i class="glyphicon glyphicon-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>