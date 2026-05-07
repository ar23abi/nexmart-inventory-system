<?php

// ✅ Database Connection (FIXED)
$conn = new mysqli("localhost","root","","nexmart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




// ✅ Get total products count
$total_products = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM products");
if ($result) {
    $row = $result->fetch_assoc();
    $total_products = $row['total'];
}

// ✅ Count products in each store

$hatfield_count = 0;
$watford_count = 0;
$london_count = 0;

// Hatfield
$res1 = $conn->query("SELECT COUNT(*) as total FROM store_hatfield");
if ($res1 && $row = $res1->fetch_assoc()) {
    $hatfield_count = $row['total'];
}

// Watford
$res2 = $conn->query("SELECT COUNT(*) as total FROM store_watford");
if ($res2 && $row = $res2->fetch_assoc()) {
    $watford_count = $row['total'];
}

// London
$res3 = $conn->query("SELECT COUNT(*) as total FROM store_london");
if ($res3 && $row = $res3->fetch_assoc()) {
    $london_count = $row['total'];

}


?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />

    <title>Warehouse Management System</title>

    <link rel="stylesheet" href="style.css" />
    <!-- Boxicons CDN Link -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  </head>
  <body>
      
      
      

    
    
    <?php
    
 include('menu.php');
    ?>
    
    
    
    
    
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
<div class="overview-boxes">

  <div class="box">
    <div class="right-side">
      <div class="box-topic">Total Products</div>
      <div class="number"><?= $total_products ?></div>
    </div>
    <i class="bx bx-cart cart three"></i>
  </div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (Hatfield)</div>
    <div class="number" style="color:green"><?= $hatfield_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (Watford)</div>
    <div class="number" style="color:red"><?= $watford_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>

<div class="box">
  <div class="right-side">
    <div class="box-topic">Store (London)</div>
    <div class="number"><?= $london_count ?></div>
  </div>
  <i class="bx bx-cart cart three"></i>
</div>


</div>


        <div class="sales-boxes">
          <div class="recent-sales box">
  <div class="title">📍 All Store Locations On Map</div>
  <br>
  <div id="map" style="height:420px;width:95%;border-radius:10px;"></div>
</div>

         
        </div>
      </div>
    </section>
    
    <br> <br>
    
   <div id="map" style="height:420px;width:95%;border-radius:10px;"></div>

<script>
function initMap() {

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        center: { lat: 20.5937, lng: 78.9629 } // India center
    });

    // ✅ Static locations
    const markers = [
        {
            lat: 51.76307453805482, 
            lng: -0.22332398153049615,
            title: "Hatfield Store"
        },
        {
            lat: 51.666015,  
            lng: -0.395213,
            title: "Watford Store"
        },
        {
            lat: 51.506307,
            lng: -0.125814,
            title: "London Store"
        }
    ];

    const bounds = new google.maps.LatLngBounds();

    markers.forEach(m => {

        const pos = { lat: m.lat, lng: m.lng };
        bounds.extend(pos);

        const marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: m.title
        });

        const info = new google.maps.InfoWindow({
            content: `<b>${m.title}</b>`
        });

        marker.addListener("click", () => info.open(map, marker));
    });

    // Auto zoom to fit all markers
    map.fitBounds(bounds);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTeAVM4rKi6G7Q87HfDVhK7xSUeyCfNnQ&callback=initMap" async defer></script>

    <script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      };
    </script>
 
</body>
</html>
