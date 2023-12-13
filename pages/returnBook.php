<?php
setcookie('prevPage', "$uri?{$_SERVER['QUERY_STRING']}");
$query = new DatabaseQuery();
$joins = [
    [
        'table' => 'books',
        'condition' => 'books.book_uuid = rented_books.book_id'
    ],
    [
        'table' => 'category',
        'condition' => 'books.category_id = category.id'
    ]
];
$config = require "./core/config.php";
$bookId = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
$rentData = $query->selectOneJoin('rented_books', $joins, "*", $bookId, 'book_id');
if (!$rentData) {
    header("Location: /mybooks");
}
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
            </div>
            <div class="space-y-2 divide-y px-8">
                <dl class="flex justify-between py-4">
                    <div class="flex gap-2 text-lg">
                        <dt class="font-medium">Issue Date:</dt>
                        <dd><?= date('d F Y', strtotime($rentData['date'])); ?></dd>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <dt class="font-medium">Due Date:</dt>
                        <dd><?= date('d F Y', strtotime($rentData['due_date'])); ?></dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Base Price</dt>
                        <dd>&#x20B9;<?= $rentData['base'] + $rentData['rent']; ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent after 30 days (&#x20B9;<?= $rentData['additional']; ?>/15days)</dt>
                        <?php
                        $dueDate = new DateTime($rentData['due_date']);
                        $date = new DateTime($rentData['date']);
                        $interval = $dueDate->diff($date);
                        $days = $interval->days;
                        ?>
                        <dd>&#x20B9;<?= $additional = $days > 30 ? (ceil(($days - 30) / 15) * $rentData['additional']) : 0 ?>
                        </dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Fine charge (&#x20B9;<?= $rentData['fine']; ?>/day)</dt>
                        <?php
                        $dueDate = new DateTime($rentData['due_date']);
                        $currentDate = new DateTime();
                        $interval = $dueDate->diff($currentDate);
                        $days = $interval->days;
                        $dueDate = $dueDate->format("Y-m-d");
                        $currentDate = $currentDate->format("Y-m-d");
                        ?>
                        <dd>&#x20B9;<?= $fine = $dueDate < $currentDate ? ($days * $rentData['fine']) : 0 ?></dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Total Rent</dt>
                        <dd>&#x20B9;<?= $rentData['base'] + $rentData['rent'] + $additional + $fine; ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Amount Paid</dt>
                        <dd>&#x20B9;<?= $rentData['base'] + $rentData['rent'] + $additional; ?></dd>
                    </div>
                </dl>
                <dl class="flex justify-between text-xl font-medium py-4">
                    <dt>Total Payable</dt>
                    <dd>&#x20B9;<?= $fine; ?></dd>
                </dl>
            </div>
        </article>
        <article class="space-y-8">
            <h2 class="font-semibold text-3xl">Payment</h2>
            <?php if ($fine): ?>
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
                    <?php $config = require "./core/config.php"; ?>
                    <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
                    <input type="hidden" name="amount"
                        value="<?= openssl_encrypt($fine, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>">
                    <button name="returnBookFine"
                        class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Return</button>
                </form>
            <?php else: ?>
                <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                    <p class="text-lg text-center font-medium text-gray-600">*No Payment Required</p>
                    <?php $config = require "./core/config.php"; ?>
                    <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING'] ?>">
                    <button name="returnBook"
                        class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Return</button>
                </form>
            <?php endif; ?>
        </article>
    </div>
</main>