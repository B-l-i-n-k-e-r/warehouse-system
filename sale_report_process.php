<?php
// Set the correct timezone for Kenya
date_default_timezone_set('Africa/Nairobi');

$page_title = 'Sales Report';
require_once('includes/load.php');
page_require_level(3);

if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)):
        $start_date  = remove_junk($db->escape($_POST['start-date']));
        $end_date    = remove_junk($db->escape($_POST['end-date']));
        $location_id = isset($_POST['location_id']) ? remove_junk($db->escape($_POST['location_id'])) : '';

        $sql  = "SELECT s.date, p.name, p.sale_price, p.buy_price, s.qty, l.location_name, ";
        $sql .= " (s.qty * p.sale_price) AS total_saleing_price, ";
        $sql .= " (s.qty * p.buy_price) AS total_buying_price ";
        $sql .= " FROM sales s ";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id ";
        $sql .= " LEFT JOIN locations l ON p.location_id = l.id ";
        $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}' ";

        if (!empty($location_id)) {
            $sql .= " AND l.id = '{$location_id}' ";
        }

        $sql .= " ORDER BY s.date DESC";
        $results = find_by_sql($sql);
    else:
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report | <?php echo $start_date; ?> to <?php echo $end_date; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --success: #10b981;
            --danger: #ef4444;
            --slate-800: #1e293b;
        }
        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            color: var(--slate-800);
            padding: 40px 20px;
        }
        .report-container {
            max-width: 1140px;
            margin: auto;
        }
        .report-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
        }
        .company-logo {
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -1px;
            color: var(--primary);
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .summary-item {
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            background: #f8fafc;
        }
        .summary-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            display: block;
            margin-bottom: 8px;
        }
        .summary-value {
            font-size: 26px;
            font-weight: 800;
            display: block;
        }
        
        /* Table column fit content logic */
        .table thead th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 15px;
            white-space: nowrap;
            width: 1%; /* Forces columns to shrink to content */
        }
        
        /* Allow product description to take remaining space */
        .table thead th:nth-child(2) {
            width: auto;
            white-space: normal;
        }

        .table tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }
        
        .table tbody td:nth-child(2) {
            white-space: normal;
        }

        .badge-location {
            background: #eef2ff;
            color: #4338ca;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }
        .profit-pos { color: var(--success); }
        .profit-neg { color: var(--danger); }

        .back-link {
            display: inline-block;
            text-align: center;
            margin-top: 30px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            cursor: pointer;
            border: none;
            background: none;
        }
        .back-link:hover {
            color: var(--primary);
        }
        
        @media print {
            body { background: white; padding: 0; }
            .report-card { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none; }
            .summary-item { border: 1px solid #eee; background: white !important; }
        }
    </style>
</head>
<body>

<?php if($results): 
    $grand_total = 0;
    $total_cost = 0;
    foreach($results as $r) {
        $grand_total += $r['total_saleing_price'];
        $total_cost += $r['total_buying_price'];
    }
    $total_profit = $grand_total - $total_cost;
?>
    <div class="report-container">
        <div class="report-card">
            <header class="report-header">
                <div>
                    <div class="company-logo">MOONLIT <span style="color:#94a3b8">LOGISTICS</span></div>
                    <h1 class="h4 fw-bold mt-2">Sales Analysis Report</h1>
                    <p class="text-muted small mb-0">
                        Generated on: <strong><?php echo date("d M Y"); ?></strong> at <strong><?php echo date("h:i A"); ?></strong>
                    </p>
                </div>
                <div class="text-end">
                    <span class="badge bg-dark rounded-pill px-3 py-2">
                        Date: <?php echo date("d/m/y", strtotime($start_date)); ?> - <?php echo date("d/m/y", strtotime($end_date)); ?>
                    </span>
                    <?php if(!empty($location_id)): ?>
                        <div class="mt-2 small fw-bold text-uppercase text-primary">
                            Site: <?php echo remove_junk($results[0]['location_name']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Total Revenue</span>
                    <span class="summary-value text-dark">Ksh <?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Buying Cost</span>
                    <span class="summary-value text-muted">Ksh <?php echo number_format($total_cost, 2); ?></span>
                </div>
                <div class="summary-item" style="background: #ecfdf5; border-color: #a7f3d0;">
                    <span class="summary-label" style="color: #059669;">Net Profit</span>
                    <span class="summary-value" style="color: #059669;">Ksh <?php echo number_format($total_profit, 2); ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product Description</th>
                            <th>Location</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total Sale</th>
                            <th class="text-end">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $result): 
                            $profit = $result['total_saleing_price'] - $result['total_buying_price'];
                        ?>
                        <tr>
                            <td class="text-muted small"><?php echo date("d/m/y", strtotime($result['date']));?></td>
                            <td>
                                <div class="fw-bold"><?php echo remove_junk(ucfirst($result['name']));?></div>
                            </td>
                            <td><span class="badge-location"><?php echo remove_junk($result['location_name']);?></span></td>
                            <td class="text-end text-muted small">Ksh <?php echo number_format($result['sale_price'], 2);?></td>
                            <td class="text-center fw-bold"><?php echo (int)$result['qty'];?></td>
                            <td class="text-end fw-bold">Ksh <?php echo number_format($result['total_saleing_price'], 2);?></td>
                            <td class="text-end fw-bold <?php echo ($profit >= 0) ? 'profit-pos' : 'profit-neg'; ?>">
                                Ksh <?php echo number_format($profit, 2);?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-between align-items-center no-print">
                <div>
                    <button class="btn btn-light border px-4 me-2" onclick="window.print()">
                        Export PDF
                    </button>
                    <button class="btn btn-primary px-4 fw-bold" onclick="window.print()">
                        Print Report
                    </button>
                </div>
            </div>
        </div>
        
        <div class="text-center no-print">
            <button onclick="window.history.back()" class="back-link">
                ← Go Back to Previous Page
            </button>
        </div>
    </div>
<?php 
    else:
        $session->msg("d", "Sorry, no sales found for this period/location.");
        redirect('sales_report.php', false);
    endif;
?>

</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>