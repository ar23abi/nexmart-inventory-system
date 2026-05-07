<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_name = $_POST['product_name'];
$store = $_POST['store'];

$allowed_tables = [
    "hatfield" => "store_hatfield",
    "london" => "store_london",
    "watford" => "store_watford"
];

if (!isset($allowed_tables[$store])) {
    echo json_encode(["error" => "Invalid store"]);
    exit();
}

$table = $allowed_tables[$store];

//  CHECK STOCK FIRST
$check = $conn->prepare("SELECT quantity FROM $table WHERE product_name=?");
$check->bind_param("s", $product_name);
$check->execute();
$result = $check->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['quantity'] <= 0) {
    echo json_encode(["error" => "Out of stock"]);
    exit();
}

//  REDUCE STOCK
$update = $conn->prepare("UPDATE $table SET quantity = quantity - 1 WHERE product_name=?");
$update->bind_param("s", $product_name);
$update->execute();

//  INSERT RESERVATION
$stmt = $conn->prepare("INSERT INTO reservations (user_id, product_name, store_name) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $product_name, $store);
$stmt->execute();

// TIME
date_default_timezone_set("Europe/London");

echo json_encode([
    "date" => date("d-m-Y"),
    "time" => date("h:i A")
]);