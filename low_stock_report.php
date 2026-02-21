<?php
  $page_title = 'Low Stock Alert';
  require_once('includes/load.php');
  // Check permission level
  page_require_level(2);
  // Fetch products with low stock (threshold is handled in the function)
  $low_stock_list = find_low_stock_products(10); 
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-warning-sign"></span>
          <span>Restock Alert: Critical Inventory Levels</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th> Product </th>
              <th class="text-center" style="width: 15%;"> Current Qty </th>
              <th class="text-center"> Warehouse Location </th>
              <th class="text-center"> Zone </th>
              <th class="text-center" style="width: 120px;"> Action </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($low_stock_list as $product): ?>
            <tr class="<?php echo ($product['quantity'] <= 0) ? 'danger' : 'warning'; ?>">
              <td> <?php echo remove_junk($product['name']); ?></td>
              <td class="text-center"> 
                <strong><?php echo (int)$product['quantity']; ?></strong>
              </td>
              <td class="text-center"> <?php echo remove_junk($product['location_name']); ?></td>
              <td class="text-center"> <?php echo remove_junk($product['zone']); ?></td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-primary btn-xs" title="Edit Product" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span> Update Stock
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($low_stock_list)): ?>
             <tr>
               <td colspan="5" class="text-center">No products currently below the stock threshold.</td>
             </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>