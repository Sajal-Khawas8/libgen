<?php
$query = new DatabaseQuery();
$joins = [
    [
        'table' => 'users',
        'condition' => 'payment.user_id = users.uuid'
    ],
    [
        'table' => 'payment_type',
        'condition' => 'payment.type = payment_type.id'
    ],
];
$paymentData = $query->selectAllJoin('payment', $joins);
if (isset($_COOKIE['data'])) {
    $data = unserialize($_COOKIE['data']);
}
?>

<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Payment Information</h1>
    <?php if (count($paymentData)): ?>
        <div class="flex items-center gap-2">
            <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
                <input type="text" name="userName" id="userName" placeholder="Search payments by user name or email"
                    value="<?= $data['userName'] ?? ''; ?>" class="px-4 py-2 text-lg outline-none w-full rounded-md">
                <button name="searchPayment"
                    class="absolute inset-y-0 right-0 px-3 rounded-r-md bg-slate-200 hover:bg-indigo-600 hover:text-white"
                    aria-label="Search">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                        <path d="M21 21l-6 -6"></path>
                    </svg>
                </button>
            </form>
            <span class="text-red-600 text-sm font-medium"><?= $searchErr ?? '' ?></span>
        </div>
    <?php endif; ?>
</header>
<div class="flex-1 overflow-auto pb-6 px-6">
    <?php if (!count($paymentData)): ?>
        <h2 class="text-5xl font-medium text-gray-500 flex justify-center items-center h-full">No Payment Data Available...
        </h2>
    <?php else: ?>
        <table class="border-separate border-spacing-0 text-center border border-b-2 border-gray-800 mx-auto">
            <thead class="sticky top-0 bg-indigo-500 text-white">
                <tr>
                    <th rowspan="2" class="border-2 border-r border-gray-800 px-3 w-44">Payment ID</th>
                    <th colspan="2" class="border-x border-y-2 border-gray-800 px-3">Reader</th>
                    <th colspan="2" class="border-x border-y-2 border-gray-800 px-3">Book</th>
                    <th rowspan="2" class="border-x border-y-2 border-gray-800 px-3">Card</th>
                    <th rowspan="2" class="border-x border-y-2 border-gray-800 px-3">Amount</th>
                    <th rowspan="2" class="border-x border-y-2 border-gray-800 px-3">Payment Type</th>
                    <th rowspan="2" class="border-2 border-l border-gray-800 px-3">Transaction Date</th>
                </tr>
                <tr>
                    <th class="border-x border-b-2 border-gray-800 px-3 w-44">Name</th>
                    <th class="border-x border-b-2 border-gray-800 px-3 w-44">Email</th>
                    <th class="border-x border-b-2 border-gray-800 px-3 w-44">Title</th>
                    <th class="border-x border-b-2 border-gray-800 px-3 w-44">Author</th>
                </tr>
            </thead>
            <tbody class="max-h-48 overflow-auto">
                <?php
                $config = require "./core/config.php";
                $userIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
                if ($_SERVER['QUERY_STRING'] && $userIds) {
                    $userIds = explode("&", $userIds);
                    unset($userIds[0]);
                    $paymentData = array_filter($paymentData, function ($data) {
                        global $userIds;
                        return in_array($data['user_id'], $userIds);
                    });
                }
                ?>

                <?php if ($_SERVER['QUERY_STRING'] && !$userIds): ?>
                    <tr class="bg-indigo-200 h-80">
                        <td colspan="6" class="text-2xl font-medium text-gray-600 border border-x-2 border-gray-800">No Data
                            Available...</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($paymentData as $key => $data): ?>
                        <tr class="<?= $key % 2 === 0 ? 'bg-indigo-200' : 'bg-indigo-300'; ?>">
                            <?php
                            $joins = [
                                [
                                    'table' => 'books',
                                    'condition' => 'paid_items.book_id = books.book_uuid'
                                ],
                            ];
                            $paidItems = $query->selectAllJoinSpecific('paid_items', $joins, $data['payment_id'], 'payment_id');
                            $numPaidItems = count($paidItems);
                            ?>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-l-2 border-gray-800 p-2">
                                <?= $data['payment_id']; ?></td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-gray-800 p-2"><?= $data['name']; ?></td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-gray-800 p-2 max-w-[11rem] truncate">
                                <a href="mailto:<?= $data['email']; ?>"><?= $data['email']; ?></a>
                            </td>
                            <td class="border border-gray-800 p-2"><?= $paidItems[0]['title']; ?>
                            </td>
                            <td class="border border-gray-800 p-2">
                                <?= $paidItems[0]['author']; ?></td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-gray-800 p-2"><?= $data['card']; ?></td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-gray-800 p-2"><?= $data['amount']; ?></td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-gray-800 p-2"><?= $data['payment_type']; ?>
                            </td>
                            <td rowspan="<?= $numPaidItems; ?>" class="border border-r-2 border-gray-800 p-2">
                                <?= date("d-m-Y", strtotime($data['creation_date'])); ?></td>
                        </tr>
                        <?php if ($numPaidItems > 1): ?>
                            <?php unset($paidItems[0]); ?>
                            <?php foreach ($paidItems as $item): ?>
                                <tr class="<?= $key % 2 === 0 ? 'bg-indigo-200' : 'bg-indigo-300'; ?>">
                                    <td class="border border-gray-800 p-2"><?= $item['title']; ?></td>
                                    <td class="border border-gray-800 p-2"><?= $item['author']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>