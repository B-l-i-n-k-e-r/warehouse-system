<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }

  // 1. Auto-suggestion (Search Results Dropdown)
  if(isset($_POST['product_name']) && strlen($_POST['product_name'])) {
    $html = '';
    $products = find_product_by_title($_POST['product_name']);
    
    if($products) {
        foreach ($products as $product):
           // Modern Suggestion Row: Name on left, Price badge on right
           $html .= "<li class='list-group-item d-flex justify-content-between align-items-center' style='cursor:pointer; padding: 12px 15px;'>";
           $html .= "  <span><i class='glyphicon glyphicon-tag' style='color:#6366f1; margin-right:8px;'></i>" . remove_junk($product['name']) . "</span>";
           $html .= "  <span class='badge' style='background:#f1f5f9; color:#6366f1; border-radius:6px;'>$" . number_format($product['sale_price'], 2) . "</span>";
           $html .= "</li>";
        endforeach;
    } else {
        $html .= "<li class='list-group-item text-muted' style='padding: 12px 15px;'>";
        $html .= "  <i class='glyphicon glyphicon-exclamation-sign'></i> No products found";
        $html .= "</li>";
    }
    echo json_encode($html);
    exit; // Ensure no extra output
  }

  // 2. Find Product Info (Loading into the Sales Table)
  if(isset($_POST['p_name']) && strlen($_POST['p_name'])) {
    $html = '';
    $product_title = remove_junk($db->escape($_POST['p_name']));
    $results = find_all_product_info_by_title($product_title);

    if($results) {
        foreach ($results as $result) {
          // Modern Table Row with refined input styling
          $html  .= "<tr>";
          $html  .= "  <td class='align-middle'>
                         <div class='fw-bold' style='color:#1e293b;'>" . remove_junk($result['name']) . "</div>
                         <input type='hidden' name='s_id' value='{$result['id']}'>
                       </td>";
          $html  .= "  <td class='align-middle'>
                         <div class='input-group input-group-sm'>
                           <span class='input-group-addon'>$</span>
                           <input type='number' step='0.01' class='form-control' name='price' value='{$result['sale_price']}' style='border-radius:0 6px 6px 0;'>
                         </div>
                       </td>";
          $html  .= "  <td class='align-middle'>
                         <input type='number' class='form-control form-control-sm' name='quantity' value='1' min='1' style='border-radius:6px; text-align:center;'>
                       </td>";
          $html  .= "  <td class='align-middle'>
                         <div class='input-group input-group-sm'>
                           <span class='input-group-addon'>$</span>
                           <input type='text' class='form-control' name='total' value='{$result['sale_price']}' readonly style='background:#f8fafc; border-radius:0 6px 6px 0;'>
                         </div>
                       </td>";
          $html  .= "  <td class='align-middle'>
                         <input type='date' class='form-control form-control-sm' name='date' value='".date('Y-m-d')."' style='border-radius:6px;'>
                       </td>";
          $html  .= "  <td class='align-middle text-center'>
                         <button type='submit' name='add_sale' class='btn btn-sm btn-primary' style='border-radius:6px; padding: 5px 15px; font-weight:600;'>
                           Add Sale
                         </button>
                       </td>";
          $html  .= "</tr>";
        }
    } else {
        $html = "<tr><td colspan='6' class='text-center text-danger p-4'>Product not found in database.</td></tr>";
    }
    echo json_encode($html);
    exit;
  }
?>