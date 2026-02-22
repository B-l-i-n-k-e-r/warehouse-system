<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  page_require_level(3);

  // Set local timezone
  date_default_timezone_set('Africa/Nairobi');

  if(isset($_POST['add_sale'])){
    if(isset($_POST['items']) && is_array($_POST['items'])) {
      $success_count = 0;
      $error_count = 0;
      
      foreach($_POST['items'] as $item) {
        $p_id = $db->escape((int)$item['product_id']);
        $s_qty = $db->escape((int)$item['quantity']);
        $s_price = $db->escape($item['price']); 
        $s_date = make_date();
        
        $sql = "INSERT INTO sales (product_id, qty, price, date) ";
        $sql .= "VALUES ('{$p_id}', '{$s_qty}', '{$s_price}', '{$s_date}')";
        
        if($db->query($sql)){
          update_product_qty($s_qty, $p_id);
          $success_count++;
        } else {
          $error_count++;
        }
      }
      
      if($success_count > 0) {
        $session->msg('s', "{$success_count} sale(s) completed (Ksh). " . ($error_count > 0 ? "{$error_count} failed." : ""));
      } else {
        $session->msg('d', 'Error: Could not process transactions.');
      }
      redirect('add_sale.php', false);
    }
  }
  $all_products = find_all('products');
?>
<?php include_once('layouts/header.php'); ?>

