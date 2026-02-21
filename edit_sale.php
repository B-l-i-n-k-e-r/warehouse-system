<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  page_require_level(3);

  $sale = find_by_id('sales',(int)$_GET['id']);
  if(!$sale){
    $session->msg("d","Missing transaction id.");
    redirect('sales.php');
  }
  
  $product = find_by_id('products',$sale['product_id']);

  if(isset($_POST['update_sale'])){
    $req_fields = array('title','quantity','price','total', 'date' );
    validate_fields($req_fields);
    
    if(empty($errors)){
      $p_id      = $db->escape((int)$product['id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      $date      = $db->escape($_POST['date']);
      $s_date    = date("Y-m-d", strtotime($date));

      $sql  = "UPDATE sales SET";
      $sql .= " product_id= '{$p_id}',qty={$s_qty},price='{$s_total}',date='{$s_date}'";
      $sql .= " WHERE id ='{$sale['id']}'";
      
      $result = $db->query($sql);
      if( $result && $db->affected_rows() === 1){
        // This function should handle the difference between old and new qty
        update_product_qty($s_qty, $p_id);
        $session->msg('s',"Transaction updated successfully.");
        redirect('sales.php', false);
      } else {
        $session->msg('d',' No changes were made or update failed.');
        redirect('sales.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_sale.php?id='.(int)$sale['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .edit-sales-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: none;
    overflow: hidden;
  }
  .card-header-indigo {
    background: #6366f1;
    color: white;
    padding: 20px 25px;
  }
  .table-edit-sale thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    padding: 15px !important;
  }
  .form-control-sale {
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    box-shadow: none;
    height: 38px;
  }
  .stock-warning {
    background: #fffbeb;
    color: #92400e;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
    border-left: 4px solid #f59e0b;
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
      <div class="edit-sales-card">
        <div class="card-header-indigo clearfix">
          <div class="pull-left">
            <h3 style="margin:0; font-weight:700;">
              <i class="glyphicon glyphicon-edit"></i> Edit Transaction: #<?php echo (int)$sale['id']; ?>
            </h3>
            <small style="opacity:0.9">Adjusting sales record for <?php echo remove_junk($product['name']); ?></small>
          </div>
          <div class="pull-right">
            <a href="sales.php" class="btn btn-default btn-sm" style="border-radius:6px;">Back to Sales History</a>
          </div>
        </div>

        <div class="panel-body">
          <div class="stock-warning">
            <i class="glyphicon glyphicon-info-sign"></i> <strong>Note:</strong> Changing the quantity will automatically adjust the inventory stock levels for this product.
          </div>

          <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
            <div class="table-responsive">
              <table class="table table-edit-sale">
                <thead>
                  <tr>
                    <th>Product Title</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Unit Price</th>
                    <th style="width: 15%;">Total Sale</th>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 150px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <input type="text" class="form-control form-control-sale" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>" readonly>
                      <div id="result" class="list-group"></div>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sale" name="quantity" value="<?php echo (int)$sale['qty']; ?>">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sale" name="price" value="<?php echo remove_junk($product['sale_price']); ?>" >
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sale" name="total" value="<?php echo remove_junk($sale['price']); ?>">
                    </td>
                    <td>
                      <input type="date" class="form-control form-control-sale" name="date" value="<?php echo date('Y-m-d', strtotime($sale['date'])); ?>">
                    </td>
                    <td>
                      <button type="submit" name="update_sale" class="btn btn-primary btn-block" style="background:#6366f1; border:none; font-weight:600;">Update Sale</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>