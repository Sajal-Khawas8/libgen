<?php
$categories = $query->selectAll('category');
if (empty($categories)): ?>
    <section class="flex items-center justify-center h-4/5">
        <h1 class="text-center text-4xl font-semibold text-gray-500">
            <a href="/admin/categories/addCategory">Please add atleast one Category before adding a book</a>
        </h1>
    </section>
<?php else: ?>
    <?php
    if (!empty($_SERVER['QUERY_STRING'])) {
        $query = new DatabaseQuery();
        $config = require "./core/config.php";
        $id = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        if (!$id) {
            setcookie('user', '', time() - 1);
            unset($_SESSION['isAdmin']);
            $_SESSION['refresh'] = true;
            header("Location: /libgen");
            exit;
        }
        $joins = [
            [
                'table' => 'quantity',
                'condition' => 'books.id = quantity.book_id'
            ],
        ];
        $bookData = $query->selectOneJoin('books', $joins, '*', $id, 'book_uuid');
    }
    if (isset($_COOKIE['err'])) {
        $err = unserialize($_COOKIE['err']);
        $bookData = unserialize($_COOKIE['data']);
    }
    ?>
    <div class="flex flex-col justify-center space-y-6">
        <h1 class="text-center text-4xl font-semibold">
            <?= empty($_SERVER['QUERY_STRING']) ? 'Add New Book' : 'Update Book Data' ?></h1>
        <form action="/formHandler" method="post" enctype="multipart/form-data" class="space-y-8 max-w-md mx-auto">
            <div>
                <input type="text" name="title" id="title" placeholder="Title" value="<?= $bookData['title'] ?? ''; ?>"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['titleErr'] ?? ''; ?></span>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <input type="text" name="author" id="author" placeholder="Author"
                        value="<?= $bookData['author'] ?? ''; ?>"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $err['authorErr'] ?? ''; ?></span>
                </div>
                <div>
                    <select name="category_id" id="category"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <option value="0" class="text-gray-500">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <?php $config = require "./core/config.php"; ?>
                            <option
                                value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>"
                                <?= isset($bookData['category_id']) ? ($category['id'] === $bookData['category_id'] ? 'selected' : '') : ''; ?>>
                                <?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-red-600 text-sm font-medium"><?= $err['categoryErr'] ?? ''; ?></span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <input type="text" name="rent" id="rent" placeholder="Rent" value="<?= $bookData['rent'] ?? ''; ?>"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $err['rentErr'] ?? ''; ?></span>
                </div>
                <div>
                    <input type="text" name="copies" id="copies" placeholder="Copies"
                        value="<?= $bookData['copies'] ?? ''; ?>"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $err['copiesErr'] ?? ''; ?></span>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <label>Choose Cover Picture: </label>
                    <input type="file" name="cover"
                        class="w-56 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-200 file:text-violet-700 hover:file:bg-violet-100 hover:file:cursor-pointer">
                    <?php if (!empty($_SERVER['QUERY_STRING'])): ?>
                        <button type="button" name="viewPicture" id="viewPicture"
                            onclick="document.getElementById('imageModal').classList.add('!flex')">
                            <svg class="w-7 h-7 text-violet-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z">
                                </path>
                                <title>View Uploaded Image</title>
                            </svg>
                        </button>
                        <section id="imageModal" class="hidden absolute inset-0 bg-gray-300/70 items-center justify-center">
                            <div class="relative w-96 bg-white py-4 space-y-2">
                                <h3 class="text-xl font-semibold text-center">Uploaded Image</h3>
                                <button type="button" id="closeImageModal"
                                    onclick="document.getElementById('imageModal').classList.remove('!flex')"
                                    class="absolute top-0.5 right-4">
                                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="-6 -6 24 24"
                                        fill="currentColor">
                                        <path
                                            d="m7.314 5.9 3.535-3.536A1 1 0 1 0 9.435.95L5.899 4.485 2.364.95A1 1 0 1 0 .95 2.364l3.535 3.535L.95 9.435a1 1 0 1 0 1.414 1.414l3.535-3.535 3.536 3.535a1 1 0 1 0 1.414-1.414L7.314 5.899z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="py-4">
                                    <img src="/libgen/assets/uploads/images/books/<?= $bookData['cover']; ?>"
                                        alt="<?= $bookData['title']; ?>" class="w-60 h-72 mx-auto">
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
                <span class="text-red-600 text-sm font-medium"><?= $err['coverErr'] ?? ''; ?></span>
            </div>
            <div>
                <textarea name="description" id="description" placeholder="Description"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500 resize-none h-28"><?= $bookData['description'] ?? ''; ?></textarea>
                <span class="text-red-600 text-sm font-medium"><?= $err['descriptionErr'] ?? ''; ?></span>
            </div>
            <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
            <button name="<?= empty($_SERVER['QUERY_STRING']) ? 'addBook' : 'updateBookData' ?>" id="addBook"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800"><?= empty($_SERVER['QUERY_STRING']) ? 'Add Book' : 'Update Book Data' ?></button>
        </form>
    </div>
<?php endif; ?>