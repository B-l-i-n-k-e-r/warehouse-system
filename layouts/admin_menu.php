<style>
  /* Sidebar Modern Styling */
  .sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #1a1d21; /* Deep charcoal */
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
    position: relative;
  }

  .sidebar-menu li a i.glyphicon {
    margin-right: 15px;
    font-size: 16px;
    color: #3498db; /* Electric Blue Icons */
  }

  /* Hover Effect */
  .sidebar-menu li a:hover {
    background-color: #252a30;
    color: #ffffff;
    padding-left: 30px; /* Slight slide effect */
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
    display: none; /* Hidden by default */
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

  /* Arrow Indicator */
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
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="group.php">Manage Groups</a></li>
      <li><a href="users.php">Manage Users</a></li>
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
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="product.php">Manage products</a></li>
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
  // Simple Toggle Script for Smooth Submenus
  document.querySelectorAll('.submenu-toggle').forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      const submenu = item.nextElementSibling;
      const isActive = item.classList.contains('active');
      
      // Close other open submenus
      document.querySelectorAll('.submenu').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.submenu-toggle').forEach(el => el.classList.remove('active'));

      if (!isActive) {
        submenu.style.display = 'block';
        item.classList.add('active');
      }
    });
  });
</script>