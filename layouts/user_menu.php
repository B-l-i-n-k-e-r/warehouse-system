<style>
  /* --- MoonLit Sales User Sidebar --- */
  .sidebar-menu {
    list-style: none;
    padding: 10px 0;
    margin: 0;
    background-color: #0f172a; /* Deep MoonLit Blue/Black */
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .sidebar-menu li {
    margin: 4px 12px;
  }

  .sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    /* Custom instruction: ensure text doesn't wrap awkwardly */
    white-space: nowrap; 
    width: fit-content;
    min-width: 100%;
  }

  /* Vibrant Blue for Sales Icons */
  .sidebar-menu li a i.glyphicon {
    margin-right: 14px;
    font-size: 18px;
    color: #0099ff; 
    transition: transform 0.3s ease;
  }

  .sidebar-menu li a:hover {
    background-color: rgba(0, 153, 255, 0.08);
    color: #ffffff;
    padding-left: 25px;
  }

  .sidebar-menu li a.active-link {
    background: rgba(0, 153, 255, 0.15);
    color: #fff;
    border-left: 4px solid #0099ff;
  }

  /* Submenu Styling */
  .submenu {
    display: none;
    background-color: rgba(255, 255, 255, 0.02);
    list-style: none;
    padding: 5px 0;
    margin: 5px 0 10px 0;
    border-radius: 12px;
  }

  .submenu li a {
    padding: 10px 15px 10px 52px;
    font-size: 13px;
    color: #64748b;
    border-left: none;
  }

  .submenu li a:hover {
    color: #0099ff;
    background: transparent;
    padding-left: 58px;
  }

  /* Arrow logic */
  .arrow {
    margin-left: auto;
    font-size: 10px !important;
    transition: transform 0.3s;
    opacity: 0.6;
  }

  .active-toggle .arrow {
    transform: rotate(90deg);
    opacity: 1;
  }
</style>

<ul class="sidebar-menu">
  <li>
    <a href="home.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active-link' : ''; ?>">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['sales.php', 'add_sale.php'])) ? 'active-toggle' : ''; ?>">
      <i class="glyphicon glyphicon-shopping-cart"></i>
      <span>Sales Orders</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu" style="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['sales.php', 'add_sale.php'])) ? 'display:block;' : ''; ?>">
        <li><a href="sales.php"><i class="glyphicon glyphicon-record" style="font-size:8px; margin-right:10px;"></i> Order History</a></li>
        <li><a href="add_sale.php"><i class="glyphicon glyphicon-record" style="font-size:8px; margin-right:10px;"></i> Create New Sale</a></li>
    </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['sales_report.php', 'monthly_sales.php', 'daily_sales.php'])) ? 'active-toggle' : ''; ?>">
      <i class="glyphicon glyphicon-stats"></i>
      <span>Reports</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu" style="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['sales_report.php', 'monthly_sales.php', 'daily_sales.php'])) ? 'display:block;' : ''; ?>">
      <li><a href="sales_report.php"><i class="glyphicon glyphicon-record" style="font-size:8px; margin-right:10px;"></i> Custom Report</a></li>
      <li><a href="monthly_sales.php"><i class="glyphicon glyphicon-record" style="font-size:8px; margin-right:10px;"></i> Monthly Report</a></li>
      <li><a href="daily_sales.php"><i class="glyphicon glyphicon-record" style="font-size:8px; margin-right:10px;"></i> Daily Report</a></li>
    </ul>
  </li>
</ul>

<script>
  // Sales User Menu Toggle Logic
  document.querySelectorAll('.submenu-toggle').forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      const submenu = item.nextElementSibling;
      const isActive = item.classList.contains('active-toggle');

      if (!isActive) {
        submenu.style.display = 'block';
        item.classList.add('active-toggle');
      } else {
        submenu.style.display = 'none';
        item.classList.remove('active-toggle');
      }
    });
  });
</script>