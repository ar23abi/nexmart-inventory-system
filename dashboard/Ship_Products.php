<?php

//  DB CONNECTION
$conn = new mysqli("localhost", "root", "", "nexmart");

$sql = "SELECT id, product_name, image_url, quantity, price, description FROM products";
$result = $conn->query($sql);
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



<!--  Add Product Form -->
<div class="sales-boxes">
<div class="recent-sales box">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-transport { background: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    </style>
<div class="title">Warehouse Stock</div>
<br>
<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Main Stock</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td>
    <img src="<?php echo $row['image_url']; ?>" width="60" height="60" style="object-fit:cover; border-radius:6px;">
</td>
        <td><?php echo $row['product_name']; ?></td>
         <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>
            <form method="POST" action="transport.php">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="transport" class="btn-transport">Transport Now</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

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