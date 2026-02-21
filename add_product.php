<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $all_locations = find_all_locations(); 
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price', 'product-location' );
   validate_fields($req_fields);
   
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     $p_loc   = remove_junk($db->escape($_POST['product-location']));

     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     
     $query  = "INSERT INTO products (";
     $query .=" name,quantity,buy_price,sale_price,categorie_id,media_id,location_id,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$p_loc}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     
     if($db->query($query)){
       $session->msg('s',"Product added to inventory and assigned to location.");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to add product!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .product-form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #edf2f7;
    margin-bottom: 30px;
  }
  .product-form-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
  }
  .product-form-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #334155;
  }
  .form-section-title {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
    margin-top: 10px;
  }
  .input-group-addon {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
  }
  .form-control {
    border-radius: 8px;
    border-color: #e2e8f0;
    height: 42px;
    box-shadow: none;
  }
  .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }
  .btn-submit-product {
    background: #6366f1;
    color: #fff;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 700;
    border: none;
    transition: all 0.2s;
  }
  .btn-submit-product:hover {
    background: #4f46e5;
    transform: translateY(-1px);
    color: #fff;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="product-form-card">
        <div class="product-form-header">
          <h3><i class="glyphicon glyphicon-plus" style="color: #6366f1; margin-right: 10px;"></i> Add New Inventory Item</h3>
        </div>
        <div class="panel-body" style="padding: 30px;">
          <form method="post" action="add_product.php">
            
            <div class="form-section-title">General Information</div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                <input type="text" class="form-control" name="product-title" placeholder="Full Product Name / Title">
              </div>
            </div>

            <div class="form-section-title">Logistics & Storage</div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control" name="product-location">
                    <option value="">Select Bin Location</option>
                    <?php foreach ($all_locations as $loc): ?>
                      <option value="<?php echo (int)$loc['id'] ?>">
                        <?php echo $loc['location_name'] ?> (<?php echo $loc['zone'] ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control" name="product-photo">
                    <option value="">Select Media File</option>
                    <?php foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-section-title">Stock Levels & Pricing</div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Initial Quantity">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-import"></i></span>
                    <input type="number" step="0.01" class="form-control" name="buying-price" placeholder="Cost Price">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-export"></i></span>
                    <input type="number" step="0.01" class="form-control" name="saleing-price" placeholder="Retail Price">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-right" style="margin-top: 20px;">
              <button type="submit" name="add_product" class="btn-submit-product">
                <i class="glyphicon glyphicon-ok"></i> Save to Inventory
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>