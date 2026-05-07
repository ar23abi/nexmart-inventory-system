<?php
//  DB CONNECTION
$conn = new mysqli("localhost", "root", "", "nexmart");
//runs only when transport now button clicks
if (isset($_POST['transport'])) {
    $product_id = $_POST['product_id'];
    $transfer_qty = 5;

    //Fetch product details from main table
    $stmt = $conn->prepare("SELECT product_name, image_url, description, price, quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

//store product data
    if ($product) {
        $p_name  = $product['product_name'];
        $p_img   = $product['image_url'];
        $p_desc  = $product['description'];
        $p_price = $product['price'];
        $current_warehouse_qty = $product['quantity'];

        // will not transport if stock is too low
        if ($current_warehouse_qty < $transfer_qty) {
            die("Error: Not enough stock in Warehouse.");
        }

        $stores = ['store_hatfield', 'store_london', 'store_watford'];

foreach ($stores as $table) {
    // INSERT new row or UPDATE quantity if product ID exists
    $query = "INSERT INTO $table (id, product_name, image_url, description, price, quantity) 
              VALUES (?, ?, ?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE 
              quantity = quantity + ?, 
              product_name = ?, 
              price = ?";
    
    $stmt_store = $conn->prepare($query);

    /**
     * CORRECTED BIND PARAM:
     * 1. id: i
     * 2. product_name: s
     * 3. image_url: s
     * 4. description: s
     * 5. price: i
     * 6. quantity: i
     * 7. quantity update: i
     * 8. name update: s  <-- This was 'i', now fixed to 's'
     * 9. price update: i <-- This was 's', now fixed to 'i'
     * NEW STRING: isssiiisi
     */
    $stmt_store->bind_param("isssiiisi", 
        $product_id,    // 1 (i)
        $p_name,        // 2 (s)
        $p_img,         // 3 (s)
        $p_desc,        // 4 (s)
        $p_price,       // 5 (i)
        $transfer_qty,  // 6 (i)
        $transfer_qty,  // 7 (i)
        $p_name,        // 8 (s)
        $p_price        // 9 (i)
    );
    $stmt_store->execute();
}

// Calculate the total quantity being removed from the warehouse
$total_to_ship = $transfer_qty * count($stores); // This equals 15 (5 * 3)

// Safety check using the correct variable warehouse has enough for all stores
if ($product['quantity'] < $total_to_ship) {
    die("Error: Not enough stock to supply all stores. Need " . $total_to_ship . " units.");
}

//Subtract the TOTAL shipped amount from main stock
$update_main = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");

// FIXED: Changed $total_shipped to $total_to_ship
$update_main->bind_param("ii", $total_to_ship, $product_id); 
$update_main->execute();

header("Location: Ship_Products.php?status=success");
exit();
    } else {
        echo "Product not found.";
    }
}
?>