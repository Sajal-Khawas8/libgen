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
    $data = $query->selectOne('category', $id, 'id');
}
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
    $data = unserialize($_COOKIE['data']);
}
?>

<div class="flex flex-col h-4/5 justify-center space-y-10">
    <h1 class="text-center text-4xl font-semibold">
        <?= empty($_SERVER['QUERY_STRING']) ? 'Add New Category' : 'Update Category' ?></h1>
    <form action="/formHandler" method="post" enctype="multipart/form-data" class="space-y-8 max-w-lg mx-auto">
        <div>
            <input type="text" name="name" id="name" placeholder="Category Name" value="<?= $data['name'] ?? ''; ?>"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $err['nameErr'] ?? ''; ?></span>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <input type="text" name="base" id="basePrice" placeholder="Base Price in Rs"
                    value="<?= $data['base'] ?? '' ?>"
                    class="w-full px-3 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['priceErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="text" name="additional" id="rent" placeholder="Rent after 30 days"
                    value="<?= $data['additional'] ?? '' ?>"
                    class="w-full px-3 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['rentErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="text" name="fine" id="fine" placeholder="Fine Charge" value="<?= $data['fine'] ?? '' ?>"
                    class="w-full px-3 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['fineErr'] ?? ''; ?></span>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
        <button name="<?= empty($_SERVER['QUERY_STRING']) ? 'addCategory' : 'updateCategoryData' ?>" id="addCategory"
            class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800"><?= empty($_SERVER['QUERY_STRING']) ? 'Add Category' : 'Update Category' ?></button>
    </form>
</div>