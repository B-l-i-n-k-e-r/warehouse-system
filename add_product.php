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
     $p_name   = $db->escape($_POST['product-title']);
     $p_cat    = $db->escape($_POST['product-categorie']);
     $p_qty    = $db->escape($_POST['product-quantity']);
     $p_buy    = $db->escape($_POST['buying-price']);
     $p_sale   = $db->escape($_POST['saleing-price']);
     $p_loc    = $db->escape($_POST['product-location']);

     // --- DIRECT UPLOAD LOGIC ---
     if(isset($_FILES['product-photo']) && $_FILES['product-photo']['error'] === 0){
        $photo = new Media();
        $photo->upload($_FILES['product-photo']);
        if($photo->process_media()){
            $new_media_query = $db->query("SELECT id FROM media ORDER BY id DESC LIMIT 1");
            $new_media = $db->fetch_assoc($new_media_query);
            $media_id = $new_media['id'];
        } else {
            $session->msg("d", join($photo->errors));
            redirect('add_product.php', false);
        }
     } else {
        $media_id = '0';
     }

     $date     = make_date();
     $query  = "INSERT INTO products (name,quantity,buy_price,sale_price,categorie_id,media_id,location_id,date) ";
     $query .="VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$p_loc}', '{$date}')";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     
     if($db->query($query)){
       $session->msg('s',"Product added successfully.");
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
  :root {
    --glass-bg: rgba(30, 41, 59, 0.7);
    --glass-border: rgba(255, 255, 255, 0.1);
    --neon-blue: #38bdf8;
    --neon-purple: #818cf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --input-fill: rgba(15, 23, 42, 0.6);
  }

  body {
    background: radial-gradient(circle at top right, #1e293b, #0f172a) !important;
    color: var(--text-main);
  }

  .product-form-card { 
    background: var(--glass-bg); 
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 24px; 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
    border: 1px solid var(--glass-border); 
    margin-bottom: 40px; 
    overflow: hidden;
  }

  .product-form-header { 
    padding: 30px; 
    border-bottom: 1px solid var(--glass-border); 
    background: rgba(255,255,255,0.02);
  }

  .product-form-header h3 { 
    margin: 0; 
    font-size: 22px; 
    font-weight: 800; 
    color: var(--text-main); 
    letter-spacing: -0.5px;
  }

  .form-section-title { 
    font-size: 11px; 
    font-weight: 800; 
    color: var(--neon-blue); 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 20px; 
    margin-top: 25px; 
    display: flex;
    align-items: center;
  }
  .form-section-title::after {
    content: "";
    height: 1px;
    background: linear-gradient(to right, var(--glass-border), transparent);
    flex-grow: 1;
    margin-left: 15px;
  }
  
  .input-group-addon { 
    background: rgba(56, 189, 248, 0.1) !important; 
    border: 1px solid var(--glass-border) !important; 
    color: var(--neon-blue) !important; 
    min-width: 60px;
    font-weight: 700;
    font-size: 12px;
    border-right: none !important;
    border-radius: 12px 0 0 12px !important;
  }

  .form-control { 
    background: var(--input-fill) !important;
    border: 1px solid var(--glass-border) !important; 
    color: #fff !important;
    height: 48px; 
    border-radius: 0 12px 12px 0 !important;
    transition: all 0.3s ease;
  }
  
  .form-control:focus { 
    border-color: var(--neon-blue) !important; 
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2) !important; 
  }

  /* Specific fix for standalone selects */
  select.form-control { border-radius: 12px !important; appearance: none; cursor: pointer; }

  .btn-submit-product { 
    background: linear-gradient(135deg, var(--neon-blue), #0ea5e9); 
    color: #fff; 
    padding: 14px 40px; 
    border-radius: 14px; 
    font-weight: 800; 
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none; 
    transition: all 0.3s; 
    box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
  }
  .btn-submit-product:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 15px 30px rgba(14, 165, 233, 0.5);
    filter: brightness(1.1);
  }

  .custom-file-upload {
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    padding: 0 15px;
    cursor: pointer;
    border-radius: 12px;
    background: var(--input-fill);
    font-size: 13px;
    color: var(--text-muted);
    transition: 0.3s;
    height: 48px;
    width: 100%;
  }
  .custom-file-upload:hover { border-color: var(--neon-blue); color: var(--text-main); }
  .custom-file-upload i { margin-right: 10px; color: var(--neon-blue); font-size: 16px; }

  .back-link { 
    display: block; 
    text-align: center; 
    margin-top: 10px; 
    color: var(--text-muted); 
    font-weight: 600; 
    font-size: 13px;
    transition: 0.3s;
  }
  .back-link:hover { color: var(--neon-blue); transform: translateX(-3px); }
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
        <div class="product-form-header text-center">
          <h3><i class="glyphicon glyphicon-barcode" style="color: var(--neon-blue); margin-right: 12px;"></i> Add New Product</h3>
        </div>
        <div class="panel-body" style="padding: 40px;">
          <form method="post" action="add_product.php" enctype="multipart/form-data">
            
            <div class="form-section-title">Primary Identification</div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i>TITLE</span>
                <input type="text" class="form-control" name="product-title" placeholder="Hardware Designation (e.g. Industrial Steel Bolt M10)">
              </div>
            </div>

            <div class="form-section-title">Logistics & Deployment</div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Sector Category</label>
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
                  <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Storage Bin</label>
                  <select class="form-control" name="product-location">
                    <option value="">Select Bin Location</option>
                    <?php foreach ($all_locations as $loc): ?>
                      <option value="<?php echo (int)$loc['id'] ?>">
                        <?php echo $loc['location_name'] ?> —  <?php echo $loc['zone'] ?> Zone
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; display: block;">Image</label>
                  <label class="custom-file-upload">
                    <input type="file" name="product-photo" style="display:none;" onchange="this.parentElement.querySelector('span').textContent = this.files[0].name; this.parentElement.style.borderColor='#38bdf8';">
                    <i class="glyphicon glyphicon-camera"></i> <span>Upload Image</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="form-section-title">Quantity & Valuation</div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Quantity">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon">KSH.</span>
                    <input type="number" step="0.01" class="form-control" name="buying-price" placeholder="Buying Cost">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon">KSH.</span>
                    <input type="number" step="0.01" class="form-control" name="saleing-price" placeholder="Selling Price">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center" style="margin-top: 40px;">
              <button type="submit" name="add_product" class="btn-submit-product">
                <i class="glyphicon glyphicon-plus-sign"></i> Add Product
              </button>
            </div>

          </form>
        </div>
      </div>
      <a href="product.php" class="back-link"><i class="glyphicon glyphicon-arrow-left"></i> Return to Master Inventory</a>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>