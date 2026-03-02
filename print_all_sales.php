<?php
  $page_title = 'All Sales Report';
  require_once('includes/load.php');
  page_require_level(3);

  // Timezone set to Africa (Nairobi)
  date_default_timezone_set('Africa/Nairobi'); 

  // SQL to fetch ALL sales
  $sql  = "SELECT s.date, s.qty, p.name, l.location_name, l.zone ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "ORDER BY s.date DESC";
  $results = find_by_sql($sql);

  // Validation: If no sales exist at all, notify and redirect
  if(empty($results)){
    $session->msg('d', "No sales records available.");
    redirect('sales.php', false);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOONLIT WMS - Master Sales Report</title>
    <style>
        :root {
            --primary: #6366f1;
            --dark: #0f172a;
            --slate: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            color: var(--dark);
            margin: 0;
            padding: 40px;
            -webkit-print-color-adjust: exact;
        }

        .wms-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
        }

        .brand-logo { font-size: 26px; font-weight: 800; color: var(--slate); }
        .brand-logo span { color: var(--dark); border-left: 3px solid var(--primary); padding-left: 10px; margin-left: 10px; }

        .header-main h1 { margin: 0 0 25px 0; font-size: 28px; font-weight: 900; }

        /* Table styling to fit content */
        table { width: 100%; border-collapse: collapse; }
        
        th { 
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--slate);
            padding: 15px;
            border-bottom: 2px solid var(--dark);
            white-space: nowrap; 
            width: 1%; /* Forces column to fit content */
        }

        /* Let the Item Description column take up remaining space */
        th:nth-child(2) { width: auto; white-space: normal; }

        td { 
            padding: 15px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            white-space: nowrap; 
        }

        td:nth-child(2) { white-space: normal; }

        .loc-tag {
            font-size: 10px;
            font-weight: 700;
            background: var(--bg);
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        .summary-banner {
            margin-top: 30px;
            background: var(--dark);
            color: white;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
        }

        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <nav class="wms-navbar no-print">
        <div class="brand-logo">MOONLIT <span>WMS</span></div>
        <button style="background:var(--primary); color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;" onclick="window.print()">Print Master List</button>
    </nav>

    <div class="header-main">
        <h1>Master Sales Report</h1>
        <p style="color:var(--slate)">Comprehensive log of all transactions across all locations.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Item Description</th>
                <th>Location</th>
                <th style="text-align: center;">Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_qty = 0;
            foreach($results as $res): 
                $total_qty += $res['qty'];
            ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($res['date'])); ?></td>
                <td><strong><?php echo remove_junk($res['name']); ?></strong></td>
                <td>
                    <span class="loc-tag">
                        <?php echo remove_junk($res['location_name']); ?> (<?php echo strtoupper($res['zone']); ?>)
                    </span>
                </td>
                <td style="text-align: center; font-weight:700;"><?php echo $res['qty']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary-banner">
        <div>Total Recorded Transactions: <?php echo count($results); ?></div>
        <div style="font-weight:800;">Grand Total: <?php echo $total_qty; ?> Units</div>
    </div>

</body>
</html>