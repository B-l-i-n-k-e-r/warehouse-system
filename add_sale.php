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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --success-green: #10b981;
    --danger-red: #ef4444;
    --hover-bg: rgba(56, 189, 248, 0.08);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  /* Navigation Link Styling */
  .btn-back {
    background: transparent;
    border: 1px solid var(--glass-border);
    color: var(--text-muted);
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    transition: 0.3s;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .btn-back:hover {
    border-color: var(--neon-blue);
    color: var(--neon-blue);
    background: rgba(56, 189, 248, 0.05);
  }

  .action-container { 
    background: var(--glass-bg); 
    backdrop-filter: blur(15px);
    padding: 30px; 
    border-radius: 20px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    margin-bottom: 30px; 
    border: 1px solid var(--glass-border);
  }

  .table-transaction { 
    width: 100% !important; 
    margin-bottom: 0; 
    background: transparent !important;
    border-collapse: separate;
    border-spacing: 0 5px;
  }
  
  .table-transaction th, .table-transaction td { 
    white-space: nowrap !important; 
    vertical-align: middle !important;
    padding: 15px 12px !important;
    width: 1%; 
    border: none !important;
    color: var(--text-main);
  }

  .table-transaction thead tr { background: rgba(0,0,0,0.2) !important; }
  .table-transaction thead th {
    color: var(--neon-blue) !important;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
  }

  .table-transaction tbody tr { transition: all 0.25s ease; border-radius: 10px; }
  .table-transaction tbody tr:hover {
    background-color: var(--hover-bg) !important;
    box-shadow: inset 4px 0 0 var(--neon-blue);
  }

  .col-product { width: auto !important; min-width: 200px; }
  .col-date { width: 160px !important; min-width: 160px !important; }

  .input-group.currency-wrap {
    display: flex !important;
    align-items: center;
    background: rgba(15, 23, 42, 0.4);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 2px 10px;
    min-width: 120px;
  }

  .currency-wrap .currency-label {
    color: var(--neon-blue);
    font-size: 11px;
    font-weight: 800;
    margin-right: 6px;
  }

  .currency-wrap input {
    background: transparent !important;
    border: none !important;
    color: #fff !important;
    padding: 6px 0 !important;
    font-weight: 700;
    box-shadow: none !important;
    height: auto !important;
    width: 100%;
  }

  .qty-input { 
    text-align: center; 
    font-weight: 800; 
    width: 80px !important; 
    color: var(--neon-blue) !important; 
    background: rgba(15, 23, 42, 0.4) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 8px !important;
  }

  .locked-input { opacity: 0.8; cursor: not-allowed; }

  .modal-content {
    background: #1e293b !important;
    border: 1px solid var(--glass-border);
    border-radius: 20px;
  }
  
  .product-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
    gap: 15px; 
    max-height: 400px; 
    overflow-y: auto; 
    padding: 15px; 
    background: rgba(0,0,0,0.2);
    border-radius: 12px;
  }

  .product-card { 
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--glass-border); 
    border-radius: 12px; 
    padding: 15px; 
    transition: 0.3s;
    cursor: pointer;
  }
  .product-card:hover { border-color: var(--neon-blue); transform: translateY(-3px); }
  .product-card.selected { background: rgba(56, 189, 248, 0.1); border-color: var(--neon-blue); }

  .remove-item { color: var(--danger-red); cursor: pointer; font-size: 18px; }

  .cart-summary {
    background: rgba(0,0,0,0.2) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 15px;
    padding: 25px;
    margin-top: 20px;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      
      <div class="action-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <a href="sales.php" class="btn-back">
            <i class="glyphicon glyphicon-arrow-left"></i> BACK TO SALES
          </a>
          <h4 style="margin: 0; color: var(--text-muted); font-weight: 700; letter-spacing: 1px;">MOONLIT WMS</h4>
        </div>

        <div class="text-center">
          <h3 style="margin-top:0; color:var(--text-main); font-weight: 800;">
            <i class="glyphicon glyphicon-shopping-cart" style="color: var(--neon-blue);"></i> NEW SALE TRANSACTION
          </h3>
          <button type="button" class="btn btn-primary" style="padding: 12px 40px; border-radius: 12px; font-weight: 800; background: var(--neon-blue); color: #000; border: none; margin-top: 10px;" onclick="showAllProducts()">
            <i class="glyphicon glyphicon-search"></i> SELECT PRODUCTS
          </button>
        </div>
      </div>

      <div style="background: var(--glass-bg); border-radius: 20px; border: 1px solid var(--glass-border); overflow: hidden;">
        <div class="panel-heading" style="padding: 20px; border-bottom: 1px solid var(--glass-border);">
          <strong style="color: var(--text-main);"><i class="glyphicon glyphicon-list-alt"></i> SHOPPING CART</strong>
          <button type="button" class="btn btn-danger btn-xs pull-right" onclick="clearCart()" id="clearCartBtn" style="display:none;">Clear Cart</button>
        </div>
        
        <div class="panel-body">
          <form method="post" action="add_sale.php">
            <div class="table-responsive">
              <table class="table table-transaction">
                <thead>
                  <tr>
                    <th class="col-product">Product Description</th>
                    <th>Price</th>
                    <th class="text-center">Qty</th>
                    <th>Subtotal</th>
                    <th class="col-date">Sale Date</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="product_info">
                    <tr><td colspan="6" class="text-center" style="padding: 60px; color: var(--text-muted);">Your cart is empty.</td></tr>
                </tbody>
              </table>
            </div>

            <div id="cartSummary" class="cart-summary" style="display:none;">
                <div class="row">
                  <div class="col-sm-6">
                    <p style="font-size: 14px; color: var(--text-muted); font-weight: 700;">TOTAL ITEMS: <span id="itemCount" style="color:var(--neon-blue); font-size: 22px;">0</span></p>
                  </div>
                  <div class="col-sm-6 text-right">
                    <span style="color: var(--text-muted); font-weight: 700;">GRAND TOTAL</span><br>
                    <span id="grandTotal" style="color:var(--success-green); font-weight:900; font-size:38px;">Ksh 0.00</span>
                    <div style="margin-top:20px;">
                      <button type="submit" name="add_sale" class="btn btn-success btn-lg" style="width: 100%; font-weight:800; border-radius: 12px; background: var(--success-green); border: none;">COMPLETE TRANSACTION</button>
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
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title" style="font-weight: 800; color: var(--neon-blue);">SELECT PRODUCTS</h4>
      </div>
      <div class="modal-body">
        <div style="margin-bottom: 20px; display: flex;">
          <input type="text" class="form-control" id="modalSearchInput" placeholder="Search..." style="border-radius: 10px 0 0 10px !important; background: rgba(15,23,42,0.5) !important; color: #fff; border: 1px solid var(--glass-border);">
          <button class="btn btn-primary" id="modalBtnFind" type="button" style="border-radius: 0 10px 10px 0 !important; background: var(--neon-blue); color: #000; border: none; font-weight: 800;">FIND</button>
        </div>
        <div class="product-grid" id="modalProductGrid">
          <?php foreach($all_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo addslashes($product['name']); ?>" data-price="<?php echo $product['sale_price']; ?>" onclick="toggleSelect(this)">
              <div style="font-weight:800; color: #fff;"><?php echo $product['name']; ?></div>
              <div style="color:var(--neon-blue); font-weight: 700;">Ksh <?php echo number_format($product['sale_price'], 2); ?></div>
              <small style="color: var(--text-muted);">Stock: <?php echo $product['quantity']; ?></small>
              <div class="selection-badge" style="display:none; float: right; color: var(--success-green);">✓</div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer" style="border: none; padding: 25px;">
        <button class="btn btn-primary btn-block btn-lg" style="border-radius: 12px; font-weight: 800; background: var(--neon-blue); color: #000; border: none;" onclick="addSelected()">ADD SELECTED TO CART</button>
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
});

