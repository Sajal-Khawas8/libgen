<?php
setcookie('prevPage', $uri);
if (!isset($_COOKIE['user']) || isset($_SESSION['isAdmin'])) {
    header("Location: /login");
}
$query = new DatabaseQuery();
$joins = [
    [
        'table' => 'books',
        'condition' => 'books.book_uuid = rented_books.book_id'
    ]
];
$rentedBooks = $query->selectAllJoin('rented_books', $joins);
$rentedBooks = array_filter($rentedBooks, function ($rentedBook) {
    return $rentedBook['user_id'] === $_COOKIE['user'];
});
?>
<main class="container border-x space-y-8">
    <h1 class="text-4xl font-semibold pt-8 pl-16">My Books</h1>
    <?php if (!$rentedBooks): ?>
        <section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-center gap-8">
            <h2 class="font-bold text-5xl text-gray-500">You have not taken any books on rent...</h2>
        </section>
    <?php else: ?>
        <div class="min-h-[calc(100vh-4rem-3.5rem)] ">
            <ul class="flex items-center gap-x-20 gap-y-12 flex-wrap px-16">
                <?php foreach ($rentedBooks as $book): ?>
                    <li class="border rounded-lg divide-y relative hover:shadow-lg">
                        <figure>
                            <div class="h-72 w-56 border">
                                <img src="/libgen/assets/uploads/images/books/<?= $book['cover'] ?>" alt="<?= $book['title'] ?>"
                                    class="h-full w-full object-fill">
                            </div>
                            <figcaption class="p-2 max-w-[14rem] space-y-4">
                                <h3 class="font-semibold text-xl text-blue-700 line-clamp-2"><?= $book['title'] ?></h3>
                                <h4 class="font-medium truncate"><?= $book['author'] ?></h4>
                            </figcaption>
                        </figure>
                        <?php $config = require "./core/config.php"; ?>
                        <a href="/returnBook?<?= openssl_encrypt($book['book_id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                            class="absolute inset-0"></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</main>