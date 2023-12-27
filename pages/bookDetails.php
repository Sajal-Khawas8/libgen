<?php
setcookie('prevPage', "$uri?{$_SERVER['QUERY_STRING']}");
$query = new DatabaseQuery();
$config = require "./core/config.php";
$bookId = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
if (!$query->selectColumn('active', 'books', $bookId, 'book_uuid')) {
    header("Location: /libgen");
    exit;
}
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
    $cardDetails = unserialize($_COOKIE['data']);
}
?>
<main class="container border-x flex gap-0 min-h-[calc(100vh-4rem-3.5rem)]">
    <?php require "./includes/book.php"; ?>
    <div class="flex-1 space-y-6 px-6 py-8">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Summary</h2>
                <?php
                $isRented = $id = false;
                if (isset($_SESSION['user'])) {
                    $conditions = [
                        'user_id' => $_SESSION['user']['id'],
                        'book_id' => openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']),
                    ];
                    $id = $query->selectColumnMultiCondition('id', 'cart', $conditions);
                    $isRented = $query->selectColumnMultiCondition('id', 'orders', $conditions);
                }
                ?>
                <?php if (!$isRented): ?>
                    <?php if ($id): ?>
                        <form action="/formHandler" method="post">
                            <input type="hidden" name="id"
                                value="<?= openssl_encrypt($id, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                            <button name="removeFromCart"
                                class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Remove
                                From Cart</button>
                        </form>
                    <?php else: ?>
                        <form action="/formHandler" method="post">
                            <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
                            <button name="addToCart"
                                class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Add
                                to Cart</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <dl class="space-y-4 mx-8">
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Availability:</dt>
                    <?php if ($bookData['available']): ?>
                        <dd class="col-span-2 px-4 rounded-full font-medium bg-green-400 text-green-700 w-fit">In stock</dd>
                    <?php else: ?>
                        <dd class="col-span-2 px-4 rounded-full font-medium bg-red-400 text-red-700 w-fit">Out of stock</dd>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Rent:</dt>
                    <dd class="col-span-2 px-4">&#x20B9;<?= $bookData['rent']; ?>/day</dd>
                </div>
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Fine charge:</dt>
                    <dd class="col-span-2 px-4">&#x20B9;<?= $bookData['fine'] ?>/day</dd>
                </div>
            </dl>
        </article>
        <?php if ($bookData['available'] && !$isRented): ?>
            <article class="space-y-8">
                <h2 class="font-semibold text-3xl">Payment</h2>
                <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-2">
                            <input type="text" name="cardNumber" id="cardNumber"
                                placeholder="Card Number ex.1234-1234-1234-1234"
                                value="<?= $cardDetails['cardNumber'] ?? ''; ?>"
                                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                            <span class="text-red-600 text-sm font-medium"><?= $err['numberErr'] ?? ''; ?></span>
                        </div>
                        <div class="flex-1">
                            <input type="text" name="returnDate" id="returnDate" placeholder="Rent Period"
                                value="<?= $cardDetails['returnDate'] ?? ''; ?>"
                                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                            <span class="text-red-600 text-sm font-medium"><?= $err['returnDateErr'] ?? ''; ?></span>
                        </div>
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
                    <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
                    <button name="payment"
                        class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Get
                        this Book</button>
                </form>
            </article>
        <?php endif; ?>
    </div>
</main>