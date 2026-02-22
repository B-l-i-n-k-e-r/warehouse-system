<?php
  $page_title = 'All Image';
  require_once('includes/load.php');
  page_require_level(2);

  // --- Pagination Logic ---
  $limit = 10; 
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  // Get total media count
  $total_res = $db->query("SELECT COUNT(id) as total FROM media");
  $total_count = $db->fetch_assoc($total_res);
  $total_records = (int)$total_count['total'];
  $total_pages = ceil($total_records / $limit);

  // Fetch only 10 images for the current page
  $media_files = find_by_sql("SELECT * FROM media ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}");

  if(isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    if($photo->process_media()){
        $session->msg('s','Photo uploaded successfully.');
        redirect('media.php');
    } else {
        $session->msg('d',join($photo->errors));
        redirect('media.php');
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<style>
  .media-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 30px;
    overflow: hidden;
  }
  .media-header {
    padding: 20px 25px;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
  }
  .media-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #334155;
  }
  .upload-form-inline {
    background: #f8fafc;
    padding: 8px 15px;
    border-radius: 10px;
    border: 1px dashed #cbd5e1;
  }
  .table-media thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 15px !important;
  }
  .img-preview {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.2s;
  }
  .img-preview:hover { transform: scale(1.1); }
  
  .file-type-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    background: #e2e8f0;
    color: #475569;
  }

  /* --- Pagination Styles --- */
  .pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
  }
  .btn-pagination {
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
  }
  .btn-pagination:hover:not(.disabled) {
    background: #f8fafc;
    border-color: #cbd5e1;
  }
  .btn-pagination.disabled {
    opacity: 0.5;
    pointer-events: none;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="media-card">
        <div class="media-header">
          <h2><i class="glyphicon glyphicon-picture" style="color: #6366f1; margin-right: 10px;"></i> Media Asset Library</h2>
          
          <div class="upload-zone">
            <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
              <div class="upload-form-inline">
                <input type="file" name="file_upload" style="display:inline-block; font-size: 12px;"/>
                <button type="submit" name="submit" class="btn btn-primary btn-sm" style="background:#6366f1; border:none; font-weight:600; margin-left:10px;">
                   <i class="glyphicon glyphicon-cloud-upload"></i> Upload
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="panel-body" style="padding: 0;">
          <div class="table-responsive">
            <table class="table table-media table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th class="text-center" style="width: 100px;">Preview</th>
                  <th>File Details</th>
                  <th class="text-center" style="width: 15%;">Format</th>
                  <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = $offset + 1;
                foreach ($media_files as $media_file): 
                ?>
                <tr>
                  <td class="text-center text-muted"><?php echo $count++; ?></td>
                  <td class="text-center">
                    <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-preview" alt="product image"/>
                  </td>
                  <td>
                    <div style="font-weight: 600; color: #1e293b;"><?php echo $media_file['file_name'];?></div>
                    <small class="text-muted">ID: <?php echo (int)$media_file['id'];?></small>
                  </td>
                  <td class="text-center">
                    <span class="file-type-badge"><?php echo $media_file['file_type'];?></span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip" onclick="return confirm('Permanently delete this image?')">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
                
                <?php if(empty($media_files)): ?>
                <tr>
                  <td colspan="5" class="text-center" style="padding: 50px !important;">
                    <i class="glyphicon glyphicon-picture" style="font-size: 40px; color: #e2e8f0;"></i>
                    <p class="text-muted" style="margin-top: 10px;">No media assets found on this page.</p>
                  </td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-wrapper">
            <div class="text-muted small">
              Page <strong><?php echo $page; ?></strong> of <strong><?php echo $total_pages; ?></strong> (Total: <?php echo $total_records; ?> assets)
            </div>
            <div class="btn-group">
              <a href="?page=<?php echo $page - 1; ?>" 
                 class="btn btn-pagination <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <i class="glyphicon glyphicon-chevron-left"></i> Previous
              </a>
              <a href="?page=<?php echo $page + 1; ?>" 
                 class="btn btn-pagination <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>" 
                 style="margin-left: 8px;">
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