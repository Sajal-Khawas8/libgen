<?php
setcookie('prevPage', "$uri?{$_SERVER['QUERY_STRING']}");
if (!isset($_SESSION['user']) || $_SESSION['user'][1] != 1) {
    header("Location: /login");
    exit;
}
$query = new DatabaseQuery();
$config = require "./core/config.php";
$bookId = explode("&", openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']));
$_SERVER['QUERY_STRING'] = openssl_encrypt($bookId[0], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
$rentedBookId = $bookId[1];
$joins = [
    [
        'table' => 'books',
        'condition' => 'books.book_uuid = rent_history.book_id'
    ],
    [
        'table' => 'category',
        'condition' => 'books.category_id = category.id'
    ]
];
$rentData = $query->selectOneJoin('rent_history', $joins, "*", $rentedBookId, "rent_history.id");
if (!$rentData || $rentData['user_id'] !== $_SESSION['user'][0]) {
    header("Location: /mybooks");
}
?>

<main class="container border-x flex gap-0">
    <?php require "./includes/book.php"; ?>
    <div class="flex-1 space-y-6 px-6 py-8">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Rent History</h2>
            </div>
            <div class="space-y-2 divide-y px-8">
                <?php
                $issueDate = new DateTime($rentData['issue_date']);
                $dueDate = new DateTime($rentData['due_date']);
                $returnDate = new DateTime($rentData['return_date']);
                $interval = $returnDate->diff($issueDate);
                $rentDays = $interval->days;
                $dueDateStr = $dueDate->format("Y-m-d");
                $returnDateStr = $returnDate->format("Y-m-d");
                $overdueDays = 0;
                if ($dueDateStr < $returnDateStr) {
                    $interval = $returnDate->diff($dueDate);
                    $overdueDays = $interval->days;
                }
                ?>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Issue Date</dt>
                        <dd><?= date('d F Y', strtotime($rentData['issue_date'])); ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Due Date</dt>
                        <dd><?= date('d F Y', strtotime($rentData['due_date'])); ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Return Date</dt>
                        <dd><?= date('d F Y', strtotime($rentData['return_date'])); ?></dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent Period</dt>
                        <dd><?= $rentDays; ?> days</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Overdue Days</dt>
                        <dd><?= $overdueDays; ?> days</dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent Paid</dt>
                        <dd>&#x20B9;<?= $rent = $rentData['rent_paid']; ?></dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Fine Paid</dt>
                        <dd>&#x20B9;<?= $fine = $rentData['fine_paid']; ?></dd>
                    </div>

                </dl>
                <dl class="flex justify-between text-xl font-medium py-4">
                    <dt class="font-medium">Total Rent Paid</dt>
                    <dd>&#x20B9;<?= $rent + $fine; ?></dd>
                </dl>
            </div>
        </article>
    </div>
</main>