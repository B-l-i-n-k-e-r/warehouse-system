<?php
  $page_title = 'Location Sales Report';
  require_once('includes/load.php');
  page_require_level(3);

  // Timezone set to Africa (Nairobi)
  date_default_timezone_set('Africa/Nairobi'); 

  $location_id = (int)$_GET['location_id'];
  
  $sql  = "SELECT s.date, s.qty, p.name, l.location_name, l.zone ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "WHERE l.id = '{$db->escape($location_id)}' ";
  $sql .= "ORDER BY s.date DESC";
  $results = find_by_sql($sql);
  
  $loc_info = find_by_id('locations', $location_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOONLIT WMS - <?php echo $loc_info['location_name']; ?></title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #e0e7ff;
            --dark: #0f172a;
            --slate: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
        }
        
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            color: var(--dark);
            background-color: #fff;
            margin: 0;
            padding: 40px;
            -webkit-print-color-adjust: exact;
        }

        /* Nav & Brand */
        .wms-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
        }

        .brand-logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--slate);
        }

        .brand-logo span {
            color: var(--dark);
            border-left: 3px solid var(--primary);
            padding-left: 10px;
            margin-left: 10px;
        }

        /* Dashboard Header */
        .header-main h1 { 
            margin: 0 0 25px 0; 
            font-size: 28px; 
            font-weight: 900;
            letter-spacing: -0.05em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: var(--primary);
        }

        .stat-card small {
            display: block;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .stat-card .val {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        /* Modern Table */
        table { 
            width: 100%; 
            border-collapse: collapse;
        }

        th { 
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate);
            padding: 15px;
            border-bottom: 2px solid var(--dark);
        }

        td { 
            padding: 20px 15px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .qty-box {
            background: var(--dark);
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
        }

        .check-square {
            width: 24px;
            height: 24px;
            border: 2px solid var(--border);
            border-radius: 6px;
            margin: 0 auto;
        }

        /* Summary */
        .summary-banner {
            margin-top: 30px;
            background: var(--dark);
            color: white;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-label { opacity: 0.7; font-size: 13px; font-weight: 500; }
        .summary-total { font-size: 24px; font-weight: 800; }

        /* Signatures */
        .signature-grid {
            margin-top: 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
        }

        .sig-field {
            border-top: 1px solid var(--dark);
            padding-top: 10px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--slate);
            text-transform: uppercase;
        }

        /* Buttons */
        .btn-action {
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; }
            .stat-card { border: 1px solid #eee; background: none; }
            .summary-banner { border: 1px solid #000; background: #000 !important; color: #fff !important; }
        }
    </style>
</head>
<body>

    <nav class="wms-navbar no-print">
        <div class="brand-logo">MOONLIT <span>WMS</span></div>
        <button class="btn-action" onclick="window.print()">Generate Printout</button>
    </nav>

    <div class="header-main">
        <h1>Picking List Details</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <small>Location Identity</small>
                <div class="val"><?php echo remove_junk($loc_info['location_name']); ?></div>
            </div>
            <div class="stat-card">
                <small>Assigned Zone</small>
                <div class="val">WH-<?php echo strtoupper($loc_info['zone']); ?></div>
            </div>
            <div class="stat-card">
                <small>Time</small>
                <div class="val"><?php echo date("d M Y, H:i"); ?></div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Log Date</th>
                <th>Item & Specification</th>
                <th width="15%" style="text-align: center;">Pick Qty</th>
                <th width="10%" style="text-align: center;">Done</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_qty = 0;
            foreach($results as $res): 
                $total_qty += $res['qty'];
            ?>
            <tr>
                <td style="color: var(--slate); font-weight: 500;">
                    <?php echo date("d/m/Y", strtotime($res['date'])); ?>
                </td>
                <td>
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">
                        <?php echo remove_junk($res['name']); ?>
                    </div>
                    <div style="font-size: 11px; color: var(--primary); font-weight: 700; text-transform: uppercase;">
                        System ID: <?php echo str_pad($res['qty'], 5, '0', STR_PAD_LEFT); ?>
                    </div>
                </td>
                <td style="text-align: center;">
                    <span class="qty-box"><?php echo $res['qty']; ?></span>
                </td>
                <td>
                    <div class="check-square"></div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary-banner">
        <div>
            <div class="summary-label">Workload Finalization</div>
            <div style="font-size: 14px;">Please verify all items before signing.</div>
        </div>
        <div style="text-align: right;">
            <div class="summary-label">Grand Total</div>
            <div class="summary-total"><?php echo $total_qty; ?> Units</div>
        </div>
    </div>

    <div class="signature-grid">
        <div class="sig-field">Warehouse Personnel</div>
        <div class="sig-field">Audit / Manager</div>
    </div>

</body>
</html>