    <div class="sidebar">
      <div class="logo-details"> 
        <i class="bx bxl-c-plus-plus"></i>
        <span class="logo_name">Nexmart</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="Dashboard.php">
            <i class="bx bx-grid-alt"></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        
                <li>
          <a href="Add_Products.php">
            <i class="bx bx-list-ul"></i>
            <span class="links_name">Add Products</span>
          </a>
        </li>
        
        





        <li>
          <a href="Ship_Products.php">
            <i class="bx bx-box"></i>
            <span class="links_name">Ship Products</span>
          </a>
        </li>
        
        
        <li class="dropdown">
  <a href="javascript:void(0);" onclick="toggleDropdown(this)">
    <i class="bx bx-user"></i>
    <span class="links_name">Support Team</span>
    <i class="bx bx-chevron-down arrow"></i>
  </a>

  <ul class="submenu">
    <li><a href="#">Dashboard</a></li>
    <li><a href="#">Create Ticket</a></li>
    <li><a href="#">View Tickets</a></li>

  </ul>
</li>
        
        
        


        
        


        
        <li>
          <a href="#">
            <i class="bx bx-cog"></i>
            <span class="links_name">Setting</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class="bx bx-log-out"></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
    </div>
    
    
    
    <style>
/* When dropdown is open → allow expansion */
.sidebar .nav-links li.dropdown.active {
  height: auto;
}

/* Submenu hidden */
.submenu {
  display: none;
  background: #05793b;
  padding-left: 60px;
}

/* Show submenu */
.dropdown.active .submenu {
  display: block;
}

/* Submenu items */
.submenu li {
  height: 40px;
}

.submenu li a {
  display: flex;
  align-items: center;
  height: 100%;
  color: #fff;
  font-size: 14px;
}

/* Arrow */
.arrow {
  margin-left: auto;
  transition: 0.3s;
}

.dropdown.active .arrow {
  transform: rotate(180deg);
}
    </style>
    
    <script>
function toggleDropdown(element) {
  let allDropdowns = document.querySelectorAll('.dropdown');

  allDropdowns.forEach(d => {
    if (d !== element.parentElement) {
      d.classList.remove("active");
    }
  });

  element.parentElement.classList.toggle("active");
}
    </script>