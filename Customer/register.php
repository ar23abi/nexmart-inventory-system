<?php
include "db.php";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // FIXED QUERY
    $sql = "INSERT INTO users_archi (name, phone, email, password) 
            VALUES ('$name', '$phone', '$email', '$password')";
    
    if ($conn->query($sql)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $conn->error; // show actual error
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    
    <input type="password" name="password" placeholder="Password" required><br>
    <button name="register">Register</button>
</form>