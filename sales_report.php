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

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root {
    --primary-indigo: #6366f1;
    --success-emerald: #10b981;
    --slate-50: #f8fafc;
    --slate-200: #e2e8f0;
    --slate-400: #94a3b8;
    --slate-700: #334155;
    --slate-900: #0f172a;
  }

  body {
    background-color: #f1f5f9;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .report-card { 
    background: #ffffff; 
    border-radius: 16px; 
    border: 1px solid rgba(255, 255, 255, 0.7); 
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .report-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
  }

  .panel-heading { 
    background: transparent !important; 
    border-bottom: 1px solid var(--slate-200) !important; 
    padding: 24px !important; 
  }

  .heading-title {
    font-size: 2.1rem;
    font-weight: 700;
    color: var(--slate-900);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .panel-body { padding: 30px; }

  /* Modernizing Inputs */
  .form-label {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--slate-400);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
    display: block;
  }

  .datepicker, .form-control { 
    border: 1.5px solid var(--slate-200);
    border-radius: 10px !important; 
    height: 48px;
    padding: 10px 15px;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: var(--primary-indigo);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    outline: none;
  }

  .input-group-addon { 
    background: var(--slate-50); 
    border: 1.5px solid var(--slate-200);
    border-left: none;
    border-right: none;
    color: var(--slate-400);
    font-weight: 600;
  }

  /* Button Styling */
  .btn-generate { 
    background: var(--primary-indigo); 
    color: white;
    border: none; 
    font-weight: 600; 
    height: 52px; 
    border-radius: 12px; 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    font-size: 2rem;
  }

  .btn-generate:hover { 
    background: #4f46e5; 
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
  }

  .btn-generate i { font-size: 1.2rem; }

</style>

<div class="container-fluid" style="padding: 40px;">
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
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--primary-indigo)"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Report Configuration
            </div>
          </div>
          <div class="panel-body">
              <form method="post" action="sale_report_process.php">
                  <div class="form-group">
                      <label class="form-label">Date Range Selection</label>
                      <div class="input-group">
                          <input type="text" class="datepicker form-control" name="start-date" placeholder="Start Date" autocomplete="off">
                          <span class="input-group-addon">to</span>
                          <input type="text" class="datepicker form-control" name="end-date" placeholder="End Date" autocomplete="off">
                      </div>
                  </div>
                  
                  <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">Warehouse Filter</label>
                    <select class="form-control" name="location_id">
                      <option value="">-- All Global Locations --</option>
                      <?php foreach($all_locations as $loc): ?>
                        <option value="<?php echo (int)$loc['id']; ?>">
                          <?php echo remove_junk($loc['location_name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div style="margin-top: 35px;">
                    <button type="submit" name="submit" class="btn btn-generate">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                      Generate Detailed Report
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--success-emerald)"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            Fastest-Moving Inventory (Last 30 Days)
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
gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
gradient.addColorStop(1, 'rgba(99, 102, 241, 0.01)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($labels); ?>,
    datasets: [{
      label: 'Units Shipped',
      data: <?php echo json_encode($qtys); ?>,
      borderColor: '#6366f1',
      borderWidth: 4,
      backgroundColor: gradient,
      fill: true,
      tension: 0.45,
      pointBackgroundColor: '#ffffff',
      pointBorderColor: '#6366f1',
      pointBorderWidth: 3,
      pointRadius: 6,
      pointHoverRadius: 8,
      pointHoverBackgroundColor: '#6366f1',
      pointHoverBorderColor: '#fff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#0f172a',
        padding: 12,
        titleFont: { size: 14, weight: 'bold' },
        cornerRadius: 8
      }
    },
    scales: {
      x: { 
        grid: { display: false },
        ticks: { color: '#94a3b8', font: { weight: '500' } }
      },
      y: { 
        beginAtZero: true,
        grid: { color: '#f1f5f9', drawBorder: false },
        ticks: { 
            color: '#94a3b8',
            font: { weight: '500' },
            stepSize: 5 
        } 
      }
    }
  }
});
</script>

<?php include_once('layouts/footer.php'); ?>