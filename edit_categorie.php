<?php
  $page_title = 'Edit Category';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Missing category id.");
    redirect('categorie.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('categorie-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  if(empty($errors)){
        $sql = "UPDATE categories SET name='{$cat_name}'";
       $sql .= " WHERE id='{$categorie['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Category");
       redirect('categorie.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update or no changes made.");
       redirect('categorie.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('categorie.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<style>
  :root {
    --primary: #38bdf8;
    --accent: #6366f1;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --text-main: #f8fafc;
    --text-dim: #94a3b8;
    --border: rgba(56, 189, 248, 0.1);
  }

  body {
    background-color: var(--dark-bg);
    color: var(--text-main);
  }

  .edit-card { 
    background: var(--card-bg); 
    border-radius: 20px; 
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4); 
    border: 1px solid var(--border); 
    margin-top: 50px;
    overflow: hidden;
    position: relative;
  }

  .edit-card-header { 
    padding: 25px; 
    background: rgba(255,255,255,0.02); 
    border-bottom: 1px solid var(--border); 
    text-align: center;
  }

  .edit-card-header h4 { 
    margin: 0; 
    font-weight: 900; 
    color: var(--primary); 
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 16px;
  }

  .form-modern .form-control { 
    background: var(--dark-bg) !important;
    border: 2px solid var(--border);
    border-radius: 12px; 
    padding: 15px; 
    height: 55px; 
    color: #fff !important;
    font-weight: 600;
    transition: all 0.3s;
  }

  .form-modern .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
    outline: none;
  }

  .btn-update { 
    background: linear-gradient(135deg, var(--accent) 0%, #4f46e5 100%); 
    border: none; 
    color: #fff; 
    width: 100%; 
    height: 55px;
    border-radius: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 15px;
    transition: 0.3s;
    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
  }

  .btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
    filter: brightness(1.1);
  }

  .back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    color: var(--text-dim);
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    transition: 0.2s;
  }

  .back-link:hover { color: var(--primary); }

  .icon-circle {
    width: 60px;
    height: 60px;
    background: rgba(56, 189, 248, 0.1);
    border: 2px solid var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-6 col-md-offset-3">
      <div class="edit-card">
        <div class="edit-card-header">
          <div class="icon-circle">
            <i class="glyphicon glyphicon-edit" style="font-size: 24px; color: var(--primary);"></i>
          </div>
          <h4>Modify Category</h4>
          <p style="color: var(--text-dim); font-size: 12px; margin-top: 5px;">
            Current: <span style="color: #fff;"><?php echo remove_junk(ucfirst($categorie['name']));?></span>
          </p>
        </div>

        <div class="panel-body" style="padding: 40px;">
          <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id'];?>" class="form-modern">
            <div class="form-group">
                <label style="color: var(--primary); font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; display: block;">Update Category Name</label>
                <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>" placeholder="Enter new name...">
            </div>
            <button type="submit" name="edit_cat" class="btn btn-update">Apply Changes</button>
          </form>
          
          <a href="categorie.php" class="back-link">&larr; Return to All Categories</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>