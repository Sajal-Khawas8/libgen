<main class="container border-x">
    <article class="space-y-6 min-h-[calc(100vh-64px-56px)] flex flex-col justify-center">
        <h1 class="text-center text-4xl font-semibold">Login to LigGen</h1>
        <form action="/formHandler" method="post" enctype="multipart/form-data"
            class="space-y-8 py-5 max-w-md mx-auto">
            <input type="text" name="loginName" id="loginName" placeholder="Email Address"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $loginNameErr ?? ''; ?></span>
            <input type="password" name="loginPassword" id="loginPassword" placeholder="Password"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $loginPasswordErr ?? ''; ?></span>
            <button name="login" id="login"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Login</button>
        </form>
        <?php if (trim($_SERVER['REQUEST_URI'], '/') === 'login'): ?>
            <footer>
                <p class="text-center text-lg">Don't have an account? <a href="/signUp"
                        class="text-indigo-600 font-medium">Create
                        here</a></p>
            </footer>
        <?php endif; ?>
    </article>
</main>