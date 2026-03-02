<?php
  $page_title = 'All categories';
  require_once('includes/load.php');
  page_require_level(1);

  // --- Sorting Logic ---
  // Default to 'latest' if no sort is specified
  $sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
  
  switch ($sort) {
    case 'name_asc':
      $order_by = "name ASC";
      break;
    case 'name_desc':
      $order_by = "name DESC";
      break;
    case 'latest':
    default:
      $order_by = "id DESC";
      break;
  }

  // --- Pagination Logic ---
  $limit = 10;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  $total_count = count_by_id('categories');
  $total_records = (int)$total_count['total'];
  $total_pages = ceil($total_records / $limit);

  if($page > $total_pages && $total_pages > 0) $page = $total_pages;

  // Final Query
  $all_categories = find_by_sql("SELECT * FROM categories ORDER BY {$order_by} LIMIT {$limit} OFFSET {$offset}");
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
  .cat-card { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #edf2f7; margin-bottom: 25px; }
  .cat-card-header { padding: 18px 25px; background: #fff; border-bottom: 1px solid #f1f5f9; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; }
  .cat-card-header h4 { margin: 0; font-weight: 700; color: #334155; font-size: 16px; }
  .form-modern .form-control { border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; height: auto; box-shadow: none; }
  .btn-modern { border-radius: 8px; padding: 10px 20px; font-weight: 600; transition: all 0.2s; cursor: pointer; }
  .btn-modern-primary { background: #6366f1; border: none; color: #fff; width: 100%; }
  .table-cat thead th { background: #f8fafc; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #64748b; padding: 15px 20px !important; }
  .table-cat tbody td { padding: 15px 20px !important; vertical-align: middle !important; }
  .action-icon { width: 32px; height: 32px; line-height: 32px; display: inline-block; border-radius: 6px; text-align: center; }
  .action-edit { background: #fff7ed; color: #f97316; }
  .action-delete { background: #fef2f2; color: #ef4444; }
  
  .sort-select { border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 12px; font-size: 13px; color: #475569; outline: none; background: #fff; font-weight: 600; }

  /* --- Pagination Styling --- */
  .pagination-wrapper { padding: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
  .btn-pagination { background: #fff; border: 1px solid #e2e8f0; color: #475569; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none !important; display: inline-block; }
  .btn-pagination:hover:not(.disabled-link) { background: #f8fafc; border-color: #cbd5e1; color: #6366f1; }
  .disabled-link { opacity: 0.4; cursor: not-allowed; pointer-events: none; background: #f1f5f9; }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
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
                <label class="text-muted small" style="font-weight: 700; text-transform: uppercase; margin-bottom: 8px; display: block;">Category Name</label>
                <input type="text" class="form-control" name="categorie-name" placeholder="e.g. Fasteners & Fixings">
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
          
          <form method="get" action="categorie.php" id="sortForm" class="form-inline">
            <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit();">
              <option value="latest" <?php if($sort == 'latest') echo 'selected'; ?>>Latest Added</option>
              <option value="name_asc" <?php if($sort == 'name_asc') echo 'selected'; ?>>Name (A-Z)</option>
              <option value="name_desc" <?php if($sort == 'name_desc') echo 'selected'; ?>>Name (Z-A)</option>
            </select>
            <input type="hidden" name="page" value="1">
          </form>
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
                <?php 
                  $count = $offset + 1;
                  foreach ($all_categories as $cat):
                ?>
                  <tr>
                    <td class="text-center text-muted"><?php echo $count++; ?></td>
                    <td style="font-weight: 600; color: #1e293b;">
                      <?php echo htmlspecialchars_decode(remove_junk(ucfirst($cat['name']))); ?>
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

          <div class="pagination-wrapper">
            <div class="text-muted small">
              Page <strong><?php echo $page; ?></strong> of <strong><?php echo $total_pages; ?></strong>
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" 
                 class="btn-pagination <?php if($page <= 1) echo 'disabled-link'; ?>">
                <i class="glyphicon glyphicon-chevron-left"></i> Previous
              </a>
              
              <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" 
                 class="btn-pagination <?php if($page >= $total_pages || $total_records == 0) echo 'disabled-link'; ?>" 
                 style="margin-left: 5px;">
                Next <i class="glyphicon glyphicon-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>