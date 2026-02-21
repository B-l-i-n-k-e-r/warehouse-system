<style>
  /* Maintain theme consistency */
  .sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #1a1d21;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
  }

  .sidebar-menu li a {
    display: block;
    padding: 15px 25px;
    color: #aeb7c2;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
  }

  /* Vibrant Blue for Sales Icons */
  .sidebar-menu li a i.glyphicon {
    margin-right: 15px;
    font-size: 16px;
    color: #3498db; 
  }

  .sidebar-menu li a:hover {
    background-color: #252a30;
    color: #ffffff;
    padding-left: 30px;
    border-left: 3px solid #3498db;
  }

  /* Submenu Styling */
  .submenu {
    display: none;
    background-color: #111417;
    list-style: none;
    padding: 0;
  }

  .submenu li a {
    padding: 10px 10px 10px 55px;
    font-size: 13px;
    color: #8a94a1;
    border-left: none;
  }

  .submenu li a:hover {
    color: #3498db;
    background: transparent;
    padding-left: 60px;
  }

  /* Arrow logic */
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
    <a href="home.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-shopping-cart"></i>
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
      <i class="glyphicon glyphicon-stats"></i>
      <span>Sales Report</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
      <li><a href="sales_report.php">Sales by Dates</a></li>
      <li><a href="monthly_sales.php">Monthly Sales</a></li>
      <li><a href="daily_sales.php">Daily Sales</a></li>
    </ul>
  </li>
</ul>

<script>
  // Sales User Menu Toggle Logic
  document.querySelectorAll('.submenu-toggle').forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      const submenu = item.nextElementSibling;
      const isActive = item.classList.contains('active');

      if (!isActive) {
        submenu.style.display = 'block';
        item.classList.add('active');
      } else {
        submenu.style.display = 'none';
        item.classList.remove('active');
      }
    });
  });
</script>