<?php
$page_title = 'Edit Media';
require_once('includes/load.php');
page_require_level(2);

$photo_data = find_by_id('media', (int)$_GET['id']);
if (!$photo_data) {
    $session->msg("d", "Missing photo id.");
    redirect('media.php');
}

if (isset($_POST['submit'])) {
    $new_name_input = remove_junk($db->escape($_POST['filename_text']));
    $old_file_name = $photo_data['file_name'];
    $photo = new Media();

    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
        $photo->upload($_FILES['file_upload']);
        if ($photo->move_file()) {
            $new_file_name = $photo->fileName;
            $old_file_path = $photo->productPath . DS . $old_file_name;
            if (file_exists($old_file_path)) { unlink($old_file_path); }

            $sql  = "UPDATE media SET file_name='{$db->escape($new_file_name)}', file_type='{$db->escape($photo->fileType)}' WHERE id='{$photo_data['id']}'";
            if ($db->query($sql)) {
                $session->msg('s', 'Image replaced successfully.');
                redirect('media.php');
            }
        } else {
            $session->msg('d', join($photo->errors));
        }
    } 
    elseif (!empty($new_name_input) && $new_name_input !== $old_file_name) {
        $ext = pathinfo($old_file_name, PATHINFO_EXTENSION);
        if(pathinfo($new_name_input, PATHINFO_EXTENSION) !== $ext){
            $new_name_input .= "." . $ext;
        }

        $old_path = $photo->productPath . DS . $old_file_name;
        $new_path = $photo->productPath . DS . $new_name_input;

        if (file_exists($old_path) && rename($old_path, $new_path)) {
            $sql = "UPDATE media SET file_name='{$db->escape($new_name_input)}' WHERE id='{$photo_data['id']}'";
            $db->query($sql);
            $session->msg('s', 'File renamed successfully.');
            redirect('media.php');
        } else {
            $session->msg('d', 'Error renaming file. Check permissions.');
        }
    } else {
        $session->msg("w", "No changes made.");
        redirect('media.php');
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<style>
  .media-edit-wrapper { max-width: 550px; margin: 20px auto; }
  .media-edit-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: none; }
  .media-edit-header { background: #6366f1; color: #fff; padding: 25px; border-radius: 12px 12px 0 0; text-align: center; }
  .current-preview { 
    width: 120px; height: 120px; object-fit: cover; border-radius: 50%; 
    border: 4px solid rgba(255,255,255,0.3); margin-bottom: 15px; background: #fff;
  }
  .form-section { padding: 30px; }
  .option-box { 
    background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; 
    border-radius: 8px; margin-bottom: 20px; 
  }
  .section-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 10px; display: block; }
  .modern-input { border-radius: 6px; border: 1px solid #cbd5e1; height: 40px; }
  .btn-save { background: #6366f1; color: #fff; border: none; font-weight: 700; padding: 10px 25px; border-radius: 6px; }
  .btn-save:hover { background: #4f46e5; color: #fff; }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
  </div>

  <div class="media-edit-wrapper">
    <div class="media-edit-card">
      <div class="media-edit-header">
        <img src="uploads/products/<?php echo $photo_data['file_name'];?>" class="current-preview" alt="current">
        <h3 style="margin:0; font-size: 18px;">Update Media Asset</h3>
        <p style="margin:0; opacity: 0.8; font-size: 13px;"><?php echo $photo_data['file_name']; ?></p>
      </div>

      <div class="form-section">
        <form method="post" action="edit_media.php?id=<?php echo (int)$photo_data['id']; ?>" enctype="multipart/form-data">
          
          <div class="option-box">
            <span class="section-label">Option A: Rename Asset</span>
            <div class="form-group" style="margin-bottom: 0;">
              <input type="text" name="filename_text" class="form-control modern-input" value="<?php echo $photo_data['file_name']; ?>">
              <small class="text-muted">The file extension will be preserved automatically.</small>
            </div>
          </div>

          <div class="option-box" style="border-style: dashed; background: #fff;">
            <span class="section-label">Option B: Replace File</span>
            <div class="form-group" style="margin-bottom: 0;">
              <input type="file" name="file_upload" class="form-control" style="border:none; padding: 5px 0;">
              <p class="help-block" style="font-size: 12px; margin-bottom:0;">Selecting a new file will delete the old one permanently.</p>
            </div>
          </div>

          <div class="text-center" style="margin-top: 25px;">
            <button type="submit" name="submit" class="btn btn-save">
              <i class="glyphicon glyphicon-ok"></i> Update Asset
            </button>
            <a href="media.php" class="btn btn-link" style="color: #94a3b8; font-weight: 600;">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>