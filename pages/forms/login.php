<?php
if (isset($_COOKIE['user'])) {
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
}
if (isset($_COOKIE['data'])) {
    $userData = unserialize($_COOKIE['data']);
}
?>
<main class="container border-x">
    <article class="space-y-6 min-h-[calc(100vh-64px-56px)] flex flex-col justify-center">
        <h1 class="text-center text-4xl font-semibold">Login to LigGen</h1>
        <form action="/formHandler" method="post" enctype="multipart/form-data"
            class="space-y-8 py-5 max-w-md mx-auto w-full">
            <div>
                <input type="text" name="email" id="email" placeholder="Email Address" value="<?= $userData['email'] ?? '' ?>"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['emailErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="password" name="password" id="password" placeholder="Password"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $err['passwordErr'] ?? ''; ?></span>
            </div>
            <a href="" class="text-indigo-600 font-medium inline-block">Forgot Password?</a>
            <?php if ($uri === 'login'): ?>
                <button name="login" id="login"
                    class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Login</button>
            </form>
            <footer>
                <p class="text-center text-lg">Don't have an account? <a href="/signUp"
                        class="text-indigo-600 font-medium">Create
                        here</a></p>
            </footer>
        <?php else: ?>
            <button name="<?= trim($_SERVER['REQUEST_URI'], '/'); ?>" id="<?= trim($_SERVER['REQUEST_URI'], '/'); ?>"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Login</button>
            </form>
        <?php endif; ?>
    </article>
</main>