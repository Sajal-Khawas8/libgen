<?php
if (isset($_COOKIE['user'])) {
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}
$config = require "./core/config.php";
$id = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
if (!$id) {
    header("Location: /libgen");
    exit;
}
list($email, $id) = explode("&", $id);
$query = new DatabaseQuery();
if (is_null($query->selectColumn('uniqueID', 'users', $email, 'email'))) {
    header("Location: /libgen");
    exit;
}
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
}
?>

<main class="container border-x">
    <article class="space-y-6 min-h-[calc(100vh-64px-56px)] flex flex-col justify-center">
        <h1 class="text-center text-4xl font-semibold">Reset Password</h1>
        <form action="/formHandler" method="post" class="space-y-8 py-5 max-w-md mx-auto w-full">
            <input type="password" name="password" id="password" placeholder="New Password"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $err['passwordErr'] ?? ''; ?></span>
            <input type="password" name="cnfrmPassword" id="cnfrmPassword" placeholder="Confirm Password"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $err['cnfrmPasswordErr'] ?? ''; ?></span>
            <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
            <button name="resetPW" id="resetPassword"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Reset
                Password</button>
        </form>
    </article>
</main>