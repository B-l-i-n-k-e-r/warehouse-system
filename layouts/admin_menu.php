<style>
  /* Sidebar Modern Styling */
  .sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #1a1d21;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .sidebar-menu li {
    border-bottom: 1px solid #24282d;
  }

  .sidebar-menu li a {
    display: block;
    padding: 15px 25px;
    color: #aeb7c2;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative; /* Essential for absolute positioning of badges */
  }

  .sidebar-menu li a i.glyphicon {
    margin-right: 15px;
    font-size: 16px;
    color: #3498db;
  }

  /* Fixed Badge Styling - Matches your screenshot */
  .notif-badge {
    background-color: #e74c3c !important;
    color: white !important;
    font-size: 10px !important;
    padding: 2px 7px !important;
    border-radius: 10px !important;
    font-weight: bold !important;
    position: absolute !important;
    right: 40px; /* Positions it to the left of the arrow */
    top: 50%;
    transform: translateY(-50%);
    box-shadow: 0 0 8px rgba(231, 76, 60, 0.5);
    z-index: 10;
  }

  /* Adjust badge position for submenus */
  .submenu .notif-badge {
    right: 15px;
  }

  .sidebar-menu li a:hover {
    background-color: #252a30;
    color: #ffffff;
    padding-left: 30px;
  }

  .sidebar-menu li a:hover::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background-color: #3498db;
  }

  /* Submenu Styling */
  .submenu {
    display: none;
    background-color: #111417;
    list-style: none;
    padding: 0;
  }

  .submenu li {
    border: none;
  }

  .submenu li a {
    padding: 10px 10px 10px 55px;
    font-size: 13px;
    color: #8a94a1;
  }

  .submenu li a:hover {
    background-color: transparent;
    color: #3498db;
    padding-left: 60px;
  }

  .arrow {
    float: right;
    font-size: 10px !important;
    margin-top: 5px;
    transition: transform 0.3s;
  }

  .submenu-toggle.active .arrow {
    transform: rotate(90deg);
  }
</style>

<ul class="sidebar-menu">
  <li>
    <a href="admin.php">
      <i class="glyphicon glyphicon-home"></i>
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
      <li><a href="group.php">Manage Groups</a></li>
      <li>
        <a href="users.php">
          <span>Manage Users</span>
          <?php if(isset($pending_count) && $pending_count > 0): ?>
            <span class="notif-badge"><?php echo (int)$pending_count; ?></span>
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
      <li><a href="locations.php">Manage Locations</a></li>
      <li><a href="add_location.php">Add Location</a></li>
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
          <span>Manage products</span>
          <?php if(isset($low_stock_count) && $low_stock_count > 0): ?>
            <span class="notif-badge"><?php echo (int)$low_stock_count; ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li><a href="add_product.php">Add product</a></li>
    </ul>
  </li>

  <li>
    <a href="media.php">
      <i class="glyphicon glyphicon-picture"></i>
      <span>Medias</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-list"></i>
      <span>Sales</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="sales.php">Manage Sales</a></li>
      <li><a href="add_sale.php">Add Sale</a></li>
    </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-signal"></i>
      <span>Sales Report</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="sales_report.php">Sales by dates</a></li>
      <li><a href="monthly_sales.php">Monthly sales</a></li>
      <li><a href="daily_sales.php">Daily sales</a></li>
    </ul>
  </li>
</ul>

<script>
  document.querySelectorAll('.submenu-toggle').forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      const submenu = item.nextElementSibling;
      const isActive = item.classList.contains('active');
      
      // Close other submenus for a cleaner accordion effect
      document.querySelectorAll('.submenu').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.submenu-toggle').forEach(el => el.classList.remove('active'));

      if (!isActive) {
        submenu.style.display = 'block';
        item.classList.add('active');
      }
    });
  });
</script>