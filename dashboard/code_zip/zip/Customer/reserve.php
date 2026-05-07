<?php
session_start();
$conn = new mysqli("localhost", "root", "", "stlmcoin_gps_data");

$product = $_POST['product_name'];
$store = $_SESSION['store'];

// Generate barcode
$barcode = rand(1000000000,9999999999);

// Save reservation
$stmt = $conn->prepare("INSERT INTO reservations (product_name, store, barcode) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $product, $store, $barcode);
$stmt->execute();

$_SESSION['barcode'] = $barcode;
$_SESSION['product'] = $product;

header("Location: confirmation.php");
exit;
