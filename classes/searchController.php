<?php
class SearchController
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

    public function searchBooks()
    {
        $categoryId = $this->decrypt($_POST['category']);
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
        $bookIds = $this->encrypt($bookIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/books?" . $bookIds);
        exit;
    }

    public function searchRentedBooks()
    {
        $categoryId = $this->decrypt($_POST['category']);
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
        $bookIds = $this->encrypt($bookIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/rentedBooks?" . $bookIds);
        exit;
    }

    public function searchCategory()
    {
        $query = new DatabaseQuery();
        $categories = $query->selectPartial('category', ['name'], $_POST['categoryName']);
        $categoryIds = '';
        foreach ($categories as $category) {
            $categoryIds .= "&" . $category['id'];
        }
        $categoryIds = ltrim($categoryIds, "&");
        $categoryIds = $this->encrypt($categoryIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/categories?" . $categoryIds);
        exit;
    }

    public function searchUser()
    {
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
        $userIds = $this->encrypt($userIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/readers?" . $userIds);
        exit;
    }

    public function searchAdmin()
    {
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
        $adminIds = $this->encrypt($adminIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/team?" . $adminIds);
        exit;
    }

    public function searchPayment()
    {
        $query = new DatabaseQuery();
        $users = $query->selectPartial('users', ['name', 'email'], $_POST['userName']);
        $userIds = '';
        foreach ($users as $user) {
            $userIds .= "&" . $user['uuid'];
        }
        $userIds = ltrim($userIds, "&");
        $userIds = $this->encrypt($userIds);
        setcookie('data', serialize($_POST), time() + 2);
        header("Location: /admin/payment?" . $userIds);
        exit;
    }
}
?>