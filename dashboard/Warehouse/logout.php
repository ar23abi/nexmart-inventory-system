<?php
session_start();

/* 1️⃣ Unset all session variables */
$_SESSION = [];

/* 2️⃣ Destroy session */
session_destroy();

/* 3️⃣ Destroy user_id cookie (IMPORTANT) */
if (isset($_COOKIE['user_id'])) {
    setcookie(
        'user_id',
        '',
        time() - 3600,   // expire in the past
        '/',             // path (MUST match cookie creation)
        '',              // domain (keep empty unless explicitly set)
        isset($_SERVER["HTTPS"]), // secure
        true             // httponly
    );
}

/* 4️⃣ Redirect to login */
header("Location: login.php");
exit;
?>
