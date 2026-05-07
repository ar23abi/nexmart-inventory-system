<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Database Connection (FIXED)
$conn = new mysqli("localhost","root","","nexmart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




// ✅ Count products in each store

$hatfield_count = 0;
$watford_count = 0;
$london_count = 0;

// Hatfield
$res1 = $conn->query("SELECT COUNT(*) as total FROM store_hatfield");
if ($res1 && $row = $res1->fetch_assoc()) {
    $hatfield_count = $row['total'];
}

// Watford
$res2 = $conn->query("SELECT COUNT(*) as total FROM store_watford");
if ($res2 && $row = $res2->fetch_assoc()) {
    $watford_count = $row['total'];
}

// London
$res3 = $conn->query("SELECT COUNT(*) as total FROM store_london");
if ($res3 && $row = $res3->fetch_assoc()) {
    $london_count = $row['total'];

}




function addOrUpdateStore($conn, $table, $name, $image, $desc, $qty) {

    // ⚠️ Validate table name (IMPORTANT for security)
    $allowed_tables = ['store_hatfield', 'store_watford', 'store_london'];
    if (!in_array($table, $allowed_tables)) {
        throw new Exception("Invalid table name");
    }

   
    $check = $conn->query("SELECT id, quantity  FROM $table WHERE TRIM(LOWER(product_name)) = TRIM(LOWER('".$conn->real_escape_string($name)."'))");

    if (!$check) {
        throw new Exception($conn->error);
    }

    if ($check->num_rows > 0) {

        $row = $check->fetch_assoc();
        $newQty = $row['quantity'] + $qty;

        $update = $conn->query("UPDATE $table SET quantity = $newQty WHERE id = ".$row['id']);

        if (!$update) {
            throw new Exception($conn->error);
        }

    } else {

        $insert = $conn->query("
            INSERT INTO $table (product_name, image_url, description, quantity)
            VALUES (
                '".$conn->real_escape_string($name)."',
                '".$conn->real_escape_string($image)."',
                '".$conn->real_escape_string($desc)."',
                $qty
            )
        ");

        if (!$insert) {
            throw new Exception($conn->error);
        }
    }
}

/* ✅ TOTAL PRODUCTS */
$total_products = 0;
$res = $conn->query("SELECT COUNT(*) as total FROM products");
if ($res) {
    $total_products = $res->fetch_assoc()['total'];
}


if ($conn->affected_rows < 1) {
    throw new Exception("Stock update failed");
}

/* ✅ STORE PRODUCT COUNTS */
$hatfield_count = $conn->query("SELECT COUNT(*) as total FROM store_hatfield")->fetch_assoc()['total'];
$watford_count  = $conn->query("SELECT COUNT(*) as total FROM store_watford")->fetch_assoc()['total'];
$london_count   = $conn->query("SELECT COUNT(*) as total FROM store_london")->fetch_assoc()['total'];

if (isset($_GET['ship_id'])) {

    $id = intval($_GET['ship_id']);
    $conn->begin_transaction();

    try {

        $result = $conn->query("SELECT * FROM products WHERE id = $id");
        if (!$result) throw new Exception($conn->error);

        $product = $result->fetch_assoc();

        if (!$product) throw new Exception("Product not found");

        if ($product['quantity'] < 15) {
            throw new Exception("Minimum 15 quantity required");
        }

        $name  = $product['product_name'];
        $image = $product['image_url'];
        $desc  = $product['description'];

        // 🔥 Call store function
        addOrUpdateStore($conn, "store_hatfield", $name, $image, $desc, 5);
        addOrUpdateStore($conn, "store_watford",  $name, $image, $desc, 5);
        addOrUpdateStore($conn, "store_london",   $name, $image, $desc, 5);

        // Deduct stock
        if (!$conn->query("UPDATE products SET quantity = quantity - 15 WHERE id = $id")) {
            throw new Exception($conn->error);
        }

        $conn->commit();

        echo "<script>alert('✅ Product shipped'); window.location='Ship_Products.php';</script>";

    } catch (Exception $e) {

        $conn->rollback();
        die("❌ ERROR: " . $e->getMessage());
    }
}

/* ✅ FETCH PRODUCTS */
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8" />
<title>Warehouse Management System</title>

<link rel="stylesheet" href="style.css" />
<link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

<?php include('menu.php'); ?>

<section class="home-section">
<nav>
    <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Dashboard</span>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Search..." />
        <i class="bx bx-search"></i>
    </div>

    <div class="profile-details">
        <img src="https://img.freepik.com/premium-vector/user-profile-icon-circle_1256048-12499.jpg?semt=ais_hybrid&w=740&q=80" alt="" />
        <span class="admin_name">Admin</span>
        <i class="bx bx-chevron-down"></i>
    </div>
</nav>

<div class="home-content">

<!-- ✅ Dashboard Boxes -->
<div class="overview-boxes">

  <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Products</div>
      <div class="number"><?= $total_products ?></div>
    </div>
    <i class="bx bx-cart cart three"></i>
  </div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (Hatfield)</div>
    <div class="number" style="color:green"><?= $hatfield_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (Watford)</div>
    <div class="number" style="color:red"><?= $watford_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (London)</div>
    <div class="number"><?= $london_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>

</div>

<!-- ✅ Add Product Form -->
<div class="sales-boxes">
<div class="recent-sales box">

<div class="title">Shipping Window</div>

<div style="margin-bottom: 15px; display:flex; justify-content:space-between; align-items:center;">
    
    <br>
       
    <br>
   
    <button onclick="refreshPage()" class="refresh-btn">
        🔄 Refresh Stock
    </button>

</div>

<script>
function refreshPage() {
    window.location.reload();
}
</script>

<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while($row = $products->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>

        <td>
            <img src="<?= $row['image_url'] ?>" width="60">
        </td>

        <td><?= $row['product_name'] ?></td>
        <td><?= $row['description'] ?></td>

        <td><?= $row['quantity'] ?></td>

        <td>
            <?php if($row['quantity'] < 15): ?>
                <span class="low-stock">Low Stock</span>
            <?php else: ?>
                <span class="ok-stock">Ready</span>
            <?php endif; ?>
        </td>

        <td>
            <?php if($row['quantity'] >= 15): ?>
                <a href="?ship_id=<?= $row['id'] ?>" 
                   onclick="return confirm('Ship 5 units to each store (Total 15)?')">
                   <button>🚚 Ship</button>
                </a>
            <?php else: ?>
                <button disabled>Not Enough</button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>

</table>


</div>
</div>

</div>
</section>



    <style>

        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #4CAF50;
            color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        img {
            border-radius: 6px;
        }

        button {
            background: #2196F3;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #1976D2;
        }

        .low-stock {
            color: red;
            font-weight: bold;
        }

        .ok-stock {
            color: green;
            font-weight: bold;
        }
    </style>

<script>
let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");

sidebarBtn.onclick = function () {
    sidebar.classList.toggle("active");
    if (sidebar.classList.contains("active")) {
        sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
        sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
};
</script>

</body>
</html>