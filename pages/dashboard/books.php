<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Library Books</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[600px]">
            <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-md">
                <select name="category" id="category" class="px-2 py-2 outline-none border-r border-gray-500 text-lg"
                    aria-label="Select category">
                    <?php
                    $query = new DatabaseQuery();
                    $categories = $query->selectAll('category');
                    $config = require "./core/config.php";
                    ?>
                    <option
                        value="<?= openssl_encrypt('all', $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                        All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                            <?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="text" name="bookName" id="bookName" placeholder="Search Books by Title or Author"
                class="pl-44 pr-4 py-2 outline-none w-full rounded-md text-lg">
            <button name="searchBookAdmin"
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
        <a href="/admin/books/addBook"
            class="flex items-center gap-3 px-6 py-1.5 rounded-md bg-indigo-500 hover:bg-indigo-700 text-white ml-auto">
            <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                </path>
            </svg>
            <span class="text-lg">Add New Book</span>
        </a>
    </div>
</header>
<?php
$joins = [
    [
        'table' => 'category',
        'condition' => 'books.category_id = category.id'
    ],
    [
        'table' => 'quantity',
        'condition' => 'quantity.book_id = books.id'
    ],
];
$books = $query->selectAllJoin('books', $joins);
?>
<?php if (!count($books)): ?>
    <section class="flex-1 flex items-center justify-center">
        <h1 class="font-bold text-5xl text-gray-500">There Are No Books In LibGen...</h1>
    </section>
