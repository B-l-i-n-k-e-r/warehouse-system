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
      $p_name   = $db->escape($_POST['product-title']);
      $p_cat    = (int)$_POST['product-categorie'];
      $p_qty    = $db->escape($_POST['product-quantity']);
      $p_buy    = $db->escape($_POST['buying-price']);
      $p_sale   = $db->escape($_POST['saleing-price']);
      $p_loc    = (int)$_POST['product-location'];

      if(isset($_FILES['new_file']) && $_FILES['new_file']['error'] === 0){
        $photo = new Media();
        $photo->upload($_FILES['new_file']);
        if($photo->process_media()){
            $new_media_query = $db->query("SELECT id FROM media ORDER BY id DESC LIMIT 1");
            $new_media = $db->fetch_assoc($new_media_query);
            $media_id = $new_media['id'];
        } else {
            $session->msg("d", join($photo->errors));
            redirect('edit_product.php?id='.$product['id'], false);
        }
      } else {
        if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
          $media_id = '0';
        } else {
          $media_id = $db->escape($_POST['product-photo']);
        }
      }
      
      $query   = "UPDATE products SET";
      $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
      $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',";
      $query  .=" media_id='{$media_id}', location_id='{$p_loc}'"; 
      $query  .=" WHERE id ='{$product['id']}'";
      
      if($db->query($query)){
        $session->msg('s',"Product updated successfully.");
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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-fill: rgba(15, 23, 42, 0.6);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .edit-container { 
    background: var(--glass-bg); 
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 20px; 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
    border: 1px solid var(--glass-border); 
    overflow: hidden; 
  }

  .edit-header { 
    padding: 25px; 
    border-bottom: 1px solid var(--glass-border); 
    background: rgba(255,255,255,0.03); 
  }

  /* Header Visibility Fix */
  .edit-header h3 {
    color: var(--text-main) !important;
    margin: 0;
    font-weight: 800;
    letter-spacing: -0.5px;
  }

  .section-tag { 
    font-size: 10px; 
    font-weight: 800; 
    color: var(--neon-blue); 
    text-transform: uppercase; 
    margin-bottom: 20px; 
    display: block; 
    letter-spacing: 1.5px;
  }

  .preview-card { 
    background: rgba(15, 23, 42, 0.4); 
    border-radius: 16px; 
    padding: 25px; 
    text-align: center; 
    border: 1px solid var(--glass-border); 
  }

  .preview-img { 
    width: 100%; 
    max-width: 180px; 
    border-radius: 12px; 
    margin-bottom: 20px; 
    border: 2px solid var(--glass-border); 
    box-shadow: 0 10px 20px rgba(0,0,0,0.3); 
    transition: transform 0.3s ease;
  }
  .preview-img:hover { transform: scale(1.05); }

  .form-control { 
    background: var(--input-fill) !important;
    border: 1px solid var(--glass-border) !important; 
    color: #fff !important;
    height: 44px; 
    border-radius: 0 10px 10px 0 !important;
    transition: all 0.3s;
  }
  .form-control:focus { 
    border-color: var(--neon-blue) !important; 
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important; 
  }

  .input-group-addon { 
    border-radius: 10px 0 0 10px !important; 
    font-weight: 700; 
    background: rgba(56, 189, 248, 0.1) !important; 
    color: var(--neon-blue) !important;
    border: 1px solid var(--glass-border) !important;
    border-right: none !important;
    min-width: 50px;
    text-align: center;
  }

  .btn-update { 
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9); 
    color: #fff; 
    border: none; 
    font-weight: 800; 
    padding: 14px 30px; 
    border-radius: 12px; 
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s; 
    box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
  }
  .btn-update:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(14, 165, 233, 0.5); }

  .upload-btn-wrapper { position: relative; overflow: hidden; display: inline-block; margin-top: 10px; width: 100%; }
  .btn-upload-custom { 
    width: 100%;
    padding: 10px; 
    font-size: 11px; 
    font-weight: 800; 
    color: var(--neon-blue); 
    background: transparent; 
    border: 1px dashed var(--neon-blue); 
    border-radius: 10px; 
    cursor: pointer; 
    text-transform: uppercase;
    transition: 0.3s;
  }
  .upload-btn-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; }

  select.form-control { border-radius: 10px !important; }

  hr { border-top: 1px solid var(--glass-border); }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="edit-container">
        <div class="edit-header">
          <h3>
            <i class="glyphicon glyphicon-edit" style="color: var(--neon-blue); margin-right: 10px;"></i> Product Modification
          </h3>
        </div>
        <div class="panel-body" style="padding: 35px;">
          <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-8">
                <span class="section-tag">Primary Metadata</span>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                    <input type="text" class="form-control" name="product-title" value="<?php echo htmlspecialchars($product['name']);?>">
                  </div>
                </div>

                <span class="section-tag">System Logistics</span>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Category</label>
                      <select class="form-control" name="product-categorie">
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
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Storage Bin</label>
                      <select class="form-control" name="product-location">
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
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Media</label>
                      <select class="form-control" name="product-photo">
                        <option value="0">Default System Image</option>
                        <?php foreach ($all_photo as $photo): ?>
                          <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']) echo "selected"; ?>>
                            <?php echo $photo['file_name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <span class="section-tag">Pricing & Capacity</span>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Quantity</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-oil"></i></span>
                        <input type="number" class="form-control" name="product-quantity" value="<?php echo (int)$product['quantity']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Buying Cost (KSH)</label>
                      <div class="input-group">
                        <span class="input-group-addon">KSH.</span>
                        <input type="number" step="0.01" class="form-control" name="buying-price" value="<?php echo (float)$product['buy_price'];?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Selling Price (KSH)</label>
                      <div class="input-group">
                        <span class="input-group-addon">KSH.</span>
                        <input type="number" step="0.01" class="form-control" name="saleing-price" value="<?php echo (float)$product['sale_price'];?>">
                      </div>
                    </div>
                  </div>
                </div>
                
                <div style="margin-top: 30px; display: flex; align-items: center; gap: 15px;">
                  <button type="submit" name="product" class="btn-update">Commit Changes</button>
                  <a href="product.php" class="btn" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px;">Discard</a>
                </div>
              </div>

              <div class="col-md-4">
                <span class="section-tag">Image Verification</span>
                <div class="preview-card">
                  <?php 
                    $current_img = 'no_image.jpg'; 
                    foreach($all_photo as $p) { 
                      if($p['id'] == $product['media_id']) {
                        $current_img = $p['file_name'];
                        break;
                      }
                    }
                  ?>
                  <img src="uploads/products/<?php echo $current_img; ?>" class="preview-img" alt="Current Product Image">
                  
                  <div class="upload-btn-wrapper">
                    <button class="btn-upload-custom" type="button"><i class="glyphicon glyphicon-camera"></i> Swap Image</button>
                    <input type="file" name="new_file" onchange="this.previousElementSibling.innerText = 'Signal Received'; this.previousElementSibling.style.background = 'rgba(56, 189, 248, 0.1)'; this.previousElementSibling.style.borderStyle = 'solid';" />
                  </div>

                  <hr style="margin: 20px 0;">
                  <div style="font-weight:800; color: #fff; font-size: 15px;">
                    <?php echo htmlspecialchars_decode($product['name']); ?>
                  </div>
                  <div style="font-size: 13px; color: var(--neon-blue); font-weight: 800; margin-top: 8px; font-family: monospace;">
                    Amount: KSH <?php echo number_format($product['sale_price'], 0); ?>
                  </div>
                  <div style="font-size:10px; color: var(--text-muted); margin-top:10px; text-transform: uppercase; letter-spacing: 1px;">
                    Last Sync: <?php echo date("d M Y", strtotime($product['date'])); ?>
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