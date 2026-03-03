<?php
  $page_title = 'All categories';
  require_once('includes/load.php');
  page_require_level(1);

  // --- Sorting Logic ---
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
        $session->msg("s", "Successfully Added Category");
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

  .cat-card { 
    background: var(--card-bg); 
    border-radius: 16px; 
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3); 
    border: 1px solid var(--border); 
    margin-bottom: 25px; 
    overflow: hidden;
  }

  .cat-card-header { 
    padding: 20px 25px; 
    background: rgba(255,255,255,0.02); 
    border-bottom: 1px solid var(--border); 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
  }

  .cat-card-header h4 { 
    margin: 0; 
    font-weight: 800; 
    color: var(--primary); 
    font-size: 15px; 
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .form-modern .form-control { 
    background: var(--dark-bg);
    border: 1px solid var(--border);
    border-radius: 10px; 
    padding: 12px 15px; 
    height: auto; 
    color: #fff;
    box-shadow: none; 
  }

  .form-modern .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
  }

  .btn-modern-primary { 
    background: linear-gradient(135deg, var(--accent) 0%, #4f46e5 100%); 
    border: none; 
    color: #fff; 
    width: 100%; 
    padding: 12px;
    border-radius: 10px;
    font-weight: 700;
    text-transform: uppercase;
    margin-top: 10px;
    transition: 0.3s;
  }

  .btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
  }

  /* Table Customization */
  .table-cat thead th { 
    background: rgba(15, 23, 42, 0.5); 
    text-transform: uppercase; 
    font-size: 10px; 
    letter-spacing: 0.1em; 
    color: var(--primary); 
    padding: 15px 20px !important; 
    border: none !important;
  }

  .table-cat tbody td { 
    padding: 18px 20px !important; 
    vertical-align: middle !important; 
    border-top: 1px solid var(--border) !important;
    color: var(--text-main);
    transition: all 0.2s ease;
  }

  /* Premium Hover Fix */
  .table-hover tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.15) !important;
  }
  
  .table-hover tbody tr:hover td {
    color: #fff !important;
  }

  .action-icon { 
    width: 35px; 
    height: 35px; 
    line-height: 35px; 
    display: inline-block; 
    border-radius: 10px; 
    text-align: center; 
    transition: 0.2s;
  }

  .action-edit { background: rgba(56, 189, 248, 0.1); color: var(--primary); }
  .action-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
  .action-icon:hover { transform: scale(1.1); filter: brightness(1.2); }
  
  .sort-select { 
    background: var(--dark-bg); 
    border: 1px solid var(--border); 
    border-radius: 8px; 
    padding: 8px 12px; 
    font-size: 12px; 
    color: var(--text-main); 
    font-weight: 600;
  }

  .pagination-wrapper { 
    padding: 20px; 
    border-top: 1px solid var(--border); 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
  }

  .btn-pagination { 
    background: var(--dark-bg); 
    border: 1px solid var(--border); 
    color: var(--text-dim); 
    padding: 8px 18px; 
    border-radius: 10px; 
    font-weight: 700; 
    font-size: 12px;
    transition: 0.2s;
  }

  .btn-pagination:hover:not(.disabled-link) { 
    border-color: var(--primary); 
    color: var(--primary); 
    background: rgba(56, 189, 248, 0.05);
  }

  .disabled-link { opacity: 0.2; cursor: not-allowed; pointer-events: none; }
</style>

<div class="container-fluid" style="padding: 30px;">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="cat-card">
        <div class="cat-card-header">
          <h4><i class="glyphicon glyphicon-plus"></i> New Category</h4>
        </div>
        <div class="panel-body" style="padding: 30px;">
          <form method="post" action="categorie.php" class="form-modern">
            <div class="form-group">
                <label class="text-muted small" style="font-weight: 800; text-transform: uppercase; margin-bottom: 10px; display: block; color: var(--text-dim);">Classification Title</label>
                <input type="text" class="form-control" name="categorie-name" placeholder="e.g. Industrial Tools">
            </div>
            <button type="submit" name="add_cat" class="btn btn-modern-primary">Register Category</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="cat-card">
        <div class="cat-card-header">
          <h4><i class="glyphicon glyphicon-th-list"></i> All Categories</h4>
          
          <form method="get" action="categorie.php" id="sortForm">
            <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit();">
              <option value="latest" <?php if($sort == 'latest') echo 'selected'; ?>>Filter: Latest</option>
              <option value="name_asc" <?php if($sort == 'name_asc') echo 'selected'; ?>>Filter: A-Z</option>
              <option value="name_desc" <?php if($sort == 'name_desc') echo 'selected'; ?>>Filter: Z-A</option>
            </select>
            <input type="hidden" name="page" value="1">
          </form>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-cat table-hover"> <thead>
                <tr>
                  <th class="text-center" style="width: 80px;">ID</th>
                  <th>Category Label</th>
                  <th class="text-center" style="width: 150px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $count = $offset + 1;
                  foreach ($all_categories as $cat):
                ?>
                  <tr>
                    <td class="text-center" style="color: var(--text-dim); font-size: 12px; font-weight: 700;">#<?php echo $count++; ?></td>
                    <td style="font-weight: 700; color: #fff; font-size: 15px;">
                      <?php echo htmlspecialchars_decode(remove_junk(ucfirst($cat['name']))); ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>" class="action-icon action-edit" data-toggle="tooltip" title="Modify">
                          <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>" class="action-icon action-delete" style="margin-left: 10px;" data-toggle="tooltip" title="Archive">
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
            <div style="color: var(--text-dim); font-size: 12px; font-weight: 600;">
              Page <span style="color: var(--primary);"><?php echo $page; ?></span> / <?php echo $total_pages; ?>
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" 
                 class="btn-pagination <?php if($page <= 1) echo 'disabled-link'; ?>">
                <i class="glyphicon glyphicon-menu-left"></i>
              </a>
              
              <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" 
                 class="btn-pagination <?php if($page >= $total_pages || $total_records == 0) echo 'disabled-link'; ?>" 
                 style="margin-left: 8px;">
                <i class="glyphicon glyphicon-menu-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>