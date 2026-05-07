<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $conn->prepare("SELECT * FROM reservations WHERE user_id=? ORDER BY reserved_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$data = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reservations</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

/* Header */
.header {
    background: #1a237e;
    color: #fff;
    padding: 15px 20px;
    font-size: 20px;
    font-weight: bold;
}

/* Container */
.container {
    padding: 20px;
}

/* Card */
.card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #1a237e;
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f1f3ff;
}

/* Badge */
.badge {
    padding: 5px 10px;
    border-radius: 6px;
    background: #e3f2fd;
    color: #1a237e;
    font-size: 12px;
}

/* Empty */
.empty {
    text-align: center;
    padding: 30px;
    color: #777;
}

/* Responsive */
@media(max-width: 768px){
    table, thead, tbody, th, td, tr {
        display: block;
    }

    th {
        display: none;
    }

    tr {
        margin-bottom: 15px;
        background: #fff;
        border-radius: 10px;
        padding: 10px;
    }

    td {
        display: flex;
        justify-content: space-between;
        padding: 8px;
        border: none;
        border-bottom: 1px solid #eee;
    }

    td::before {
        font-weight: bold;
    }

    td:nth-child(1)::before { content: "Product"; }
    td:nth-child(2)::before { content: "Store"; }
    td:nth-child(3)::before { content: "Date"; }
    td:nth-child(4)::before { content: "Time"; }
}
</style>

</head>

<body>

<div class="header">
    My Reservations
</div>

<div class="container">
    <div class="card">

        <table>
            <tr>
                <th>Product</th>
                <th>Store</th>
                <th>Date</th>
                <th>Time</th>
            </tr>

            <?php if($data->num_rows > 0): ?>
                <?php while($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><span class="badge"><?php echo ucfirst($row['store_name']); ?></span></td>
                    <td><?php echo date("d M Y", strtotime($row['reserved_at'])); ?></td>
                    <td><?php echo date("h:i A", strtotime($row['reserved_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty">No reservations found</td>
                </tr>
            <?php endif; ?>

        </table>

    </div>
</div>

</body>
</html>