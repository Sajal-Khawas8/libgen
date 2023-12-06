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
    'update' => './pages/forms/registration.php',
    'login' => './pages/forms/login.php',
    'masterLogin' => './pages/forms/login.php',
    'formHandler' => './core/formHandler.php',
    'settings' => './pages/settings.php',
    'admin' => './pages/dashboard.php',
]);

$uri = trim(str_ireplace('libgen', '', $_SERVER['REQUEST_URI']), '/');
$queryString = parse_url($uri, PHP_URL_QUERY);
parse_str($queryString, $queryParams);
$uri = explode('?', $uri)[0];

// To refresh the page when user presses the back button
$_SESSION['refresh'] = false;
if ($_SESSION['refresh']) {
    header("Refresh: 0");
    $_SESSION['refresh'] = false;
}

// setcookie('prevPage', $uri);
if (strpos($uri, 'admin') === 0) {
    require $router->direct('admin');
} else {
    ob_start();
    require "./includes/header.php";
    extract($queryParams);
    require $router->direct($uri);
    ob_end_flush();
    require "./includes/footer.php";
}
?>