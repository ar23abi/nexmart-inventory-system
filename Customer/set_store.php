<?php
session_start();

$allowed = ['hatfield','london','watford'];

//prevents invalid branch values from being saved
if (in_array($_GET['store'], $allowed)) {
    $_SESSION['store'] = $_GET['store'];
}

//choose again
if ($_GET['store'] == "reset") {
    unset($_SESSION['store']);
    exit();
}
?>