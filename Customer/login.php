<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users_archi WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ui.php");
    } else {
        echo "Invalid login!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Nexmart</title>
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
/*  MOBILE VIEW FIX */
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
        overflow-x: hidden;   /*  no left scroll */
        overflow-y: auto;     /* allow vertical scroll */
        width: 100%;
    }

    .container {
        max-width: 100%;
        overflow-x: hidden;   /*  no bottom scroll */
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
                 src="logoo.png">
            <br><br>

            <div class="login-box">
                <h2>Nexmart Customer Login</h2>

                
                    
                    <form method="POST">
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button class="send" name="login" style="
    padding: 10px 20px;
    background-color: #609b0c;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    width: 50%;
    height: 50px;
">Login</button>
</form>
                    
                    
              
            </div>
        </center>
    </div>

</div>



</body>
</html>
