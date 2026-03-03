<?php
  $page_title = 'Monthly Sales Report';
  require_once('includes/load.php');
  page_require_level(3);
  $all_locations = find_all_locations();

  $current_month = date('m');
  $current_year = date('Y');
  
  $chart_sql = "SELECT DATE(date) as day, SUM(qty) as total_qty FROM sales ";
  $chart_sql .= "WHERE MONTH(date) = '{$current_month}' AND YEAR(date) = '{$current_year}' ";
  $chart_sql .= "GROUP BY DATE(date) ORDER BY DATE(date) ASC";
  $chart_results = find_by_sql($chart_sql);

  $days = [];
  $qty_totals = [];
  foreach($chart_results as $row) {
    $days[] = date('d M', strtotime($row['day']));
    $qty_totals[] = (int)$row['total_qty'];
  }
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

  /* Modern Dark Card */
  .monthly-report-card { 
    background: var(--card-bg); 
    border-radius: 24px; 
    border: 1px solid var(--border); 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
    overflow: hidden;
    margin-bottom: 30px;
    position: relative;
  }

  .monthly-report-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
  }

  .card-header-modern { 
    background: rgba(255, 255, 255, 0.02); 
    border-bottom: 1px solid var(--border); 
    padding: 25px 30px; 
  }

  .card-header-modern h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
  }

  .panel-body { padding: 30px; }

  /* Dark Form Styling */
  .form-label-modern {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin-bottom: 12px;
    display: block;
  }

  .datepicker, .form-control { 
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid var(--border);
    border-radius: 12px !important; 
    height: 54px;
    color: #fff !important;
    font-weight: 500;
    transition: all 0.3s;
  }

  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
    background: rgba(15, 23, 42, 0.8) !important;
  }

  .input-group-addon {
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid var(--border);
    border-left: none;
    border-right: none;
    color: var(--primary);
  }

  /* Neon Button */
  .btn-generate-report { 
    background: var(--primary);
    border: none; 
    color: var(--dark-bg);
    font-weight: 800; 
    height: 56px; 
    border-radius: 16px; 
    margin-top: 20px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .btn-generate-report:hover { 
    background: #fff;
    transform: translateY(-3px);
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
  }

  .chart-container { position: relative; height: 380px; width: 100%; }

  select.form-control option {
    background: var(--card-bg);
    color: #fff;
  }
</style>

<div class="container-fluid" style="padding: 40px;">
  <div class="row">
    <div class="col-md-4">
      <div class="monthly-report-card">
        <div class="card-header-modern">
          <h3>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Parameters
          </h3>
        </div>
        <div class="panel-body">
          <form method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label-modern">Target Period</label>
              <div class="input-group">
                <input type="text" class="datepicker form-control" name="start-date" value="<?php echo date('Y-m-01'); ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-arrow-right"></i></span>
                <input type="text" class="datepicker form-control" name="end-date" value="<?php echo date('Y-m-t'); ?>">
              </div>
            </div>
            
            <div class="form-group" style="margin-top: 25px;">
              <label class="form-label-modern">Warehouse Filter</label>
              <select class="form-control" name="location_id">
                <option value="">All Warehouse Locations</option>
                <?php foreach($all_locations as $loc): ?>
                  <option value="<?php echo (int)$loc['id']; ?>"><?php echo remove_junk($loc['location_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <button type="submit" name="submit" class="btn btn-generate-report">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
              Export Report
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="monthly-report-card">
        <div class="card-header-modern">
          <h3>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20V16"></path></svg>
            Outflow Trend (<?php echo date('F Y'); ?>)
          </h3>
        </div>
        <div class="panel-body">
          <div class="chart-container">
            <canvas id="monthlyQtyChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxMonth = document.getElementById('monthlyQtyChart').getContext('2d');

// Refined Cyan-to-Transparent Gradient
const cyanGradient = ctxMonth.createLinearGradient(0, 0, 0, 400);
cyanGradient.addColorStop(0, 'rgba(56, 189, 248, 0.4)');
cyanGradient.addColorStop(1, 'rgba(15, 23, 42, 0)');

new Chart(ctxMonth, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($days); ?>,
    datasets: [{
      label: 'Units Shipped',
      data: <?php echo json_encode($qty_totals); ?>,
      borderColor: '#38bdf8',
      backgroundColor: cyanGradient,
      borderWidth: 4,
      fill: true,
      tension: 0.4,
      pointRadius: 4,
      pointBackgroundColor: '#0f172a',
      pointBorderColor: '#38bdf8',
      pointBorderWidth: 2,
      pointHoverRadius: 8,
      pointHoverBackgroundColor: '#38bdf8',
      pointHoverBorderColor: '#fff',
      pointHoverBorderWidth: 3
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#1e293b',
        titleColor: '#38bdf8',
        bodyColor: '#fff',
        titleFont: { size: 14, weight: 'bold', family: 'Plus Jakarta Sans' },
        bodyFont: { size: 13, family: 'Plus Jakarta Sans' },
        padding: 15,
        cornerRadius: 12,
        displayColors: false,
        borderColor: 'rgba(56, 189, 248, 0.2)',
        borderWidth: 1
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: { color: 'rgba(148, 163, 184, 0.05)', drawBorder: false },
        ticks: { color: '#94a3b8', font: { size: 11, weight: '600' } }
      },
      x: {
        grid: { display: false },
        ticks: { color: '#94a3b8', font: { size: 11, weight: '600' } }
      }
    }
  }
});
</script>

<?php include_once('layouts/footer.php'); ?>