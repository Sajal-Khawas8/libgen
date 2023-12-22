<?php
$query = new DatabaseQuery();
$user = $query->selectOne('users', $_SESSION['user'][0], 'uuid');
?>
<article
    class="mx-auto <?= ($_SESSION['user'][1] !== '1') ? 'bg-white' : 'bg-slate-200' ?> rounded-md px-4 py-6 w-4/5 max-w-md space-y-4">
    <?php if (empty($user['image'])): ?>
        <div class="w-64 h-64 rounded-md mx-auto bg-gray-600 flex items-center justify-center">
            <svg class="w-60 h-60 text-white" xmlns="http://www.w3.org/2000/svg" docname="user.svg" version="0.48.4 r9939"
                x="0px" y="0px" viewBox="0 0 1200 1200" enable-background="new 0 0 1200 1200" xml:space="preserve"
                fill="currentColor">
                <path id="path25031" connector-curvature="0"
                    d="M939.574,858.383c-157.341-57.318-207.64-105.702-207.64-209.298 c0-62.17,51.555-102.462,69.128-155.744c17.575-53.283,27.741-116.367,36.191-162.256c8.451-45.889,11.809-63.638,16.404-112.532 C859.276,157.532,818.426,0,600,0C381.639,0,340.659,157.532,346.404,218.553c4.596,48.894,7.972,66.645,16.404,112.532 c8.433,45.888,18.5,108.969,36.063,162.256c17.562,53.286,69.19,93.574,69.19,155.744c0,103.596-50.298,151.979-207.638,209.298 C102.511,915.83,0,972.479,0,1012.5c0,39.957,0,187.5,0,187.5h1200c0,0,0-147.543,0-187.5S1097.426,915.894,939.574,858.383 L939.574,858.383z">
                </path>
            </svg>
        </div>
    <?php else: ?>
        <div class="w-64 h-64 rounded-md mx-auto border border-gray-400">
            <img src="/libgen/assets/uploads/images/users/<?= $user['image'] ?>" alt="<?= $user['name']; ?>"
                class="w-full h-full object-cover rounded-md">
        </div>
    <?php endif; ?>
    <div class="space-y-3">
        <h2 class="font-semibold text-2xl text-center"><?= $user['name']; ?></h2>
        <dl class="max-w-xs mx-auto overflow-hidden">
            <div class="grid grid-cols-2 gap-2">
                <dt class="font-medium">Email:</dt>
                <dd><a href="mailto:<?= $user['email']; ?>"><?= $user['email']; ?></a></dd>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <dt class="font-medium">Address:</dt>
                <dd><?= $user['address']; ?></dd>
            </div>
        </dl>
    </div>
    <div class="space-y-6">
        <?php $config = require "./core/config.php"; ?>
        <div class="flex gap-10">
            <form action="/formHandler" method="post" class="flex-1">
                <input type="hidden" name="id"
                    value="<?= openssl_encrypt($user['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>">
                <button name="<?= isset($_SESSION['isAdmin']) ? 'updateAdmin' : 'updateUser' ?>"
                    class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                    Info</button>
            </form>
            <form action="/formHandler" method="post" class="flex-1">
                <button name="logout"
                    class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Log
                    Out</button>
            </form>
        </div>
        <form action="/formHandler" method="post">
            <input type="hidden" name="id"
                value="<?= openssl_encrypt($user['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>">
            <button name="deleteAccount"
                class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                Account</button>
        </form>
    </div>
</article>