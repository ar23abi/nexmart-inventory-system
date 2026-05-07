<?php
session_start();

//  SHOW ERRORS (REMOVE AFTER TESTING)
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  DB CONNECTION (UPDATE USERNAME & PASSWORD CORRECTLY)
$conn = new mysqli("localhost","root","","nexmart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//  STORE FILTER
$store = "watford";

//  QUERY WITH users_archi
$stmt = $conn->prepare("
    SELECT r.*, u.name, u.email, u.phone 
    FROM reservations r
    LEFT JOIN users_archi u ON r.user_id = u.id
    WHERE r.store_name = ?
    ORDER BY r.reserved_at DESC
");

$stmt->bind_param("s", $store);
$stmt->execute();
$result = $stmt->get_result();
?>


<style>


table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #fff;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #1a237e;
    color: #fff;
}

tr:nth-child(even) {
    background: #f2f2f2;
}
</style>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Watford Store Inventory</title>
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
          <a href="watford.php">
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
                <span class="dashboard">Watford Inventory</span>
            </div>
            </nav>

        <div class="home-content">
            <div class="sales-boxes">
                <div class="recent-sales box" style="width: 100%;">
                    <div class="title">Store Orders</div>
                    
                    <table>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Product</th>
    <th>Date</th>
    <th>Time</th>
</tr>

<?php 
$sn = 1;
while($row = $result->fetch_assoc()): 
?>
<tr>
    <td><?php echo $sn++; ?></td>
    <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
    <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
    <td><?php echo date("d-m-Y", strtotime($row['reserved_at'])); ?></td>
    <td><?php echo date("h:i A", strtotime($row['reserved_at'])); ?></td>
</tr>
<?php endwhile; ?>

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