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
  body {
    background-color: #0f172a;
    color: #f8fafc;
  }
  .table-edit-sale {
    background-color: transparent !important;
    border: none;
  }
  .table-edit-sale tbody tr {
    background-color: transparent !important;
  }
  .edit-sales-card {
    background: #1e293b;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    border: 1px solid rgba(56, 189, 248, 0.1);
    overflow: hidden;
    margin-top: 20px;
  }
  .card-header-moonlit {
    background: #1e293b;
    color: #38bdf8;
    padding: 25px;
    border-bottom: 2px solid rgba(56, 189, 248, 0.2);
  }
  .table-edit-sale thead th {
    background: transparent;
    color: #38bdf8;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 15px !important;
    border-bottom: 1px solid rgba(56, 189, 248, 0.2) !important;
  }
  .table-edit-sale tbody td {
    border: none !important;
    vertical-align: middle;
    padding: 20px 15px !important;
  }
  .form-control-sale {
    background: #0f172a;
    border: 1px solid #334155;
    color: #f8fafc !important;
    border-radius: 8px;
    height: 45px;
    transition: all 0.3s ease;
  }
  .form-control-sale:focus {
    border-color: #38bdf8;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.2);
    outline: none;
  }
  /* Style for uneditable fields to match your 'readonly' look */
  .form-control-sale[readonly] {
    background: rgba(15, 23, 42, 0.7);
    color: #94a3b8 !important;
    border-color: rgba(51, 65, 85, 0.5);
    cursor: not-allowed;
  }
  .stock-warning {
    background: rgba(245, 158, 11, 0.1);
    color: #fbbf24;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 25px;
    font-size: 13px;
    border-left: 4px solid #f59e0b;
  }
  .btn-moonlit-update {
    background: #38bdf8;
    color: #0f172a;
    border: none;
    font-weight: 800;
    text-transform: uppercase;
    padding: 12px;
    border-radius: 8px;
    transition: 0.3s;
  }
  .btn-moonlit-update:hover {
    background: #7dd3fc;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(56, 189, 248, 0.4);
    color: #0f172a;
  }
  .btn-back-moonlit {
    background: transparent;
    border: 1px solid rgba(56, 189, 248, 0.4);
    color: #38bdf8;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none !important;
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
        <div class="card-header-moonlit clearfix">
          <div class="pull-left">
            <h3 style="margin:0; font-weight:900; letter-spacing:-0.5px;">
              <i class="glyphicon glyphicon-edit" style="margin-right:10px;"></i> PRODUCT MODIFICATION
            </h3>
            <p style="margin:5px 0 0 0; font-size:12px; color:#94a3b8; font-weight:600;">
              REF: SALE #<?php echo (int)$sale['id']; ?> — Editing <?php echo remove_junk($product['name']); ?>
            </p>
          </div>
          <div class="pull-right">
            <a href="sales.php" class="btn btn-back-moonlit btn-sm">
              <i class="glyphicon glyphicon-arrow-left"></i> BACK TO HISTORY
            </a>
          </div>
        </div>

        <div class="panel-body" style="padding:0;">
          <div class="stock-warning">
            <i class="glyphicon glyphicon-flash"></i> <strong>AUTO-SYNC ACTIVE:</strong> Price is locked to product catalog. Changing quantity will recalculate totals.
          </div>

          <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" style="padding: 0 25px 30px 25px;">
            <div class="table-responsive">
              <table class="table table-edit-sale">
                <thead>
                  <tr>
                    <th>Product Title</th>
                    <th style="width: 12%;">Qty</th>
                    <th style="width: 15%;">Unit Price</th>
                    <th style="width: 15%;">Total Sale</th>
                    <th style="width: 20%;">Sale Date</th>
                    <th style="width: 150px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <input type="text" class="form-control form-control-sale" name="title" value="<?php echo remove_junk($product['name']); ?>" readonly>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sale" name="quantity" id="qty" value="<?php echo (int)$sale['qty']; ?>">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sale" name="price" id="price" value="<?php echo remove_junk($product['sale_price']); ?>" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sale" name="total" id="total" value="<?php echo remove_junk($sale['price']); ?>" readonly>
                    </td>
                    <td>
                      <input type="date" class="form-control form-control-sale" name="date" value="<?php echo date('Y-m-d', strtotime($sale['date'])); ?>">
                    </td>
                    <td>
                      <button type="submit" name="update_sale" class="btn btn-moonlit-update btn-block">SAVE CHANGES</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('qty');
    const priceInput = document.getElementById('price');
    const totalInput = document.getElementById('total');

    function updatePrice() {
        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = qty * price;
        totalInput.value = total.toFixed(2);
    }

    // Still triggers on quantity change to update the readonly total
    qtyInput.addEventListener('input', updatePrice);
});
</script>

<?php include_once('layouts/footer.php'); ?>