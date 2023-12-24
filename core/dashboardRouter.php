<?php
$dashboardRouter = new Router();
$dashboardRouter->define([
    '' => './pages/dashboard/index.php',
    'books' => './pages/dashboard/books.php',
    'readers' => './pages/dashboard/users.php',
    'rentedBooks' => './pages/dashboard/rentedBooks.php',
    'categories' => './pages/dashboard/categories.php',
    'payment' => './pages/dashboard/payment.php',
    'team' => './pages/dashboard/admin.php',
    'settings' => './pages/dashboard/settings.php',
    'books/addBook' => './pages/forms/addBook.php',
    'books/updateBook' => './pages/forms/addBook.php',
    'categories/addCategory' => './pages/forms/addCategory.php',
    'categories/updateCategory' => './pages/forms/addCategory.php',
    'addMember' => './pages/forms/registration.php',
    'settings/update' => './pages/forms/registration.php'
]);
$uri = trim(str_ireplace('admin', '', $uri), '/');
require $dashboardRouter->direct($uri);
?>