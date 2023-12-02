<?php
require "./core/bootstrap.php";
$router = new Router();
$router->define([
    '' => "./pages/index.php",
    'mybooks' => './pages/mybooks.php',
    'bookDetails' => './pages/bookDetails.php',
    'returnBook' => './pages/returnBook.php',
    'cart' => './pages/cart.php',
    'success' => './pages/paymentSuccess.php',
    'signUp' => './pages/forms/registration.php',
    'login' => './pages/forms/login.php',
    'adminLogin' => './pages/forms/login.php',
    'formHandler'=>'./core/formHandler.php',
    'app/dashboard'=>'./pages/dashboard.php',
]);
$uri=trim(str_ireplace('libgen', '', $_SERVER['REQUEST_URI']), '/');
if (strpos($uri, 'app/dashboard') === 0) {
    require $router->direct('app/dashboard');
} else {
    ob_start();
    require "./includes/header.php";
    require $router->direct($uri);
    ob_end_flush();
    require "./includes/footer.php";
}
?>