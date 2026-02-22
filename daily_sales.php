<?php
  // Set Timezone to Africa/Nairobi
  date_default_timezone_set('Africa/Nairobi');

  $page_title = 'Daily Sales';
  require_once('includes/load.php');
  page_require_level(3);
  $all_locations = find_all_locations();

  // --- Pagination Logic ---
  $limit = 10;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if ($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  $today = date('Y-m-d');
  $target_date = isset($_POST['daily-date']) ? $db->escape($_POST['daily-date']) : $today;
  $location_id = !empty($_POST['location_id']) ? (int)$_POST['location_id'] : false;

  // Get total count for pagination
  $count_sql = "SELECT COUNT(*) AS total FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE DATE(s.date) = '{$target_date}'";
  if($location_id) { $count_sql .= " AND p.location_id = '{$location_id}' "; }
  $total_records = $db->fetch_assoc($db->query($count_sql))['total'];
  $total_pages = ceil($total_records / $limit);

  // SQL with LIMIT and OFFSET for pagination
  $sql  = "SELECT s.date, p.name, p.sale_price, p.buy_price, s.qty, l.location_name ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "WHERE DATE(s.date) = '{$target_date}' ";
  if($location_id) { $sql .= " AND l.id = '{$location_id}' "; }
  $sql .= " ORDER BY s.date DESC ";
  $sql .= " LIMIT {$limit} OFFSET {$offset}";
  $sales_data = find_by_sql($sql);

  // Calculate Totals for KPI Cards (Calculate on full day, not just the page)
  $kpi_sql = "SELECT SUM(s.qty * p.sale_price) as revenue, SUM(s.qty * (p.sale_price - p.buy_price)) as profit ";
  $kpi_sql .= "FROM sales s JOIN products p ON s.product_id = p.id WHERE DATE(s.date) = '{$target_date}'";
  if($location_id) { $kpi_sql .= " AND p.location_id = '{$location_id}' "; }
  $kpi_res = $db->fetch_assoc($db->query($kpi_sql));
  
  $grand_total = $kpi_res['revenue'] ?? 0;
  $total_profit = $kpi_res['profit'] ?? 0;
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .action-bar {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
  }

  .kpi-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    border-left: 5px solid #6366f1;
    margin-bottom: 25px;
  }

  .kpi-card.profit { border-left-color: #10b981; }

  .kpi-label { font-size: 0.8rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.05em; }
  .kpi-value { font-size: 1.5rem; font-weight: 800; color: #1e293b; display: block; }

  .modern-table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }

  .table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 15px;
    border-bottom: 2px solid #f1f5f9;
  }

  .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
  
  .btn-modern {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.2s;
  }

  .btn-modern:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

  .pagination-bar {
    padding: 15px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
  }

  @media print { .no-print { display: none !important; } .container-fluid { width: 100%; } }
</style>

<div class="container-fluid" style="padding: 30px;">
  
  <div class="row">
    <div class="col-md-3">
      <div class="kpi-card">
        <span class="kpi-label">Target Date</span>
        <span class="kpi-value text-primary"><?php echo date('M d, Y', strtotime($target_date)); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card">
        <span class="kpi-label">Daily Revenue</span>
        <span class="kpi-value">Ksh. <?php echo number_format($grand_total, 2); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card profit">
        <span class="kpi-label">Estimated Profit</span>
        <span class="kpi-value text-success">Ksh. <?php echo number_format($total_profit, 2); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card" style="border-left-color: #f59e0b;">
        <span class="kpi-label">Sales Count</span>
        <span class="kpi-value"><?php echo (int)$total_records; ?> Transactions</span>
      </div>
    </div>
  </div>

  <div class="row no-print">
    <div class="col-md-12">
      <div class="action-bar d-flex justify-content-between align-items-center">
        <form class="form-inline d-flex align-items-center" method="post" action="daily_sales.php" style="gap: 15px;">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="datepicker form-control" name="daily-date" value="<?php echo $target_date; ?>" style="width: 150px;">
            </div>

            <select class="form-control" name="location_id" style="width: 200px;">
              <option value="">All Locations</option>
              <?php foreach($all_locations as $loc): ?>
                <option value="<?php echo (int)$loc['id']; ?>" <?php if($location_id == $loc['id']) echo 'selected'; ?>>
                  <?php echo remove_junk($loc['location_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button type="submit" name="submit" class="btn btn-primary btn-modern">Update View</button>
        </form>

        <form method="post" action="sale_report_process.php">
          <input type="hidden" name="start-date" value="<?php echo $target_date; ?>">
          <input type="hidden" name="end-date" value="<?php echo $target_date; ?>">
          <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
          <button type="submit" name="submit" class="btn btn-success btn-modern">
             <i class="glyphicon glyphicon-print"></i> Generate PDF
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="modern-table-container">
          <table class="table mb-0">
            <thead>
              <tr>
                <th class="text-center" style="width: 60px;">#</th>
                <th>Product Information</th>
                <th>Warehouse</th>
                <th class="text-end">Buying Price</th>
                <th class="text-end">Selling Price</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total Revenue</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales_data as $index => $sale): 
                  $total_sale = $sale['qty'] * $sale['sale_price'];
              ?>
              <tr>
                <td class="text-center text-muted"><?php echo ($offset + $index + 1); ?></td>
                <td>
                  <div class="fw-bold" style="color: #1e293b;"><?php echo remove_junk($sale['name']); ?></div>
                  <small class="text-muted">ID: #<?php echo ($offset + $index + 101); ?></small>
                </td>
                <td class="text-center">
                  <span class="badge" style="background: #f1f5f9; color: #475569; border-radius: 4px; padding: 5px 10px;">
                    <?php echo remove_junk($sale['location_name']); ?>
                  </span>
                </td>
                <td class="text-end text-muted">Ksh. <?php echo number_format($sale['buy_price'], 2); ?></td>
                <td class="text-end fw-bold">Ksh. <?php echo number_format($sale['sale_price'], 2); ?></td>
                <td class="text-center">
                  <span class="badge bg-light" style="border: 1px solid #e2e8f0; color: #6366f1; font-weight: 800; font-size: 1.1rem;">
                    <?php echo (int)$sale['qty']; ?>
                  </span>
                </td>
                <td class="text-end">
                  <strong style="color: #6366f1;">Ksh. <?php echo number_format($total_sale, 2); ?></strong>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if(empty($sales_data)): ?>
              <tr>
                <td colspan="7" class="text-center p-5 text-muted">No sales recorded for this date and location.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <div class="pagination-bar d-flex justify-content-between align-items-center no-print">
            <div class="text-muted small">
               Showing <?php echo min($offset + 1, $total_records); ?> to <?php echo min($offset + $limit, $total_records); ?> of <?php echo $total_records; ?> entries
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo max(1, $page - 1); ?>" class="btn btn-default btn-sm <?php if($page <= 1) echo 'disabled'; ?>">
                <i class="glyphicon glyphicon-chevron-left"></i> Previous
              </a>
              <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="btn btn-default btn-sm <?php if($page >= $total_pages) echo 'disabled'; ?>">
                Next <i class="glyphicon glyphicon-chevron-right"></i>
              </a>
            </div>
          </div>

      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>