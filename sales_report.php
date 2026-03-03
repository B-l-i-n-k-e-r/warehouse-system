<?php
  $page_title = 'Sale Report';
  require_once('includes/load.php');
  page_require_level(3);
  $all_locations = find_all_locations();

  // Top Products by Quantity in the last 30 days
  $sql = "SELECT p.name, SUM(s.qty) as total_qty FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "WHERE s.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
  $sql .= "GROUP BY p.name ORDER BY total_qty DESC LIMIT 8";
  $top_products = find_by_sql($sql);

  $labels = []; $qtys = [];
  foreach($top_products as $row) { 
    $labels[] = $row['name']; 
    $qtys[] = (int)$row['total_qty']; 
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --primary: #38bdf8; 
    --accent: #22c55e;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --text-main: #f8fafc;
    --text-dim: #94a3b8;
    --border: rgba(56, 189, 248, 0.2);
    --input-bg: rgba(15, 23, 42, 0.6);
    --danger: #ef4444;
  }

  body {
    background-color: var(--dark-bg);
    background-image: radial-gradient(circle at 2px 2px, rgba(56, 189, 248, 0.05) 1px, transparent 0);
    background-size: 40px 40px;
    color: var(--text-main);
  }

  /* --- MOONLIT ALERT STYLING --- */
  .alert {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
  }
  .alert-danger { border-left: 5px solid var(--danger); color: #fca5a5; }
  .alert-warning { border-left: 5px solid #f59e0b; color: #fcd34d; }
  .alert-success { border-left: 5px solid var(--accent); color: #86efac; }

  .report-card { 
    background: var(--card-bg); 
    border-radius: 16px; 
    border: 1px solid var(--border); 
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
  }

  .panel-heading { 
    background: rgba(15, 23, 42, 0.4) !important; 
    border-bottom: 1px solid var(--border) !important; 
    padding: 20px 25px !important; 
  }

  .heading-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--text-main);
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .form-label {
    font-size: 11px;
    font-weight: 800;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
  }

  .datepicker, .form-control { 
    background-color: var(--input-bg) !important;
    border: 1px solid var(--border) !important;
    color: #fff !important;
    border-radius: 8px !important; 
    height: 45px;
  }

  .btn-generate { 
    background: var(--primary); 
    color: var(--dark-bg);
    border: none; 
    font-weight: 800; 
    height: 50px; 
    border-radius: 10px; 
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    text-transform: uppercase;
  }

  .btn-generate:hover { 
    background: #fff;
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
  }
</style>

<div class="container-fluid" style="padding: 30px;">
  
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-5">
      <div class="panel report-card">
          <div class="panel-heading">
            <div class="heading-title">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--primary)"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Report Engine
            </div>
          </div>
          <div class="panel-body">
              <form method="post" action="sale_report_process.php">
                  <div class="form-group">
                      <label class="form-label">Sequence Range</label>
                      <div class="input-group">
                          <input type="text" class="datepicker form-control" name="start-date" placeholder="Start" autocomplete="off">
                          <span class="input-group-addon">TO</span>
                          <input type="text" class="datepicker form-control" name="end-date" placeholder="End" autocomplete="off">
                      </div>
                  </div>
                  
                  <div class="form-group" style="margin-top: 25px;">
                    <label class="form-label">Warehouse Filter</label>
                    <select class="form-control" name="location_id">
                      <option value="">GLOBAL (ALL LOCATIONS)</option>
                      <?php foreach($all_locations as $loc): ?>
                        <option value="<?php echo (int)$loc['id']; ?>">
                          <?php echo strtoupper(remove_junk($loc['location_name'])); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div style="margin-top: 35px;">
                    <button type="submit" name="submit" class="btn btn-generate">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                      Generate Report
                    </button>
                  </div>
              </form>
          </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="panel report-card">
        <div class="panel-heading">
          <div class="heading-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent)"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            Active Inventory Flow
          </div>
        </div>
        <div class="panel-body">
          <div style="height: 300px; width: 100%;">
            <canvas id="reportQtyChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('reportQtyChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(56, 189, 248, 0.3)');
gradient.addColorStop(1, 'rgba(56, 189, 248, 0.01)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($labels); ?>,
    datasets: [{
      label: 'Units Transferred',
      data: <?php echo json_encode($qtys); ?>,
      borderColor: '#38bdf8',
      borderWidth: 4,
      backgroundColor: gradient,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#0f172a',
      pointBorderColor: '#38bdf8',
      pointBorderWidth: 3,
      pointRadius: 5
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false }
    },
    scales: {
      x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
      y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }, ticks: { color: '#94a3b8' } }
    }
  }
});
</script>

<?php include_once('layouts/footer.php'); ?>