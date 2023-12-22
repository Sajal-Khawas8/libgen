<?php
if (isset($_COOKIE['data'])) {
    $data = unserialize($_COOKIE['data']);
}
?>
<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Books given on Rent</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[600px]">
            <div class="absolute left-0 inset-y-0 px-3 divide-x divide-gray-500 rounded-l-md">
                <select name="category" id="category" class="px-2 py-2 text-lg outline-none border-r border-gray-500"
                    aria-label="Select category">
                    <?php
                    $query = new DatabaseQuery();
                    $categories = $query->selectAll('category');
                    $config = require "./core/config.php";
                    ?>
                    <option
                        value="<?= openssl_encrypt('all', $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                        <?= (isset($data) && $data['category'] === openssl_encrypt('all', $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])) ? 'selected' : ''; ?>>
                        All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                            <?= (isset($data) && $data['category'] === openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])) ? 'selected' : ''; ?>>
                            <?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="text" name="bookName" id="bookName" placeholder="Search Books by Title or Author"
                value="<?= $data['bookName'] ?? ''; ?>" class="pl-44 pr-4 py-2 text-lg outline-none w-full rounded-md">
            <button name="searchRentedBook"
                class="absolute inset-y-0 right-0 px-3 rounded-r-md bg-slate-200 hover:bg-indigo-600 hover:text-white"
                aria-label="Search">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                </svg>
            </button>
        </form>
        <span class="text-red-600 text-sm font-medium"><?= $_COOKIE['err'] ?? '' ?></span>
    </div>
</header>
<?php
$rentedBookIds = $query->selectNegate('quantity', 'available', 'copies');
$rentedBookIds = array_map(function ($book) {
    return $book['book_id'];
}, $rentedBookIds);
$joins = [
    [
        'table' => 'category',
        'condition' => 'books.category_id = category.id'
    ],
    [
        'table' => 'quantity',
        'condition' => 'books.book_uuid = quantity.book_id'
    ]
];
?>
<?php if (!count($rentedBookIds)): ?>
    <section class="flex items-center justify-center h-full">
        <h2 class="text-5xl font-medium text-gray-500">No book is given on rent...</h2>
    </section>
