<?php
//  Database Connection 
$conn = new mysqli("localhost","root","","nexmart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




//  Get total products count
$total_products = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM products");
if ($result) {
    $row = $result->fetch_assoc();
    $total_products = $row['total'];
}



//  Count products in each store

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


//  Insert Product (SECURE - Prepared Statement)
if (isset($_POST['submit'])) {

    $product_name = $_POST['product_name'];
    $image_url = $_POST['image_url'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, image_url, description, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $product_name, $image_url, $description, $quantity);

    if ($stmt->execute()) {
        echo "<script>
                alert('Product Added Successfully');
                window.location.href = window.location.href;
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
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

<!--  Dashboard Boxes -->
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

<!--  Add Product Form -->
<div class="sales-boxes">
<div class="recent-sales box">

<div class="title">Add Product Window</div>

<form method="POST" class="product-form">

    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="product_name" required>
    </div>

    <div class="form-group">
        <label>Image URL</label>
        <input type="text" name="image_url">
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" required></textarea>
    </div>

    <div class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" min="0" value="0" required>
    </div>

    <button type="submit" name="submit" class="btn-submit">Add Product</button>

</form>

</div>
</div>

</div>
</section>


<style>
    /* Form Container */
.product-form {
    max-width: 500px;
    margin-top: 20px;
}

/* Form Group */
.form-group {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
}

/* Labels */
.form-group label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

/* Inputs & Textarea */
.form-group input,
.form-group textarea {
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}

/* Focus Effect */
.form-group input:focus,
.form-group textarea:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
}

/* Textarea Height */
.form-group textarea {
    min-height: 80px;
    resize: vertical;
}

/* Submit Button */
.btn-submit {
    background: #4CAF50;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

/* Hover Effect */
.btn-submit:hover {
    background: #45a049;
}

/* Responsive */
@media (max-width: 600px) {
    .product-form {
        width: 100%;
    }
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