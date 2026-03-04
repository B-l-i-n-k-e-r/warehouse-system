<?php
// Set the correct timezone for Kenya
date_default_timezone_set('Africa/Nairobi');

$page_title = 'Sales Report';
require_once('includes/load.php');
page_require_level(3);

if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)) {
        $start_date  = remove_junk($db->escape($_POST['start-date']));
        $end_date    = remove_junk($db->escape($_POST['end-date']));
        $location_id = isset($_POST['location_id']) ? remove_junk($db->escape($_POST['location_id'])) : '';

        $sql  = "SELECT s.date, p.name, p.sale_price, p.buy_price, s.qty, l.location_name, ";
        $sql .= " (s.qty * p.sale_price) AS total_selling_price, ";
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

        // Validation: Redirect if no results found
        if (empty($results)) {
            $session->msg("w", "No sales records found for the selected criteria.");
            redirect('sales_report.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    }
} else {
    $session->msg("d", "Please select a date range to generate a report.");
    redirect('sales_report.php', false);
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoonLit Report | <?php echo $start_date; ?> - <?php echo $end_date; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #38bdf8; /* Neon Blue */
            --accent: #22c55e;  /* Green */
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-dim: #94a3b8; /* Date & Product Color */
            --border: rgba(56, 189, 248, 0.2);
            --qty-grey: #475569; /* Dark Grey for Quantity */
        }

        body {
            background-color: var(--dark-bg);
            background-image: radial-gradient(circle at 2px 2px, rgba(56, 189, 248, 0.05) 1px, transparent 0);
            background-size: 40px 40px;
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            padding: 40px 20px;
        }

        .report-container {
            width: 100%;
            max-width: 1400px;
            margin: auto;
        }

        .report-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .report-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px dashed var(--border);
        }

        .company-logo {
            font-weight: 900;
            font-size: 26px;
            letter-spacing: 2px;
            color: var(--primary);
            text-transform: uppercase;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
        }

        .table {
            color: var(--text-main);
            border-color: var(--border);
            width: 100%;
            table-layout: auto; 
            margin-bottom: 0;
        }

        /* FIT CONTENT LOGIC */
        .table thead th, 
        .table tbody td {
            white-space: nowrap;
            width: 1%;
            padding: 12px 15px;
            vertical-align: middle;
        }

        /* Allow description to wrap if absolutely necessary but maintain space */
        .table .col-desc {
            width: auto;
            white-space: normal;
            min-width: 250px;
        }

        .table thead th {
            background: rgba(56, 189, 248, 0.1);
            color: var(--primary);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--primary);
        }

        .table tbody td {
            border-bottom: 1px solid var(--border);
        }

        /* Colors as per request */
        .product-name {
            font-weight: 600;
            color: var(--text-dim); /* Matches Date color */
            display: block;
        }

        .qty-text {
            color: var(--qty-grey); /* Dark Grey */
            font-weight: 800;
        }

        .total-neon {
            color: var(--primary); /* Neon Blue */
            font-weight: 800;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.3);
        }

        .profit-up { color: var(--accent); font-weight: 800; } /* Green */
        .profit-down { color: #f87171; font-weight: 800; }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-item {
            padding: 20px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
        }

        .summary-label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary);
            letter-spacing: 1.5px;
            margin-bottom: 5px;
            display: block;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .badge-loc {
            background: rgba(56, 189, 248, 0.1);
            color: var(--primary);
            border: 1px solid var(--border);
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .btn-print {
            background: var(--primary);
            color: var(--dark-bg);
            font-weight: 800;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .btn-print:hover {
            background: #fff;
            box-shadow: 0 0 15px var(--primary);
        }

        @media print {
            body { background: #fff !important; color: #000 !important; padding: 0; }
            .report-card { border: 1px solid #ddd; box-shadow: none; background: #fff !important; }
            .report-card::before, .no-print { display: none; }
            .summary-item { background: #f8fafc !important; border: 1px solid #ddd !important; }
            .summary-value { color: #000 !important; }
            .product-name, .qty-text, .total-neon { color: #000 !important; text-shadow: none !important; }
            .table thead th { color: #444 !important; background: #eee !important; }
        }
    </style>
</head>
<body>

<?php if($results): 
    $grand_total = 0;
    $total_cost = 0;
    foreach($results as $r) {
        $grand_total += $r['total_selling_price'];
        $total_cost += $r['total_buying_price'];
    }
    $total_profit = $grand_total - $total_cost;
?>
    <div class="report-container">
        <div class="report-card">
            <header class="report-header">
                <div>
                    <div class="company-logo">MoonLit <span style="color:var(--text-dim)">Systems</span></div>
                    <div style="font-size: 11px; color: var(--text-dim); margin-top: 5px;">
                        GENERATED ON: <?php echo date("Y-m-d H:i"); ?>
                    </div>
                </div>
                <div class="text-end">
                    <div style="font-size: 10px; font-weight: 800; color: var(--primary);">DATE RANGE</div>
                    <div style="font-weight: 700; letter-spacing: 1px;">
                        <?php echo strtoupper(date("d M Y", strtotime($start_date))); ?> — <?php echo strtoupper(date("d M Y", strtotime($end_date))); ?>
                    </div>
                </div>
            </header>

            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Total Revenue</span>
                    <span class="summary-value">Ksh <?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Buying Cost</span>
                    <span class="summary-value" style="color: var(--text-dim);">Ksh <?php echo number_format($total_cost, 2); ?></span>
                </div>
                <div class="summary-item" style="border-color: var(--accent);">
                    <span class="summary-label" style="color: var(--accent);">Net Profit</span>
                    <span class="summary-value" style="color: var(--accent);">Ksh <?php echo number_format($total_profit, 2); ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="col-desc">Product Description</th>
                            <th>Location</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Total Sales</th>
                            <th class="text-end">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $result): 
                            $profit = $result['total_selling_price'] - $result['total_buying_price'];
                        ?>
                        <tr>
                            <td style="color: var(--text-dim); font-size: 11px;">
                                <?php echo date("d.m.y", strtotime($result['date']));?>
                            </td>
                            <td class="col-desc">
                                <span class="product-name"><?php echo strtoupper(remove_junk($result['name']));?></span>
                            </td>
                            <td><span class="badge-loc"><?php echo strtoupper($result['location_name']);?></span></td>
                            
                            <td class="text-end" style="color: var(--text-dim);">
                                Ksh <?php echo number_format($result['sale_price'], 0);?>
                            </td>
                            
                            <td class="text-center qty-text"><?php echo (int)$result['qty'];?></td>
                            
                            <td class="text-end total-neon">
                                Ksh <?php echo number_format($result['total_selling_price'], 0);?>
                            </td>
                            
                            <td class="text-end <?php echo ($profit >= 0) ? 'profit-up' : 'profit-down'; ?>">
                                <?php echo ($profit >= 0 ? '+ Ksh ' : '- Ksh ') . number_format(abs($profit), 0);?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-between align-items-center no-print">
                <a href="sales_report.php" style="color: var(--text-dim); text-decoration: none; font-size: 12px; font-weight: 700;">
                    &larr; Back to Generator
                </a>
                <button class="btn-print" onclick="window.print()">
                    Print Final Report
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>