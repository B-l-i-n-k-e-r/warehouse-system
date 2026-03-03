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
  $total_records_res = $db->query($count_sql);
  $total_records = $db->fetch_assoc($total_records_res)['total'];
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

  // Calculate Totals for KPI Cards
  $kpi_sql = "SELECT SUM(s.qty * p.sale_price) as revenue, SUM(s.qty * (p.sale_price - p.buy_price)) as profit ";
  $kpi_sql .= "FROM sales s JOIN products p ON s.product_id = p.id WHERE DATE(s.date) = '{$target_date}'";
  if($location_id) { $kpi_sql .= " AND p.location_id = '{$location_id}' "; }
  $kpi_res = $db->fetch_assoc($db->query($kpi_sql));
  
  $grand_total = $kpi_res['revenue'] ?? 0;
  $total_profit = $kpi_res['profit'] ?? 0;
?>
<?php include_once('layouts/header.php'); ?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  :root {
    --primary: #38bdf8; 
    --accent: #22c55e;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --text-main: #f8fafc;
    --text-dim: #94a3b8;
    --border: rgba(56, 189, 248, 0.1);
  }

  body {
    background-color: var(--dark-bg);
    background-image: radial-gradient(circle at 2px 2px, rgba(56, 189, 248, 0.05) 1px, transparent 0);
    background-size: 40px 40px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
  }

  /* KPI Cards */
  .kpi-card {
    background: var(--card-bg);
    padding: 25px;
    border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s;
  }
  .kpi-card:hover { transform: translateY(-5px); }
  .kpi-label { font-size: 0.7rem; text-transform: uppercase; color: var(--primary); font-weight: 800; letter-spacing: 0.1em; }
  .kpi-value { font-size: 1.6rem; font-weight: 800; color: #fff; display: block; margin-top: 5px; }

  /* Action Bar */
  .action-bar {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 16px;
    border: 1px solid var(--border);
    margin-bottom: 30px;
  }

  .form-control { 
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid var(--border);
    border-radius: 10px !important; 
    color: #fff !important;
  }

  /* COMBOBOX FIT CONTENT */
  select[name="location_id"] {
    width: fit-content !important;
    min-width: 160px;
    max-width: 300px;
    height:auto;
    padding-right: 35px; /* Space for the dropdown arrow */
  }

  /* TABLE CUSTOMIZATION */
  .modern-table-container {
    background: var(--card-bg);
    border-radius: 20px;
    border: 1px solid var(--border);
    overflow: hidden;
  }

  .table { color: var(--text-main); margin-bottom: 0; width: 100%; table-layout: auto; }
  
  .table thead th {
    background: rgba(56, 189, 248, 0.05);
    color: var(--primary);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.7rem;
    padding: 18px 15px;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
    width: 1%; /* Shrink all columns to fit content */
  }

  /* Let Product Description expand */
  .table thead th:nth-child(2), 
  .table tbody td:nth-child(2) { 
    width: auto; 
    white-space: normal; 
    min-width: 200px; 
  }

  .table tbody td { 
    padding: 15px; 
    vertical-align: middle; 
    border-bottom: 1px solid var(--border); 
    white-space: nowrap;
    color: var(--text-dim);
  }

  /* HOVER EFFECT */
  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(56, 189, 248, 0.08) !important;
    box-shadow: inset 4px 0 0 var(--primary);
  }

  .btn-modern {
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: 0.3s;
  }

  .pagination-bar {
    padding: 20px;
    background: rgba(15, 23, 42, 0.4);
    border-top: 1px solid var(--border);
  }

  .badge-loc {
    background: rgba(56, 189, 248, 0.1);
    color: var(--primary);
    border: 1px solid var(--border);
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.75rem;
  }

  @media print { .no-print { display: none !important; } }
</style>

