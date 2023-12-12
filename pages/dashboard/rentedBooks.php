<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Books given on Rent</h1>
    <div class="flex items-center gap-2">
        <form action="" method="post" class="text-gray-800 divide-gray-500 relative w-[700px]">
            <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-md">
                <select name="searchCriteria" id="searchCriteria" class="px-2 py-1 text-base outline-none"
                    aria-label="Select search criteria">
                    <option value="title">Search by Title</option>
                    <option value="author">Search by Author</option>
                </select>
                <select name="category" id="category" class="px-2 py-1 text-base outline-none border-r border-gray-500"
                    aria-label="Select category">
                    <option value="all">All</option>
                    <option value="1">Science Fiction</option>
                    <option value="2">2</option>
                </select>
                <div class="bg-gray-500 absolute right-0 inset-y-0 w-0"></div>
            </div>
            <input type="text" name="searchBox" id="searchBox" placeholder="Search Books by Title or Author"
                class="pl-[21rem] pr-4 py-1 text-base outline-none w-full rounded-md">
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
        'condition' => 'books.id = quantity.book_id'
    ]
];
?>
<?php if (count($rentedBookIds)): ?>
    <div>
        <ul class="flex-1 px-6 space-y-4 overflow-y-auto">
            <?php foreach ($rentedBookIds as $bookKey => $bookId): ?>
                <?php $book = $query->selectOne('books', $bookId) ?>
                <li class="px-5 py-3 bg-white rounded-md">
                    <article class="flex items-center gap-10 h-32">
                        <div class="aspect-w-16 aspect-h-9 h-full">
                            <img src="/libgen/assets/uploads/images/books/<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                class="h-full w-full object-cover object-center">
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
                                    <dt>Base Price (For 30 days):</dt>
                                    <dd>&#x20B9;<?= $book['base'] + $book['rent']; ?></dd>
                                </div>
                                <div class="flex gap-2 font-medium">
                                    <dt>Rent after 30 days:</dt>
                                    <dd>&#x20B9;<?= $book['additional']; ?>/15 days</dd>
                                </div>
                                <div class="flex gap-2 font-medium">
                                    <dt>Fine charge:</dt>
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
                                // $joins = [
                                //     [
                                //         'table' => 'users',
                                //         'condition' => 'rented_books.category_id = category.id'
                                //     ],
                                //     [
                                //         'table' => 'quantity',
                                //         'condition' => 'books.id = quantity.book_id'
                                //     ]
                                // ];
                                // $rentedBook = $query->selectAllJoin('rented_books', $joins);
                                $users = $query->selectAllSpecific('rented_books', $book['book_uuid'], 'book_id');
                                // echo "<pre>";
                                // print_r($users);
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
                                            <th class="border-x border-b-2 border-gray-800 px-1 w-28">Base Price</th>
                                            <th class="border-x border-b-2 border-gray-800 px-1 w-28">Rent after 30 days</th>
                                            <th class="border-x border-b-2 border-gray-800 px-1 w-28">Fine after due date</th>
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
                                                <td class="border border-gray-800 p-2">
                                                    &#x20B9;<?= $base = $book['base'] + $book['rent']; ?>
                                                </td>
                                                <?php
                                                $dueDate = new DateTime($user['due_date']);
                                                $date = new DateTime($user['date']);
                                                $interval = $dueDate->diff($date);
                                                $days = $interval->days;
                                                ?>
                                                <td class="border border-gray-800 p-2">
                                                    &#x20B9;<?= $additional = $days > 30 ? (ceil(($days - 30) / 15) * $book['additional']) : 0; ?>
                                                </td>
                                                <?php
                                                $dueDate = new DateTime($user['due_date']);
                                                $currentDate = new DateTime();
                                                $interval = $dueDate->diff($currentDate);
                                                $days = $interval->days;
                                                $dueDate = $dueDate->format("Y-m-d");
                                                $currentDate = $currentDate->format("Y-m-d");
                                                ?>
                                                <td class="border border-gray-800 p-2">
                                                    &#x20B9;<?= $fine = $dueDate < $currentDate ? ($days * $book['fine']) : 0 ?>
                                                </td>
                                                <td class="border border-r-2 border-gray-800 p-2">
                                                    &#x20B9;<?= $base + $additional + $fine; ?></td>
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
    <section class="flex items-center justify-center h-full">
        <h2 class="text-3xl font-medium text-gray-600">No book is given on rent...</h2>
    </section>
<?php endif; ?>