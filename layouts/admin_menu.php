<style>
  /* --- MoonLit Sidebar Modern Styling --- */
  .sidebar-menu {
    list-style: none;
    padding: 10px 0;
    margin: 0;
    background-color: #0f172a; /* Deep slate to match MoonLit theme */
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .sidebar-menu li {
    position: relative;
    margin: 4px 12px;
  }

  .sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 14px 18px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    /* Custom Instruction: Columns fit content */
    white-space: nowrap;
    width: fit-content;
    min-width: 100%;
  }

  .sidebar-menu li a i.glyphicon {
    margin-right: 14px;
    font-size: 18px;
    color: #0099ff; /* Electric Blue */
    transition: transform 0.3s ease;
  }

  /* --- Glowing Notification Badge --- */
  .notif-badge {
    background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
    color: white !important;
    font-size: 10px !important;
    padding: 2px 7px !important;
    border-radius: 8px !important;
    font-weight: 800 !important;
    margin-left: auto;
    box-shadow: 0 0 12px rgba(239, 68, 68, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  /* --- Hover & Active States --- */
  .sidebar-menu li a:hover {
    background-color: rgba(0, 153, 255, 0.08);
    color: #ffffff;
  }

  .sidebar-menu li a:hover i.glyphicon {
    transform: scale(1.1);
    color: #fff;
  }

  .sidebar-menu li a.active {
    background: linear-gradient(135deg, #0099ff, #0066ff);
    color: #fff !important;
    box-shadow: 0 10px 20px -5px rgba(0, 153, 255, 0.4);
  }

  .sidebar-menu li a.active i.glyphicon {
    color: #fff;
  }

  /* --- Submenu Styling --- */
  .submenu {
    display: none;
    background-color: rgba(255, 255, 255, 0.02);
    list-style: none;
    padding: 5px 0;
    margin: 5px 0 10px 0;
    border-radius: 12px;
    border-left: 2px solid rgba(0, 153, 255, 0.2);
  }

  .submenu li a {
    padding: 10px 15px 10px 50px;
    font-size: 13px;
    color: #64748b;
  }

  .submenu li a:hover {
    background: transparent;
    color: #0099ff;
    padding-left: 55px;
  }

  .arrow {
    margin-left: auto;
    font-size: 10px !important;
    transition: transform 0.4s;
    opacity: 0.5;
  }

  .active .arrow {
    transform: rotate(90deg);
    opacity: 1;
  }
</style>

<ul class="sidebar-menu">
  <li>
    <a href="admin.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>">
      <i class="glyphicon glyphicon-stats"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-user"></i>
      <span>User Management</span>
      <?php if(isset($pending_count) && $pending_count > 0): ?>
        <span class="notif-badge"><?php echo (int)$pending_count; ?></span>
      <?php endif; ?>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="group.php"><i class="glyphicon glyphicon-record"></i> Manage Groups</a></li>
      <li>
        <a href="users.php">
          <i class="glyphicon glyphicon-record"></i> 
          <span>Manage Users</span>
          <?php if(isset($pending_count) && $pending_count > 0): ?>
            <span class="notif-badge" style="position:static; margin-left:10px;"><?php echo (int)$pending_count; ?></span>
          <?php endif; ?>
        </a>
      </li>
    </ul>
  </li>

  <li>
    <a href="categorie.php">
      <i class="glyphicon glyphicon-indent-left"></i>
      <span>Categories</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-map-marker"></i>
      <span>Warehouse Bins</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="locations.php"><i class="glyphicon glyphicon-record"></i> Manage Locations</a></li>
      <li><a href="add_location.php"><i class="glyphicon glyphicon-record"></i> Add Location</a></li>
    </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
      <?php if(isset($low_stock_count) && $low_stock_count > 0): ?>
        <span class="notif-badge"><?php echo (int)$low_stock_count; ?></span>
      <?php endif; ?>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li>
        <a href="product.php">
          <i class="glyphicon glyphicon-record"></i> 
          <span>Manage Stock</span>
          <?php if(isset($low_stock_count) && $low_stock_count > 0): ?>
            <span class="notif-badge" style="position:static; margin-left:10px;"><?php echo (int)$low_stock_count; ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li><a href="add_product.php"><i class="glyphicon glyphicon-record"></i> Add product</a></li>
    </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-shopping-cart"></i>
      <span>Sales Orders</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="sales.php"><i class="glyphicon glyphicon-record"></i> Manage Sales</a></li>
      <li><a href="add_sale.php"><i class="glyphicon glyphicon-record"></i> Create Sale</a></li>
    </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-duplicate"></i>
      <span>Reports</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="sales_report.php"><i class="glyphicon glyphicon-record"></i> Custom Reports</a></li>
      <li><a href="monthly_sales.php"><i class="glyphicon glyphicon-record"></i> Monthly Reports</a></li>
      <li><a href="daily_sales.php"><i class="glyphicon glyphicon-record"></i> Daily Reports</a></li>
    </ul>
  </li>
</ul>

<script>
  document.querySelectorAll('.submenu-toggle').forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      const submenu = item.nextElementSibling;
      const arrow = item.querySelector('.arrow');
      const isActive = item.classList.contains('active-toggle');
      
      // Close all other submenus for a clean accordion effect
      document.querySelectorAll('.submenu').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.submenu-toggle').forEach(el => el.classList.remove('active-toggle'));

      if (!isActive) {
        submenu.style.display = 'block';
        item.classList.add('active-toggle');
      }
    });
  });

  // Keep the current submenu open based on URL (optional but helpful)
  const currentPath = window.location.pathname.split("/").pop();
  document.querySelectorAll('.submenu li a').forEach(link => {
    if(link.getAttribute('href') === currentPath) {
      const parentSub = link.closest('.submenu');
      const parentToggle = parentSub.previousElementSibling;
      parentSub.style.display = 'block';
      parentToggle.classList.add('active-toggle');
      link.style.color = "#0099ff";
    }
  });
</script>