<?php elseif ($_SERVER['QUERY_STRING']): ?>
    <?php
    $bookIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($bookIds):
        $bookIds = explode("&", $bookIds);
        unset($bookIds[0]);
        ?>
        <div>
            <ul class="flex-1 px-6 space-y-4 overflow-y-auto">
                <?php foreach ($bookIds as $bookId): ?>
                    <?php
                    $uuidBook = $query->selectColumn('book_uuid', 'books', $bookId);
                    $book = $query->selectOneJoin('books', $joins, '*', $uuidBook, 'book_uuid');
                    ?>
                    <li class="px-5 py-3 bg-white rounded-md relative">
                        <?php if (isset($_COOKIE['deleteId']) && $_COOKIE['deleteId'] === $book['book_uuid']): ?>
                            <div class="absolute top-0 inset-x-0 bg-red-500 text-white py-1 px-3 text-center text-lg">
                                <?= $_COOKIE['err'] ?>
                            </div>
                            <button type="button" class="absolute top-2.5 right-8 text-white font-medium"
                                onclick="window.location.reload()">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill="currentColor"
                                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                    </path>
                                </svg>
                            </button>
                        <?php endif; ?>
                        <article class="flex items-center gap-10 h-32">
                            <div class="aspect-w-16 aspect-h-9 h-full">
                                <img src="/libgen/assets/uploads/images/books/<?= $book['cover'] ?>" alt=""
                                    class="h-full w-full object-cover object-center">
                            </div>
                            <div class="flex flex-col justify-between h-full">
                                <h2 class="text-2xl font-semibold"><?= $book['title'] ?></h2>
                                <h3 class="text-lg font-semibold"><?= $book['author'] ?></h3>
                                <dl class="grid grid-cols-3 gap-8">
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Category:</dt>
                                        <dd><?= $book['name'] ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Total Quantity:</dt>
                                        <dd><?= $book['copies'] ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Available:</dt>
                                        <dd><?= $book['available'] ?></dd>
                                    </div>
                                </dl>
                                <dl class="grid grid-cols-3 gap-8">
                                    <div class="flex gap-2 font-medium">
                                        <dt>Base Price (For 30 days):</dt>
                                        <dd>&#x20B9;<?= $book['rent'] + $book['base'] ?></dd>
                                    </div>
                                    <div class="flex gap-2 font-medium">
                                        <dt>Rent after 30 days:</dt>
                                        <dd>&#x20B9;<?= $book['additional'] ?>/15 days</dd>
                                    </div>
                                    <div class="flex gap-2 font-medium">
                                        <dt>Fine charge:</dt>
                                        <dd>&#x20B9;<?= $book['fine'] ?>/day</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="flex flex-col justify-evenly h-full ml-auto">
                                <form action="/formHandler" method="post">
                                    <input type="hidden" name="id"
                                        value="<?= openssl_encrypt($book['book_uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                    <button name="updateBook"
                                        class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                                        Book Info</button>
                                </form>
                                <form action="/formHandler" method="post">
                                    <input type="hidden" name="id"
                                        value="<?= openssl_encrypt($book['book_uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                    <button type="button" onclick="document.getElementById('deleteModal').style.display='flex'"
                                        class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                                        Book</button>
                                    <div id="deleteModal"
                                        class="absolute inset-0 bg-gray-500/60 hidden flex-col justify-center items-center space-y-8">
                                        <div class="flex gap-16 items-center">
                                            <p class="font-semibold text-3xl">Are you sure?</p>
                                            <button type="button"
                                                onclick="document.getElementById('deleteModal').style.display='none'" class=""
                                                onclick="document.getElementById('modal-encryptedId').style.display='none'">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill="currentColor"
                                                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex gap-16 items-center w-72">
                                            <button name="deleteBook"
                                                class="flex-1 px-4 py-1 bg-white text-red-600 font-medium text-lg rounded-md hover:bg-red-600 hover:text-white">Yes</button>
                                            <button type="button"
                                                onclick="document.getElementById('deleteModal').style.display='none'"
                                                class="flex-1 px-4 py-1 bg-white text-black font-medium text-lg rounded-md">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </article>
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
    <div>
        <ul class="flex-1 px-6 space-y-4 overflow-y-auto">
            <?php foreach ($books as $book): ?>
                <li class="px-5 py-3 bg-white rounded-md relative">
                    <?php if (isset($_COOKIE['deleteId']) && $_COOKIE['deleteId'] === $book['book_uuid']): ?>
                        <div class="absolute top-0 inset-x-0 bg-red-500 text-white py-1 px-3 text-center text-lg">
                            <?= $_COOKIE['err'] ?>
                        </div>
                        <button type="button" class="absolute top-2.5 right-8 text-white font-medium"
                            onclick="window.location.reload()">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill="currentColor"
                                    d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                </path>
                            </svg>
                        </button>
                    <?php endif; ?>
                    <article class="flex items-center gap-10 h-32">
                        <div class="aspect-w-16 aspect-h-9 h-full">
                            <img src="/libgen/assets/uploads/images/books/<?= $book['cover'] ?>" alt=""
                                class="h-full w-full object-cover object-center">
                        </div>
                        <div class="flex flex-col justify-between h-full">
                            <h2 class="text-2xl font-semibold"><?= $book['title'] ?></h2>
                            <h3 class="text-lg font-semibold"><?= $book['author'] ?></h3>
                            <dl class="grid grid-cols-3 gap-8">
                                <div class="flex gap-2">
                                    <dt class="font-medium">Category:</dt>
                                    <dd><?= $book['name'] ?></dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Total Quantity:</dt>
                                    <dd><?= $book['copies'] ?></dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Available:</dt>
                                    <dd><?= $book['available'] ?></dd>
                                </div>
                            </dl>
                            <dl class="grid grid-cols-3 gap-8">
                                <div class="flex gap-2 font-medium">
                                    <dt>Base Price (For 30 days):</dt>
                                    <dd>&#x20B9;<?= $book['rent'] + $book['base'] ?></dd>
                                </div>
                                <div class="flex gap-2 font-medium">
                                    <dt>Rent after 30 days:</dt>
                                    <dd>&#x20B9;<?= $book['additional'] ?>/15 days</dd>
                                </div>
                                <div class="flex gap-2 font-medium">
                                    <dt>Fine charge:</dt>
                                    <dd>&#x20B9;<?= $book['fine'] ?>/day</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="flex flex-col justify-evenly h-full ml-auto">
                            <form action="/formHandler" method="post">
                                <input type="hidden" name="id"
                                    value="<?= openssl_encrypt($book['book_uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                <button name="updateBook"
                                    class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                                    Book Info</button>
                            </form>
                            <form action="/formHandler" method="post">
                                <input type="hidden" name="id"
                                    value="<?= openssl_encrypt($book['book_uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                <button type="button" onclick="document.getElementById('deleteModal').style.display='flex'"
                                    class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                                    Book</button>
                                <div id="deleteModal"
                                    class="absolute inset-0 bg-gray-500/60 hidden flex-col justify-center items-center space-y-8">
                                    <div class="flex gap-16 items-center">
                                        <p class="font-semibold text-3xl">Are you sure?</p>
                                        <button type="button"
                                            onclick="document.getElementById('deleteModal').style.display='none'" class=""
                                            onclick="document.getElementById('modal-encryptedId').style.display='none'">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill="currentColor"
                                                    d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex gap-16 items-center w-72">
                                        <button name="deleteBook"
                                            class="flex-1 px-4 py-1 bg-white text-red-600 font-medium text-lg rounded-md hover:bg-red-600 hover:text-white">Yes</button>
                                        <button type="button"
                                            onclick="document.getElementById('deleteModal').style.display='none'"
                                            class="flex-1 px-4 py-1 bg-white text-black font-medium text-lg rounded-md">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>