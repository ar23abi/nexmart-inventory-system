<?php
session_start();
//protects the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$store_selected = isset($_SESSION['store']);
$store_name = $store_selected ? ucfirst($_SESSION['store']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexmart | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .btn-primary { background-color: #006d44; color: white; transition: 0.3s; }
        .btn-primary:hover { background-color: #004d30; }
        
        /* Modal Style for Store Selection if no store has been selected*/
        .modal {
            display: <?= $store_selected ? 'none' : 'flex' ?>;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            z-index: 100; justify-content: center; align-items: center;
        }
    </style>
</head>
<body class="pb-24">

<div class="modal" id="storeModal">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm text-center">
        <div class="text-[#006d44] text-4xl mb-4"><i class="fa-solid fa-store"></i></div>
        <h3 class="text-xl font-bold mb-2">Select Your Store</h3>
        <p class="text-gray-500 mb-6 text-sm">Choose a branch to see available products.</p>
        
        <div class="space-y-3">
            <button onclick="selectStore('hatfield')" class="w-full py-3 border rounded-xl hover:bg-green-50 hover:border-[#006d44] transition font-medium">Hatfield</button>
            <button onclick="selectStore('london')" class="w-full py-3 border rounded-xl hover:bg-green-50 hover:border-[#006d44] transition font-medium">London</button>
            <button onclick="selectStore('watford')" class="w-full py-3 border rounded-xl hover:bg-green-50 hover:border-[#006d44] transition font-medium">Watford</button>
        </div>
    </div>
</div>

<header class="bg-white border-b sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-8">
            <div class="text-2xl font-bold text-[#006d44] flex items-center">
                <span class="mr-2 bg-[#006d44] text-white px-2 py-0.5 rounded">N</span> Nexmart
            </div>
            <div class="hidden md:block text-sm text-gray-600">
                <i class="fa-solid fa-location-dot text-[#006d44] mr-1"></i>
                <strong>Store:</strong> <span id="currentStore"><?php echo $store_name ?: 'Not Selected'; ?></span>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <a href="my_reservations.php" class="bg-green-100 text-[#006d44] px-4 py-2 rounded-lg text-sm font-bold flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> My Reservations
            </a>
            <button onclick="changeStore()" class="bg-orange-100 text-orange-700 px-4 py-2 rounded-lg text-sm font-bold">
                Change Store
            </button>
            
            <a href="logout.php" class="bg-green-100 text-[#006d44] px-4 py-2 rounded-lg text-sm font-bold flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i>logout
            </a>
            
        </div>
    </div>
</header>


<img src="banner.jpeg" style="width: 2220px;">

<main class="container mx-auto px-4 mt-8">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Featured Products</h2>
        <span class="text-gray-400 text-sm">Showing availability for <span class="text-[#006d44] font-bold"><?= $store_name ?: 'None' ?></span></span>
    </div>

    <div id="products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        </div>
</main>

<div id="reserveModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index: 200; justify-content:center; align-items:center; backdrop-filter: blur(4px);">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm text-center">
        <div class="w-16 h-16 bg-green-100 text-[#006d44] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fa-solid fa-check"></i>
        </div>
        <h3 class="text-xl font-bold mb-4">Reservation Successful</h3>

        <div class="bg-gray-50 rounded-xl p-4 text-left text-sm space-y-3 border mb-6">
            <div class="flex justify-between border-b pb-1"><strong>Product:</strong> <span id="resProduct" class="text-gray-600"></span></div>
            <div class="flex justify-between border-b pb-1"><strong>Store:</strong> <span id="resStore" class="text-gray-600"></span></div>
            <div class="flex justify-between border-b pb-1"><strong>Date:</strong> <span id="resDate" class="text-gray-600"></span></div>
            <div class="flex justify-between"><strong>Time:</strong> <span id="resTime" class="text-gray-600"></span></div>
        </div>

        <button onclick="closeReserveModal()" class="w-full btn-primary py-3 rounded-xl font-bold">OK, Got it</button>
    </div>
</div>

<script>
    // Logic 
    function reserveProduct(productName) {
        let store = "<?= $_SESSION['store'] ?? '' ?>";
    //it reduces stock, it inserts a reservation
    //format of the submitted form-style data
        fetch("reserve_product.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "product_name=" + encodeURIComponent(productName) + "&store=" + store
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("resProduct").innerText = productName;
            document.getElementById("resStore").innerText = capitalize(store);
            document.getElementById("resDate").innerText = data.date;
            document.getElementById("resTime").innerText = data.time;
            document.getElementById("reserveModal").style.display = "flex";
        });
    }

    function closeReserveModal() {
    document.getElementById("reserveModal").style.display = "none";
    
    // Refresh page after closing modal
    location.reload();
}

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
        // Adding a small loading message inside the styled grid
        document.getElementById("products").innerHTML = '<p class="col-span-full text-center py-10 text-gray-400">Loading local stock...</p>';
        
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