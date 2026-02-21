<?php
  $page_title = 'Location Sales Report';
  require_once('includes/load.php');
  page_require_level(3);

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
    <title>Picking List - <?php echo $loc_info['location_name']; ?></title>
    <style>
        body { 
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
            color: #000;
            margin: 0;
            padding: 40px;
        }
        .header { 
            text-align: center; 
            border-bottom: 3px solid #333; 
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 { 
            text-transform: uppercase; 
            margin: 0; 
            font-size: 28px; 
            letter-spacing: 2px;
        }
        .loc-info-banner {
            background: #f0f0f0;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid #ccc;
            display: inline-block;
            min-width: 300px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 30px; 
        }
        th { 
            background: #333; 
            color: #fff; 
            padding: 12px; 
            text-align: left; 
            font-size: 13px;
        }
        td { 
            border-bottom: 1px solid #000; 
            padding: 12px; 
            font-size: 14px; 
        }
        .total-row {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            font-size: 18px;
        }
        .footer-sig {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .sig-box {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-size: 12px;
        }
        .no-print {
            background: #6366f1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-weight: bold;
        }
        @media print { 
            .no-print { display: none; } 
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print();">

    <button class="no-print" onclick="window.print()">Print Document</button>

    <div class="header">
        <h1>Sales Picking List</h1>
        <div class="loc-info-banner">
            <strong>BIN LOCATION:</strong> <?php echo remove_junk($loc_info['location_name']); ?><br>
            <strong>WH ZONE:</strong> <?php echo strtoupper($loc_info['zone']); ?>
        </div>
        <p style="margin-top: 15px;">Generated: <?php echo date("F j, Y, g:i a"); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20%">Transaction Date</th>
                <th>Product Description</th>
                <th width="15%" style="text-align: center;">Qty to Pick</th>
                <th width="10%">Verified</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_qty = 0;
            foreach($results as $res): 
                $total_qty += $res['qty'];
            ?>
            <tr>
                <td><?php echo date("m/d/Y", strtotime($res['date'])); ?></td>
                <td><strong><?php echo remove_junk($res['name']); ?></strong></td>
                <td style="text-align: center; font-size: 18px;">[ <?php echo $res['qty']; ?> ]</td>
                <td>[ &nbsp;&nbsp; ]</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-row">
        Total Units for This Location: <?php echo $total_qty; ?>
    </div>

    <div class="footer-sig">
        <div class="sig-box">Warehouse Picker Signature</div>
        <div class="sig-box">Manager Verification</div>
    </div>

</body>
</html>