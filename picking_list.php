<?php
  $page_title = 'Warehouse Picking List';
  require_once('includes/load.php');
  page_require_level(3);

  $sale_id = (int)$_GET['id'];
  $sql  = "SELECT s.*, p.name as prod_name, l.location_name, l.zone ";
  $sql .= " FROM sales s ";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= " LEFT JOIN locations l ON p.location_id = l.id ";
  $sql .= " WHERE s.id = '{$sale_id}' LIMIT 1";
  $result = $db->query($sql);
  $sale_data = $db->fetch_assoc($result);

  if(!$sale_data){
    $session->msg("d","Sale record not found.");
    redirect('sales.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pick List #<?php echo $sale_data['id']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e293b;
            --accent-color: #38bdf8; /* Neon Blue */
            --border-color: #e2e8f0;
            --success-green: #10b981;
            --dark-bg: #0f172a;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            padding: 40px 20px; 
            color: var(--primary-color);
            line-height: 1.4;
            background-color: var(--dark-bg);
        }
        .paper {
            background: #fff;
            max-width: 850px;
            margin: 0 auto;
            padding: 50px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
            border-top: 8px solid var(--accent-color);
            border-radius: 4px;
        }
        
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 55px;
            margin-top: -10px;
            color: #000;
        }

        .header-flex { 
            display: flex; 
            justify-content: space-between; 
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title-area h1 { margin: 0; font-size: 38px; font-weight: 900; letter-spacing: -1.5px; }
        .title-area p { margin: 5px 0 0 0; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .meta-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        .meta-label { font-size: 10px; text-transform: uppercase; color: #64748b; font-weight: 800; margin-bottom: 5px; display: block; }
        .meta-value { font-size: 14px; font-weight: 800; color: #1e293b; }

        .instruction-box {
            background: #f0f9ff;
            border-left: 5px solid var(--accent-color);
            padding: 15px 20px;
            margin-bottom: 30px;
            font-size: 13px;
            color: #0369a1;
            font-weight: 500;
        }

        .pick-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .pick-table th { 
            text-align: left;
            padding: 15px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--primary-color);
            background: #f8fafc;
        }
        .pick-table td { 
            padding: 25px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .location-box {
            background: var(--primary-color);
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }
        .loc-zone { font-size: 11px; font-weight: 800; color: var(--accent-color); display: block; margin-bottom: 2px; }
        .loc-name { font-size: 24px; font-weight: 900; display: block; letter-spacing: 1px; }

        .qty-box {
            font-size: 42px;
            font-weight: 900;
            text-align: center;
            color: #000;
            border: 4px solid #000;
            width: 80px;
            height: 80px;
            line-height: 72px;
            border-radius: 15px;
            margin: 0 auto;
        }

        .check-square {
            width: 45px;
            height: 45px;
            border: 3px solid #cbd5e1;
            border-radius: 10px;
            margin: 0 auto;
        }

        .footer-sig {
            margin-top: 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .sig-box {
            border-top: 2px solid var(--primary-color);
            padding-top: 10px;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
        }

        /* Fixed Navigation Buttons */
        .nav-controls {
            position: fixed;
            top: 30px;
            left: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        .btn-action {
            text-decoration: none !important;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        /* Matches your Product Modification / Sales Header Style */
        .btn-back { 
            background: #1e293b; 
            color: #fff; 
            border: 1px solid rgba(56, 189, 248, 0.2); 
        }
        .btn-back:hover { 
            background: #334155; 
            transform: translateX(-5px); 
            border-color: var(--accent-color); 
            color: var(--accent-color); 
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
        }
        
        .btn-print { 
            background: var(--accent-color); 
            color: #000; 
        }
        .btn-print:hover { 
            background: #7dd3fc; 
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.6);
        }

        @media print {
            body { background: white; padding: 0; }
            .paper { box-shadow: none; max-width: 100%; padding: 0; border: none; margin: 0; }
            .nav-controls { display: none; }
            .location-box { border: 2px solid #000; color: #000; background: #fff !important; }
            .loc-zone { color: #000; }
        }
    </style>
</head>
<body>

    <div class="nav-controls">
        <a href="sales.php" class="btn-action btn-back">
            <i class="glyphicon glyphicon-arrow-left"></i> BACK TO SALES
        </a>
        <button class="btn-action btn-print" onclick="window.print()">
            <i class="glyphicon glyphicon-print"></i> PRINT PICKING SLIP
        </button>
    </div>

    <div class="paper">
        <div class="header-flex">
            <div class="title-area">
                <h1>PICKING SLIP</h1>
                <p>MoonLit Warehouse Management System</p>
            </div>
            <div style="text-align: right;">
                <div class="barcode">SAL<?php echo $sale_data['id']; ?></div>
                <div style="font-size: 13px; font-weight: 900; margin-top: -5px; color: var(--primary-color);">REF: #SAL-<?php echo $sale_data['id']; ?></div>
            </div>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">Order Date</span>
                <span class="meta-value"><?php echo date("F j, Y", strtotime($sale_data['date'])); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Picked By</span>
                <span class="meta-value">____________________</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">System Print Time</span>
                <span class="meta-value"><?php echo date("H:i:s"); ?></span>
            </div>
        </div>

        <div class="instruction-box">
            <strong>PICKER INSTRUCTIONS:</strong> 1. Verify Bin Location below. 2. Cross-check SKU ending in <strong><?php echo (int)$sale_data['product_id']; ?></strong>. 3. Mark the "Verified" box only after the item is physically in your cart.
        </div>

        <table class="pick-table">
            <thead>
                <tr>
                    <th>Description & Item ID</th>
                    <th width="220" style="text-align: center;">Location / Bin</th>
                    <th width="120" style="text-align: center;">Qty to Pick</th>
                    <th width="100" style="text-align: center;">Verified</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-size: 22px; font-weight: 900; margin-bottom: 8px; color: #000;">
                            <?php echo remove_junk($sale_data['prod_name']); ?>
                        </div>
                        <span style="background: #000; padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 800; color: var(--accent-color);">
                            SKU-<?php echo (int)$sale_data['product_id']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="location-box">
                            <span class="loc-zone">ZONE <?php echo $sale_data['zone'] ? $sale_data['zone'] : 'N/A'; ?></span>
                            <span class="loc-name"><?php echo $sale_data['location_name'] ? strtoupper($sale_data['location_name']) : "OFF-BIN"; ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="qty-box">
                            <?php echo $sale_data['qty']; ?>
                        </div>
                    </td>
                    <td>
                        <div class="check-square"></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-sig">
            <div class="sig-box">
                Authorized Picker Signature
            </div>
            <div class="sig-box">
                Dispatcher / QC Signature
            </div>
        </div>

        <div style="margin-top: 70px; text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 20px;">
            <div class="barcode" style="font-size: 35px; opacity: 0.2;">SAL<?php echo $sale_data['id']; ?></div>
            <p style="font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 3px; font-weight: 700;">
                Internal Document - MoonLit Logistics Division
            </p>
        </div>
    </div>

</body>
</html>