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

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  body {
    background-color: #f4f7fa;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  /* Card Styling */
  .monthly-report-card { 
    background: #ffffff; 
    border-radius: 20px; 
    border: none; 
    box-shadow: 0 10px 25px rgba(29, 78, 216, 0.05); 
    overflow: hidden;
    margin-bottom: 30px;
  }

  .card-header-modern { 
    background: #ffffff; 
    border-bottom: 1px solid #f1f5f9; 
    padding: 25px 30px; 
  }

  .card-header-modern h3 {
    margin: 0;
    font-size: 2.1rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .panel-body { padding: 30px; }

  /* Form Elements */
  .form-label-modern {
    font-size: 1.7rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 10px;
    display: block;
  }

  .datepicker, .form-control { 
    border: 1.5px solid #e2e8f0;
    border-radius: 12px !important; 
    height: 50px;
    padding-left: 15px;
    font-weight: 500;
    color: #334155;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
  }

  .input-group-addon {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-left: none;
    border-right: none;
    color: #94a3b8;
  }

  /* Main Button */
  .btn-generate-report { 
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none; 
    color: white;
    font-weight: 700; 
    height: 54px; 
    border-radius: 14px; 
    margin-top: 15px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: all 0.3s;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .btn-generate-report:hover { 
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.3);
    opacity: 0.95;
  }

  .chart-container { position: relative; height: 350px; width: 100%; }
</style>

<div class="container-fluid" style="padding: 40px;">
  <div class="row">
    <div class="col-md-4">
      <div class="monthly-report-card">
        <div class="card-header-modern">
          <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Report Parameters
          </h3>
        </div>
        <div class="panel-body">
          <form method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label-modern">Target Period</label>
              <div class="input-group">
                <input type="text" class="datepicker form-control" name="start-date" value="<?php echo date('Y-m-01'); ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-arrow-right"></i></span>
                <input type="text" class="datepicker form-control" name="end-date" value="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
              <label class="form-label-modern">Warehouse Filter</label>
              <select class="form-control" name="location_id">
                <option value="">All Warehouse Locations</option>
                <?php foreach($all_locations as $loc): ?>
                  <option value="<?php echo (int)$loc['id']; ?>"><?php echo remove_junk($loc['location_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <button type="submit" name="submit" class="btn btn-generate-report">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Export Monthly Analysis
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="monthly-report-card">
        <div class="card-header-modern">
          <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20V16"></path></svg>
            Quantity Outflow Trend (<?php echo date('F Y'); ?>)
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

// Create a professional soft blue gradient
const blueGradient = ctxMonth.createLinearGradient(0, 0, 0, 350);
blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

new Chart(ctxMonth, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($days); ?>,
    datasets: [{
      label: 'Units Shipped',
      data: <?php echo json_encode($qty_totals); ?>,
      borderColor: '#3b82f6',
      backgroundColor: blueGradient,
      borderWidth: 4,
      fill: true,
      tension: 0.4,
      pointRadius: 0, // Hidden by default for a cleaner line
      pointHoverRadius: 7,
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: '#3b82f6',
      pointHoverBorderWidth: 3
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      intersect: false,
      mode: 'index',
    },
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#1e293b',
        titleFont: { size: 13, family: 'Plus Jakarta Sans' },
        bodyFont: { size: 13, family: 'Plus Jakarta Sans' },
        padding: 12,
        cornerRadius: 10,
        displayColors: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: { borderDash: [8, 4], color: '#f1f5f9', drawBorder: false },
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