<main class="container border-x">
    <article class="space-y-6 min-h-[calc(100vh-64px-56px)] flex flex-col justify-center">
        <h1 class="text-center text-4xl font-semibold">Forgot Password</h1>
        <form action="/formHandler" method="post" class="space-y-8 py-5 max-w-md mx-auto w-full">
            <input type="text" name="email" id="email" placeholder="Email Address" value="<?= $_COOKIE['data'] ?? '' ?>"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $_COOKIE['err'] ?? ''; ?></span>
            <button name="forgotPassword" id="forgotPassword"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Send
                Mail</button>
        </form>
    </article>
</main>