<?php elseif ($_SERVER['QUERY_STRING']): ?>
    <?php
    $bookIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    $bookIds = explode("&", $bookIds);
    unset($bookIds[0]);
    $bookIds = array_intersect($bookIds, $rentedBookIds);
    if ($bookIds): ?>
        <div class="flex-1 overflow-y-auto">
            <ul class="px-6 space-y-4">
                <?php foreach ($bookIds as $bookKey => $bookId): ?>
                    <?php
                    $book = $query->selectOneJoin('books', $joins, "*", $bookId, 'book_uuid');
                    ?>
                    <li class="px-5 py-3 bg-white rounded-md">
                        <article class="flex items-center gap-10 h-32">
                            <div class="h-full w-24">
                                <img src="/libgen/assets/uploads/images/books/<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                    class="h-full w-full object-fill">
                            </div>
                            <div class="flex flex-col justify-between h-full">
                                <h2 class="text-2xl font-semibold"><?= $book['title']; ?></h2>
                                <h3 class="text-lg font-semibold"><?= $book['author']; ?></h3>
                                <dl class="grid grid-cols-3 gap-8">
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Category:</dt>
                                        <dd><?= $book['name']; ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Total Quantity:</dt>
                                        <dd><?= $book['copies']; ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Available:</dt>
                                        <dd><?= $book['available']; ?></dd>
                                    </div>
                                </dl>
                                <dl class="grid grid-cols-3 gap-8">
                                    <div class="flex gap-2 font-medium">
                                        <dt>Rent:</dt>
                                        <dd>&#x20B9;<?= $book['rent']; ?>/day</dd>
                                    </div>
                                    <div class="flex gap-2 font-medium">
                                        <dt>Fine:</dt>
                                        <dd>&#x20B9;<?= $book['fine']; ?>/day</dd>
                                    </div>
                                </dl>
                            </div>
                            <button type="button"
                                class="ml-auto bg-indigo-500 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700"
                                onclick="document.getElementById('modal-<?= $bookKey; ?>').style.display='flex'">View
                                Readers</button>
                        </article>
                        <div id="modal-<?= $bookKey; ?>" class="absolute inset-0 bg-gray-200/90 justify-center px-6 py-4 hidden">
                            <article class="space-y-4 flex flex-col">
                                <h2 class="font-semibold text-2xl text-center">Readers of <?= $book['title']; ?></h2>
                                <div class="flex-1 overflow-auto">
                                    <?php
                                    $users = $query->selectAllSpecific('orders', $book['book_uuid'], 'book_id');
                                    ?>
                                    <table class="text-center border border-b-2 border-gray-800 border-separate border-spacing-0">
                                        <thead class="sticky top-0 bg-indigo-500 text-white">
                                            <tr>
                                                <th rowspan="2" class="border-2 border-r border-gray-800 px-1">S. No.</th>
                                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Name</th>
                                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Email</th>
                                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-52">Address</th>
                                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Issue Date</th>
                                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Due Date</th>
                                                <th colspan="3" class="border-2 border-l border-gray-800 px-1">Rent</th>
                                            </tr>
                                            <tr>
                                                <th class="border-x border-b-2 border-gray-800 px-1 w-28">Rent</th>
                                                <th class="border-x border-b-2 border-gray-800 px-1 w-28">Fine</th>
                                                <th class="border-x border-b-2 border-r-2 border-gray-800 px-1 w-28">Total Rent</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $key => $user): ?>
                                                <?php
                                                $userData = $query->selectOne('users', $user['user_id'], 'uuid');
                                                ?>
                                                <tr class="odd:bg-indigo-200 even:bg-indigo-300">
                                                    <td class="border border-l-2 border-gray-800 p-2"><?= $key + 1; ?></td>
                                                    <td class="border border-gray-800 p-2"><?= $userData['name']; ?></td>
                                                    <td class="border border-gray-800 p-2 max-w-[10rem] truncate">
                                                        <a href="mailto:<?= $userData['email']; ?>"><?= $userData['email']; ?></a>
                                                    </td>
                                                    <td class="border border-gray-800 p-2">
                                                        <address class="not-italic"><?= $userData['address']; ?></address>
                                                    </td>
                                                    <td class="border border-gray-800 p-2"><?= $user['date']; ?></td>
                                                    <td class="border border-gray-800 p-2"><?= $user['due_date']; ?></td>
                                                    <?php
                                                    $dueDate = new DateTime($user['due_date']);
                                                    $rentDate = new DateTime($user['date']);
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
                                                    <td class="border border-gray-800 p-2">
                                                        &#x20B9;<?= $rent = $book['rent'] * $rentDays; ?>
                                                    </td>
                                                    <td class="border border-gray-800 p-2">
                                                        &#x20B9;<?= $fine = $book['fine'] * $overdueDays; ?>
                                                    </td>
                                                    <td class="border border-r-2 border-gray-800 p-2">
                                                        &#x20B9;<?= $rent + $fine; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </article>
                            <button type="button" class="absolute top-5 right-8"
                                onclick="document.getElementById('modal-<?= $bookKey; ?>').style.display='none'">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill="currentColor"
                                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="flex-1 flex items-center justify-center">
            <h3 class="font-bold text-5xl text-gray-500">No Data Found...</h3>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="flex-1 overflow-y-auto">
        <ul class="px-6 space-y-4">
            <?php foreach ($rentedBookIds as $bookKey => $bookId): ?>
                <?php
                $book = $query->selectOneJoin('books', $joins, "*", $bookId, 'book_uuid');
                ?>
                <li class="px-5 py-3 bg-white rounded-md">
                    <article class="flex items-center gap-10 h-32">
                        <div class="h-full w-24">
                            <img src="/libgen/assets/uploads/images/books/<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                class="h-full w-full object-fill">
                        </div>
                        <div class="flex flex-col justify-between h-full">
                            <h2 class="text-2xl font-semibold"><?= $book['title']; ?></h2>
                            <h3 class="text-lg font-semibold"><?= $book['author']; ?></h3>
                            <dl class="grid grid-cols-3 gap-8">
                                <div class="flex gap-2">
                                    <dt class="font-medium">Category:</dt>
                                    <dd><?= $book['name']; ?></dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Total Quantity:</dt>
                                    <dd><?= $book['copies']; ?></dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Available:</dt>
                                    <dd><?= $book['available']; ?></dd>
                                </div>
                            </dl>
                            <dl class="grid grid-cols-3 gap-8">
                                <div class="flex gap-2 font-medium">
                                    <dt>Rent:</dt>
                                    <dd>&#x20B9;<?= $book['rent']; ?>/day</dd>
                                </div>
                                <div class="flex gap-2 font-medium">
                                    <dt>Fine:</dt>
                                    <dd>&#x20B9;<?= $book['fine']; ?>/day</dd>
                                </div>
                            </dl>
                        </div>
                        <button type="button"
                            class="ml-auto bg-indigo-500 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700"
                            onclick="document.getElementById('modal-<?= $bookKey; ?>').style.display='flex'">View
                            Readers</button>
                    </article>
                    <div id="modal-<?= $bookKey; ?>" class="absolute inset-0 bg-gray-200/90 justify-center px-6 py-4 hidden">
                        <article class="space-y-4 flex flex-col">
                            <h2 class="font-semibold text-2xl text-center">Readers of <?= $book['title']; ?></h2>
                            <div class="flex-1 overflow-auto">
                                <?php
                                $users = $query->selectAllSpecific('orders', $book['book_uuid'], 'book_id');
                                ?>
                                <table class="text-center border border-b-2 border-gray-800 border-separate border-spacing-0">
                                    <thead class="sticky top-0 bg-indigo-500 text-white">
                                        <tr>
                                            <th rowspan="2" class="border-2 border-r border-gray-800 px-1">S. No.</th>
                                            <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Name</th>
                                            <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Email</th>
                                            <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-52">Address</th>
                                            <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Issue Date</th>
                                            <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Due Date</th>
                                            <th colspan="4" class="border-2 border-l border-gray-800 px-1">Rent</th>
                                        </tr>
                                        <tr>
                                            <th class="border-x border-b-2 border-gray-800 px-1 w-28">Rent</th>
                                            <th class="border-x border-b-2 border-gray-800 px-1 w-28">Fine</th>
                                            <th class="border-x border-b-2 border-r-2 border-gray-800 px-1 w-28">Total Rent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $key => $user): ?>
                                            <?php
                                            $userData = $query->selectOne('users', $user['user_id'], 'uuid');
                                            ?>
                                            <tr class="odd:bg-indigo-200 even:bg-indigo-300">
                                                <td class="border border-l-2 border-gray-800 p-2"><?= $key + 1; ?></td>
                                                <td class="border border-gray-800 p-2"><?= $userData['name']; ?></td>
                                                <td class="border border-gray-800 p-2 max-w-[10rem] truncate">
                                                    <a href="mailto:<?= $userData['email']; ?>"><?= $userData['email']; ?></a>
                                                </td>
                                                <td class="border border-gray-800 p-2">
                                                    <address class="not-italic"><?= $userData['address']; ?></address>
                                                </td>
                                                <td class="border border-gray-800 p-2"><?= $user['date']; ?></td>
                                                <td class="border border-gray-800 p-2"><?= $user['due_date']; ?></td>
                                                <?php
                                                $dueDate = new DateTime($user['due_date']);
                                                $rentDate = new DateTime($user['date']);
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
                                                <td class="border border-gray-800 p-2">
                                                    &#x20B9;<?= $rent = $book['rent'] * $rentDays; ?>
                                                </td>
                                                <td class="border border-gray-800 p-2">
                                                    &#x20B9;<?= $fine = $book['fine'] * $overdueDays; ?>
                                                </td>
                                                <td class="border border-r-2 border-gray-800 p-2">
                                                    &#x20B9;<?= $rent + $fine; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                        <button type="button" class="absolute top-5 right-8"
                            onclick="document.getElementById('modal-<?= $bookKey; ?>').style.display='none'">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill="currentColor"
                                    d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>