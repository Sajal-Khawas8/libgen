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

/******* Handle Login and Logout *******/

// Handle Admin Login
if (isset($_POST['masterLogin'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $err = [
        'emailErr' => $validation->validateAdminLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $_POST['email'], $isDataValid)
    ];
    if ($isDataValid) {
        $query = new DatabaseQuery();
        $user = $query->selectOne('users', $_POST['email'], 'email');
        $user = [$user['uuid'], $user['role']];
        $_SESSION['user'] = $user;
        $_SESSION['refresh'] = true;
        header("Location: /admin");
        exit;
    }
    $_SESSION['refresh'] = true;
    setcookie('err', serialize($err), time() + 2);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /masterLogin");
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
    if ($isDataValid) {
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
    $_SESSION['refresh'] = true;
    setcookie('err', serialize($err), time() + 2);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /login");
    exit;
}


// Handle Logout
if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}


/******* Handle Forgot Password and Reset Password *******/

// Handle Forgot Password
if (isset($_POST['forgotPassword'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $err = $validation->validateLoginEmail($_POST['email'], $isDataValid);

    if ($isDataValid) {
        $id = uniqid();
        $query = new DatabaseQuery();
        $query->update('users', "uniqueID='$id', ", $_POST['email'], 'email');
        $config = require "./core/config.php";
        $link = openssl_encrypt($_POST['email'] . "&" . $id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $mail = new MailClass();
        $message = "<p style='display: flex; font-size: 18px; align-items: center; flex-direction: column; gap: 50px;'>Please click on the following button to reset your password:<a href='http://localhost/resetPassword?$link' style='display: inline-block; text-align: center; text-decoration: none; padding: 8px 13px; background-color: #0054ff; color: white; font-size: 20px; border-radius: 10px;'>Reset Password</a></p>";
        $mail->sendMail('Reset Password', $message, $_POST['email']);
        header("Location: /libgen");
    }
    $_SESSION['refresh'] = true;
    setcookie('err', $err, time() + 2);
    setcookie('data', $_POST['email'], time() + 2);
    header("Location: /forgotPassword");
    exit;
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
    if ($isDataValid) {
        list($email, $id) = explode("&", $id);
        $query = new DatabaseQuery();
        $idFromDb = $query->selectColumn('uniqueID', 'users', $email, 'email');
        if ($idFromDb === $id) {
            $query->update('users', "password='{$_POST['password']}', uniqueID=null, ", $email, 'email');
            setcookie('message', 'Password has been updated successfully', time() + 2);
            header("Location: /login");
            exit;
        } else {
            setcookie('error', true, time() + 2);
            setcookie('message', 'Some Error Occurred!', time() + 2);
            header("Location: /forgotPassword");
            exit;
        }
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        header("Location: /resetPassword?" . $_POST['id']);
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
    if ($id === $_SESSION['user'][0]) {
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        $updateSuccess = $user->updateUser($_POST, $_SESSION['user'][0]);
        if (!$updateSuccess) {
            $_SESSION['refresh'] = true;
            $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
            $location = ($_SESSION['user'][1] !== '1') ? '/admin/settings/update' : '/settings/update';
            header("Location: $location?$id");
            exit;
        }
        $_SESSION['refresh'] = true;
        $location = ($_SESSION['user'][1] !== '1') ? '/admin/settings' : '/settings';
        header("Location: $location");
        exit;
    }
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}


// Handle delete account
if (isset($_POST['deleteAccount'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id === $_SESSION['user'][0]) {
        $user = new User();
        $user->removeUser($id);
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}

/******* Handle Admins *******/

// Handle remove admin
if (isset($_POST['removeAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') === '3');
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id && $isSuperAdmin) {
        $user = new User();
        $user->removeUser($id);
        // $query->update('users', 'role=1, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}


// Make an Admin as Super Admin
if (isset($_POST['makeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') === '3');
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id && $isSuperAdmin) {
        $query->update('users', 'role=3, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}


// Handle Remove Super Admin
if (isset($_POST['removeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user'][0], 'uuid') === '3');
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id && $isSuperAdmin) {
        $query->update('users', 'role=2, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }
    setcookie('user', '', time() - 1);
    unset($_SESSION['user']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}


// Handle block user
if (isset($_POST['blockUser'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    unset($_POST['addBook'], $_POST['id']);
    $isUpdateSuccess = $book->updateBook($_POST, $id);
    if (!$isUpdateSuccess) {
        $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: admin/books/updateBook?$id");
        exit;
    }
    header("Location: admin");
    exit;
}


// Delete Book
if (isset($_POST['deleteBook'])) {
    $book = new Book();
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$id) {
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    $book->removeBook($id);
    header("Location: admin");
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    unset($_POST['payment'], $_POST['amount'], $_POST['id']);
    $validation = new ValidateData();
    $isDataValid = true;
    $err = [
        'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
        'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
        'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
        'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
        'returnDateErr' => $validation->validateReturnDate($_POST['returnDate'], $isDataValid),
    ];
    if ($isDataValid) {
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
        // $bookId = $query->selectColumn('id', 'books', $uuid, 'book_uuid');
        $dueDate = new DateTime($_POST['returnDate']);
        $date = new DateTime();
        $interval = $dueDate->diff($date);
        $days = $interval->days;
        $paymentData = [
            'payment_id' => uniqid("pay-"),
            'user_id' => $_SESSION['user'][0],
            'amount' => ($bookData['rent'] * $days),
            'card' => substr($_POST['cardNumber'], -4)
        ];

        $query->add('payment', $paymentData);
        $rentStatus = [
            'book_id' => $uuid,
            'user_id' => $_SESSION['user'][0],
            'date' => date("Y-m-d"),
            'due_date' => $_POST['returnDate']
        ];
        $query->add('orders', $rentStatus);
        $availableBooks = $bookData['available'] - 1;
        $query->update('quantity', "available=$availableBooks, ", $uuid, 'book_id');
        $conditions = [
            [
                'criteria' => 'book_id',
                'id' => $uuid
            ],
            [
                'criteria' => 'user_id',
                'id' => $_SESSION['user'][0]
            ]
        ];
        $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
        $cart = new Cart();
        $cart->removeItem($cartId);
        setcookie('message', 'Payment is Successful', time() + 2);
        header("Location: /mybooks");
        exit;
    }
    $_SESSION['refresh'] = true;
    setcookie('err', serialize($err), time() + 2);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: {$_COOKIE['prevPage']}");
    exit;
}

/******* Handle Return Book with Fine or without Fine *******/

// Handle Return Book
if (isset($_POST['returnBook'])) {
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuid) {
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    $query = new DatabaseQuery();
    $conditions = [
        [
            'criteria' => 'book_id',
            'id' => $uuid
        ],
        [
            'criteria' => 'user_id',
            'id' => $_SESSION['user'][0]
        ]
    ];
    $rentedBookId = $query->selectColumnMultiCondition('id', 'orders', $conditions);
    $query->delete('orders', $rentedBookId);
    $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
    $availableBooks++;
    $query->update('quantity', "available=$availableBooks, ", $uuid, 'book_id');
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    $validation = new ValidateData();
    $isDataValid = true;
    $err = [
        'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
        'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
        'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
        'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
    ];
    if ($isDataValid) {
        $query = new DatabaseQuery();
        $conditions = [
            [
                'criteria' => 'book_id',
                'id' => $uuid
            ],
            [
                'criteria' => 'user_id',
                'id' => $_SESSION['user'][0]
            ]
        ];
        $rentedBookId = $query->selectColumnMultiCondition('id', 'orders', $conditions);
        $query->delete('orders', $rentedBookId);
        $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
        $availableBooks++;
        $query->update('quantity', "available=$availableBooks, ", $uuid, 'book_id');
        $paymentData = [
            'payment_id' => uniqid("pay-"),
            'user_id' => $_SESSION['user'][0],
            'amount' => $amount,
            'card' => substr($_POST['cardNumber'], -4)
        ];
        $query->add('payment', $paymentData);
        setcookie('message', 'Payment is Successful and Book is Returned', time() + 2);
        header("Location: /mybooks");
        exit;
    }
    $_SESSION['refresh'] = true;
    setcookie('err', serialize($err), time() + 2);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: {$_COOKIE['prevPage']}");
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
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
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
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
        $err[$bookData['title'] . 'returnDateErr'] = $validation->validateReturnDate($_POST["returnDate-" . str_replace(" ", "_", $bookData['title'])], $isDataValid);
    }
    if ($isDataValid) {
        $totalRent = 0;
        foreach ($cartItems as $itemId) {
            $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
            $dueDate = new DateTime($_POST['returnDate-' . str_replace(" ", "_", $bookData['title'])]);
            $currentDate = new DateTime();
            $interval = $dueDate->diff($currentDate);
            $days = $interval->days;
            $totalRent += ($bookData['rent'] * $days);
            $rentStatus = [
                'book_id' => $itemId,
                'user_id' => $_SESSION['user'][0],
                'date' => date("Y-m-d"),
                'due_date' => $_POST['returnDate-' . str_replace(" ", "_", $bookData['title'])]
            ];
            $query->add('orders', $rentStatus);
            $availableBooks = $bookData['available'];
            $availableBooks--;
            $query->update('quantity', "available=$availableBooks, ", $bookData['book_id'], 'book_id');
            $conditions = [
                [
                    'criteria' => 'user_id',
                    'id' => $_SESSION['user'][0]
                ],
                [
                    'criteria' => 'book_id',
                    'id' => $itemId
                ]
            ];
            $cart = new Cart();
            $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
            $cart->removeItem($cartId);
        }
        $paymentId = uniqid("pay-");
        $paymentData = [
            'payment_id' => $paymentId,
            'user_id' => $_SESSION['user'][0],
            'amount' => $totalRent,
            'card' => substr($_POST['cardNumber'], -4)
        ];

        $query->add('payment', $paymentData);
        setcookie('message', 'Payment is Successful', time() + 2);
        header("Location: /mybooks");
        exit;
    }
    $_SESSION['refresh'] = true;
    setcookie('err', serialize($err), time() + 2);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: {$_COOKIE['prevPage']}");
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
    $condition = [];
    if ($categoryId !== 'all') {
        $condition = [
            [
                'criteria' => 'category_id',
                'id' => $categoryId
            ]
        ];
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['id'];
    }
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
        header("Location: /admin");
        exit;
    }
    $query = new DatabaseQuery();
    $condition = [];
    if ($categoryId !== 'all') {
        $condition = [
            [
                'criteria' => 'category_id',
                'id' => $categoryId
            ]
        ];
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['id'];
    }
    $bookIds = openssl_encrypt($bookIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin?" . $bookIds);
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
            [
                'criteria' => 'category_id',
                'id' => $categoryId
            ]
        ];
    }
    $books = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
    $bookIds = '';
    foreach ($books as $book) {
        $bookIds .= "&" . $book['book_uuid'];
    }
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
        [
            'criteria' => 'active',
            'id' => true
        ]
    ];
    $users = $query->selectPartial('users', ['name', 'email'], $_POST['userName'], $condition);
    $userIds = '';
    foreach ($users as $user) {
        $userIds .= "&" . $user['id'];
    }
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
        [
            'criteria' => 'active',
            'id' => true
        ]
    ];
    $admins = $query->selectPartial('users', ['name', 'email'], $_POST['adminName'], $condition);
    $adminIds = '';
    foreach ($admins as $admin) {
        if ($admin['role'] !== '1') {
            $adminIds .= "&" . $admin['id'];
        }
    }
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
    $userIds = openssl_encrypt($userIds, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    setcookie('data', serialize($_POST), time() + 2);
    header("Location: /admin/payment?" . $userIds);
    exit;
}
?>