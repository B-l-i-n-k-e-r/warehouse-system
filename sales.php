<?php
  $page_title = 'All sale';
  require_once('includes/load.php');
  page_require_level(3);

  // 1. Set Timezone to Africa/Nairobi
  date_default_timezone_set('Africa/Nairobi');

  // Logic to handle "No sales available" error if coming back from a failed print request
  if(isset($_GET['error']) && $_GET['error'] == 'empty'){
    $session->msg('d', "No sale available for the selected location.");
  }

  // 2. Pagination Logic
  $limit = 10; 
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  $all_sales = find_all_sale();
  $total_sales = count($all_sales);
  $total_pages = ceil($total_sales / $limit);

  // Slice results for pagination
  $sales = array_slice($all_sales, $offset, $limit);
  $locations = find_all_locations(); 
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --dark-surface: #0f172a;
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .sales-card { 
    background: var(--glass-bg); 
    backdrop-filter: blur(15px);
    border-radius: 20px; 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
    border: 1px solid var(--glass-border); 
    margin-bottom: 30px;
    overflow: hidden;
  }

  .sales-header { 
    padding: 25px; 
    border-bottom: 1px solid var(--glass-border); 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    background: rgba(255,255,255,0.03);
  }

  .sales-header h2 { 
    margin: 0; 
    font-size: 18px; 
    font-weight: 800; 
    color: var(--text-main); 
    letter-spacing: -0.5px;
  }
  
  /* COLUMN FIT CONTENT - Requirement: Make all columns fit content */
  .table-sales { 
    margin-bottom: 0; 
    background: transparent !important;
  }
  
  .table-sales thead th, 
  .table-sales tbody td { 
    white-space: nowrap !important; 
    width: 1% !important; 
    border: none !important;
    background: transparent !important;
  }

  /* Exception: Product details can expand to fill remaining space */
  .table-sales .prod-col { 
    width: auto !important; 
    white-space: normal !important; 
  }

  .table-sales thead th { 
    background: rgba(15, 23, 42, 0.6) !important; 
    color: var(--neon-blue) !important; 
    font-size: 10px !important; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    padding: 18px 15px !important;
  }

  .table-sales tbody tr { 
    transition: background 0.3s; 
    border-bottom: 1px solid var(--glass-border) !important;
  }
  
  .table-sales tbody tr:hover { 
    background: rgba(255,255,255,0.05) !important; 
  }

  .table-sales tbody td { 
    padding: 15px !important; 
    vertical-align: middle !important; 
    color: var(--text-main) !important;
  }

  .loc-badge { 
    background: rgba(56, 189, 248, 0.1); 
    color: var(--neon-blue); 
    padding: 6px 12px; 
    border-radius: 8px; 
    font-size: 11px; 
    font-weight: 700;
    border: 1px solid rgba(56, 189, 248, 0.3);
    display: inline-block;
  }

  .price-text { 
    font-weight: 800; 
    color: #fff !important; 
    font-family: 'Monaco', 'Courier New', monospace; 
  }
  
  .date-text { color: var(--text-muted); font-size: 12px; }
  
  .btn-sale-action { 
    width: 34px; height: 34px; line-height: 34px; padding: 0; 
    border-radius: 10px; margin: 0 2px; 
    background: rgba(15, 23, 42, 0.5);
    border: 1px solid var(--glass-border);
    color: var(--text-muted);
    transition: 0.3s;
    display: inline-block;
    text-align: center;
  }
  .btn-sale-action:hover { color: #fff; border-color: var(--neon-blue); transform: translateY(-2px); }

  /* Pagination Styles */
  .pagination-container {
    padding: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(15, 23, 42, 0.4);
  }
  .btn-pagination {
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid var(--glass-border);
    padding: 10px 20px;
    border-radius: 12px;
    color: var(--text-main);
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    transition: all 0.2s;
    margin: 0 8px;
    text-decoration: none !important;
    letter-spacing: 0.5px;
  }
  .btn-pagination:hover:not(.disabled) { background: var(--neon-blue); color: #000; box-shadow: 0 0 15px rgba(56, 189, 248, 0.4); }
  .btn-pagination.disabled { opacity: 0.2; cursor: not-allowed; }
  .page-info { color: var(--text-muted); font-size: 10px; text-transform: uppercase; margin: 0 15px; font-weight: 800; letter-spacing: 1px; }

  .dropdown-menu {
    background: #1e293b;
    border: 1px solid var(--glass-border);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    padding: 10px;
  }
  .dropdown-menu li a { color: var(--text-main); padding: 10px 15px; font-weight: 600; border-radius: 8px; }
  .dropdown-menu li a:hover { background: var(--neon-blue); color: #000; }
  .divider { background-color: var(--glass-border) !important; margin: 8px 0; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="sales-card">
        <div class="sales-header">
          <h2><i class="glyphicon glyphicon-list-alt" style="color: var(--neon-blue); margin-right: 10px;"></i> Sales Transaction History</h2>
          
          <div class="actions">
            <div class="btn-group">
              <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" style="background: transparent; border: 1px solid #10b981; color: #10b981; border-radius: 10px; font-weight:800; text-transform: uppercase; padding: 10px 20px;">
                <i class="glyphicon glyphicon-print"></i> Export <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="print_all_sales.php" target="_blank">All Transactions</a></li>
                <li class="divider"></li>
                <li class="dropdown-header" style="color: var(--neon-blue); font-size: 10px; padding-left: 15px;">By Source Bin</li>
                <?php foreach($locations as $loc): ?>
                  <li><a href="print_location_sales.php?location_id=<?php echo (int)$loc['id'];?>">
                    <i class="glyphicon glyphicon-map-marker" style="font-size: 10px; margin-right: 8px;"></i> <?php echo remove_junk($loc['location_name']); ?>
                  </a></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <a href="add_sale.php" class="btn btn-primary btn-sm" style="background: var(--neon-blue); border:none; color: #000; border-radius:10px; font-weight:800; text-transform: uppercase; padding: 10px 20px; margin-left:12px;">
              <i class="glyphicon glyphicon-plus"></i> New Sale
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-sales">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="prod-col">Product Details</th>
                  <th class="text-center">Source Bin</th> 
                  <th class="text-center">Qty</th>
                  <th class="text-center">Total Value</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sales as $index => $sale):?>
                <tr>
                  <td class="text-center" style="color: var(--text-muted); font-size: 11px; font-weight: 700;"><?php echo $offset + ($index + 1);?></td>
                  <td class="prod-col">
                    <div style="font-weight: 700; font-size: 14px; color: var(--text-main);"><?php echo remove_junk($sale['name']); ?></div>
                  </td>
                  <td class="text-center">
                    <span class="loc-badge">
                      <i class="glyphicon glyphicon-tags" style="font-size: 9px; margin-right: 5px;"></i>
                      <?php echo $sale['location_name'] ? remove_junk($sale['location_name']) : 'N/A'; ?>
                    </span>
                  </td>
                  <td class="text-center" style="font-weight: 800; color: var(--neon-blue); font-size: 15px;"><?php echo (int)$sale['qty']; ?></td>
                  <td class="text-center price-text">
                    Ksh <?php echo number_format((float)$sale['price'], 2); ?>
                  </td>
                  <td class="text-center date-text">
                    <?php echo read_date($sale['date']); ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="picking_list.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-sale-action" title="Pick List" target="_blank">
                        <i class="glyphicon glyphicon-print"></i>
                      </a>
                      <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-sale-action" title="Edit">
                        <i class="glyphicon glyphicon-edit" style="color: #f59e0b;"></i>
                      </a>
                      <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-sale-action" title="Delete" onclick="return confirm('Archive this transaction?')">
                        <i class="glyphicon glyphicon-trash" style="color: #ef4444;"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>

          <div class="pagination-container">
            <a href="?page=<?php echo $page - 1; ?>" class="btn-pagination <?php if($page <= 1) echo 'disabled'; ?>">
              <i class="glyphicon glyphicon-chevron-left"></i> Prev
            </a>
            
            <span class="page-info">System Page <?php echo $page; ?> / <?php echo $total_pages; ?></span>

            <a href="?page=<?php echo $page + 1; ?>" class="btn-pagination <?php if($page >= $total_pages) echo 'disabled'; ?>">
              Next <i class="glyphicon glyphicon-chevron-right"></i>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>