<style>
  /* --- RESPONSIVE LAYOUT SYSTEM --- */
  .search-container {
    background: #fff; padding: 25px; border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;
  }
  
  /* Ensure table doesn't break layout on mobile */
  .table-responsive {
    border: none !important;
    -webkit-overflow-scrolling: touch;
  }

  /* Form controls inside table */
  .table-transaction input.form-control {
    min-width: 90px; /* Prevents input from disappearing on tiny screens */
    height: 38px;
  }
  
  .date-input { min-width: 140px !important; }

  /* Grid for Product Cards */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    max-height: 450px;
    overflow-y: auto;
    padding: 10px;
  }

  /* Mobile Adjustments */
  @media (max-width: 768px) {
    .search-container { padding: 15px; }
    .total-amount { font-size: 20px; display: block; margin-top: 10px; }
    .cart-summary { flex-direction: column; text-align: center; gap: 10px; }
    .btn-show-all { width: 100%; margin-top: 10px; }
    .product-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
  }

  .product-card {
    border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;
    cursor: pointer; transition: 0.2s; position: relative;
  }
  .product-card.selected { border-color: #6366f1; background-color: #f5f3ff; }
  .selection-badge { position: absolute; top: 5px; right: 5px; background: #6366f1; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; }
  .remove-item { color: #ef4444; cursor: pointer; font-weight: 600; white-space: nowrap; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="search-container">
        <div class="text-center">
          <h4><i class="glyphicon glyphicon-shopping-cart" style="color: #6366f1;"></i> New Sale (Ksh)</h4>
        </div>
        <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
          <div class="input-group">
            <input type="text" id="sug_input" class="form-control" style="height:45px;" placeholder="Search product name...">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary" style="height:45px;">Find</button>
            </span>
          </div>
          <button type="button" class="btn btn-success btn-block" style="margin-top:15px; height:45px;" onclick="showAllProducts()">
            <i class="glyphicon glyphicon-th"></i> Browse All Products
          </button>
          <div id="result" class="list-group" style="position: absolute; width: 100%; z-index: 999;"></div>
        </form>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong><i class="glyphicon glyphicon-list-alt"></i> Shopping Cart</strong>
          <button class="btn btn-danger btn-xs pull-right" onclick="clearCart()" id="clearCartBtn" style="display:none;">Clear Cart</button>
        </div>
        <div class="panel-body">
          <form method="post" action="add_sale.php">
            <div class="table-responsive">
              <table class="table table-bordered table-transaction">
                <thead>
                  <tr style="background: #f8fafc;">
                    <th>Product</th>
                    <th style="width: 15%;">Price</th>
                    <th style="width: 12%;">Qty</th>
                    <th style="width: 15%;">Total</th>
                    <th style="width: 15%;">Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="product_info">
                  </tbody>
              </table>
            </div>

            <div id="cartSummary" class="cart-summary" style="display:none; padding: 20px; background: #f8fafc; border-radius: 8px; margin-top: 15px; border: 1px solid #eef2f6;">
               <div class="row">
                  <div class="col-sm-6">
                    <span class="text-muted">Items in Cart:</span> <b id="itemCount">0</b>
                  </div>
                  <div class="col-sm-6 text-right">
                    <span class="text-muted">Grand Total:</span>
                    <span class="total-amount" id="grandTotal" style="color:#10b981; font-weight:bold; font-size:24px; margin-left:10px;">Ksh 0.00</span>
                    <div style="margin-top:15px;">
                      <button type="submit" name="add_sale" class="btn btn-primary btn-lg btn-block-xs">Complete Transaction</button>
                    </div>
                  </div>
               </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Products</h4>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" id="productSearch" placeholder="Filter items..." style="margin-bottom:15px;">
        <div class="product-grid">
          <?php foreach($all_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo addslashes($product['name']); ?>" data-price="<?php echo $product['sale_price']; ?>" onclick="toggleSelect(this)">
              <div style="font-weight:700;"><?php echo $product['name']; ?></div>
              <div style="color:#10b981;">Ksh <?php echo number_format($product['sale_price'], 2); ?></div>
              <small class="text-muted">Stock: <?php echo $product['quantity']; ?></small>
              <span class="selection-badge" style="display:none;">✓</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-block" onclick="addSelected()">Add Selected Items</button>
      </div>
    </div>
  </div>
</div>

<script>
let cart = [];

function showAllProducts() { $('#productModal').modal('show'); }

function toggleSelect(el) {
  $(el).toggleClass('selected');
  $(el).find('.selection-badge').toggle();
}

function addSelected() {
  $('.product-card.selected').each(function() {
    addToCart($(this).data('id'), $(this).data('name'), parseFloat($(this).data('price')));
  });
  $('.product-card').removeClass('selected').find('.selection-badge').hide();
  $('#productModal').modal('hide');
}

function addToCart(id, name, price, qty = 1) {
  let existing = cart.find(i => i.id == id);
  if(existing) { existing.qty += qty; } else { cart.push({id, name, price, qty}); }
  renderCart();
}

function renderCart() {
  let html = '';
  let grandTotal = 0;
  cart.forEach((item, i) => {
    let lineTotal = item.price * item.qty;
    grandTotal += lineTotal;
    html += `<tr>
      <td><input type="hidden" name="items[${i}][product_id]" value="${item.id}">${item.name}</td>
      <td><input type="number" name="items[${i}][price]" class="form-control" value="${item.price}" onchange="updateCart(${i}, 'price', this.value)"></td>
      <td><input type="number" name="items[${i}][quantity]" class="form-control" value="${item.qty}" min="1" onchange="updateCart(${i}, 'qty', this.value)"></td>
      <td><input type="text" class="form-control" value="Ksh ${lineTotal.toFixed(2)}" readonly></td>
      <td><input type="date" name="items[${i}][date]" class="form-control date-input" value="<?php echo date('Y-m-d'); ?>"></td>
      <td class="text-center"><span class="remove-item" onclick="remove(${i})"><i class="glyphicon glyphicon-trash"></i></span></td>
    </tr>`;
  });
  
  $('#product_info').html(html || '<tr><td colspan="6" class="text-center">Your cart is empty</td></tr>');
  $('#grandTotal').text('Ksh ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
  $('#itemCount').text(cart.length);
  $('#cartSummary, #clearCartBtn').toggle(cart.length > 0);
}

function updateCart(i, field, val) {
  if(field === 'qty') cart[i].qty = parseInt(val) || 1;
  if(field === 'price') cart[i].price = parseFloat(val) || 0;
  renderCart();
}

function remove(i) { cart.splice(i, 1); renderCart(); }
function clearCart() { if(confirm('Clear entire cart?')) { cart = []; renderCart(); } }

// Search filtering in modal
$('#productSearch').on('keyup', function() {
  let val = $(this).val().toLowerCase();
  $('.product-card').each(function() {
    $(this).toggle($(this).data('name').toLowerCase().includes(val));
  });
});
</script>

<?php include_once('layouts/footer.php'); ?>