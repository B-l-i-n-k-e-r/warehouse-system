<?php
  $page_title = 'All Sales Report';
  require_once('includes/load.php');
  page_require_level(3);

  date_default_timezone_set('Africa/Nairobi'); 

  $sql  = "SELECT s.date, s.qty, p.name, l.location_name, l.zone ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= "ORDER BY s.date DESC";
  $results = find_by_sql($sql);

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
            max-width: 1200px;
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
            padding: 25px 40px;
            background: rgba(15, 23, 42, 0.6);
            border-bottom: 1px solid var(--border);
        }

        .brand-logo { font-size: 22px; font-weight: 900; letter-spacing: -1px; }
        .brand-logo span { color: var(--primary); }

        .nav-actions { display: flex; gap: 15px; align-items: center; }

        .btn-back {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 15px;
            border: 1px solid transparent;
            transition: 0.3s;
        }
        .btn-back:hover {
            color: var(--primary);
            border-color: var(--border);
            border-radius: 6px;
            background: rgba(56, 189, 248, 0.05);
        }

        .btn-print {
            background: var(--primary);
            color: var(--dark-bg);
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(56, 189, 248, 0.3); }

        .header-main { padding: 40px; }
        .header-main h1 { 
            margin: 0; 
            font-size: 42px; 
            font-weight: 900; 
            text-transform: uppercase;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        table { width: 100%; border-collapse: collapse; }
        
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
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 14px;
            white-space: nowrap; 
        }

        td:nth-child(2) { white-space: normal; font-weight: 600; color: #fff; }

        .loc-tag {
            font-size: 10px;
            font-weight: 800;
            color: var(--primary);
            background: rgba(56, 189, 248, 0.1);
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            text-transform: uppercase;
        }

        .qty-badge {
            font-size: 16px;
            font-weight: 900;
            color: var(--accent);
        }

        .summary-banner {
            background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border);
        }

        .stat-group label { color: var(--text-dim); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px; }
        .stat-group span { font-size: 28px; font-weight: 900; }

        @media print { 
            .no-print { display: none !important; } 
            body { background: white; color: black; padding: 0; }
            .report-container { border: none; box-shadow: none; background: white; }
            th { background: #f1f5f9 !important; color: black !important; border-bottom: 2px solid black; }
            td { border-bottom: 1px solid #e2e8f0; color: black !important; }
            td:nth-child(2) { color: black !important; }
            .loc-tag { border: 1px solid black; color: black; background: none; }
            .summary-banner { border-top: 2px solid black; background: white !important; color: black !important; }
            .qty-badge { color: black !important; }
            .header-main h1 { -webkit-text-fill-color: black; }
        }
    </style>
</head>
<body>

    <div class="report-container">
        <nav class="wms-navbar no-print">
            <div class="brand-logo">MOONLIT <span>WMS</span></div>
            <div class="nav-actions">
                <a href="sales.php" class="btn-back">
                    <i class="glyphicon glyphicon-menu-left"></i> Go Back
                </a>
                <button class="btn-print" onclick="window.print()">
                    Export/Print
                </button>
            </div>
        </nav>

        <div class="header-main">
            <h1>Master Sales Report</h1>
            <p style="color:var(--text-dim); font-weight: 500;">
                Log Sequence: 001 — <span style="color:var(--primary)"><?php echo date("d F Y, H:i"); ?></span>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Item Specification</th>
                    <th>Logistics Hub</th>
                    <th style="text-align: center;">Units</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_qty = 0;
                foreach($results as $res): 
                    $total_qty += $res['qty'];
                ?>
                <tr>
                    <td><?php echo date("d.m.y", strtotime($res['date'])); ?></td>
                    <td><?php echo remove_junk($res['name']); ?></td>
                    <td>
                        <span class="loc-tag">
                            <?php echo remove_junk($res['location_name']); ?> // <?php echo strtoupper($res['zone']); ?>
                        </span>
                    </td>
                    <td style="text-align: center;" class="qty-badge">
                        <?php echo $res['qty']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary-banner">
            <div class="stat-group">
                <label>Data Points</label>
                <span><?php echo count($results); ?> Transactions</span>
            </div>
            <div class="stat-group" style="text-align: right;">
                <label>Inventory Out-Stock</label>
                <span style="color:var(--primary);"><?php echo number_format($total_qty); ?> <small style="font-size:14px">Units</small></span>
            </div>
        </div>
    </div>

</body>
</html>