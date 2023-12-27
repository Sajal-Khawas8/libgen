<?php
setcookie('prevPage', $uri);
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
    header("Location: /login");
}
$query = new DatabaseQuery();
$joins = [
    [
        'table' => 'books',
        'condition' => 'books.book_uuid = orders.book_id'
    ]
];
$currentReads = $query->selectAllJoinSpecific('orders', $joins, $_SESSION['user']['id'], 'user_id');
$joins = [
    [
        'table' => 'rent_history',
        'condition' => 'books.book_uuid = rent_history.book_id'
    ]
];
$previousReads = $query->selectAllJoinSpecific('books', $joins, $_SESSION['user']['id'], 'user_id');
?>
<main class="container border-x min-h-[calc(100vh-4rem-3.5rem)]">
    <h1 class="text-4xl font-semibold pt-8 pl-16">My Books</h1>
    <?php if (!$currentReads && !$previousReads): ?>
        <section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-center gap-8">
            <h2 class="font-bold text-5xl text-gray-500">You have not taken any books on rent...</h2>
        </section>
    <?php else: ?>
        <section class="py-8">
            <h2 class="text-2xl font-semibold py-4 pl-16">Currently Reading</h2>
            <?php if (!$currentReads): ?>
                <p class="font-semibold text-4xl text-gray-500 text-center">You are not reading any book currently...</p>
            <?php else: ?>
                <ul class="grid grid-cols-5 items-center gap-x-16 gap-y-12 flex-wrap px-16">
                    <?php foreach ($currentReads as $book): ?>
                        <li class="border rounded-lg divide-y relative hover:shadow-lg">
                            <figure>
                                <div class="h-72 w-full border">
                                    <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                        class="h-full w-full object-fill">
                                </div>
                                <figcaption class="p-2 max-w-full space-y-4">
                                    <h3 class="font-semibold text-xl text-blue-700 truncate"><?= $book['title']; ?></h3>
                                    <h4 class="font-medium truncate"><?= $book['author']; ?></h4>
                                </figcaption>
                            </figure>
                            <?php $config = require "./core/config.php"; ?>
                            <a href="/returnBook?<?= openssl_encrypt($book['book_id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                                class="absolute inset-0"></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
        <?php
        // $countCurrentReads = count($currentReads);
        // $countReturnedBooks = $countCurrentReads > 10 ? 0 : 10 - $countCurrentReads;
        // $previousReads = array_slice($previousReads, 0, $countReturnedBooks);
        ?>
        <section class="py-8">
            <h2 class="text-2xl font-semibold py-4 pl-16">Previous Reads</h2>
            <?php if (!$previousReads): ?>
                <p class="font-semibold text-4xl text-gray-500 text-center">You have not completed any book yet...</p>
            <?php else: ?>
                <ul class="grid grid-cols-5 items-center gap-x-20 gap-y-12 flex-wrap px-16">
                    <?php foreach ($previousReads as $book): ?>
                        <li class="border rounded-lg divide-y relative hover:shadow-lg">
                            <figure>
                                <div class="h-72 w-full border">
                                    <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                        class="h-full w-full object-fill">
                                </div>
                                <figcaption class="p-2 max-w-full space-y-4">
                                    <h3 class="font-semibold text-xl text-blue-700 truncate"><?= $book['title']; ?></h3>
                                    <h4 class="font-medium truncate"><?= $book['author']; ?></h4>
                                </figcaption>
                            </figure>
                            <?php $config = require "./core/config.php"; ?>
                            <a href="/rentHistory?<?= openssl_encrypt($book['book_id'] . "&" . $book['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                                class="absolute inset-0"></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>