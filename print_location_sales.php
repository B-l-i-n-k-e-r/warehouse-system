<?php
  $page_title = 'Location Sales Report';
  require_once('includes/load.php');
  page_require_level(3);

  // Timezone set to Africa (Nairobi)
  date_default_timezone_set('Africa/Nairobi'); 

  $location_id = (int)$_GET['location_id'];
  
  // 1. First, verify the location actually exists
  $loc_info = find_by_id('locations', $location_id);
  if(!$loc_info){
    $session->msg('d', "Error: Warehouse location not found in the system.");
    redirect('sales.php', false);
  }

  // 2. Fetch sales for this specific location
  $sql  = "SELECT s.date, s.qty, p.name, l.location_name, l.zone ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "WHERE l.id = '{$db->escape($location_id)}' ";
  $sql .= "ORDER BY s.date DESC";
  $results = find_by_sql($sql);

  // 3. Validation: If no sales are found, notify the user and redirect
  if(empty($results)){
    $session->msg('w', "No sales records found for: " . strtoupper($loc_info['location_name']));
    redirect('sales.php', false);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOONLIT WMS - <?php echo $loc_info['location_name']; ?></title>
    <style>
        :root {
            --primary: #38bdf8; 
            --accent: #22c55e;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --border: rgba(56, 189, 248, 0.2);
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            color: var(--text-main);
            margin: 0;
            padding: 40px;
            background-color: var(--dark-bg);
            background-image: radial-gradient(circle at 2px 2px, rgba(56, 189, 248, 0.05) 1px, transparent 0);
            background-size: 40px 40px;
        }

        .report-container {
            max-width: 1100px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .wms-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(15, 23, 42, 0.6);
            border-bottom: 1px solid var(--border);
        }

        .brand-logo { font-size: 20px; font-weight: 900; letter-spacing: -1px; }
        .brand-logo span { color: var(--primary); }

        .nav-actions { display: flex; gap: 15px; }

        .btn-terminal {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 10px 15px;
            border: 1px solid transparent;
            transition: 0.3s;
        }
        .btn-terminal:hover { color: var(--primary); background: rgba(56, 189, 248, 0.05); border-radius: 6px; }

        .btn-print {
            background: var(--primary);
            color: var(--dark-bg);
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
        }

        .header-main { padding: 40px 40px 20px 40px; }
        .header-main h1 { 
            margin: 0 0 30px 0; 
            font-size: 32px; 
            font-weight: 900; 
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.4);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .stat-card small {
            display: block;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .stat-card div { font-size: 18px; font-weight: 700; color: #fff; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        
        th { 
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--primary);
            background: rgba(56, 189, 248, 0.05);
            padding: 20px;
            border-bottom: 2px solid var(--border);
            white-space: nowrap; 
            width: 1%; 
        }

        th:nth-child(2) { width: auto; white-space: normal; }

        td { 
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 14px;
            white-space: nowrap; 
        }

        td:nth-child(2) { white-space: normal; font-weight: 600; color: #fff; }

        .qty-box {
            background: var(--primary);
            color: var(--dark-bg);
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 900;
            font-size: 16px;
        }

        .check-square {
            width: 22px;
            height: 22px;
            border: 2px solid var(--border);
            border-radius: 4px;
            margin: 0 auto;
        }

        .summary-banner {
            background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border);
        }

        .summary-total { font-size: 24px; font-weight: 900; color: var(--primary); }

        .signature-grid {
            padding: 60px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
        }

        .sig-field {
            border-top: 1px solid var(--border);
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-dim);
            letter-spacing: 1px;
        }

        @media print { 
            .no-print { display: none !important; } 
            body { background: white; color: black; padding: 0; }
            .report-container { border: none; box-shadow: none; background: white; max-width: 100%; }
            .stat-card { border: 1px solid black; background: none; }
            .stat-card div, td:nth-child(2) { color: black !important; }
            th { background: #f1f5f9 !important; color: black !important; border-bottom: 2px solid black; }
            td { border-bottom: 1px solid #e2e8f0; color: black !important; }
            .qty-box { background: none; border: 1px solid black; color: black; }
            .check-square { border: 2px solid black; }
            .summary-banner { border-top: 2px solid black; background: white !important; color: black !important; }
            .signature-grid { padding-top: 100px; }
            .sig-field { border-top: 1px solid black; color: black; }
        }
    </style>
</head>
<body>

    <div class="report-container">
        <nav class="wms-navbar no-print">
            <div class="brand-logo">MOONLIT <span>WMS</span></div>
            <div class="nav-actions">
                <a href="sales.php" class="btn-terminal">Go Back</a>
                <button class="btn-print" onclick="window.print()">Print</button>
            </div>
        </nav>

        <div class="header-main">
            <h1>Picking List Details</h1>
            <div class="stats-grid">
                <div class="stat-card">
                    <small>Logistics Hub</small>
                    <div><?php echo remove_junk($loc_info['location_name']); ?></div>
                </div>
                <div class="stat-card">
                    <small>Assigned Zone</small>
                    <div><?php echo strtoupper($loc_info['zone']); ?></div>
                </div>
                <div class="stat-card">
                    <small>Sync Time</small>
                    <div><?php echo date("d.m.y // H:i"); ?></div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Log Date</th>
                    <th>Item & Specification</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: center;">Verified</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_qty = 0;
                foreach($results as $res): 
                    $total_qty += $res['qty'];
                ?>
                <tr>
                    <td style="color: var(--text-dim); font-weight: 500;">
                        <?php echo date("d/m/Y", strtotime($res['date'])); ?>
                    </td>
                    <td>
                        <div style="font-size: 16px; margin-bottom: 4px;">
                            <?php echo remove_junk($res['name']); ?>
                        </div>
                        <div style="font-size: 10px; color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                            SKU REF: <?php echo str_pad($res['qty'] + $location_id, 8, '0', STR_PAD_LEFT); ?>
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
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--text-dim); margin-bottom: 5px;">Workload Status</div>
                <div style="font-size: 14px; font-weight: 600;">Confirm physical stock before dispatch.</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--text-dim); margin-bottom: 5px;">Out-Stock Total</div>
                <div class="summary-total"><?php echo $total_qty; ?> Units</div>
            </div>
        </div>

        <div class="signature-grid">
            <div class="sig-field">Warehouse Personnel</div>
            <div class="sig-field">Quality Audit / Manager</div>
        </div>
    </div>

</body>
</html>