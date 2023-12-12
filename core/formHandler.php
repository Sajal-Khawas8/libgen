<?php
require "./classes/validations.php";
require "./classes/user.php";
require "./classes/category.php";
require "./classes/books.php";
require "./classes/cart.php";

if (isset($_POST['masterLogin'])) {
    $isDataValid = true;
    $validation = new ValidateData();
    $query = new DatabaseQuery();
    $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
    $err = [
        'emailErr' => $validation->validateAdminLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $uuid, $isDataValid)
    ];
    if ($isDataValid) {
        setcookie('user', $uuid);
        $_SESSION['isAdmin'] = true;
        $_SESSION['refresh'] = true;
        header("Location: /admin");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /masterLogin");
        exit;
    }
}

if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 1);
    unset($_SESSION['isAdmin']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}

if (isset($_POST['register'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['register'], $_POST['id'], $_POST['role']);
    $uuid = $user->addUser($_POST);
    setcookie('user', $uuid, time() + 86400);
    $_SESSION['refresh'] = true;
    $location = $_COOKIE['prevPage'] ?? '/libgen';
    header("Location: $location");
    exit;
}

if (isset($_POST['registerAdmin'])) {
    $validationObj = new ValidateData();
    $user = new User($validationObj);
    unset($_POST['registerAdmin'], $_POST['id']);
    $_POST['role'] = true;
    $user->addUser($_POST);
    $_SESSION['refresh'] = true;
    header("Location: /admin/team");
    exit;
}

if (isset($_POST['login'])) {
    $isDataValid = true;
    $query = new DatabaseQuery();
    $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
    $validation = new ValidateData();
    $err = [
        'emailErr' => $validation->validateLoginEmail($_POST['email'], $isDataValid),
        'passwordErr' => $validation->validatePassword($_POST['password'], $uuid, $isDataValid)
    ];
    if ($isDataValid) {
        setcookie('user', $uuid, time() + 86400);
        $_SESSION['refresh'] = true;
        $location = $_COOKIE['prevPage'] ?? '/libgen';
        header("Location: $location");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /login");
        exit;
    }
}

if (isset($_POST['updateAdmin'])) {
    header("Location: admin/settings/update?{$_POST['id']}");
}

if (isset($_POST['updateUser'])) {
    header("Location: settings/update?{$_POST['id']}");
}

if (isset($_POST['updateData'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    unset($_POST['updateData'], $_POST['id']);
    if ($id === $_COOKIE['user']) {
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        $updateSuccess = $user->updateUser($_POST, $_COOKIE['user']);
        if (!$updateSuccess) {
            $_SESSION['refresh'] = true;
            $id = openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
            $location = isset($_SESSION['isAdmin']) ? '/admin/settings/update' : '/settings/update';
            header("Location: $location?$id");
            exit;
        }
        $_SESSION['refresh'] = true;
        $location = isset($_SESSION['isAdmin']) ? '/admin/settings' : '/settings';
        header("Location: $location");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if (isset($_POST['deleteAccount'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($id === $_COOKIE['user']) {
        $user = new User();
        $user->removeUser($id);
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if (isset($_POST['removeAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if ($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $user = new User();
        $user->removeUser($id);
        $query->update('users', 'role=false, isSuper=false, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if (isset($_POST['makeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if ($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $query->update('users', 'isSuper=true, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if (isset($_POST['removeSuperAdmin'])) {
    $query = new DatabaseQuery();
    $isSuperAdmin = $query->selectColumn('isSuper', 'users', $_COOKIE['user'], 'uuid');
    if ($isSuperAdmin) {
        $config = require "./core/config.php";
        $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        $query->update('users', 'isSuper=false, ', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    } else {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
}

if (isset($_POST['blockUser'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $user = new User();
    $user->removeUser($id);
    $_SESSION['refresh'] = true;
    header("Location: /admin/readers");
    exit;
}

if (isset($_POST['searchUser'])) {
    $query = new DatabaseQuery();
    $validation = new ValidateData();
    $isDataValid = true;
    $err = $validation->validateLoginEmail($_POST['email'], $isDataValid);
    if ($isDataValid) {
        $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
        $config = require "./core/config.php";
        $uuid = openssl_encrypt($uuid, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: /admin/readers?$uuid");
        exit;
    } else {
        setcookie('err', $err, time() + 2);
        setcookie('data', $_POST['email'], time() + 2);
        header("Location: /admin/readers");
        exit;
    }
}

if (isset($_POST['searchAdmin'])) {
    $query = new DatabaseQuery();
    $validation = new ValidateData();
    $isDataValid = true;
    $err = $validation->validateLoginEmail($_POST['email'], $isDataValid);
    if ($isDataValid) {
        $uuid = $query->selectColumn('uuid', 'users', $_POST['email'], 'email');
        $config = require "./core/config.php";
        $uuid = openssl_encrypt($uuid, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        header("Location: /admin/team?$uuid");
        exit;
    } else {
        setcookie('err', $err, time() + 2);
        setcookie('data', $_POST['email'], time() + 2);
        header("Location: /admin/team");
        exit;
    }
}

if (isset($_POST['addCategory'])) {
    $validationObj = new ValidateData();
    $category = new Category($validationObj);
    unset($_POST['addCategory'], $_POST['id']);
    $category->addCategory($_POST);
}

if (isset($_POST['updateCategory'])) {
    header("Location: admin/categories/updateCategory?{$_POST['id']}");
    exit;
}

if (isset($_POST['updateCategoryData'])) {
    $validationObj = new ValidateData();
    $category = new Category($validationObj);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
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

if (isset($_POST['deleteCategory'])) {
    $category = new Category();
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $category->removeCategory($id);
    header("Location: admin/categories");
}

if (isset($_POST['addBook'])) {
    $validationObj = new ValidateData();
    $book = new Book($validationObj);
    unset($_POST['addBook'], $_POST['id']);
    $book->addBook($_POST);
}

if (isset($_POST['updateBook'])) {
    header("Location: admin/books/updateBook?{$_POST['id']}");
    exit;
}

if (isset($_POST['updateBookData'])) {
    $validationObj = new ValidateData();
    $book = new Book($validationObj);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
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

if (isset($_POST['deleteBook'])) {
    $book = new Book($validationObj);
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $book->removeBook($id);
    header("Location: admin");
    exit;
}

if (isset($_POST['payment'])) {
    if (!isset($_COOKIE['user'])) {
        header("Location: /login");
        exit;
    }
    $config = require "./core/config.php";
    // $amount = openssl_decrypt($_POST['amount'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
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
                'condition' => 'quantity.book_id = books.id'
            ],
        ];
        $bookData = $query->selectOneJoin('books', $joins, '*', $uuid, 'book_uuid');
        $bookId = $query->selectColumn('id', 'books', $uuid, 'book_uuid');
        // $availableBooks = $query->selectColumn('available', 'quantity', $bookId);
        $dueDate = new DateTime($_POST['returnDate']);
        $date = new DateTime();
        $interval = $dueDate->diff($date);
        $days = $interval->days;
        $additional = $days > 30 ? (ceil(($days - 30) / 15) * $bookData['additional']) : 0;
        $amount=$bookData['base']+$bookData['rent']+$additional;
        $paymentData = [
            'payment_id' => uniqid("pay-"),
            'user_id' => $_COOKIE['user'],
            'item_id' => $bookId,
            'amount' => $amount
        ];

        $query->add('payment', $paymentData);
        $rentStatus = [
            'book_id' => $uuid,
            'user_id' => $_COOKIE['user'],
            'date' => date("Y-m-d"),
            'due_date' => $_POST['returnDate']
        ];
        $query->add('rented_books', $rentStatus);
        $availableBooks=$bookData['available']-1;
        $query->update('quantity', "available=$availableBooks, ", $bookId, 'book_id');
        header("Location: /mybooks");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }
}

if (isset($_POST['returnBook'])) {
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $query = new DatabaseQuery();
    $conditions = [
        [
            'criteria' => 'book_id',
            'id' => $uuid
        ],
        [
            'criteria' => 'user_id',
            'id' => $_COOKIE['user']
        ]
    ];
    $rentedBookId = $query->selectColumnMultiCondition('id', 'rented_books', $conditions);
    $query->delete('rented_books', $rentedBookId);
    $bookId = $query->selectColumn('id', 'books', $uuid, 'book_uuid');
    $availableBooks = $query->selectColumn('available', 'quantity', $bookId, 'book_id');
    $availableBooks++;
    $query->update('quantity', "available=$availableBooks, ", $bookId, 'book_id');
    header("Location: /paymentSuccess");
    exit;
}

if (isset($_POST['returnBookFine'])) {
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $amount = openssl_decrypt($_POST['amount'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
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
                'id' => $_COOKIE['user']
            ]
        ];
        $rentedBookId = $query->selectColumnMultiCondition('id', 'rented_books', $conditions);
        $query->delete('rented_books', $rentedBookId);
        $bookId = $query->selectColumn('id', 'books', $uuid, 'book_uuid');
        $availableBooks = $query->selectColumn('available', 'quantity', $bookId, 'book_id');
        $availableBooks++;
        $query->update('quantity', "available=$availableBooks, ", $bookId, 'book_id');
        $paymentData = [
            'payment_id' => uniqid("pay-"),
            'user_id' => $_COOKIE['user'],
            'item_id' => $bookId,
            'amount' => $amount
        ];
        $query->add('payment', $paymentData);
        header("Location: /paymentSuccess");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }
}

if (isset($_POST['addToCart'])) {
    if (!isset($_COOKIE['user'])) {
        header("Location: /login");
        exit;
    }
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $query = new DatabaseQuery();
    $cart = new Cart();
    $cart->addItem($uuid);
    header("Location: {$_COOKIE['prevPage']}");
    exit;
}

if (isset($_POST['removeFromCart'])) {
    $config = require "./core/config.php";
    $id = openssl_decrypt($_POST['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $cart = new Cart();
    $cart->removeItem($id);
    header("Location: {$_COOKIE['prevPage']}");
    exit;
}

if (isset($_POST['cartPayment'])) {
    $config = require "./core/config.php";
    $cartItems = openssl_decrypt($_POST['items'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
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
            'table' => 'category',
            'condition' => 'books.category_id = category.id'
        ],
        [
            'table' => 'quantity',
            'condition' => 'quantity.book_id = books.id'
        ],
    ];
    $totalRent = 0;
    foreach ($cartItems as $itemId) {
        $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
        $err[$bookData['title'] . 'returnDateErr'] = $validation->validateReturnDate($_POST["returnDate-" . str_replace(" ", "_", $bookData['title'])], $isDataValid);
        $totalRent += $bookData['base'] + $bookData['rent'];
    }
    if ($isDataValid) {
        $paymentId = uniqid("pay-");
        $paymentData = [
            'payment_id' => $paymentId,
            'user_id' => $_COOKIE['user'],
            'item_id' => uniqid("cart-"),
            'amount' => $totalRent
        ];

        $query->add('payment', $paymentData);
        $cart = new Cart();
        foreach ($cartItems as $itemId) {
            $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
            $rentStatus = [
                'book_id' => $itemId,
                'user_id' => $_COOKIE['user'],
                'date' => date("Y-m-d"),
                'due_date' => $_POST['returnDate-' . str_replace(" ", "_", $bookData['title'])]
            ];
            $query->add('rented_books', $rentStatus);
            $availableBooks = $bookData['available'];
            $availableBooks--;
            $query->update('quantity', "available=$availableBooks, ", $bookData['book_id'], 'book_id');
            $conditions = [
                [
                    'criteria' => 'user_id',
                    'id' => $_COOKIE['user']
                ],
                [
                    'criteria' => 'book_id',
                    'id' => $itemId
                ]
            ];
            $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
            $cart->removeItem($cartId);
            $paid_items = [
                'payment_id' => $paymentId,
                'item_id' => $bookData['book_id']
            ];
            $query->add('paid_items', $paid_items);
        }
        header("Location: /mybooks");
        exit;
    } else {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }
}
?>