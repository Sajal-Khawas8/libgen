<?php
$config = require "./core/config.php";
$bookId = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
if (!$bookId) {
    setcookie('user', '', time() - 1);
    unset($_SESSION['isAdmin']);
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
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
$bookData = $query->selectOneJoin('books', $joins, '*', $bookId, 'book_uuid');
?>

<section class="flex-1 space-y-3 px-6 py-4 bg-slate-100">
    <div class="h-80 w-60 mx-auto border">
        <img src="<?= $bookData['cover']; ?>" alt="<?= $bookData['title']; ?>"
            class="h-full w-full object-fill">
    </div>
    <h1 class="font-semibold text-3xl"><?= $bookData['title']; ?></h1>
    <h2 class="font-medium text-xl"><?= $bookData['author']; ?></h2>
    <dl class="flex gap-2 text-lg">
        <dt class="font-medium">Category:</dt>
        <dd><?= $bookData['name']; ?></dd>
    </dl>
    <p><?= $bookData['description']; ?></p>
</section>