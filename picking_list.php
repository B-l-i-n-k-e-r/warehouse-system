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
            --primary-color: #1a1d21;
            --accent-color: #4f46e5;
            --border-color: #e2e8f0;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            padding: 20px; 
            color: var(--primary-color);
            line-height: 1.4;
            background-color: #f1f5f9;
        }
        .paper {
            background: #fff;
            max-width: 850px;
            margin: 0 auto;
            padding: 50px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
            border-top: 8px solid var(--accent-color);
        }
        
        /* Barcode Simulation */
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 45px;
            margin-top: -10px;
        }

        .header-flex { 
            display: flex; 
            justify-content: space-between; 
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title-area h1 { margin: 0; font-size: 32px; font-weight: 900; letter-spacing: -1px; }
        .title-area p { margin: 5px 0 0 0; color: #64748b; font-weight: 600; }

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
        .meta-label { font-size: 10px; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 5px; display: block; }
        .meta-value { font-size: 14px; font-weight: 700; }

        .instruction-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin-bottom: 30px;
            font-size: 12px;
            color: #92400e;
        }

        .pick-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .pick-table th { 
            text-align: left;
            padding: 15px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--primary-color);
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
            border-radius: 6px;
            text-align: center;
        }
        .loc-zone { font-size: 10px; opacity: 0.7; display: block; margin-bottom: 2px; }
        .loc-name { font-size: 22px; font-weight: 900; display: block; }

        .qty-box {
            font-size: 36px;
            font-weight: 900;
            text-align: center;
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 12px;
            margin: 0 auto;
        }

        .check-square {
            width: 40px;
            height: 40px;
            border: 3px solid #cbd5e1;
            border-radius: 8px;
            margin: 0 auto;
        }

        .footer-sig {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .sig-box {
            border-top: 1px solid var(--primary-color);
            padding-top: 10px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .btn-print {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            cursor: pointer;
            z-index: 100;
        }

        @media print {
            body { background: white; padding: 0; }
            .paper { box-shadow: none; max-width: 100%; padding: 0; border: none; }
            .btn-print { display: none; }
            .location-box { border: 2px solid #000; color: #000; background: #fff !important; }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">
        Print Picking Slip
    </button>

    <div class="paper">
        <div class="header-flex">
            <div class="title-area">
                <h1>PICKING SLIP</h1>
                <p>MoonLit Warehouse Management System</p>
            </div>
            <div style="text-align: right;">
                <div class="barcode">SAL<?php echo $sale_data['id']; ?></div>
                <div style="font-size: 12px; font-weight: 700; margin-top: -5px;">#SAL-<?php echo $sale_data['id']; ?></div>
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
                <span class="meta-label">Print Time</span>
                <span class="meta-value"><?php echo date("H:i:s"); ?></span>
            </div>
        </div>

        <div class="instruction-box">
            <strong>ATTENTION PICKER:</strong> Please verify the Bin Location before picking. Scan the item to confirm SKU match. Ensure fragile items are placed at the top of the bin.
        </div>

        <table class="pick-table">
            <thead>
                <tr>
                    <th>Description & Item ID</th>
                    <th width="200" style="text-align: center;">Location</th>
                    <th width="100" style="text-align: center;">Quantity</th>
                    <th width="80" style="text-align: center;">Verified</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-size: 18px; font-weight: 900; margin-bottom: 5px;">
                            <?php echo remove_junk($sale_data['prod_name']); ?>
                        </div>
                        <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; color: #475569;">
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

        <div style="margin-top: 50px; text-align: center;">
            <div class="barcode" style="font-size: 30px; opacity: 0.3;">SAL<?php echo $sale_data['id']; ?></div>
            <p style="font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px;">
                Internal Document - MoonLit Logistics Division
            </p>
        </div>
    </div>

</body>
</html>