<?php
  $page_title = 'All categories';
  require_once('includes/load.php');
  page_require_level(1);
  $all_categories = find_all('categories')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('categorie-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO categories (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added Categorie");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
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
  .cat-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    border: 1px solid #edf2f7;
    margin-bottom: 25px;
  }
  .cat-card-header {
    padding: 18px 25px;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    border-radius: 12px 12px 0 0;
  }
  .cat-card-header h4 {
    margin: 0;
    font-weight: 700;
    color: #334155;
    font-size: 16px;
  }
  .form-modern .form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 10px 15px;
    height: auto;
    box-shadow: none;
    transition: all 0.2s;
  }
  .form-modern .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }
  .btn-modern {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.2s;
  }
  .btn-modern-primary {
    background: #6366f1;
    border: none;
    color: #fff;
    width: 100%;
  }
  .btn-modern-primary:hover {
    background: #4f46e5;
    transform: translateY(-1px);
  }
  .table-cat { margin: 0; }
  .table-cat thead th {
    background: #f8fafc;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.05em;
    color: #64748b;
    border-top: none !important;
    padding: 15px 20px !important;
  }
  .table-cat tbody td {
    padding: 15px 20px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .action-icon {
    width: 32px;
    height: 32px;
    line-height: 32px;
    display: inline-block;
    border-radius: 6px;
    text-align: center;
    transition: all 0.2s;
  }
  .action-edit { background: #fff7ed; color: #f97316; }
  .action-delete { background: #fef2f2; color: #ef4444; }
  .action-icon:hover { transform: scale(1.1); }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="cat-card">
        <div class="cat-card-header">
          <h4><i class="glyphicon glyphicon-plus" style="color: #6366f1; margin-right: 8px;"></i> Add New Category</h4>
        </div>
        <div class="panel-body" style="padding: 25px;">
          <form method="post" action="categorie.php" class="form-modern">
            <div class="form-group">
                <label class="text-muted small" style="font-weight: 600; text-transform: uppercase; margin-bottom: 8px; display: block;">Category Name</label>
                <input type="text" class="form-control" name="categorie-name" placeholder="e.g. Electronics">
            </div>
            <button type="submit" name="add_cat" class="btn btn-modern btn-modern-primary">Create Category</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="cat-card">
        <div class="cat-card-header">
          <h4><i class="glyphicon glyphicon-th-list" style="color: #6366f1; margin-right: 8px;"></i> Existing Categories</h4>
        </div>
        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-cat table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width: 70px;">#</th>
                  <th>Category Name</th>
                  <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_categories as $cat):?>
                  <tr>
                    <td class="text-center text-muted"><?php echo count_id();?></td>
                    <td style="font-weight: 600; color: #1e293b;">
                      <?php echo remove_junk(ucfirst($cat['name'])); ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>" class="action-icon action-edit" data-toggle="tooltip" title="Edit">
                          <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>" class="action-icon action-delete" style="margin-left: 8px;" data-toggle="tooltip" title="Remove">
                          <i class="glyphicon glyphicon-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>