function showAllProducts() { $('#productModal').modal('show'); }
function toggleSelect(el) { $(el).toggleClass('selected'); $(el).find('.selection-badge').toggle(); }

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
        <div style="font-weight:700; color: #fff;">${item.name}</div>
      </td>
      <td>
        <div class="input-group currency-wrap">
          <span class="currency-label">Ksh</span>
          <input type="text" name="items[${i}][price]" value="${item.price}" class="locked-input" readonly>
        </div>
      </td>
      <td class="text-center">
        <input type="number" name="items[${i}][quantity]" class="form-control qty-input" value="${item.qty}" min="1" onchange="updateCart(${i}, 'qty', this.value)">
      </td>
      <td>
        <div class="input-group currency-wrap">
          <span class="currency-label">Ksh</span>
          <input type="text" value="${lineTotal.toFixed(2)}" class="locked-input" readonly>
        </div>
      </td>
      <td class="col-date">
        <input type="date" name="items[${i}][date]" class="form-control locked-input" value="<?php echo date('Y-m-d'); ?>" readonly style="background: rgba(15,23,42,0.4) !important; color: #fff !important; border: 1px solid var(--glass-border) !important;">
      </td>
      <td class="text-center">
        <span class="remove-item" onclick="remove(${i})"><i class="glyphicon glyphicon-trash"></i></span>
      </td>
    </tr>`;
  });
  
  $('#product_info').html(html || '<tr><td colspan="6" class="text-center" style="padding: 60px; color: var(--text-muted);">Your cart is empty.</td></tr>');
  $('#grandTotal').text('Ksh ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
  $('#itemCount').text(cart.length);
  $('#cartSummary, #clearCartBtn').toggle(cart.length > 0);
}

function updateCart(i, field, val) { if(field === 'qty') cart[i].qty = parseInt(val) || 1; renderCart(); }
function remove(i) { cart.splice(i, 1); renderCart(); }
function clearCart() { if(confirm('Clear cart?')) { cart = []; renderCart(); } }
</script>