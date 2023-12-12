<?php
$query = new DatabaseQuery();
$joins = [
    [
        'table' => 'users',
        'condition' => 'payment.user_id = users.uuid'
    ],
    [
        'table' => 'books',
        'condition' => 'payment.item_id = books.id'
    ]
];
$paymentData = $query->selectAllJoin('payment', $joins);
?>

<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Payment Information</h1>
    <?php if (count($paymentData)): ?>
        <div class="flex items-center gap-2">
            <form action="" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
                <input type="text" name="searchBox" id="searchBox" placeholder="Search payments by user email"
                    class="px-4 py-1 text-base outline-none w-full rounded-md">
                <button class="absolute inset-y-0 right-0 px-2 rounded-r-md bg-slate-200" aria-label="Search">
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
<div class="flex-1 overflow-auto">
    <?php if (!count($paymentData)): ?>
        <h2 class="text-3xl font-medium text-gray-600">No Payment Data Available...
        </h2>
    <?php else: ?>
        <table class="mx-4 border-separate border-spacing-0 text-center border border-b-2 border-gray-800">
            <thead class="sticky top-0 bg-indigo-500 text-white">
                <tr>
                    <th rowspan="2" class="border-2 border-r border-gray-800 px-1 w-56">Payment ID</th>
                    <th colspan="2" class="border-x border-y-2 border-gray-800 px-1">Reader</th>
                    <th colspan="2" class="border-x border-y-2 border-gray-800 px-1">Book</th>
                    <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-24">Amount</th>
                    <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-32">Transaction Date</th>
                </tr>
                <tr>
                    <th class="border-x border-b-2 border-gray-800 px-1 w-48">Name</th>
                    <th class="border-x border-b-2 border-gray-800 px-1 w-48">Email</th>
                    <th class="border-x border-b-2 border-gray-800 px-1 w-48">Title</th>
                    <th class="border-x border-b-2 border-gray-800 px-1 w-48">Author</th>
                </tr>
            </thead>
            <tbody class="max-h-48 overflow-auto">
                <?php foreach ($paymentData as $key => $data): ?>
                    <tr class="<?= $key%2 == 0 ? 'bg-indigo-200' : 'bg-indigo-300'; ?>">
                        <td rowspan="2" class="border border-l-2 border-gray-800 p-2"><?= $data['payment_id'] ?></td>
                        <td rowspan="2" class="border border-gray-800 p-2"><?= $data['name'] ?></td>
                        <td rowspan="2" class="border border-gray-800 p-2 max-w-[11rem] truncate"><a
                                href="mailto:<?= $data['email'] ?>"><?= $data['email'] ?></a></td>
                        <?php if (!str_contains($data['item_id'], 'cart')): ?>
                            <td class="border border-gray-800 p-2"><?= $data['title'] ?></td>
                            <td class="border border-gray-800 p-2"><?= $data['author'] ?></td>
                            <td rowspan="2" class="border border-gray-800 p-2">&#x20B9;30</td>
                            <td rowspan="2" class="border border-gray-800 p-2">30-11-2023</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $joins = [
                            [
                                'table' => 'paid_items',
                                'condition' => 'paid_items.item_id = books.id'
                            ],
                        ];
                        $paidItems = $query->selectAllJoinSpecific('books', $joins, $data['payment_id'], 'payment_id');
                        ?>
                        <td class="border border-gray-800 p-2"><?= $paidItems[0]['title'] ?></td>
                        <td class="border border-gray-800 p-2"><?= $paidItems[0]['author'] ?></td>
                        <td rowspan="2" class="border border-gray-800 p-2">&#x20B9;30</td>
                        <td rowspan="2" class="border border-gray-800 p-2">30-11-2023</td>
                        </tr>
                        <?php
                        unset($paidItems[0]);
                        foreach ($paidItems as $item):
                            ?>
                            <tr class="<?= $key%2 == 0 ? 'bg-indigo-200' : 'bg-indigo-300'; ?>">
                                <td class="border border-gray-800 p-2"><?= $item['title']; ?></td>
                                <td class="border border-gray-800 p-2"><?= $item['author']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>