<div class="container-fluid" style="padding: 40px;">
  
  <div class="row" style="margin-bottom: 30px;">
    <div class="col-md-3">
      <div class="kpi-card" style="border-top: 4px solid var(--primary);">
        <span class="kpi-label">Target Date</span>
        <span class="kpi-value"><?php echo date('d M, Y', strtotime($target_date)); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card" style="border-top: 4px solid var(--accent);">
        <span class="kpi-label">Revenue</span>
        <span class="kpi-value">Ksh <?php echo number_format($grand_total, 0); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card" style="border-top: 4px solid #f59e0b;">
        <span class="kpi-label">Net Profit</span>
        <span class="kpi-value" style="color: var(--accent);">Ksh <?php echo number_format($total_profit, 0); ?></span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="kpi-card" style="border-top: 4px solid #8b5cf6;">
        <span class="kpi-label">Transactions</span>
        <span class="kpi-value"><?php echo (int)$total_records; ?> Items</span>
      </div>
    </div>
  </div>

  <div class="row no-print">
    <div class="col-md-12">
      <div class="action-bar d-flex justify-content-between align-items-center">
        <form class="form-inline d-flex align-items-center" method="post" action="daily_sales.php" style="gap: 15px;">
            <div class="input-group">
              <input type="text" class="datepicker form-control" name="daily-date" value="<?php echo $target_date; ?>" style="width: 140px;">
            </div>

            <select class="form-control" name="location_id">
              <option value="">All Warehouses</option>
              <?php foreach($all_locations as $loc): ?>
                <option value="<?php echo (int)$loc['id']; ?>" <?php if($location_id == $loc['id']) echo 'selected'; ?>>
                  <?php echo remove_junk($loc['location_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button type="submit" name="submit" class="btn btn-primary btn-modern">Filter</button>
        </form>

        <form method="post" action="sale_report_process.php">
          <input type="hidden" name="start-date" value="<?php echo $target_date; ?>">
          <input type="hidden" name="end-date" value="<?php echo $target_date; ?>">
          <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
          <button type="submit" name="submit" class="btn btn-success btn-modern" <?php if(empty($sales_data)) echo 'disabled'; ?>>
              Print Report
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="modern-table-container">
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Product Description</th>
                <th>Warehouse</th>
                <th class="text-end">Buy Price</th>
                <th class="text-end">Sale Price</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales_data as $index => $sale): 
                  $total_sale = $sale['qty'] * $sale['sale_price'];
              ?>
              <tr>
                <td class="text-center" style="font-size: 0.8rem;"><?php echo ($offset + $index + 1); ?></td>
                <td>
                  <div style="color: #fff; font-weight: 700;"><?php echo strtoupper(remove_junk($sale['name'])); ?></div>
                </td>
                <td>
                  <span class="badge-loc"><?php echo strtoupper($sale['location_name']); ?></span>
                </td>
                <td class="text-end">Ksh <?php echo number_format($sale['buy_price'], 0); ?></td>
                <td class="text-end" style="color: #fff;">Ksh <?php echo number_format($sale['sale_price'], 0); ?></td>
                <td class="text-center">
                  <span style="color: var(--primary); font-weight: 800;"><?php echo (int)$sale['qty']; ?></span>
                </td>
                <td class="text-end">
                  <strong style="color: var(--primary);">Ksh <?php echo number_format($total_sale, 0); ?></strong>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if(empty($sales_data)): ?>
              <tr>
                <td colspan="7" class="text-center p-5">
                  <div style="color: var(--text-dim);">No transactions recorded for this selection.</div>
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <div class="pagination-bar d-flex justify-content-between align-items-center no-print">
            <div style="color: var(--text-dim); font-size: 0.75rem;">
                Showing <?php echo min($offset + 1, $total_records); ?> - <?php echo min($offset + $limit, $total_records); ?> of <?php echo $total_records; ?>
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo max(1, $page - 1); ?>" class="btn btn-sm btn-outline-secondary <?php if($page <= 1) echo 'disabled'; ?>" style="border-color: var(--border); color: #fff;">Prev</a>
              <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="btn btn-sm btn-outline-secondary <?php if($page >= $total_pages) echo 'disabled'; ?>" style="border-color: var(--border); color: #fff;">Next</a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>