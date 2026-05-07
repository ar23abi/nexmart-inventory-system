<?php
session_start();

$barcode = $_SESSION['barcode'];
$product = $_SESSION['product'];
$store = $_SESSION['store'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Success</title>

<style>
.popup {
    width:450px;
    margin:100px auto;
    background:#fff;
    padding:20px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 0 20px #ccc;
}

.btn {
    background:#2e7d32;
    color:#fff;
    padding:10px;
    border:none;
    width:100%;
    border-radius:6px;
}
</style>

</head>

<body style="background:rgba(0,0,0,0.6);">

<div class="popup">

    <h2>✅ Product Reserved Successfully!</h2>

    <p><b><?= $product ?></b></p>

    <p>📍 Store: <?= ucfirst($store) ?></p>
    <p>📅 Date: <?= date("d M Y") ?></p>
    <p>⏰ Time: 10:30 AM - 5:00 PM</p>

    <h3><?= $barcode ?></h3>

    <button class="btn" onclick="window.location='index.php'">Done</button>

</div>

</body>
</html>