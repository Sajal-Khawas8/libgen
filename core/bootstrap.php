<?php
session_start();
if (isset($_COOKIE['user'])) {
    $config = require "./core/config.php";
    $user= openssl_decrypt($_COOKIE['user'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $user=unserialize($user);
    $_SESSION['user']=$user;
}
require "./classes/router.php";
require "./classes/database.php";
$router = new Router();
$router->define([
    '' => "./pages/index.php",
    'mybooks' => './pages/mybooks.php',
    'bookDetails' => './pages/bookDetails.php',
    'returnBook' => './pages/returnBook.php',
    'rentHistory' => './pages/rentHistory.php',
    'cart' => './pages/cart.php',
    'paymentSuccess' => './pages/paymentSuccess.php',
    'signUp' => './pages/forms/registration.php',
    'settings/update' => './pages/forms/registration.php',
    'login' => './pages/forms/login.php',
    'forgotPassword' => './pages/forms/forgotPassword.php',
    'resetPassword' => './pages/forms/resetPassword.php',
    'masterLogin' => './pages/forms/login.php',
    'formHandler' => './core/formHandler.php',
    'settings' => './pages/settings.php',
    'admin' => './pages/dashboard.php',
]);
?>