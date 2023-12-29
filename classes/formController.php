<?php
class FormController
{
    private $data = [];

    public function __construct($formData = [])
    {
        $this->data = $formData;
    }

    private function encrypt($data)
    {
        $config = require "./core/config.php";
        return openssl_encrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    }

    private function decrypt($data)
    {
        $config = require "./core/config.php";
        return openssl_decrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    }

    private function redirectWithErr($err, $location)
    {
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($this->data), time() + 2);
        header("Location: $location");
        exit;
    }

    public function logout()
    {
        setcookie('user', '', time() - 1);
        unset($_SESSION['user']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }

    public function adminLogin()
    {
        // Validate login data
        $isDataValid = true;
        $validation = new ValidateData();
        $err = [
            'emailErr' => $validation->validateAdminLoginEmail($this->data['email'], $isDataValid),
            'passwordErr' => $validation->validatePassword($this->data['password'], $this->data['email'], $isDataValid)
        ];

        // If data is not valid, show error message
        if (!$isDataValid) {
            $this->redirectWithErr($err, "/masterLogin");
        }

        // Add user data to session
        $query = new DatabaseQuery();
        $user = $query->selectOne('users', $this->data['email'], 'email');
        $user = [
            'id' => $user['uuid'],
            'role' => $user['role']
        ];
        $_SESSION['user'] = $user;
        $_SESSION['refresh'] = true;
        header("Location: /admin");
        exit;
    }

    public function login()
    {
        // Validate login data
        $isDataValid = true;
        $validation = new ValidateData();
        $err = [
            'emailErr' => $validation->validateLoginEmail($_POST['email'], $isDataValid),
            'passwordErr' => $validation->validatePassword($_POST['password'], $_POST['email'], $isDataValid)
        ];

        // If data is not valid, show error message
        if (!$isDataValid) {
            $this->redirectWithErr($err, "/login");
        }

        // Add user data to session and in encypted form in cookie
        $query = new DatabaseQuery();
        $user = $query->selectOne('users', $_POST['email'], 'email');
        $user = [
            'id' => $user['uuid'],
            'role' => $user['role']
        ];
        $_SESSION['user'] = $user;
        $user = serialize($user);
        $user = $this->encrypt($user);
        setcookie('user', $user, time() + 86400);
        $_SESSION['refresh'] = true;
        $location = $_COOKIE['prevPage'] ?? '/libgen';
        header("Location: $location");
        exit;
    }

    public function forgetPassword()
    {
        // Validate email
        $isDataValid = true;
        $validation = new ValidateData();
        $err = $validation->validateLoginEmail($_POST['email'], $isDataValid);

        // If email is not valid, show error message
        if (!$isDataValid) {
            $this->redirectWithErr($err, "/forgotPassword");
        }

        // Generate unique id, insert it to db and send it to user in encrypted form
        $id = uniqid();
        $query = new DatabaseQuery();
        $query->update('users', "uniqueID='$id'", $_POST['email'], 'email');
        $link = $this->encrypt($_POST['email'] . "&" . $id);
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

    public function resetPW()
    {
        // Get unique id from the url and decrypt it
        $isDataValid = true;
        $validation = new ValidateData();
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            setcookie('error', true, time() + 2);
            setcookie('message', 'Some Error Occurred', time() + 2);
            header("Location: /libgen");
            exit;
        }

        // Validate password formats
        $err = [
            'passwordErr' => $validation->validatePasswordFormat($_POST['password'], $isDataValid),
            'cnfrmPasswordErr' => $validation->validateCnfrmPassword($_POST['cnfrmPassword'], $_POST['password'], $isDataValid),
        ];
        if (!$isDataValid) {
            $this->redirectWithErr($err, "/resetPassword?" . $_POST['id']);
        }

        // Check if the id from the url and db is same or not
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

    public function register()
    {
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        unset($_POST['register'], $_POST['id'], $_POST['role']);
        $uuid = $user->addUser($_POST);
        $user = [
            'id' => $uuid,
            'role' => 1
        ];
        $_SESSION['user'] = $user;
        $user = serialize($user);
        $user = $this->encrypt($user);
        setcookie('user', $user, time() + 86400);
        $_SESSION['refresh'] = true;
        $location = $_COOKIE['prevPage'] ?? '/libgen';
        header("Location: $location");
        exit;
    }

    public function registerAdmin()
    {
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        unset($_POST['registerAdmin'], $_POST['id']);
        $_POST['role'] = 2;
        $user->addUser($_POST);
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }

    public function updateUserData()
    {
        $id = $this->decrypt($_POST['id']);
        unset($_POST['updateData'], $_POST['id']);
        if ($id !== $_SESSION['user']['id']) {
            $this->logout();
        }
        $validationObj = new ValidateData();
        $user = new User($validationObj);
        $updateSuccess = $user->updateUser($_POST, $_SESSION['user']['id']);
        if (!$updateSuccess) {
            $_SESSION['refresh'] = true;
            $id = $this->encrypt($id);
            $location = ($_SESSION['user']['role'] != 1) ? '/admin/settings/update' : '/settings/update';
            header("Location: $location?$id");
            exit;
        }
        $_SESSION['refresh'] = true;
        $location = ($_SESSION['user']['role'] != 1) ? '/admin/settings' : '/settings';
        header("Location: $location");
        exit;
    }

    public function deleteUserAccount()
    {
        $id = $this->decrypt($_POST['id']);
        if ($id !== $_SESSION['user']['id']) {
            $this->logout();
        }
        $user = new User();
        $user->removeUser($id);
        $this->logout();
        exit;
    }

    public function removeAdmin()
    {
        $query = new DatabaseQuery();
        $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user']['id'], 'uuid') == 3);
        $id = $this->decrypt($_POST['id']);
        if (!$id || !$isSuperAdmin) {
            $this->logout();
        }
        $user = new User();
        $user->removeUser($id);
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }

    public function makeSuperAdmin()
    {
        $query = new DatabaseQuery();
        $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user']['id'], 'uuid') == 3);
        $id = $this->decrypt($_POST['id']);
        if (!$id || !$isSuperAdmin) {
            $this->logout();
        }
        $query->update('users', 'role=3', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }

    public function removeSuperAdmin()
    {
        $query = new DatabaseQuery();
        $isSuperAdmin = ($query->selectColumn('role', 'users', $_SESSION['user']['id'], 'uuid') == 3);
        $id = $this->decrypt($_POST['id']);
        if (!$id || !$isSuperAdmin) {
            $this->logout();
        }
        $query->update('users', 'role=2', $id, 'uuid');
        $_SESSION['refresh'] = true;
        header("Location: /admin/team");
        exit;
    }

    public function blockAccount()
    {
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        $user = new User();
        $user->removeUser($id);
        $_SESSION['refresh'] = true;
        header("Location: /admin/readers");
        exit;
    }

    public function addCategory()
    {
        $validationObj = new ValidateData();
        $category = new Category($validationObj);
        unset($_POST['addCategory'], $_POST['id']);
        $category->addCategory($_POST);
    }

    public function updateCategoryData()
    {
        $validationObj = new ValidateData();
        $category = new Category($validationObj);
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        unset($_POST['updateCategoryData'], $_POST['id']);
        $isUpdateSuccess = $category->updateCategory($_POST, $id);
        if (!$isUpdateSuccess) {
            $id = $this->encrypt($id);
            header("Location: admin/categories/updateCategory?$id");
            exit;
        }
        header("Location: admin/categories");
        exit;
    }

    public function deleteCategory()
    {
        $category = new Category();
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        $category->removeCategory($id);
        header("Location: admin/categories");
        exit;
    }

    public function addBook()
    {
        $validationObj = new ValidateData();
        $book = new Book($validationObj);
        unset($_POST['addBook'], $_POST['id']);
        $book->addBook($_POST);
    }

    public function updateBookData()
    {
        $validationObj = new ValidateData();
        $book = new Book($validationObj);
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        unset($_POST['addBook'], $_POST['id']);
        $isUpdateSuccess = $book->updateBook($_POST, $id);
        if (!$isUpdateSuccess) {
            $id = $this->encrypt($id);
            header("Location: admin/books/updateBook?$id");
            exit;
        }
        header("Location: /admin/books");
        exit;
    }

    public function deleteBook()
    {
        $book = new Book();
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        $book->removeBook($id);
        header("Location: /admin/books");
        exit;
    }

    public function singleBookPayment()
    {

        // Check if the user is logged in or not
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        // Get the uuid of the book
        $uuid = $this->decrypt($_POST['id']);
        if (!$uuid) {
            $this->logout();
        }
        unset($_POST['payment'], $_POST['amount'], $_POST['id']);

        // Validate the card details
        $validation = new ValidateData();
        $isDataValid = true;
        $err = [
            'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
            'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
            'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
            'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
            'returnDateErr' => $validation->validateNumber($_POST['returnDate'], $isDataValid, 'Period'),
        ];

        // If data is not valid, then show error messages
        if (!$isDataValid) {
            $this->redirectWithErr($err, $_COOKIE['prevPage']);
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

        // Add payment data to payment table
        $paymentId = uniqid("pay-");
        $paymentData = [
            'payment_id' => $paymentId,
            'user_id' => $_SESSION['user']['id'],
            'amount' => ($bookData['rent'] * $_POST['returnDate']),
            'card' => substr($_POST['cardNumber'], -4)
        ];
        $query->add('payment', $paymentData);

        // Add list of books taken on rent to paid_items table
        $paidItems = [
            'payment_id' => $paymentId,
            'book_id' => $bookData['book_uuid'],
        ];
        $query->add('paid_items', $paidItems);


        // Add order details to orders table
        $rentStatus = [
            'book_id' => $uuid,
            'user_id' => $_SESSION['user']['id'],
            'date' => date("Y-m-d"),
            'due_date' => $dueDate
        ];
        $query->add('orders', $rentStatus);

        // Update the quantity of book
        $availableBooks = $bookData['available'] - 1;
        $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');

        // Remove the book from the cart
        $conditions = [
            'book_id' => $uuid,
            'user_id' => $_SESSION['user']['id'],
        ];
        $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
        $cart = new Cart();
        $cart->removeItem($cartId);

        // Redirect and show message
        setcookie('message', 'Payment is Successful', time() + 2);
        header("Location: /mybooks");
        exit;
    }

    public function returnBook()
    {
        // Get uuid of the book
        $uuid = $this->decrypt($_POST['id']);
        if (!$uuid) {
            $this->logout();
        }
        $query = new DatabaseQuery();

        // Delete this book from the orders table
        $conditions = [
            'book_id' => $uuid,
            'user_id' => $_SESSION['user']['id'],
        ];
        $rentedBookData = $query->selectAllMultiCondition('orders', $conditions);
        $query->delete('orders', $rentedBookData[0]['id']);

        // Add history of book in rent_history table
        $issueDate = new DateTime($rentedBookData[0]['date']);
        $dueDate = new DateTime($rentedBookData[0]['due_date']);
        $interval = $dueDate->diff($issueDate);
        $days = $interval->days;
        $rentHistory = [
            'user_id' => $_SESSION['user']['id'],
            'book_id' => $uuid,
            'issue_date' => $rentedBookData[0]['date'],
            'due_date' => $rentedBookData[0]['due_date'],
            'return_date' => date("Y-m-d"),
            'rent_paid' => ($query->selectColumn('rent', 'books', $uuid, 'book_uuid') * $days),
            'fine_paid' => 0
        ];
        $query->add('rent_history', $rentHistory);

        // Increase the quantity of the book
        $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
        $availableBooks++;
        $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');

        // Redirect and show message
        setcookie('message', 'Book Returned Successfully', time() + 2);
        header("Location: /mybooks");
        exit;
    }

    public function returnBookWithFine()
    {
        // get uuid and fine amount of book
        $uuid = $this->decrypt($_POST['id']);
        $amount = $this->decrypt($_POST['amount']);
        if (!$uuid || !$amount) {
            $this->logout();
        }

        // Validate card details
        $validation = new ValidateData();
        $isDataValid = true;
        $err = [
            'numberErr' => $validation->validateCardNumber($_POST['cardNumber'], $isDataValid),
            'nameErr' => $validation->validateTextData($_POST['cardName'], $isDataValid, 'Name'),
            'expiryErr' => $validation->validateExpiryDate($_POST['expiryDate'], $isDataValid, 'Category'),
            'cvvErr' => $validation->validateCVV($_POST['cvv'], $isDataValid),
        ];

        // If data is not valid, show error message
        if (!$isDataValid) {
            $this->redirectWithErr($err, $_COOKIE['prevPage']);
        }

        $query = new DatabaseQuery();

        // Delete book data from orders table
        $conditions = [
            'book_id' => $uuid,
            'user_id' => $_SESSION['user']['id'],
        ];
        $rentedBookData = $query->selectAllMultiCondition('orders', $conditions);
        $query->delete('orders', $rentedBookData[0]['id']);

        // Add history of book to rent_history table
        $issueDate = new DateTime($rentedBookData[0]['date']);
        $dueDate = new DateTime($rentedBookData[0]['due_date']);
        $interval = $dueDate->diff($issueDate);
        $days = $interval->days;
        $rentHistory = [
            'user_id' => $_SESSION['user']['id'],
            'book_id' => $uuid,
            'issue_date' => $rentedBookData[0]['date'],
            'due_date' => $rentedBookData[0]['due_date'],
            'return_date' => date("Y-m-d"),
            'rent_paid' => ($query->selectColumn('rent', 'books', $uuid, 'book_uuid') * $days),
            'fine_paid' => $amount
        ];
        $query->add('rent_history', $rentHistory);

        // Increase quantity of book
        $availableBooks = $query->selectColumn('available', 'quantity', $uuid, 'book_id');
        $availableBooks++;
        $query->update('quantity', "available=$availableBooks", $uuid, 'book_id');

        // Add payment data to payment table
        $paymentId = uniqid("pay-");
        $paymentData = [
            'payment_id' => $paymentId,
            'user_id' => $_SESSION['user']['id'],
            'amount' => $amount,
            'card' => substr($_POST['cardNumber'], -4),
            'type' => 2
        ];
        $query->add('payment', $paymentData);

        // Add data of book returned to paid_items table
        $paidItems = [
            'payment_id' => $paymentId,
            'book_id' => $uuid,
        ];
        $query->add('paid_items', $paidItems);

        // Redirect and show message
        setcookie('message', 'Payment is Successful and Book is Returned', time() + 2);
        header("Location: /mybooks");
        exit;
    }

    public function addToCart()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
        $uuidBook = $this->decrypt($_POST['id']);
        if (!$uuidBook) {
            $this->logout();
        }
        $query = new DatabaseQuery();
        $cart = new Cart();
        $cart->addItem($uuidBook);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }

    public function removeFromCart()
    {
        $id = $this->decrypt($_POST['id']);
        if (!$id) {
            $this->logout();
        }
        $cart = new Cart();
        $cart->removeItem($id);
        header("Location: {$_COOKIE['prevPage']}");
        exit;
    }

    public function cartPayment()
    {
        // Get all cart items
        $cartItems = $this->decrypt($_POST['items']);
        if (!$cartItems) {
            setcookie('error', true, time() + 2);
            setcookie('message', 'All Books are currently Out of Stock', time() + 2);
            header("Location: /cart");
            exit;
        }
        $cartItems = explode('&', $cartItems);
        unset($cartItems[0]);

        // Validate card details
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

        // Validate return dates
        foreach ($cartItems as $itemId) {
            $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
            $err[$bookData['title'] . 'returnDateErr'] = $validation->validateNumber($_POST["returnDate-" . str_replace(" ", "_", $bookData['title'])], $isDataValid, "Period");
        }

        // Id data is not valid, show error message
        if (!$isDataValid) {
            $this->redirectWithErr($err, $_COOKIE['prevPage']);
        }

        // Add payment data to payment table
        $paymentId = uniqid("pay-");
        $paymentData = [
            'payment_id' => $paymentId,
            'user_id' => $_SESSION['user']['id'],
            'amount' => 0,
            'card' => substr($_POST['cardNumber'], -4)
        ];
        $query->add('payment', $paymentData);

        $totalRent = 0;
        foreach ($cartItems as $itemId) {
            // Get book data
            $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
            $days = $_POST['returnDate-' . str_replace(" ", "_", $bookData['title'])];
            $currentDate = new DateTime();
            $dueDate = $currentDate->add(new DateInterval("P" . $days . "D"));
            $dueDate = $dueDate->format("Y-m-d");
            $totalRent += ($bookData['rent'] * $days); // Add rent

            // Add order details to order table
            $rentStatus = [
                'book_id' => $itemId,
                'user_id' => $_SESSION['user']['id'],
                'date' => date("Y-m-d"),
                'due_date' => $dueDate
            ];
            $query->add('orders', $rentStatus);

            // Decrease quantity of book
            $availableBooks = $bookData['available'];
            $availableBooks--;
            $query->update('quantity', "available=$availableBooks", $bookData['book_id'], 'book_id');

            // remove item from the cart
            $conditions = [
                'user_id' => $_SESSION['user']['id'],
                'book_id' => $itemId,
            ];
            $cart = new Cart();
            $cartId = $query->selectColumnMultiCondition('id', 'cart', $conditions);
            $cart->removeItem($cartId);

            // Add book data to paid_items table
            $paidItems = [
                'payment_id' => $paymentId,
                'book_id' => $bookData['book_uuid'],
            ];
            $query->add('paid_items', $paidItems);
        }

        // Update total rent paid in payment table
        $query->update("payment", "amount = $totalRent", $paymentId, "payment_id");

        // Redirect and show message
        setcookie('message', 'Payment is Successful', time() + 2);
        header("Location: /mybooks");
        exit;
    }
}
?>