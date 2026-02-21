<?php
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #334155;
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .report-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 40px;
            max-width: 1100px;
            margin: auto;
        }
        .report-header {
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 30px;
            padding-bottom: 20px;
            text-align: center;
        }
        .report-header h1 {
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.025em;
        }
        .date-badge {
            background: #e2e8f0;
            color: #475569;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
        .profit-text {
            color: #10b981;
            font-weight: 600;
        }
        .grand-total-row {
            background-color: #f8fafc;
            font-weight: 600;
        }
        @media print {
            body { background: white; padding: 0; }
            .report-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<?php if($results): ?>
    <div class="report-card">
        <header class="report-header">
            <h1>Inventory Sales Report</h1>
            <div class="mt-3">
                <span class="date-badge">
                    <?php echo $start_date; ?> &mdash; <?php echo $end_date; ?>
                </span>
            </div>
            <?php if(!empty($location_id)): ?>
                <div class="mt-2">
                    <small class="text-muted text-uppercase tracking-wider">Location:</small>
                    <span class="badge bg-primary rounded-pill"><?php echo remove_junk($results[0]['location_name']); ?></span>
                </div>
            <?php endif; ?>
        </header>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Title</th>
                        <th>Location</th>
                        <th class="text-end">Buying</th>
                        <th class="text-end">Selling</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $grand_total = 0;
                        $total_profit = 0;
                        foreach($results as $result): 
                            $grand_total += $result['total_saleing_price'];
                            $total_profit += ($result['total_saleing_price'] - $result['total_buying_price']);
                    ?>
                    <tr>
                        <td class="text-muted"><?php echo remove_junk($result['date']);?></td>
                        <td><strong><?php echo remove_junk(ucfirst($result['name']));?></strong></td>
                        <td><span class="text-secondary"><?php echo remove_junk($result['location_name']);?></span></td>
                        <td class="text-end">$<?php echo number_format($result['buy_price'], 2);?></td>
                        <td class="text-end">$<?php echo number_format($result['sale_price'], 2);?></td>
                        <td class="text-center"><?php echo (int)$result['qty'];?></td>
                        <td class="text-end"><strong>$<?php echo number_format($result['total_saleing_price'], 2);?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td colspan="5" class="border-0"></td>
                        <td class="text-end border-0">Grand Total</td>
                        <td class="text-end border-0 text-primary">$<?php echo number_format($grand_total, 2);?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="border-0"></td>
                        <td class="text-end border-0">Total Profit</td>
                        <td class="text-end border-0 profit-text">$<?php echo number_format($total_profit, 2);?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-end no-print">
            <button class="btn btn-outline-secondary me-2" onclick="window.print()">
                Export PDF
            </button>
            <button class="btn btn-primary px-4" onclick="window.print()">
                Print Report
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