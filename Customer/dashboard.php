<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$store_selected = isset($_SESSION['store']);
$store_name = $store_selected ? ucfirst($_SESSION['store']) : '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
.modal {
    display: <?= $store_selected ? 'none' : 'flex' ?>;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
}
.modal-content {
    background:#fff;
    padding:20px;
    width:300px;
    text-align:center;
    border-radius:8px;
}
</style>

</head>

<body>

<!-- STORE MODAL -->
<div class="modal" id="storeModal">
    <div class="modal-content">
        <h3>Select Store</h3>

        <button onclick="selectStore('hatfield')">Hatfield</button><br><br>
        <button onclick="selectStore('london')">London</button><br><br>
        <button onclick="selectStore('watford')">Watford</button>
    </div>
</div>

<!-- STORE HEADER -->
<div style="padding:10px; background:#1a237e; color:#fff; display:flex; justify-content:space-between; align-items:center; border-radius:6px; margin-bottom:15px;">
    
    <div>
        <strong>Current Store:</strong> 
        <span id="currentStore"><?php echo $store_name ?: 'Not Selected'; ?></span>
    </div>

    <button onclick="changeStore()" style="padding:6px 12px; background:#ff9800; border:none; color:#fff; border-radius:4px; cursor:pointer;">
        Change Store
    </button>
    
    <a href="my_reservations.php" 
   style="padding:8px 15px; background:#4caf50; color:#fff; text-decoration:none; border-radius:5px;">
   My Reservations
</a>

</div>

<div id="products"></div>
<div id="reserveModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center;">
    
    <div style="background:#fff; padding:20px; border-radius:8px; text-align:center; width:300px;">
        <h3>✅ Reservation Successful</h3>

        <p><strong>Product:</strong> <span id="resProduct"></span></p>
        <p><strong>Store:</strong> <span id="resStore"></span></p>
        <p><strong>Date:</strong> <span id="resDate"></span></p>
        <p><strong>Time:</strong> <span id="resTime"></span></p>

        <button onclick="closeReserveModal()" style="margin-top:10px;">OK</button>
    </div>

</div>

<script>
    function reserveProduct(productName) {
    let store = "<?= $_SESSION['store'] ?? '' ?>";

    fetch("reserve_product.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "product_name=" + encodeURIComponent(productName) + "&store=" + store
    })
    .then(res => res.json())
    .then(data => {

        // Fill modal
        document.getElementById("resProduct").innerText = productName;
        document.getElementById("resStore").innerText = capitalize(store);
        document.getElementById("resDate").innerText = data.date;
        document.getElementById("resTime").innerText = data.time;

        // Show modal
        document.getElementById("reserveModal").style.display = "flex";
    });
}

function closeReserveModal() {
    document.getElementById("reserveModal").style.display = "none";
}
</script>


<script>

// AUTO LOAD products if store already selected
<?php if ($store_selected): ?>
    loadProducts("<?= $_SESSION['store'] ?>");
<?php endif; ?>

function selectStore(store) {
    fetch("set_store.php?store=" + store)
    .then(() => {
        document.getElementById("storeModal").style.display = "none";
        
        document.getElementById("currentStore").innerText = capitalize(store);

        loadProducts(store);
    });
}

function loadProducts(store) {
    fetch("get_products.php?store=" + store)
    .then(res => res.text())
    .then(data => {
        document.getElementById("products").innerHTML = data;
    });
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function changeStore() {
    fetch("set_store.php?store=reset")
    .then(() => {
        location.reload();
    });
}

</script>

</body>
</html>