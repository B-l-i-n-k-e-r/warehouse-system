<?php
  $page_title = 'Edit product';
  require_once('includes/load.php');
  page_require_level(2);

  $product = find_by_id('products',(int)$_GET['id']);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $all_locations = find_all_locations(); 

  if(!$product){
    $session->msg("d","Missing product id.");
    redirect('product.php');
  }

  if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price', 'product-location' );
    validate_fields($req_fields);

    if(empty($errors)){
      $p_name  = remove_junk($db->escape($_POST['product-title']));
      $p_cat   = (int)$_POST['product-categorie'];
      $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
      $p_buy   = remove_junk($db->escape($_POST['buying-price']));
      $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
      $p_loc   = (int)$_POST['product-location'];

      if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
        $media_id = '0';
      } else {
        $media_id = remove_junk($db->escape($_POST['product-photo']));
      }
      
      $query   = "UPDATE products SET";
      $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
      $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',";
      $query  .=" media_id='{$media_id}', location_id='{$p_loc}'"; 
      $query  .=" WHERE id ='{$product['id']}'";
      
      if($db->query($query)){
        $session->msg('s',"Product and Location updated successfully.");
        redirect('product.php', false);
      } else {
        $session->msg('d',' Sorry, failed to update!');
        redirect('edit_product.php?id='.$product['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_product.php?id='.$product['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .edit-container { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
  .edit-header { padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
  .section-tag { font-size: 11px; font-weight: 700; color: #6366f1; text-transform: uppercase; margin-bottom: 15px; display: block; }
  .preview-card { background: #f8fafc; border-radius: 10px; padding: 20px; text-align: center; border: 1px solid #e2e8f0; }
  .preview-img { width: 100%; max-width: 200px; border-radius: 8px; margin-bottom: 15px; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
  .form-control { border-radius: 0 8px 8px 0 !important; height: 40px; }
  .btn-update { background: #6366f1; color: #fff; border: none; font-weight: 700; padding: 12px 25px; border-radius: 8px; transition: 0.2s; }
  .btn-update:hover { background: #4f46e5; transform: translateY(-1px); }
  
  /* FIX FOR KSH FITTING */
  .input-group-addon { 
    border-radius: 8px 0 0 8px !important; 
    font-weight: 600; 
    background: #f1f5f9; 
    min-width: 35px; /* Gives enough space for Ksh. */
    text-align: center;
  }
  .input-group { display: flex; width: 100%; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="edit-container">
        <div class="edit-header">
          <h3 style="margin:0; font-weight:700;"><i class="glyphicon glyphicon-pencil"></i> Modify Product Details</h3>
        </div>
        <div class="panel-body">
          <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
            <div class="row">
              <div class="col-md-8">
                <span class="section-tag">Identification & Title</span>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                    <input type="text" class="form-control" style="border-radius: 0 8px 8px 0 !important;" name="product-title" value="<?php echo remove_junk($product['name']);?>">
                  </div>
                </div>

                <span class="section-tag">Organization & Logistics</span>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Category</label>
                      <select class="form-control" style="border-radius:8px !important;" name="product-categorie">
                        <?php foreach ($all_categories as $cat): ?>
                          <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']) echo "selected"; ?>>
                            <?php echo remove_junk($cat['name']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Warehouse Bin</label>
                      <select class="form-control" style="border-radius:8px !important;" name="product-location">
                        <?php foreach ($all_locations as $loc): ?>
                          <option value="<?php echo (int)$loc['id']; ?>" <?php if($product['location_id'] === $loc['id']) echo "selected"; ?>>
                            <?php echo remove_junk($loc['location_name']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Attached Media</label>
                      <select class="form-control" style="border-radius:8px !important;" name="product-photo">
                        <option value="">No image</option>
                        <?php foreach ($all_photo as $photo): ?>
                          <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']) echo "selected"; ?>>
                            <?php echo $photo['file_name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <span class="section-tag">Inventory & Pricing</span>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Current Quantity</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
                        <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Buying Price</label>
                      <div class="input-group">
                        <span class="input-group-addon">Ksh.</span>
                        <input type="number" step="0.01" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']);?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Selling Price</label>
                      <div class="input-group">
                        <span class="input-group-addon">Ksh.</span>
                        <input type="number" step="0.01" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']);?>">
                      </div>
                    </div>
                  </div>
                </div>
                
                <hr>
                <button type="submit" name="product" class="btn-update">Save All Changes</button>
                <a href="product.php" class="btn btn-default" style="margin-left:10px; border-radius:8px; padding:12px 20px;">Cancel</a>
              </div>

              <div class="col-md-4">
                <span class="section-tag">Current Snapshot</span>
                <div class="preview-card">
                  <?php 
                    $current_img = 'no_image.jpg';
                    foreach($all_photo as $p) { if($p['id'] == $product['media_id']) $current_img = $p['file_name']; }
                  ?>
                  <img src="uploads/products/<?php echo $current_img; ?>" class="preview-img" alt="Current Product Image">
                  <div style="font-weight:700; color:#334155;"><?php echo $product['name']; ?></div>
                  <div style="font-size: 14px; color: #6366f1; font-weight: bold; margin-top: 5px;">
                    Price: Ksh. <?php echo number_format($product['sale_price'], 2); ?>
                  </div>
                  <div class="text-muted" style="font-size:12px; margin-top:5px;">
                    Last Updated: <?php echo read_date($product['date']); ?>
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

<?php include_once('layouts/footer.php'); ?>