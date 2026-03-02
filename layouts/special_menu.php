<style>
  /* Ensuring consistency with the main theme */
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
    /* Custom instruction: fit content regardless of window size */
    white-space: nowrap;
    width: fit-content;
    min-width: 100%;
  }

  .sidebar-menu li a i.glyphicon {
    margin-right: 15px;
    font-size: 16px;
    color: #3498db; /* Blue accent */
  }

  .sidebar-menu li a:hover {
    background-color: #252a30;
    color: #ffffff;
    padding-left: 30px;
    border-left: 3px solid #3498db;
  }

  /* Submenu Style */
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
    <a href="home.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
      <i class="glyphicon glyphicon-menu-right arrow"></i>
    </a>
    <ul class="nav submenu">
       <li><a href="product.php">Manage Products</a></li>
       <li><a href="add_product.php">Add Product</a></li>
    </ul>
  </li>

  <li>
    <a href="media.php">
      <i class="glyphicon glyphicon-picture"></i>
      <span>Media Gallery</span>
    </a>
  </li>
</ul>

<script>
  // Script to handle the toggle for Special User Menu
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