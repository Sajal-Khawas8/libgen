<?php
setcookie('prevPage', "$uri?{$_SERVER['QUERY_STRING']}");
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
    $cardDetails = unserialize($_COOKIE['data']);
}
?>
<main class="container border-x flex gap-0">
    <?php require "./includes/book.php"; ?>
    <div class="flex-1 space-y-6 px-6 py-8">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Summary</h2>
                <?php
                $query = new DatabaseQuery();
                $config = require "./core/config.php";
                $isRented = $id = false;
                if (isset($_COOKIE['user'])) {
                    $conditions = [
                        [
                            'criteria' => 'user_id',
                            'id' => $_COOKIE['user']
                        ],
                        [
                            'criteria' => 'book_id',
                            'id' => openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])
                        ]
                    ];
                    $id = $query->selectColumnMultiCondition('id', 'cart', $conditions);
                    $isRented = $query->selectColumnMultiCondition('id', 'rented_books', $conditions);
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
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Availability:</dt>
                    <?php if ($bookData['available']): ?>
                        <dd class="px-4 rounded-full font-medium bg-green-400 text-green-700">In stock</dd>
                    <?php else: ?>
                        <dd class="px-4 rounded-full font-medium bg-red-400 text-red-700">Out of stock</dd>
                    <?php endif; ?>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Base Price:</dt>
                    <dd>&#x20B9;<?= $bookData['rent'] + $bookData['base'] ?></dd>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Rent after 30 days:</dt>
                    <dd>&#x20B9;<?= $bookData['additional'] ?>/15 days</dd>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Fine charge:</dt>
                    <dd>&#x20B9;<?= $bookData['fine'] ?>/day</dd>
                </div>
            </dl>
        </article>
        <?php if ($bookData['available'] && !$isRented): ?>
            <article class="space-y-8">
                <h2 class="font-semibold text-3xl">Payment</h2>
                <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                    <div>
                        <input type="text" name="cardNumber" id="cardNumber"
                            placeholder="Card Number ex.1234-1234-1234-1234"
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
                    <div class="flex items-center gap-7">
                        <label for="returnDate" class="font-medium cursor-pointer">Choose Return Date:</label>
                        <div class="flex-1">
                            <input type="date" name="returnDate" id="returnDate"
                                value="<?= $cardDetails['returnDate'] ?? ''; ?>"
                                class="w-full px-4 py-2 cursor-pointer border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                            <span class="text-red-600 text-sm font-medium"><?= $err['returnDateErr'] ?? ''; ?></span>
                        </div>
                    </div>
                    <input type="hidden" name="amount"
                        value="<?= openssl_encrypt(($bookData['rent'] + $bookData['base']), $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                    <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
                    <button name="payment"
                        class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Read
                        @ 20</button>
                </form>
            </article>
        <?php endif; ?>
    </div>
</main>