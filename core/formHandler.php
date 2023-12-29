<?php

// Redirect user to homepage if formHandler is opened directly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /libgen");
    exit;
}


require "./classes/validations.php";
require "./classes/user.php";
require "./classes/category.php";
require "./classes/books.php";
require "./classes/cart.php";
require "./classes/mail.php";
require "./classes/formController.php";
require "./classes/searchController.php";


function logout()
{
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}

function encrypt($data)
{
    $config = require "./core/config.php";
    return openssl_encrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
}

function decrypt($data)
{
    $config = require "./core/config.php";
    return openssl_decrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
}

/******* Handle Login and Logout *******/

// Handle Admin Login
if (isset($_POST['masterLogin'])) {
    $form = new FormController($_POST);
    $form->adminLogin();
}


// Handle User Login
if (isset($_POST['login'])) {
    $form = new FormController($_POST);
    $form->login();
}


// Handle Logout
if (isset($_POST['logout'])) {
    $form = new FormController();
    $form->logout();
}


/******* Handle Forgot Password and Reset Password *******/

// Handle Forgot Password
if (isset($_POST['forgotPassword'])) {
    $form = new FormController($_POST);
    $form->forgetPassword();
}


// Handle Reset Password
if (isset($_POST['resetPW'])) {
    $form = new FormController($_POST);
    $form->resetPW();
}

/******* Handle Users *******/

// Handle User Registration
if (isset($_POST['register'])) {
    $form = new FormController($_POST);
    $form->register();
}


// Handle Admin Registration
if (isset($_POST['registerAdmin'])) {
    $form = new FormController($_POST);
    $form->registerAdmin();
}

// Handle Update button of Admin
if (isset($_POST['updateAdmin'])) {
    header("Location: admin/settings/update?{$_POST['id']}");
    exit;
}


// Handle Update button of User
if (isset($_POST['updateUser'])) {
    header("Location: settings/update?{$_POST['id']}");
    exit;
}


// Handle Update data
if (isset($_POST['updateData'])) {
    $form = new FormController($_POST);
    $form->updateUserData();
}


// Handle delete account
if (isset($_POST['deleteAccount'])) {
    $form = new FormController($_POST);
    $form->deleteUserAccount();
}

/******* Handle Admins *******/

// Handle remove admin
if (isset($_POST['removeAdmin'])) {
    $form = new FormController($_POST);
    $form->removeAdmin();
}


// Make an Admin as Super Admin
if (isset($_POST['makeSuperAdmin'])) {
    $form = new FormController($_POST);
    $form->makeSuperAdminAdmin();
}


// Handle Remove Super Admin
if (isset($_POST['removeSuperAdmin'])) {
    $form = new FormController($_POST);
    $form->removeSuperAdmin();
}


// Handle block user
if (isset($_POST['blockUser'])) {
    $form = new FormController($_POST);
    $form->blockAccount();
}
/******* Handle Categories *******/

// Handle Add category
if (isset($_POST['addCategory'])) {
    $form = new FormController($_POST);
    $form->addCategory();
}


// Handle Update Category button
if (isset($_POST['updateCategory'])) {
    header("Location: admin/categories/updateCategory?{$_POST['id']}");
    exit;
}


// Update Category Data
if (isset($_POST['updateCategoryData'])) {
    $form = new FormController($_POST);
    $form->updateCategoryData();
}


// Delete Category
if (isset($_POST['deleteCategory'])) {
    $form = new FormController($_POST);
    $form->deleteCategory();
}

/******* Handle Books *******/

// Add a book
if (isset($_POST['addBook'])) {
    $form = new FormController($_POST);
    $form->addBook();
}


// Handle update book button
if (isset($_POST['updateBook'])) {
    header("Location: admin/books/updateBook?{$_POST['id']}");
    exit;
}


// Update Book Data
if (isset($_POST['updateBookData'])) {
    $form = new FormController($_POST);
    $form->updateBookData();
}


// Delete Book
if (isset($_POST['deleteBook'])) {
    $form = new FormController($_POST);
    $form->deleteBook();
}


// Handle Payment of Single Book
if (isset($_POST['payment'])) {
    $form = new FormController($_POST);
    $form->singleBookPayment();
}

/******* Handle Return Book with Fine or without Fine *******/

// Handle Return Book
if (isset($_POST['returnBook'])) {
    $form = new FormController($_POST);
    $form->returnBook();
}


// Handle Return Book with Fine
if (isset($_POST['returnBookFine'])) {
    $form = new FormController($_POST);
    $form->returnBookWithFine();
}

/******* Handle Cart *******/

// Handle Add to cart
if (isset($_POST['addToCart'])) {
    $form = new FormController($_POST);
    $form->addToCart();
}


// Handle Remove from cart
if (isset($_POST['removeFromCart'])) {
    $form = new FormController($_POST);
    $form->removeFromCart();
}


// Handle Cart Payment
if (isset($_POST['cartPayment'])) {
    $form = new FormController($_POST);
    $form->cartPayment();
}


/******* Handle Search Functionalities *******/

// Handle Serach Book in Admin Panel
if (isset($_POST['searchBookAdmin'])) {
    $search = new SearchController($_POST);
    $search->searchBooks();
}


// Handle Search Books Given on Rent
if (isset($_POST['searchRentedBook'])) {
    $search = new SearchController($_POST);
    $search->searchRentedBooks();
}


// Handle Search Category
if (isset($_POST['searchCategory'])) {
    $search = new SearchController($_POST);
    $search->searchCategory();
}


// Handle Search User
if (isset($_POST['searchUser'])) {
    $search = new SearchController($_POST);
    $search->searchUser();
}


// Handle Search Admin
if (isset($_POST['searchAdmin'])) {
    $search = new SearchController($_POST);
    $search->searchAdmin();
}


// Handle Search Payment
if (isset($_POST['searchPayment'])) {
    $search = new SearchController($_POST);
    $search->searchPayment();
}
?>