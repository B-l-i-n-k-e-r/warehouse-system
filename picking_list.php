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
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 40px; 
            color: #333;
            line-height: 1.6;
        }
        .header-table { width: 100%; border-bottom: 3px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: 900; letter-spacing: 1px; }
        .order-ref { font-size: 16px; color: #666; }
        
        .instruction-alert {
            background: #f8f9fa;
            border: 1px dashed #ccc;
            padding: 15px;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .pick-table { width: 100%; border-collapse: collapse; }
        .pick-table th { 
            background: #333; 
            color: #fff; 
            text-transform: uppercase; 
            font-size: 12px; 
            padding: 12px;
            text-align: left;
        }
        .pick-table td { 
            border: 1px solid #eee; 
            padding: 20px 12px; 
            vertical-align: middle;
        }

        .location-tag {
            background: #000;
            color: #fff;
            padding: 8px 12px;
            display: inline-block;
            font-size: 20px;
            font-weight: 800;
            border-radius: 4px;
        }
        .zone-label {
            display: block;
            font-size: 10px;
            color: #d1d5db;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        
        .qty-circle {
            width: 50px;
            height: 50px;
            border: 3px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            margin: 0 auto;
        }

        .check-box {
            width: 40px;
            height: 40px;
            border: 2px solid #333;
            margin: 0 auto;
        }

        .footer { margin-top: 60px; display: flex; justify-content: space-between; }
        .sig-line { border-top: 1px solid #333; width: 250px; text-align: center; padding-top: 5px; font-size: 12px; }

        .btn-print {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            float: right;
        }

        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
            .location-tag { border: 1px solid #000; color: #000; background: #fff; }
            .zone-label { color: #555; }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Print Picking Slip
    </button>

    <table class="header-table">
        <tr>
            <td class="title">PICKING SLIP</td>
            <td align="right" class="order-ref">
                <strong>REF:</strong> #SAL-<?php echo $sale_data['id']; ?><br>
                <strong>DATE:</strong> <?php echo date("d M Y", strtotime($sale_data['date'])); ?>
            </td>
        </tr>
    </table>

    <div class="instruction-alert">
        <strong>PICKER INSTRUCTION:</strong> Verify Item Name and SKU before pulling. Ensure packaging is intact. If stock is insufficient, notify the supervisor immediately.
    </div>

    <table class="pick-table">
        <thead>
            <tr>
                <th>Product Information</th>
                <th width="30%">Storage Location</th>
                <th width="15%" style="text-align: center;">Pick Qty</th>
                <th width="10%" style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-size: 18px; font-weight: 700;"><?php echo remove_junk($sale_data['prod_name']); ?></div>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">ID: <?php echo (int)$sale_data['product_id']; ?></div>
                </td>
                <td>
                    <div class="location-tag">
                        <span class="zone-label">Zone: <?php echo $sale_data['zone'] ? $sale_data['zone'] : 'N/A'; ?></span>
                        <?php echo $sale_data['location_name'] ? strtoupper($sale_data['location_name']) : "UNASSIGNED"; ?>
                    </div>
                </td>
                <td>
                    <div class="qty-circle">
                        <?php echo $sale_data['qty']; ?>
                    </div>
                </td>
                <td>
                    <div class="check-box"></div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="sig-line">
            Warehouse Picker Signature
        </div>
        <div class="sig-line">
            Quality Assurance Check
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px; color: #ccc; font-size: 10px;">
        End of Picking Slip #SAL-<?php echo $sale_data['id']; ?>
    </div>

</body>
</html>