<?php
  $page_title = 'All sale';
  require_once('includes/load.php');
  page_require_level(3);
?>
<?php
 $sales = find_all_sale();
 $locations = find_all_locations(); 
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .sales-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 30px;
  }
  .sales-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
  }
  .sales-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
  }
  .table-sales thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 15px !important;
    border: none !important;
  }
  .table-sales tbody td {
    padding: 15px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .loc-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }
  .price-text {
    font-weight: 700;
    color: #0f172a;
  }
  .date-text {
    color: #94a3b8;
    font-size: 12px;
  }
  .btn-sale-action {
    width: 30px;
    height: 30px;
    line-height: 30px;
    padding: 0;
    border-radius: 6px;
    margin: 0 2px;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="sales-card">
        <div class="sales-header">
          <h2><i class="glyphicon glyphicon-list-alt" style="color: #6366f1; margin-right: 10px;"></i> Sales Transaction History</h2>
          
          <div class="actions">
            <div class="btn-group">
              <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" style="border-radius: 6px; font-weight:600;">
                <i class="glyphicon glyphicon-print"></i> Print by Location <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <?php foreach($locations as $loc): ?>
                  <li><a href="print_location_sales.php?location_id=<?php echo (int)$loc['id'];?>" target="_blank">
                    <i class="glyphicon glyphicon-map-marker"></i> <?php echo remove_junk($loc['location_name']); ?>
                  </a></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <a href="add_sale.php" class="btn btn-primary btn-sm" style="background:#6366f1; border:none; border-radius:6px; font-weight:600; margin-left:5px;">
              <i class="glyphicon glyphicon-plus"></i> New Sale
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-sales table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Product Details</th>
                  <th class="text-center">Source Bin</th> 
                  <th class="text-center">Qty</th>
                  <th class="text-center">Total Value</th>
                  <th class="text-center">Transaction Date</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sales as $sale):?>
                <tr>
                  <td class="text-center text-muted"><?php echo count_id();?></td>
                  <td>
                    <div style="font-weight: 600; color: #334155;"><?php echo remove_junk($sale['name']); ?></div>
                  </td>
                  <td class="text-center">
                    <span class="loc-badge">
                      <i class="glyphicon glyphicon-map-marker"></i>
                      <?php echo $sale['location_name'] ? remove_junk($sale['location_name']) : 'N/A'; ?>
                    </span>
                  </td>
                  <td class="text-center" style="font-weight: 600;"><?php echo (int)$sale['qty']; ?></td>
                  <td class="text-center price-text">
                    $<?php echo number_format((float)$sale['price'], 2); ?>
                  </td>
                  <td class="text-center date-text">
                    <?php echo read_date($sale['date']); ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="picking_list.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-default btn-sale-action" title="Pick List" target="_blank">
                        <i class="glyphicon glyphicon-print"></i>
                      </a>
                      <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-warning btn-sale-action" title="Edit">
                        <i class="glyphicon glyphicon-edit"></i>
                      </a>
                      <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-sale-action" title="Delete" onclick="return confirm('Archive this transaction?')">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>