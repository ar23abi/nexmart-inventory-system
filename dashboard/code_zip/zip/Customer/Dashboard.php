<?php
session_start();
$conn = new mysqli("localhost", "root", "", "stlmcoin_gps_data");

// Handle store selection
if(isset($_POST['select_store'])){
    $_SESSION['store'] = $_POST['store'];
}

// Get selected store
$store = $_SESSION['store'] ?? null;

// Fetch products if store selected
if($store){
    $stmt = $conn->prepare("SELECT * FROM store_products WHERE store=?");
    $stmt->bind_param("s", $store);
    $stmt->execute();
    $products = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Nexmart</title>

<style>
body { font-family: Arial; background:#f5f6fa; }

/* Header */
.header {
    display:flex;
    justify-content:space-between;
    padding:15px;
    background:#fff;
}

/* Product Cards */
.products {
    display:flex;
    gap:20px;
    padding:20px;
}
.card {
    width:220px;
    background:#fff;
    padding:10px;
    border-radius:10px;
    box-shadow:0 0 10px #ddd;
}
.card img { width:100%; border-radius:8px; }

.btn {
    background:#2e7d32;
    color:#fff;
    padding:8px;
    border:none;
    width:100%;
    margin-top:10px;
    border-radius:6px;
}

/* Modal */
.modal {
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    display:flex;
    justify-content:center;
    align-items:center;
}

.modal-box {
    background:#fff;
    padding:20px;
    width:400px;
    border-radius:10px;
}

.store-option {
    padding:10px;
    border:1px solid #ccc;
    margin:5px 0;
    border-radius:6px;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="header">
    <h2>🛒 Nexmart</h2>
    <button onclick="openModal()">Select Store</button>
</div>

<?php if(!$store): ?>
<!-- Store Modal -->
<div class="modal" id="storeModal">
    <div class="modal-box">
        <h3>Select Store Location</h3>

        <form method="POST">
            <div class="store-option">
                <input type="radio" name="store" value="hatfield" required> Hatfield
            </div>
            <div class="store-option">
                <input type="radio" name="store" value="watford"> Watford
            </div>
            <div class="store-option">
                <input type="radio" name="store" value="london"> London
            </div>

            <button class="btn" name="select_store">Select</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Products -->
<?php if($store): ?>
<h3 style="padding:20px;">Store: <?= ucfirst($store) ?></h3>

<div class="products">
<?php while($row = $products->fetch_assoc()): ?>
    <div class="card">
        <img src="<?= $row['image_url'] ?>">
        <h4><?= $row['product_name'] ?></h4>
        <p><?= $row['description'] ?></p>
        <b>£<?= rand(5,20) ?></b>

        <form method="POST" action="reserve.php">
            <input type="hidden" name="product_name" value="<?= $row['product_name'] ?>">
            <button class="btn">Reserve Now</button>
        </form>
    </div>
<?php endwhile; ?>
</div>
<?php endif; ?>

<script>
function openModal(){
    document.getElementById("storeModal").style.display = "flex";
}
</script>

</body>
</html>