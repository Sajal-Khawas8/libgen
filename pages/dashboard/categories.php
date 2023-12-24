<?php
if (isset($_COOKIE['data'])) {
    $data = unserialize($_COOKIE['data']);
}
?>
<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Book Categories</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <input type="text" name="categoryName" id="categoryName" placeholder="Search categories"
                value="<?= $data['categoryName'] ?? ''; ?>" class="px-4 py-2 text-base outline-none w-full rounded-md">
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
$config = require "./core/config.php";
$categoryIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
if ($_SERVER['QUERY_STRING'] && $categoryIds) {
    $categoryIds = explode("&", $categoryIds);
    unset($categoryIds[0]);
    $categories = array_filter($categories, function ($category) {
        global $categoryIds;
        return in_array($category['id'], $categoryIds);
    });
}
?>
<?php if (!count($categories)): ?>
    <section class="flex-1 flex items-center justify-center gap-8">
        <h1 class="font-bold text-5xl text-gray-500">There Are No Categories...</h1>
    </section>
<?php elseif ($_SERVER['QUERY_STRING'] && !$categoryIds): ?>
    <div class="flex-1 flex items-center justify-center">
        <h3 class="font-bold text-5xl text-gray-500">No Data Found...</h3>
    </div>
<?php else: ?>
    <div>
        <ul class="flex-1 px-6 grid grid-cols-3 gap-8 overflow-y-auto">
            <?php foreach ($categories as $category): ?>
                <li class="px-5 py-3 bg-white rounded-md h-fit relative">
                    <?php if (isset($_COOKIE['deleteId']) && $_COOKIE['deleteId'] === $category['id']): ?>
                        <div class="absolute top-0 inset-x-0 bg-red-500 text-white py-1 px-3 text-lg">
                            <?= $_COOKIE['errCategory'] ?>
                        </div>
                        <button type="button" class="absolute top-2 right-1 text-white font-medium"
                            onclick="window.location.reload()">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill="currentColor"
                                    d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                                </path>
                            </svg>
                        </button>
                    <?php endif; ?>
                    <article class="flex justify-between">
                        <div class="space-y-4">
                            <h2 class="text-2xl font-semibold"><?= $category['name']; ?></h2>
                            <dl class="flex gap-2">
                                <dt class="font-medium">Total Books:</dt>
                                <dd><?= count($query->selectAllSpecific('books', $category['id'], 'category_id')); ?></dd>
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