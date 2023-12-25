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

function logout()
{
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}

/******* Handle Login and Logout *******/

// Handle Admin Login
if (isset($_POST['masterLogin'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $err = [
        'emailErr' => $validation->validateAdminLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $_POST['email'], $isDataValid)
    ];
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /masterLogin");
        exit;
    }

    $query = new DatabaseQuery();
    $user = $query->selectOne('users', $_POST['email'], 'email');
    $user = [$user['uuid'], $user['role']];
    $_SESSION['user'] = $user;
    $_SESSION['refresh'] = true;
    header("Location: /admin");
    exit;
}


// Handle User Login
if (isset($_POST['login'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $err = [
        'emailErr' => $validation->validateLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $_POST['email'], $isDataValid)
    ];
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /login");
        exit;
    }

    $query = new DatabaseQuery();
    $user = $query->selectOne('users', $_POST['email'], 'email');
    $user = [$user['uuid'], $user['role']];
    $_SESSION['user'] = $user;
    $user = serialize($user);
    $config = require "./core/config.php";
    $user = openssl_encrypt($user, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('user', $user, time() + 86400);
    $_SESSION['refresh'] = true;
    $location = $_COOKIE['prevPage'] ?? '/libgen';
    header("Location: $location");
    exit;
}


// Handle Logout
if (isset($_POST['logout'])) {
    logout();
}


/******* Handle Forgot Password and Reset Password *******/

// Handle Forgot Password
if (isset($_POST['forgotPassword'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $err = $validation->validateLoginEmail($_POST['email'], $isDataValid);

    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', $err, time() + 2);
        setcookie('data', $_POST['email'], time() + 2);
        header("Location: /forgotPassword");
        exit;
    }

    $id = uniqid();
    $query = new DatabaseQuery();
    $query->update('users', "uniqueID='$id'", $_POST['email'], 'email');
    $config = require "./core/config.php";
    $link = openssl_encrypt($_POST['email'] . "&" . $id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $mail = new MailClass();
    $message = "<table style='width: 100%; border-collapse: collapse;'>
    <tr>
      <td style='text-align: center; font-size: 18px; line-height: 24px;'>
        Please click on the following button to reset your password:
      </td>
    </tr>
    <tr>
      <td style='text-align: center; padding-top: 20px;'>
        <a href='http://localhost/resetPassword?$link' style='display: inline-block; text-align: center; text-decoration: none; padding: 8px 13px; background-color: #0054ff; color: white; font-size: 20px; border-radius: 10px;'>
          Reset Password
        </a>
      </td>
    </tr>
  </table>";
    $mail->sendMail('Reset Password', $message, $_POST['email']);
    header("Location: /libgen");
}


// Handle Reset Password
if (isset($_POST['resetPW'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        setcookie('error', true, time() + 2);
        setcookie('message', 'Some Error Occurred', time() + 2);
        header("Location: /libgen");
        exit;
    }
    $err = [
        'passwordErr' => $validation->validatePasswordFormat($_POST['password'], $isDataValid),
        'cnfrmPasswordErr' => $validation->validateCnfrmPassword($_POST['cnfrmPassword'], $_POST['password'], $isDataValid),
    ];
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        header("Location: /resetPassword?" . $_POST['id']);
        exit;
    }

    list($email, $id) = explode("&", $id);
    $query = new DatabaseQuery();
    $idFromDb = $query->selectColumn('uniqueID', 'users', $email, 'email');
    if ($idFromDb === $id) {
        $query->update('users', "password='{$_POST['password']}', uniqueID=null", $email, 'email');
        setcookie('message', 'Password has been updated successfully', time() + 2);
        header("Location: /login");
        exit;
    } else {
        setcookie('error', true, time() + 2);
        setcookie('message', 'Some Error Occurred!', time() + 2);
        header("Location: /forgotPassword");
        exit;
    }
}

/******* Handle Users *******/

// Handle User Registration
if (isset($_POST['register'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['register'], $_POST['id'], $_POST['role']);
    $uuid = $user->addUser($_POST);
    $user = [$uuid, 1];
    $_SESSION['user'] = $user;
    $user = serialize($user);
    $config = require "./core/config.php";
    $user = openssl_encrypt($user, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('user', $user, time() + 86400);
    $_SESSION['refresh'] = true;
    $location = $_COOKIE['prevPage'] ?? '/libgen';
    header("Location: $location");
    exit;
}


// Handle Admin Registration
if (isset($_POST['registerAdmin'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['registerAdmin'], $_POST['id']);
    $_POST['role'] = 2;
    $user->addUser($_POST);
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}

// Handle Update button of Admin
if (isset($_POST['updateAdmin'])) {
    header("Location: admin/settings/update?{$_POST['id']}");
}


// Handle Update button of User
if (isset($_POST['updateUser'])) {
    header("Location: settings/update?{$_POST['id']}");
}


// Handle Update data
if (isset($_POST['updateData'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    unset($_POST['updateData'], $_POST['id']);
    if ($id !== $_SESSION['user'][0]) {
        logout();
    }
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    $updateSuccess = $user->updateUser($_POST, $_SESSION['user'][0]);
    if (!$updateSuccess) {
        $_SESSION['refresh'] = true;
        $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $location = ($_SESSION['user'][1] != 1) ? '/admin/settings/update' : '/settings/update';
        header("Location: $location?$id");
        exit;
    }
    $_SESSION['refresh'] = true;
    $location = ($_SESSION['user'][1] != 1) ? '/admin/settings' : '/settings';
    header("Location: $location");
    exit;
}


// Handle delete account
if (isset($_POST['deleteAccount'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id !== $_SESSION['user'][0]) {
        logout();
    }
    $user = new User();
    $user->removeUser($id);
    logout();
    exit;
}

/******* Handle Admins *******/

// Handle remove admin
if (isset($_POST['removeAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') == 3);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id || !$isSuperAdmin) {
        logout();
    }
    $user = new User();
    $user->removeUser($id);
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}


// Make an Admin as Super Admin
if (isset($_POST['makeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') == 3);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id || !$isSuperAdmin) {
        logout();
    }
    $query->update('users', 'role=3', $id, 'uuid');
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}


// Handle Remove Super Admin
if (isset($_POST['removeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') == 3);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id || !$isSuperAdmin) {
        logout();
    }
    $query->update('users', 'role=2', $id, 'uuid');
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}


// Handle block user
if (isset($_POST['blockUser'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    $user = new User();
    $user->removeUser($id);
    $_SESSION['refresh'] = true;
    header("Location: /admin/readers");
    exit;
}

/******* Handle Categories *******/

// Handle Add category
if (isset($_POST['addCategory'])) {
    $validationObj = new ValidateData();
    $category = new Category($validationObj);
    unset($_POST['addCategory'], $_POST['id']);
    $category->addCategory($_POST);
}


// Handle Update Category button
if (isset($_POST['updateCategory'])) {
    header("Location: admin/categories/updateCategory?{$_POST['id']}");
    exit;
}


// Update Category Data
if (isset($_POST['updateCategoryData'])) {
    $validationObj = new ValidateData();
    $category = new Category($validationObj);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    unset($_POST['updateCategoryData'], $_POST['id']);
    $isUpdateSuccess = $category->updateCategory($_POST, $id);
    if (!$isUpdateSuccess) {
        $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: admin/categories/updateCategory?$id");
        exit;
    }
    header("Location: admin/categories");
    exit;
}


// Delete Category
if (isset($_POST['deleteCategory'])) {
    $category = new Category();
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    $category->removeCategory($id);
    header("Location: admin/categories");
    exit;
}

/******* Handle Books *******/

// Add a book
if (isset($_POST['addBook'])) {
    $validationObj = new ValidateData();
    $book = new Book($validationObj);
    unset($_POST['addBook'], $_POST['id']);
    $book->addBook($_POST);
}


// Handle update book button
if (isset($_POST['updateBook'])) {
    header("Location: admin/books/updateBook?{$_POST['id']}");
    exit;
}


// Update Book Data
if (isset($_POST['updateBookData'])) {
    $validationObj = new ValidateData();
    $book = new Book($validationObj);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    unset($_POST['addBook'], $_POST['id']);
    $isUpdateSuccess = $book->updateBook($_POST, $id);
    if (!$isUpdateSuccess) {
        $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: admin/books/updateBook?$id");
        exit;
    }
    header("Location: /admin/books");
    exit;
}


// Delete Book
if (isset($_POST['deleteBook'])) {
    $book = new Book();
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    $book->removeBook($id);
    header("Location: /admin/books");
    exit;
}


// Handle Payment of Single Book
if (isset($_POST['payment'])) {
    if (!isset($_SESSION['user'])) {
        header("Location: /login");
        exit;
    }
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuid) {
        logout();
    }
    unset($_POST['payment'], $_POST['amount'], $_POST['id']);
    $validation = new ValidateData();
    $isDataValid = true;
    $err = [
        'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
        'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
        'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
        'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
        'returnDateErr' => $validation->validateNumber($_POST['returnDate'], $isDataValid, 'Period'),
    ];
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }

    $query = new DatabaseQuery();
    $joins = [
        [
            'table' => 'category',
            'condition' => 'books.category_id = category.id'
        ],
        [
            'table' => 'quantity',
            'condition' => 'quantity.book_id = books.book_uuid'
        ],
    ];
    $bookData = $query->selectOneJoin('books', $joins, '*', $uuid, 'book_uuid');
    $currentDate = new DateTime();
    $dueDate = $currentDate->add(new DateInterval("P" . $_POST['returnDate'] . "D"));
    $dueDate = $dueDate->format("Y-m-d");
    $paymentId = uniqid("pay-");
    $paymentData = [
        'payment_id' => $paymentId,
        'user_id' => $_SESSION['user'][0],
        'amount' => ($bookData['rent'] * $_POST['returnDate']),
        'card' => substr($_POST['cardNumber'], -4)
    ];
    $query->add('payment', $paymentData);

    $paidItems = [
        'payment_id' => $paymentId,
        'book_id' => $bookData['book_uuid'],
    ];
    $query->add('paid_items', $paidItems);

    $rentStatus = [
        'book_id' => $uuid,
        'user_id' => $_SESSION['user'][0],
        'date' => date("Y-m-d"),
        'due_date' => $dueDate
    ];
    $query->add('orders', $rentStatus);
    $availableBooks = $bookData['available'] - 1;
    $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');
    $conditions = [
        'book_id' => $uuid,
        'user_id' => $_SESSION['user'][0],
    ];
    $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
    $cart = new Cart();
    $cart->removeItem($cartId);
    setcookie('message', 'Payment is Successful', time() + 2);
    header("Location: /mybooks");
    exit;
}

/******* Handle Return Book with Fine or without Fine *******/

// Handle Return Book
if (isset($_POST['returnBook'])) {
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuid) {
        logout();
    }
    $query = new DatabaseQuery();
    $conditions = [
        'book_id' => $uuid,
        'user_id' => $_SESSION['user'][0],
    ];
    $rentedBookData = $query->selectAllMultiCondition('orders', $conditions);
    $query->delete('orders', $rentedBookData[0]['id']);
    $issueDate = new DateTime($rentedBookData[0]['date']);
    $dueDate = new DateTime($rentedBookData[0]['due_date']);
    $interval = $dueDate->diff($issueDate);
    $days = $interval->days;
    $rentHistory = [
        'user_id' => $_SESSION['user'][0],
        'book_id' => $uuid,
        'issue_date' => $rentedBookData[0]['date'],
        'due_date' => $rentedBookData[0]['due_date'],
        'return_date' => date("Y-m-d"),
        'rent_paid' => ($query->selectColumn('rent', 'books', $uuid, 'book_uuid') * $days),
        'fine_paid' => 0
    ];
    $query->add('rent_history', $rentHistory);
    $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
    $availableBooks++;
    $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');
    setcookie('message', 'Book Returned Successfully', time() + 2);
    header("Location: /mybooks");
    exit;
}


// Handle Return Book with Fine
if (isset($_POST['returnBookFine'])) {
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $amount = openssl_decrypt($_POST['amount'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuid || !$amount) {
        logout();
    }
    $validation = new ValidateData();
    $isDataValid = true;
    $err = [
        'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
        'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
        'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
        'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
    ];
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }

    $query = new DatabaseQuery();
    $conditions = [
        'book_id' => $uuid,
        'user_id' => $_SESSION['user'][0],
    ];
    $rentedBookData = $query->selectAllMultiCondition('orders', $conditions);
    $query->delete('orders', $rentedBookData[0]['id']);
    $issueDate = new DateTime($rentedBookData[0]['date']);
    $dueDate = new DateTime($rentedBookData[0]['due_date']);
    $interval = $dueDate->diff($issueDate);
    $days = $interval->days;
    $rentHistory = [
        'user_id' => $_SESSION['user'][0],
        'book_id' => $uuid,
        'issue_date' => $rentedBookData[0]['date'],
        'due_date' => $rentedBookData[0]['due_date'],
        'return_date' => date("Y-m-d"),
        'rent_paid' => ($query->selectColumn('rent', 'books', $uuid, 'book_uuid') * $days),
        'fine_paid' => $amount
    ];
    $query->add('rent_history', $rentHistory);
    $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
    $availableBooks++;
    $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');
    $paymentId = uniqid("pay-");
    $paymentData = [
        'payment_id' => $paymentId,
        'user_id' => $_SESSION['user'][0],
        'amount' => $amount,
        'card' => substr($_POST['cardNumber'], -4),
        'type' => 2
    ];
    $query->add('payment', $paymentData);

    $paidItems = [
        'payment_id' => $paymentId,
        'book_id' => $uuid,
    ];
    $query->add('paid_items', $paidItems);

    setcookie('message', 'Payment is Successful and Book is Returned', time() + 2);
    header("Location: /mybooks");
    exit;
}

/******* Handle Cart *******/

// Handle Add to cart
if (isset($_POST['addToCart'])) {
    if (!isset($_SESSION['user'])) {
        header("Location: /login");
        exit;
    }
    $config = require "./core/config.php";
    $uuidBook = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuidBook) {
        logout();
    }
    $query = new DatabaseQuery();
    $cart = new Cart();
    $cart->addItem($uuidBook);
    header("Location: {$_COOKIE['prevPage']}");
    exit;
}


// Handle Remove from cart
if (isset($_POST['removeFromCart'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        logout();
    }
    $cart = new Cart();
    $cart->removeItem($id);
    header("Location: {$_COOKIE['prevPage']}");
    exit;
}


// Handle Cart Payment
if (isset($_POST['cartPayment'])) {
    $config = require "./core/config.php";
    $cartItems = openssl_decrypt($_POST['items'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$cartItems) {
        setcookie('error', true, time() + 2);
        setcookie('message', 'All Books are currently Out of Stock', time() + 2);
        header("Location: /cart");
        exit;
    }
    $cartItems = explode('&', $cartItems);
    unset($cartItems[0]);
    $validation = new ValidateData();
    $isDataValid = true;
    $err = [
        'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
        'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
        'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
        'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid)
    ];
    $query = new DatabaseQuery();
    $bookData = [];
    $joins = [
        [
            'table' => 'quantity',
            'condition' => 'quantity.book_id = books.book_uuid'
        ]
    ];
    foreach ($cartItems as $itemId) {
        $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
        $err[$bookData['title'] . 'returnDateErr'] = $validation->validateNumber($_POST["returnDate-" . str_replace(" ", "_", $bookData['title'])], $isDataValid, "Period");
    }
    if (!$isDataValid) {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }

    $paymentId = uniqid("pay-");
    $paymentData = [
        'payment_id' => $paymentId,
        'user_id' => $_SESSION['user'][0],
        'amount' => 0,
        'card' => substr($_POST['cardNumber'], -4)
    ];
    $query->add('payment', $paymentData);
    $totalRent = 0;
    foreach ($cartItems as $itemId) {
        $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
        $days = $_POST['returnDate-' . str_replace(" ", "_", $bookData['title'])];
        $currentDate = new DateTime();
        $dueDate = $currentDate->add(new DateInterval("P" . $days . "D"));
        $dueDate = $dueDate->format("Y-m-d");
        $totalRent += ($bookData['rent'] * $days);
        $rentStatus = [
            'book_id' => $itemId,
            'user_id' => $_SESSION['user'][0],
            'date' => date("Y-m-d"),
            'due_date' => $dueDate
        ];
        $query->add('orders', $rentStatus);
        $availableBooks = $bookData['available'];
        $availableBooks--;
        $query->update('quantity', "available=$availableBooks", $bookData['book_id'], 'book_id');
        $conditions = [
            'user_id' => $_SESSION['user'][0],
            'book_id' => $itemId,
        ];
        $cart = new Cart();
        $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
        $cart->removeItem($cartId);

        $paidItems = [
            'payment_id' => $paymentId,
            'book_id' => $bookData['book_uuid'],
        ];
        $query->add('paid_items', $paidItems);
    }

    $query->update("payment", "amount = $totalRent", $paymentId, "payment_id");

    setcookie('message', 'Payment is Successful', time() + 2);
    header("Location: /mybooks");
    exit;
}


/******* Handle Search Functionalities *******/

// Handle Search Books in Home Page
if (isset($_POST['searchBookHome'])) {
    $config = require "./core/config.php";
    $categoryId = openssl_decrypt($_POST['category'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$categoryId) {
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /libgen");
        exit;
    }
    $query = new DatabaseQuery();
    $condition = [
        'active' => true,
    ];
    if ($categoryId !== 'all') {
        $condition['category_id'] = $categoryId;
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['id'];
    }
    $bookIds = ltrim($bookIds, "&");
    $bookIds = openssl_encrypt($bookIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /libgen?" . $bookIds . "#search");
    exit;
}


// Handle Serach Book in Admin Panel
if (isset($_POST['searchBookAdmin'])) {
    $config = require "./core/config.php";
    $categoryId = openssl_decrypt($_POST['category'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$categoryId) {
        setcookie('data', serialize($_POST), time() + 2);
        setcookie("err", "*Something went wrong!", time() + 2);
        header("Location: /admin/books");
        exit;
    }
    $query = new DatabaseQuery();
    $condition = [
        'active' => true,
    ];
    if ($categoryId !== 'all') {
        $condition['category_id'] = $categoryId;
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['id'];
    }
    $bookIds = ltrim($bookIds, "&");
    $bookIds = openssl_encrypt($bookIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/books?" . $bookIds);
    exit;
}


// Handle Search Books Given on Rent
if (isset($_POST['searchRentedBook'])) {
    $config = require "./core/config.php";
    $categoryId = openssl_decrypt($_POST['category'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$categoryId) {
        setcookie('data', serialize($_POST), time() + 2);
        setcookie("err", "*Something went wrong!", time() + 2);
        header("Location: admin/rentedBooks");
        exit;
    }
    $query = new DatabaseQuery();
    $condition = [];
    if ($categoryId !== 'all') {
        $condition = [
            'category_id' => $categoryId,
        ];
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['book_uuid'];
    }
    $bookIds = ltrim($bookIds, "&");
    $bookIds = openssl_encrypt($bookIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/rentedBooks?" . $bookIds);
    exit;
}


// Handle Search Category
if (isset($_POST['searchCategory'])) {
    $config = require "./core/config.php";
    $query = new DatabaseQuery();
    $categories = $query->selectPartial('category', ['name'], $_POST['categoryName']);
    $categoryIds = '';
    foreach ($categories as $category) {
        $categoryIds .= "&" . $category['id'];
    }
    $categoryIds = ltrim($categoryIds, "&");
    $categoryIds = openssl_encrypt($categoryIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/categories?" . $categoryIds);
    exit;
}


// Handle Search User
if (isset($_POST['searchUser'])) {
    $config = require "./core/config.php";
    $query = new DatabaseQuery();
    $condition = [
        'active' => true
    ];
    $users = $query->selectPartial('users', ['name', 'email'], $_POST['userName'], $condition);
    $userIds = '';
    foreach ($users as $user) {
        $userIds .= "&" . $user['id'];
    }
    $userIds = ltrim($userIds, "&");
    $userIds = openssl_encrypt($userIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/readers?" . $userIds);
    exit;
}


// Handle Search Admin
if (isset($_POST['searchAdmin'])) {
    $config = require "./core/config.php";
    $query = new DatabaseQuery();
    $condition = [
        'active' => true,
    ];
    $admins = $query->selectPartial('users', ['name', 'email'], $_POST['adminName'], $condition);
    $adminIds = '';
    foreach ($admins as $admin) {
        if ($admin['role'] !== '1') {
            $adminIds .= "&" . $admin['id'];
        }
    }
    $adminIds = ltrim($adminIds, "&");
    $adminIds = openssl_encrypt($adminIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/team?" . $adminIds);
    exit;
}


// Handle Search Payment
if (isset($_POST['searchPayment'])) {
    $config = require "./core/config.php";
    $query = new DatabaseQuery();
    $users = $query->selectPartial('users', ['name', 'email'], $_POST['userName']);
    $userIds = '';
    foreach ($users as $user) {
        $userIds .= "&" . $user['uuid'];
    }
    $userIds = ltrim($userIds, "&");
    $userIds = openssl_encrypt($userIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/payment?" . $userIds);
    exit;
}
?>