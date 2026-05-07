<?php
// ✅ Database Connection
$conn = new mysqli("localhost","root", "", "nexmart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch all products specifically for the london Store
$london_products = $conn->query("SELECT * FROM store_london ORDER BY created_at DESC");

// ... (Keep your existing count logic here) ...
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>london Store Inventory</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Table Styling */
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .inventory-table th, .inventory-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .inventory-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .badge-qty {
            background: #e1f5fe;
            color: #03a9f4;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
       <div class="sidebar">
      <div class="logo-details"> 
        <i class="bx bxl-c-plus-plus"></i>
        <span class="logo_name">Nexmart</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="london.php">
            <i class="bx bx-grid-alt"></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
                <li>
          <a href="orders_london.php">
            <i class="bx bx-grid-alt"></i>
            <span class="links_name">Orders</span>
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

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">london Inventory</span>
            </div>
            </nav>

        <div class="home-content">
            <div class="sales-boxes">
                <div class="recent-sales box" style="width: 100%;">
                    <div class="title">london Store In-Stock</div>
                    
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Current Stock</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($london_products->num_rows > 0): ?>
                                <?php while($product = $london_products->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="product-img">
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($product['product_name']); ?></strong></td>
                                    <td>£<?php echo number_format($product['price'], 2); ?></td>
                                    <td><span class="badge-qty"><?php echo $product['quantity']; ?> pcs</span></td>
                                    <td><?php echo date('d M Y', strtotime($product['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 20px;">No stock currently in london.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        };
    </script>
</body>
</html>