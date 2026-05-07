<?php
session_start();

// If already logged in
if (isset($_COOKIE['user_id'])) {
    header('Location: Dashboard.php');
    exit();
}

// Handle login
if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $captcha_input = trim($_POST['captcha_input']);

  
        // 🔐 Demo credentials
        if ($username === "warehouse" && $password === "12345") {

            $_SESSION['userSession'] = true;
            setcookie("user_id", $username, time() + (86400 * 30), "/");

            header("Location: Dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Warehouse System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
/* Main layout */
.container {
    display: flex;
    height: 100vh;
}

/* Columns */
.column {
    flex: 1;
    padding: 120px 50px 50px;
}

/* Left background */
.left-bg {
    background-color: #00a84f;
    background-image: url('https://dmrqkbkq8el9i.cloudfront.net/Pictures/2000xAny/8/0/4/247804_thefoodwarehouse100thstore_39836.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Login box */
.login-box {
    text-align: center;
}

/* Inputs */
.login-box input {
    width: 45%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Button */
#send {
    padding: 10px 20px;
    background-color: #609b0c;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    width: 50%;
    height: 50px;
}

#send:hover {
    background-color: #0056b3;
}

/* Captcha */
.captcha-box {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.captcha-box input {
    width: 260px;
    text-align: center;
    font-weight: bold;
    letter-spacing: 3px;
}

/* ========================= */
/* 📱 MOBILE VIEW FIX */
/* ========================= */
@media (max-width: 768px) {

    /* Hide left column */
    .left-bg {
        display: none;
    }

    /* Show only right column */
    .container {
        flex-direction: column;
    }

    .column {
        padding: 40px 0px;
        width: 100%;
    }

    /* Full width inputs on mobile */
    .login-box input,
    #send {
        width: 70%;
    }

    .captcha-box {
        flex-direction: column;
    }

    .captcha-box input {
        width: 70%;
    }
}


@media (max-width: 768px) {

    html, body {
        overflow-x: hidden;   /* ❌ no left scroll */
        overflow-y: auto;     /* ✅ allow vertical scroll */
        width: 100%;
    }

    .container {
        max-width: 100%;
        overflow-x: hidden;   /* ❌ no bottom scroll */
    }
}

</style>
</head>

<body style="margin:0;">



<!-- Desktop/Laptop login UI -->
<div class="container">

    <!-- Left column -->
    <div class="column left-bg"></div>

    <!-- Right column -->
    <div class="column">
        <center>
            <img style="width:50%; height:50%;"
                 src="https://www.smartcommerce.de/fileadmin/user_upload/Projekte-Referenzen/nexMart/logo-referenzen-nexmart.png">
            <br><br>

            <div class="login-box">
                <h2>Nexmart Management System</h2>

                <form method="post" action="">
                    <input type="text" name="username" placeholder="Username" required><br><br>

                    <input type="password" name="password" placeholder="Password" required><br><br>

                    <div class="captcha-box">
                        <input type="text" id="captcha" readonly>
                        <button type="button" onclick="generateCaptcha()">↻</button>
                    </div><br>

                    <input type="text" name="captcha_input" placeholder="Enter Captcha" required><br><br>

                    <input type="submit" name="login" value="Login" id="send">
                </form>
            </div>
        </center>
    </div>

</div>

<script>
function generateCaptcha() {
    let num = Math.floor(1000 + Math.random() * 9000);
    document.getElementById("captcha").value = num;

    // store captcha in session
    fetch("set_captcha.php?c=" + num);
}
window.onload = generateCaptcha;
</script>

</body>
</html>
