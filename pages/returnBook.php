<?php
setcookie('prevPage', "$uri?{$_SERVER['QUERY_STRING']}");
if (!isset($_SESSION['user']) || $_SESSION['user'][1] !== '1') {
    header("Location: /login");
    exit;
}
$query = new DatabaseQuery();
$config = require "./core/config.php";
$bookId = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
$conditions = [
    [
        'criteria' => 'book_id',
        'id' => $bookId
    ],
    [
        'criteria' => 'user_id',
        'id' => $_SESSION['user'][0]
    ]
];
$rentedBookId = $query->selectColumnMultiCondition('id', 'orders', $conditions);
$joins = [
    [
        'table' => 'books',
        'condition' => 'books.book_uuid = orders.book_id'
    ],
    [
        'table' => 'category',
        'condition' => 'books.category_id = category.id'
    ]
];
$rentData = $query->selectOneJoin('orders', $joins, "*", $rentedBookId, "orders.id");
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
                    <?php
                    $dueDate = new DateTime($rentData['due_date']);
                    $rentDate = new DateTime($rentData['date']);
                    $interval = $dueDate->diff($rentDate);
                    $rentDays = $interval->days;
                    $currentDate = new DateTime();
                    $dueDateStr = $dueDate->format("Y-m-d");
                    $currentDateStr = $currentDate->format("Y-m-d");
                    $overdueDays = 0;
                    if ($dueDateStr < $currentDateStr) {
                        $interval = $dueDate->diff($currentDate);
                        $overdueDays = $interval->days;
                    }
                    ?>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent Period</dt>
                        <dd><?= $rentDays; ?> days</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent (&#x20B9;<?= $rentData['rent']; ?>/day)</dt>
                        <dd>&#x20B9;<?= $rent = $rentData['rent'] * $rentDays; ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Overdue Days</dt>
                        <dd><?= $overdueDays; ?> days</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Fine (&#x20B9;<?= $rentData['fine']; ?>/day)</dt>
                        <dd>&#x20B9;<?= $fine = $rentData['fine'] * $overdueDays; ?></dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Total Rent</dt>
                        <dd>&#x20B9;<?= $rent + $fine; ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Amount Paid</dt>
                        <dd>&#x20B9;<?= $rent; ?></dd>
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