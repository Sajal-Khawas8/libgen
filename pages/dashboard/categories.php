<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Book Categories</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <input type="text" name="categoryName" id="categoryName" placeholder="Search categories"
                class="px-4 py-2 text-base outline-none w-full rounded-md">
            <button name="searchCategory"
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
        <a href="/admin/categories/addCategory"
            class="flex items-center gap-3 px-6 py-1.5 rounded-md bg-indigo-500 hover:bg-indigo-700 text-white ml-auto">
            <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                </path>
            </svg>
            <span class="text-lg">Add New Category</span>
        </a>
    </div>
</header>
<?php
$query = new DatabaseQuery();
$categories = $query->selectAll('category');
?>
<?php if (!count($categories)): ?>
    <section class="flex-1 flex items-center justify-center gap-8">
        <h1 class="font-bold text-5xl text-gray-500">There Are No Categories...</h1>
    </section>
<?php elseif ($_SERVER['QUERY_STRING']): ?>
    <?php
    $config = require "./core/config.php";
    $categoryIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($categoryIds):
        $categoryIds = explode("&", $categoryIds);
        unset($categoryIds[0]);
        ?>
        <div>
            <ul class="flex-1 px-6 flex flex-wrap gap-8 overflow-y-auto">
                <?php foreach ($categoryIds as $categoryId): ?>
                    <?php $category = $query->selectOne('category', $categoryId); ?>
                    <li class="px-5 py-3 bg-white rounded-md h-fit">
                        <article class="flex gap-10">
                            <div class="space-y-4">
                                <h2 class="text-2xl font-semibold"><?= $category['name']; ?></h2>
                                <dl class="space-y-2">
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Total Books:</dt>
                                        <dd>50</dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Base Price (For 30 days):</dt>
                                        <dd>&#x20B9;<?= $category['base']; ?></dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Rent after 30 days:</dt>
                                        <dd>&#x20B9;<?= $category['additional']; ?>/15 days</dd>
                                    </div>
                                    <div class="flex gap-2">
                                        <dt class="font-medium">Fine charge:</dt>
                                        <dd>&#x20B9;<?= $category['fine']; ?>/day</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="space-y-4">
                                <?php $config = require "./core/config.php"; ?>
                                <form action="/formHandler" method="post">
                                    <input type="hidden" name="id"
                                        value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                    <button name="updateCategory" class="p-1 bg-indigo-500 text-white rounded-md">
                                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path
                                                d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z">
                                            </path>
                                            <title>Edit Category Info</title>
                                        </svg>
                                    </button>
                                </form>
                                <form action="/formHandler" method="post">
                                    <input type="hidden" name="id"
                                        value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                    <button name="deleteCategory" class="p-1 bg-red-500 text-white rounded-md">
                                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                            </path>
                                            <title>Delete this Category</title>
                                        </svg>
                                    </button>
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
        <ul class="flex-1 px-6 flex flex-wrap gap-8 overflow-y-auto">
            <?php foreach ($categories as $category): ?>
                <li class="px-5 py-3 bg-white rounded-md h-fit">
                    <article class="flex gap-10">
                        <div class="space-y-4">
                            <h2 class="text-2xl font-semibold"><?= $category['name']; ?></h2>
                            <dl class="space-y-2">
                                <div class="flex gap-2">
                                    <dt class="font-medium">Total Books:</dt>
                                    <dd>50</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Base Price (For 30 days):</dt>
                                    <dd>&#x20B9;<?= $category['base']; ?></dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Rent after 30 days:</dt>
                                    <dd>&#x20B9;<?= $category['additional']; ?>/15 days</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="font-medium">Fine charge:</dt>
                                    <dd>&#x20B9;<?= $category['fine']; ?>/day</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="space-y-4">
                            <?php $config = require "./core/config.php"; ?>
                            <form action="/formHandler" method="post">
                                <input type="hidden" name="id"
                                    value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                <button name="updateCategory" class="p-1 bg-indigo-500 text-white rounded-md">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z">
                                        </path>
                                        <title>Edit Category Info</title>
                                    </svg>
                                </button>
                            </form>
                            <form action="/formHandler" method="post">
                                <input type="hidden" name="id"
                                    value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>">
                                <button name="deleteCategory" class="p-1 bg-red-500 text-white rounded-md">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                        </path>
                                        <title>Delete this Category</title>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>