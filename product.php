<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  page_require_level(2);

  // 1. Set Timezone
  date_default_timezone_set('Africa/Nairobi');
  $current_time = date("d M Y, h:i A");

  // 2. Fetch Data with "Total Sold" calculation
  $sql  = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.media_id, p.date, ";
  $sql .= " c.name AS categorie, m.file_name AS image, l.location_name, ";
  $sql .= " SUM(s.qty) AS total_sold "; 
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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-purple: #818cf8;
    --neon-green: #10b981;
    --neon-red: #f43f5e;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .inventory-card { 
    background: var(--glass-bg); 
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    border: 1px solid var(--glass-border); 
    margin-bottom: 30px; 
    overflow: hidden;
  }

  .inventory-header { 
    padding: 25px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    border-bottom: 1px solid var(--glass-border); 
    flex-wrap: wrap; 
    gap: 15px; 
  }

  .inventory-header h2 { margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main); }
  
  .sync-status { font-size: 11px; color: var(--text-muted); display: block; margin-top: 6px; font-weight: 500; }
  .sync-dot { 
    height: 8px; width: 8px; background-color: var(--neon-green); 
    border-radius: 50%; display: inline-block; margin-right: 6px; 
    box-shadow: 0 0 8px var(--neon-green);
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
  }

  .sort-form .form-control { 
    height: 40px; 
    background: rgba(15, 23, 42, 0.6);
    border-radius: 8px; 
    font-size: 13px; 
    border: 1px solid var(--glass-border); 
    color: var(--text-main); 
    width: 180px; 
    display: inline-block; 
  }

  .product-img { 
    width: 45px; height: 45px; object-fit: cover; 
    border-radius: 10px; border: 1px solid var(--glass-border); 
    transition: 0.3s;
  }
  .product-img:hover { transform: scale(1.1); border-color: var(--neon-blue); }

  /* Fit Content Table Rules */
  .table-inventory { width: auto !important; min-width: 100%; margin: 0; background: transparent !important; }
  .table-inventory thead th { 
    background: rgba(255, 255, 255, 0.03) !important; 
    color: var(--neon-blue) !important; 
    font-size: 10px; text-transform: uppercase; letter-spacing: 1px; 
    padding: 15px 15px !important; border: none !important;
    white-space: nowrap;
    width: 1%;
  }

  /* Allow product name column to grow */
  .table-inventory thead th:nth-child(3) { width: auto; }

  .table-inventory tbody td { 
    padding: 15px 15px !important; vertical-align: middle !important; 
    border-top: 1px solid var(--glass-border) !important; 
    white-space: nowrap;
    color: var(--text-main);
  }

  .table-inventory tbody tr:hover { background: rgba(255, 255, 255, 0.02); }
  
  .stock-count { 
    font-weight: 800; padding: 5px 12px; border-radius: 50px; 
    font-size: 11px; display: inline-block; min-width: 50px; text-align: center;
    text-transform: uppercase;
  }
  .stock-low { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); border: 1px solid rgba(244, 63, 94, 0.3); }
  .stock-ok { background: rgba(16, 185, 129, 0.15); color: var(--neon-green); border: 1px solid rgba(16, 185, 129, 0.3); }
  .stock-out-badge { background: rgba(148, 163, 184, 0.1); color: var(--text-muted); border: 1px solid var(--glass-border); }

  .loc-badge { color: var(--neon-purple); font-weight: 700; font-size: 11px; }

  .pagination-container { 
    padding: 25px; display: flex; justify-content: space-between; 
    align-items: center; border-top: 1px solid var(--glass-border); 
  }
  .btn-pagination { 
    background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); 
    padding: 8px 18px; border-radius: 8px; color: var(--text-main); 
    font-weight: 700; font-size: 11px; text-transform: uppercase; text-decoration: none; 
    transition: 0.3s;
  }
  .btn-pagination:hover:not(.disabled) { 
    background: var(--neon-purple); border-color: var(--neon-purple); color: #fff; 
    box-shadow: 0 0 15px rgba(129, 140, 248, 0.4);
  }
  .btn-pagination.disabled { opacity: 0.3; cursor: not-allowed; }
  
  .back-link { display: block; text-align: center; margin-top: 20px; color: var(--text-muted); font-weight: 600; font-size: 13px; transition: 0.3s; }
  .back-link:hover { color: var(--neon-blue); text-decoration: none; transform: translateX(-3px); }

  .btn-action {
    width: 32px; height: 32px; line-height: 32px; text-align: center; border-radius: 8px; display: inline-block; transition: 0.3s;
  }
  .btn-edit { background: rgba(56, 189, 248, 0.15); color: var(--neon-blue); }
  .btn-delete { background: rgba(244, 63, 94, 0.15); color: var(--neon-red); }
  .btn-action:hover { transform: scale(1.15); filter: brightness(1.2); }
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
            <h2><i class="glyphicon glyphicon-th-large" style="color: var(--neon-blue); margin-right: 12px;"></i> Master Inventory</h2>
            <span class="sync-status"><span class="sync-dot"></span> System Live: <?php echo $current_time; ?></span>
          </div>

          <div style="display: flex; gap: 12px; align-items: center;">
            <form class="sort-form" method="get" action="product.php" id="sortForm">
              <select name="sort_by" class="form-control" onchange="document.getElementById('sortForm').submit();">
                <option value="newest" <?php if($sort_option == 'newest') echo 'selected'; ?>>Latest</option>
                <option value="oldest" <?php if($sort_option == 'oldest') echo 'selected'; ?>>Oldest</option>
                <option value="most_sold" <?php if($sort_option == 'most_sold') echo 'selected'; ?>>Most Sold</option>
                <option value="stock_low" <?php if($sort_option == 'stock_low') echo 'selected'; ?>>Low Stock</option>
              </select>
            </form>

            <a href="add_product.php" class="btn" style="background: linear-gradient(135deg, var(--neon-blue), #0ea5e9); color:#fff; border-radius:8px; padding: 10px 20px; font-weight:800; font-size: 11px; text-transform: uppercase; border:none; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2);">
              <i class="glyphicon glyphicon-plus"></i> New Product
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-inventory">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Image</th>
                  <th>Product Details</th>
                  <th class="text-center">Location</th>
                  <th class="text-center">Stock In</th>
                  <th class="text-center">Stock Out</th>
                  <th class="text-center">Pricing</th>
                  <th class="text-center">Timestamp</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $index => $product):?>
                <tr>
                  <td class="text-center" style="color: var(--text-muted); font-family: monospace;"><?php echo $offset + ($index + 1);?></td>
                  <td>
                    <img class="product-img" src="uploads/products/<?php echo ($product['media_id'] === '0') ? 'no_image.jpg' : $product['image']; ?>">
                  </td>
                  <td>
                    <div style="font-weight: 800; font-size: 14px; letter-spacing: -0.2px;"><?php echo remove_junk($product['name']); ?></div>
                    <div style="font-size: 10px; color: var(--neon-blue); font-weight: 700; margin-top: 2px;">REF-<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></div>
                  </td>
                  <td class="text-center">
                    <span class="loc-badge">
                      <i class="glyphicon glyphicon-map-marker" style="font-size: 10px; opacity: 0.7;"></i> <?php echo remove_junk($product['location_name'] ?: 'UNASSIGNED'); ?>
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
                    <div style="color: #fff; font-size: 14px; font-weight: 800;">Ksh <?php echo number_format($product['sale_price'], 0); ?></div>
                  </td>
                  <td class="text-center" style="color: var(--text-muted); font-size: 11px; font-weight: 500;">
                    <?php echo date("d/m/y", strtotime($product['date'])); ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn-action btn-edit" title="Edit Item"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn-action btn-delete" style="margin-left: 6px;" title="Delete Item"><i class="glyphicon glyphicon-trash"></i></a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-container">
            <div class="small" style="color: var(--text-muted);">
              Sector <span style="color: var(--text-main); font-weight: 800;"><?php echo $page; ?></span> of <span style="color: var(--neon-blue); font-weight: 800;"><?php echo $total_pages; ?></span>
            </div>
            <div style="display: flex;">
              <a href="?page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_option; ?>" class="btn-pagination <?php if($page <= 1) echo 'disabled'; ?>">
                <i class="glyphicon glyphicon-chevron-left"></i> Previous
              </a>
              <a href="?page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_option; ?>" class="btn-pagination <?php if($page >= $total_pages) echo 'disabled'; ?>" style="margin-left: 10px;">
                Next <i class="glyphicon glyphicon-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <a href="javascript:history.back()" class="back-link">
  <i class="glyphicon glyphicon-arrow-left"></i> Go Back
</a>    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>