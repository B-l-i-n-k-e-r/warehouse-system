<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  page_require_level(3);

  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);
    
    if(empty($errors)){
      $p_id      = $db->escape((int)$_POST['s_id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      $date      = $db->escape($_POST['date']);
      $s_date    = make_date();

      $sql  = "INSERT INTO sales (product_id, qty, price, date) ";
      $sql .= "VALUES ('{$p_id}', '{$s_qty}', '{$s_total}', '{$s_date}')";

      if($db->query($sql)){
        update_product_qty($s_qty, $p_id);
        $session->msg('s',"Sale completed. Inventory updated.");
        redirect('add_sale.php', false);
      } else {
        $session->msg('d','Error: Could not process transaction.');
        redirect('add_sale.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_sale.php', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .search-container {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
  }
  .search-header { margin-bottom: 20px; color: #334155; }
  .search-header h4 { font-weight: 700; margin-top: 0; }
  
  .sug-input-group .form-control {
    height: 50px;
    font-size: 16px;
    border-radius: 8px 0 0 8px;
    border-right: none;
  }
  .sug-input-group .btn {
    height: 50px;
    border-radius: 0 8px 8px 0;
    padding: 0 25px;
    font-weight: 600;
  }
  
  .transaction-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: none;
    overflow: hidden;
  }
  .table-transaction thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    text-transform: uppercase;
    padding: 15px !important;
    border: none !important;
  }
  #product_info tr td {
    padding: 15px !important;
    vertical-align: middle !important;
  }
  .price-field { font-weight: 700; color: #1e293b; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="search-container">
        <div class="search-header text-center">
          <h4><i class="glyphicon glyphicon-search" style="color: #6366f1;"></i> Find Product for Sale</h4>
          <p class="text-muted">Start typing a name or SKU to begin the transaction</p>
        </div>
        <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
          <div class="form-group">
            <div class="input-group sug-input-group">
              <input type="text" id="sug_input" class="form-control" name="title" placeholder="Enter Product Name...">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary">
                  Find Item
                </button>
              </span>
            </div>
            <div id="result" class="list-group" style="position: absolute; width: 100%; z-index: 999;"></div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="transaction-card">
        <div class="panel-heading" style="border-bottom: 1px solid #f1f5f9;">
          <strong style="font-size: 16px;">
            <i class="glyphicon glyphicon-shopping-cart" style="margin-right: 8px; color: #10b981;"></i> 
            New Transaction Entry
          </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="add_sale.php">
            <div class="table-responsive">
              <table class="table table-transaction">
                <thead>
                  <tr>
                    <th>Item Description</th>
                    <th style="width: 15%;">Price</th>
                    <th style="width: 15%;">Quantity</th>
                    <th style="width: 15%;">Total</th>
                    <th style="width: 18%;">Date</th>
                    <th style="width: 100px;">Action</th>
                  </tr>
                </thead>
                <tbody id="product_info">
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