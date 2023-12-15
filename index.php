<?php

require "./core/bootstrap.php";

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