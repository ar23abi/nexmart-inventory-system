<?php
include "db.php";
//the store comes from the URL
$store = $_GET['store'];
//maps to correct database table
$tables = [
    "hatfield" => "store_hatfield",
    "london" => "store_london",
    "watford" => "store_watford"
];
//prevents invalid store names from being used
if (!isset($tables[$store])) {
    die("Invalid store");
}
//loads all products from the selected store table
$current_table = $tables[$store];

$result = $conn->query("SELECT * FROM $current_table");
//goes through each product one by one
while ($row = $result->fetch_assoc()) {

    $product = $row['product_name'];
    $stock = $row['quantity'];

    $otherStoresAvailable = [];

    // Check other stores and loops through all branches in the tables array
    if ($stock <= 0) {
        foreach ($tables as $key => $table) {
            if ($key == $store) continue;
    //c skip and check other br
            $check = $conn->prepare("SELECT quantity FROM $table WHERE product_name=?");
            $check->bind_param("s", $product);
            $check->execute();
            $res = $check->get_result();
            $data = $res->fetch_assoc();

            if ($data && $data['quantity'] > 0) {
                $otherStoresAvailable[] = ucfirst($key);
            }
        }
    }

    // BUTTON LOGIC
    $disabled = ($stock <= 0) ? "disabled style='background: #ff0000;color: white;cursor:not-allowed;'" : "";

    echo "
    <div style='border:1px solid #ccc; padding:10px; margin:10px;'>
        <h3>{$row['product_name']}</h3>
        <img src='{$row['image_url']}' width='100'><br>
        <p>{$row['description']}</p>
        <b>Price: £{$row['price']}</b><br>
        <b>Stock: {$stock}</b><br><br>
    ";

    // OUT OF STOCK MESSAGE
    if ($stock <= 0) {
        echo "<p style='color:red;'><strong>Out of Stock</strong></p>";

        if (!empty($otherStoresAvailable)) {
            echo "<p style='color:green;'>
                    Available in: <strong>" . implode(", ", $otherStoresAvailable) . "</strong>
                  </p>";
        } else {
            echo "<p style='color:gray;'>Not available in other stores</p>";
        }
    }

    echo "
        <button $disabled onclick=\"reserveProduct('{$row['product_name']}')\" 
            style='padding:6px 12px;color:#fff;background-color:green;border:none;border-radius:4px;width: 300px;'>
            ".($stock <= 0 ? "Out of Stock" : "Reserve Now")."
        </button>
    </div>
    ";
}
?>