<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  page_require_level(3);

  date_default_timezone_set('Africa/Nairobi');

  if(isset($_POST['add_sale'])){
    if(isset($_POST['items']) && is_array($_POST['items'])) {
      $success_count = 0;
      $error_count = 0;
      
      foreach($_POST['items'] as $item) {
        $p_id = $db->escape((int)$item['product_id']);
        $s_qty = $db->escape((int)$item['quantity']);
        $s_price = $db->escape($item['price']); 
        $s_date = $db->escape($item['date']); 
        
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
  .action-container { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
  
  /* Table Logic: Force columns to fit content while protecting specific inputs */
  .table-transaction { width: 100% !important; margin-bottom: 0; table-layout: auto; }
  .table-transaction th, .table-transaction td { 
    white-space: nowrap !important; 
    vertical-align: middle !important;
    padding: 10px 12px !important;
    width: 1%; /* Forces columns to shrink to content width */
  }

  /* Only the product description expands to fill remaining space */
  .col-product { width: auto !important; min-width: 150px; }

  /* DATE COLUMN FIX: Force a minimum width to display YYYY-MM-DD clearly */
  .col-date { 
    width: 160px !important; /* Fixed width specifically for the date input */
    min-width: 160px !important;
  }

  /* Input Group Fix: Ensure 'Ksh' doesn't cut while staying compact */
  .input-group.currency-wrap { 
    display: inline-flex !important; 
    width: auto; 
    min-width: 140px; 
  }
  .input-group.currency-wrap .input-group-addon { 
    width: auto !important; 
    padding: 6px 8px;
    background-color: #f8fafc;
  }
  .input-group.currency-wrap input { 
    flex: 1;
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
  }

  .qty-input { text-align: center; font-weight: 700; width: 80px !important; }
  .date-input { width: 100% !important; height: 38px; }
  .locked-input { background: #f1f5f9 !important; cursor: not-allowed; color: #1e293b !important; font-weight: 600; }

  /* Modal & Product Selection Styles */
  .modal-search-wrapper { display: flex; gap: 0; margin-bottom: 20px; width: 100%; }
  .modal-search-wrapper input { border-radius: 8px 0 0 8px !important; height: 45px; border-right: none; box-shadow: none !important; }
  .modal-search-wrapper button { border-radius: 0 8px 8px 0 !important; height: 45px; width: 80px; font-weight: 600; background-color: #6366f1; border-color: #6366f1; color: white; }

  .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; max-height: 400px; overflow-y: auto; padding: 10px; border: 1px solid #f1f5f9; border-radius: 8px; }
  .product-card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; cursor: pointer; transition: 0.2s; position: relative; background: #fff; }
  .product-card:hover { border-color: #6366f1; transform: translateY(-2px); }
  .product-card.selected { border-color: #6366f1; background-color: #f5f3ff; box-shadow: 0 0 0 2px #6366f1; }
  .selection-badge { position: absolute; top: 5px; right: 5px; background: #6366f1; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; }
  .remove-item { color: #ef4444; cursor: pointer; font-weight: 600; font-size: 18px; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="action-container text-center">
        <h3 style="margin-top:0; color:#1e293b;"><i class="glyphicon glyphicon-shopping-cart" style="color: #6366f1;"></i> New Sale Transaction</h3>
        <button type="button" class="btn btn-primary btn-lg" style="padding: 12px 40px; border-radius: 8px; font-weight: 600;" onclick="showAllProducts()">
          <i class="glyphicon glyphicon-search"></i> All Products
        </button>
      </div>

      <div class="panel panel-default" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="panel-heading" style="background: #fff; border-bottom: 1px solid #f1f5f9; padding: 15px 20px;">
          <strong><i class="glyphicon glyphicon-list-alt"></i> Current Shopping Cart</strong>
          <button type="button" class="btn btn-danger btn-xs pull-right" onclick="clearCart()" id="clearCartBtn" style="display:none;">Clear Cart</button>
        </div>
        <div class="panel-body">
          <form method="post" action="add_sale.php">
            <div class="table-responsive">
              <table class="table table-hover table-transaction">
                <thead>
                  <tr style="background: #f8fafc; color: #64748b;">
                    <th class="col-product">Product Description</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th class="col-date">Sale Date</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="product_info">
                   <tr><td colspan="6" class="text-center text-muted" style="padding: 40px;">Your cart is empty ! !</td></tr>
                </tbody>
              </table>
            </div>

            <div id="cartSummary" class="cart-summary" style="display:none; padding: 25px; background: #f8fafc; border-radius: 12px; margin-top: 20px; border: 1px solid #e2e8f0;">
               <div class="row">
                  <div class="col-sm-6"><p style="font-size: 16px; color: #64748b;">Total Items: <b id="itemCount" style="color:#1e293b;">0</b></p></div>
                  <div class="col-sm-6 text-right">
                    <span class="text-muted" style="font-size: 18px;">Grand Total:</span>
                    <span id="grandTotal" style="color:#10b981; font-weight:bold; font-size:32px; margin-left:10px;">Ksh 0.00</span>
                    <div style="margin-top:20px;">
                      <button type="submit" name="add_sale" class="btn btn-success btn-lg" style="width: 100%; font-weight:700; border-radius: 8px;">Complete Sale</button>
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
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-radius: 15px 15px 0 0;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-weight: 700; color: #1e293b;">Select Products</h4>
      </div>
      <div class="modal-body" style="padding: 25px;">
        <div class="modal-search-wrapper">
          <input type="text" class="form-control" id="modalSearchInput" placeholder="Type product name..." autocomplete="off">
          <button class="btn btn-primary" id="modalBtnFind" type="button">Find</button>
        </div>
        <div class="product-grid" id="modalProductGrid">
          <?php foreach($all_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo addslashes($product['name']); ?>" data-price="<?php echo $product['sale_price']; ?>" onclick="toggleSelect(this)">
              <div class="product-title" style="font-weight:700; color: #1e293b;"><?php echo $product['name']; ?></div>
              <div style="color:#6366f1; font-weight: 600;">Ksh <?php echo number_format($product['sale_price'], 2); ?></div>
              <small class="text-muted">In Stock: <?php echo $product['quantity']; ?></small>
              <span class="selection-badge" style="display:none;">✓</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none; padding: 0 25px 25px 25px;">
        <button class="btn btn-primary btn-block btn-lg" style="border-radius: 8px; font-weight: 700;" onclick="addSelected()">Add to Cart</button>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<script>
let cart = [];

$(document).ready(function() {
  $('#modalSearchInput').on('keyup', function() {
    let val = $(this).val().toLowerCase();
    $('.product-card').each(function() {
      let name = $(this).data('name').toLowerCase();
      $(this).toggle(name.includes(val));
    });
  });

  $('#modalBtnFind').click(function() {
    let val = $('#modalSearchInput').val().toLowerCase().trim();
    if(val) {
      let match = $('.product-card').filter(function() {
         return $(this).data('name').toLowerCase().includes(val);
      }).first();
      if(match.length > 0) {
        $('.product-card').show(); 
        if(!match.hasClass('selected')) { toggleSelect(match[0]); }
        let container = $('#modalProductGrid');
        container.animate({ scrollTop: match.position().top + container.scrollTop() - 20 }, 500);
      }
    }
  });
});

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
      <td class="col-product">
        <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
        <div style="font-weight:600;">${item.name}</div>
      </td>
      <td>
        <div class="input-group input-group-sm currency-wrap">
          <span class="input-group-addon">Ksh</span>
          <input type="text" name="items[${i}][price]" class="form-control locked-input" value="${item.price}" readonly>
        </div>
      </td>
      <td>
        <input type="number" name="items[${i}][quantity]" class="form-control input-sm qty-input" value="${item.qty}" min="1" onchange="updateCart(${i}, 'qty', this.value)">
      </td>
      <td>
        <div class="input-group input-group-sm currency-wrap">
          <span class="input-group-addon">Ksh</span>
          <input type="text" class="form-control locked-input" value="${lineTotal.toFixed(2)}" readonly>
        </div>
      </td>
      <td class="col-date">
        <input type="date" name="items[${i}][date]" class="form-control input-sm locked-input date-input" value="<?php echo date('Y-m-d'); ?>" readonly>
      </td>
      <td class="text-center">
        <span class="remove-item" onclick="remove(${i})"><i class="glyphicon glyphicon-trash"></i></span>
      </td>
    </tr>`;
  });
  
  $('#product_info').html(html || '<tr><td colspan="6" class="text-center text-muted" style="padding: 40px;">Cart empty.</td></tr>');
  $('#grandTotal').text('Ksh ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
  $('#itemCount').text(cart.length);
  $('#cartSummary, #clearCartBtn').toggle(cart.length > 0);
}

function updateCart(i, field, val) {
  if(field === 'qty') cart[i].qty = parseInt(val) || 1;
  renderCart();
}

function remove(i) { cart.splice(i, 1); renderCart(); }
function clearCart() { if(confirm('Clear cart?')) { cart = []; renderCart(); } }
</script>