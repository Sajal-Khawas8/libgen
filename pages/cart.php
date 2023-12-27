<?php
setcookie('prevPage', $uri);
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
    header("Location: /login");
    exit;
}
$query = new DatabaseQuery();
$cartItems = $query->selectAllSpecific('cart', $_SESSION['user']['id'], 'user_id');
$cartItems = array_map(function ($cartItem) {
    return $cartItem['book_id'];
}, $cartItems);

if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
    $cardDetails = unserialize($_COOKIE['data']);
}
?>
<main class="container border-x flex gap-0 min-h-[calc(100vh-4rem-3.5rem)]">
    <?php if (count($cartItems)): ?>
        <section class="flex-1 space-y-8 px-6 py-4 bg-slate-100">
            <h1 class="font-semibold text-3xl">Books in Cart</h1>
            <ul class="mx-auto space-y-6">
                <?php foreach ($cartItems as $itemId): ?>
                    <?php
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
                    $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
                    ?>
                    <li class="px-3 py-3 rounded-md <?= $bookData['available'] ? 'bg-white' : 'bg-slate-300/80'; ?>">
                        <article class="flex items-center gap-7 h-32">
                            <div class="h-full w-24">
                                <img src="<?= $bookData['cover']; ?>" alt="<?= $bookData['title']; ?>"
                                    class="h-full w-full object-fill">
                            </div>
                            <div class="flex-1 flex flex-col justify-between h-full">
                                <div class="flex justify-between">
                                    <h2 class="text-2xl font-semibold"><?= $bookData['title']; ?></h2>
                                    <form action="/formHandler" method="post">
                                        <?php
                                        $config = require "./core/config.php";
                                        $conditions = [
                                            'user_id' => $_SESSION['user']['id'],
                                            'book_id' => $itemId,
                                        ];
                                        $id = $query->selectColumnMultiCondition('id', 'cart', $conditions);
                                        ?>
                                        <input type="hidden" name="id"
                                            value="<?= openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                        <button name="removeFromCart" class="p-1 bg-red-500 text-white rounded-md">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path
                                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                                </path>
                                                <title>Remove this book</title>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <h3 class="text-lg font-semibold"><?= $bookData['author']; ?></h3>
                                <dl class="grid grid-cols-2 gap-8">
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Category:</dt>
                                        <dd><?= $bookData['name'] ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Availability:</dt>
                                        <?php if ($bookData['available']): ?>
                                            <dd class="px-4 rounded-full font-medium bg-green-400 text-green-700">In stock</dd>
                                        <?php else: ?>
                                            <dd class="px-4 rounded-full font-medium bg-red-400 text-red-700">Out of stock</dd>
                                        <?php endif; ?>
                                    </div>
                                </dl>
                                <dl class="grid grid-cols-2 gap-8">
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Rent:</dt>
                                        <dd>&#x20B9;<?= $bookData['rent']; ?>/day</dd>
                                    </div>
                                </dl>
                            </div>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="flex-1 space-y-8 px-6 py-4">
            <h2 class="font-semibold text-3xl">Payment</h2>
            <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                <div>
                    <input type="text" name="cardNumber" id="cardNumber" placeholder="Card Number"
                        value="<?= $cardDetails['cardNumber'] ?? ''; ?>"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $err['numberErr'] ?? ''; ?></span>
                </div>
                <div>
                    <input type="text" name="cardName" id="cardName" placeholder="Name on card"
                        value="<?= $cardDetails['cardName'] ?? ''; ?>"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $err['nameErr'] ?? ''; ?></span>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <input type="text" name="expiryDate" id="expiryDate" placeholder="Expiration Date (MM/YY)"
                            value="<?= $cardDetails['expiryDate'] ?? ''; ?>"
                            class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <span class="text-red-600 text-sm font-medium"><?= $err['expiryErr'] ?? ''; ?></span>
                    </div>
                    <div>
                        <input type="password" name="cvv" id="cvv" placeholder="CVV"
                            class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <span class="text-red-600 text-sm font-medium"><?= $err['cvvErr'] ?? ''; ?></span>
                    </div>
                </div>
                <?php $cartItemsId = ""; ?>
                <?php foreach ($cartItems as $itemId): ?>
                    <?php
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
                    $bookData = $query->selectOneJoin('books', $joins, '*', $itemId, 'book_uuid');
                    ?>
                    <?php if ($bookData['available']): ?>
                        <div class="flex items-center gap-7">
                            <label for="returnDate-<?= $bookData['title']; ?>" class="font-medium cursor-pointer w-1/2">Enter
                                Rent Period of <?= $bookData['title'] ?>:</label>
                            <div class="flex-1">
                                <input type="text" name="returnDate-<?= str_replace(" ", "_", $bookData['title']); ?>"
                                    id="returnDate-<?= $bookData['title']; ?>"
                                    value="<?= $cardDetails["returnDate-" . str_replace(" ", "_", $bookData['title'])] ?? ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                                <span
                                    class="text-red-600 text-sm font-medium"><?= $err[$bookData['title'] . "returnDateErr"] ?? ''; ?></span>
                            </div>
                        </div>
                        <?php $cartItemsId .= ("&" . $itemId); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php $config = require "./core/config.php"; ?>
                <input type="hidden" name="items"
                    value="<?= openssl_encrypt($cartItemsId, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                <button name="cartPayment"
                    class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Get
                    All Books</button>
            </form>
        </section>
    <?php else: ?>
        <section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-center gap-8 w-full">
            <h2 class="font-bold text-5xl text-gray-500">Your cart is empty...</h2>
        </section>
    <?php endif; ?>
</main>