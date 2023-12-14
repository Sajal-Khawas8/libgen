<?php
setcookie('prevPage', $uri);
if (isset($_SESSION['isAdmin'])) {
    header("Location: /admin");
    exit;
}
$query = new DatabaseQuery();
if (isset($_COOKIE['data'])) {
    $data = unserialize($_COOKIE['data']);
}
?>
<main class="space-y-8">

    <?php if ($query->rowCount('books') === 0): ?>
        <section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-around gap-8">
            <h1 class="font-bold text-5xl text-gray-500">Coming Soon...</h1>
        </section>
    <?php else: ?>
        <!-- Books -->
        <section class="bg-[url(/libgen/assets/images/banner.jpeg)] h-[calc(100vh-4rem)] flex flex-col items-center gap-28">
            <header class="text-center text-2xl font-semibold text-white space-y-8 mt-40">
                <h1 class="text-4xl">Welcome to <a href="/libgen" class="text-red-600 text-5xl">LibGen</a>
                </h1>
                <p>Unlocking Worlds, One Page at a Time: Your Gateway to Knowledge and Imagination.</p>
            </header>
            <form action="/formHandler" method="post" class="text-gray-800 divide-x divide-gray-500 relative w-[800px]">
                <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-lg">
                    <select name="category" id="category" class="px-2 py-2 outline-none border-r border-gray-500 text-lg"
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
                    value="<?= $data['bookName'] ?? ''; ?>" class="pl-44 pr-4 py-2 outline-none w-full rounded-lg text-lg">
                <button name="searchBookHome"
                    class="absolute inset-y-0 right-0 px-3 rounded-r-lg text-lg bg-indigo-600 hover:bg-indigo-800 text-white font-medium"
                    aria-label="Search">
                    Search Book
                </button>
            </form>
        </section>
        <?php if ($_SERVER['QUERY_STRING']): ?>
            <?php
            $bookIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
            if (!$bookIds) {
                header("Location: /libgen");
                exit;
            }
            $bookIds = explode("&", $bookIds);
            unset($bookIds[0]);
            ?>
            <section id="search">
                <h2 class="font-medium text-3xl pl-16 text-center">Search Results</h2>
                <ul class="flex items-center gap-x-20 gap-y-12 flex-wrap px-16 py-8">
                    <?php foreach ($bookIds as $bookId): ?>
                        <?php $book = $query->selectOne('books', $bookId, 'id'); ?>
                        <li class="border rounded-lg divide-y relative hover:shadow-lg">
                            <figure>
                                <div class="h-72 w-56 border">
                                    <img src="/libgen/assets/uploads/images/books/<?= $book['cover']; ?>"
                                        alt="<?= $book['title']; ?>" class="h-full w-full object-fill">
                                </div>
                                <figcaption class="p-2 max-w-[14rem] space-y-4">
                                    <h3 class="font-semibold text-xl text-blue-700 line-clamp-2"><?= $book['title']; ?></h3>
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
            </section>
        <?php else: ?>
            <?php
            $categories = $query->selectAll('Category');
            foreach ($categories as $category):
                $books = $query->selectAllSpecific('books', $category['id'], 'category_id');
                if ($books):
                    ?>
                    <Section>
                        <h2 class="font-medium text-2xl pl-16"><?= $category['name'] ?></h2>
                        <ul class="flex items-center gap-x-20 gap-y-12 flex-wrap px-16 py-8">
                            <?php
                            foreach ($books as $book):
                                ?>
                                <li class="border rounded-lg divide-y relative hover:shadow-lg">
                                    <figure>
                                        <div class="h-72 w-56 border">
                                            <img src="/libgen/assets/uploads/images/books/<?= $book['cover']; ?>"
                                                alt="<?= $book['title']; ?>" class="h-full w-full object-fill">
                                        </div>
                                        <figcaption class="p-2 max-w-[14rem] space-y-4">
                                            <h3 class="font-semibold text-xl text-blue-700 line-clamp-2"><?= $book['title']; ?></h3>
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
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- Reviews section -->
        <!-- <section id="reviews" class="bg-slate-100 space-y-8 py-4">
            <h2 class="font-medium text-3xl pl-16">User Reviews</h2>
            <ul class="flex flex-wrap gap-6 px-16">
                <li class="bg-white rounded-md px-4 py-2">
                    <figure class="flex flex-col justify-between w-80 h-40">
                        <p class="line-clamp-5 flex items-center justify-center h-full">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Mollitia, ducimus. Error, accusantium? Facilis repellat itaque perspiciatis
                            eaque culpa. Vel, architecto.
                        </p>
                        <figcaption class="text-right font-medium text-lg">Sajal</figcaption>
                    </figure>
                </li>
                <li class="bg-white rounded-md px-4 py-2">
                    <figure class="flex flex-col justify-between w-80 h-40">
                        <p class="line-clamp-5 flex items-center justify-center h-full">
                            Good
                        </p>
                        <figcaption class="text-right font-medium text-lg">Sajal</figcaption>
                    </figure>
                </li>
            </ul>
        </section> -->

    <?php endif; ?>
</main>