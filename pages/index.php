<?php
setcookie('prevPage', $uri);
if (isset($_SESSION['user']) && ($_SESSION['user']['role'] != 1)) {
    header("Location: /admin");
    exit;
}
$query = new DatabaseQuery();
$config = require "./core/config.php";

if (isset($_POST['searchBooks'])) {
    $categoryId = openssl_decrypt($_POST['category'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$categoryId) {
        header("Location: /libgen");
        exit;
    }
    $query = new DatabaseQuery();
    $condition = [
        'active' => true,
    ];
    if ($categoryId !== 'all') {
        $condition['category_id'] = $categoryId;
    }
    $categorizedBooks[] = $query->selectPartial('books', ['title', 'author'], $_POST['bookName'], $condition);
} else {
    $joins = [
        [
            'table' => 'category',
            'condition' => 'books.category_id=category.id'
        ]
    ];
    $uncategorizedBooks = $query->selectAllJoinSpecific('books', $joins, 1, 'active');
    $categorizedBooks = [];
    foreach ($uncategorizedBooks as $book) {
        if (!isset($categorizedBooks[$book['name']])) {
            $categorizedBooks[$book['name']] = [];
        }
        $categorizedBooks[$book['name']][] = $book;
    }
    ksort($categorizedBooks);
}
?>
<main class="space-y-8">

    <?php if (count($categorizedBooks) === 0): ?>
        <section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-around gap-8">
            <h1 class="font-bold text-5xl text-gray-500">Coming Soon...</h1>
        </section>
    <?php else: ?>
        <!-- Books -->
        <section
            class="bg-[url(/libgen/assets/images/banner.jpeg)] h-[calc(100vh-4rem)] bg-no-repeat bg-cover flex flex-col items-center gap-28">
            <header class="text-center text-2xl font-semibold text-white space-y-8 mt-40">
                <h1 class="text-4xl">Welcome to <a href="/libgen" class="text-red-600 text-5xl">LibGen</a>
                </h1>
                <p>Unlocking Worlds, One Page at a Time: Your Gateway to Knowledge and Imagination.</p>
            </header>
            <form action="<?= htmlspecialchars($_SERVER["QUERY_STRING"]) . "#search"; ?>" method="post"
                class="text-gray-800 divide-x divide-gray-500 relative w-[800px]">
                <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-lg">
                    <select name="category" id="category" class="px-2 py-2 outline-none border-r border-gray-500 text-lg"
                        aria-label="Select category">
                        <?php
                        $categories = $query->selectAll('category');
                        ?>
                        <option
                            value="<?= openssl_encrypt('all', $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                            <?= (isset($_POST['searchBooks']) && $_POST['category'] === openssl_encrypt('all', $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])) ? 'selected' : ''; ?>>
                            All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']); ?>"
                                <?= (isset($_POST['searchBooks']) && $_POST['category'] === openssl_encrypt($category['id'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])) ? 'selected' : ''; ?>>
                                <?= $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="text" name="bookName" id="bookName" placeholder="Search Books by Title or Author"
                    value="<?= $_POST['bookName'] ?? ''; ?>" class="pl-44 pr-4 py-2 outline-none w-full rounded-lg text-lg">
                <button name="searchBooks"
                    class="absolute inset-y-0 right-0 px-3 rounded-r-lg text-lg bg-indigo-600 hover:bg-indigo-800 text-white font-medium"
                    aria-label="Search">
                    Search Book
                </button>
            </form>
        </section>
        <div id="search">
            <?php foreach ($categorizedBooks as $category => $books): ?>
                <Section>
                    <h2 class="font-medium text-2xl pl-16"><?= is_string($category) ? $category : 'Searched Books'; ?></h2>
                    <ul class="grid grid-cols-5 items-center gap-x-20 gap-y-12 flex-wrap px-16 py-8">
                        <?php
                        foreach ($books as $book):
                            ?>
                            <li class="border rounded-lg divide-y relative hover:shadow-lg">
                                <figure>
                                    <div class="h-72 w-full border">
                                        <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?>"
                                            class="h-full w-full object-fill">
                                    </div>
                                    <figcaption class="p-2 max-w-full space-y-4">
                                        <h3 class="font-semibold text-xl text-blue-700 truncate"><?= $book['title']; ?></h3>
                                        <h4 class="font-medium truncate"><?= $book['author']; ?></h4>
                                    </figcaption>
                                </figure>
                                <?php
                                $config = require "./core/config.php";
                                $bookId = openssl_encrypt($book['book_uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
                                ?>
                                <a href="/bookDetails?<?= $bookId; ?>" class="absolute inset-0"></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </Section>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>