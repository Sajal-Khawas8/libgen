<?php
unset($userData);
if ((isset($_SESSION['user']) && empty($_SERVER['QUERY_STRING']) && $uri !== 'addMember') || (!isset($_SESSION['user']) && !empty($_SERVER['QUERY_STRING']))) {
    $_SESSION['refresh'] = true;
    header("Location: /libgen");
    exit;
}
if (!empty($_SERVER['QUERY_STRING'])) {
    $query = new DatabaseQuery();
    $config = require "./core/config.php";
    $uuid = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if (!$uuid) {
        setcookie('user', '', time() - 1);
        unset($_SESSION['isAdmin']);
        $_SESSION['refresh'] = true;
        header("Location: /libgen");
        exit;
    }
    $userData = $query->selectOne('users', $uuid, 'uuid');
}
if (isset($_COOKIE['err'])) {
    $err = unserialize($_COOKIE['err']);
    $userData = unserialize($_COOKIE['data']);
}
?>
<main class="container border-x space-y-6 py-8 min-h-[calc(100vh-4rem-3.5rem)] overflow-auto">
    <h1 class="text-center text-4xl font-semibold">
        <?= empty($_SERVER['QUERY_STRING']) ? ($uri !== 'addMember' ? 'New User Registration' : 'New Admin Registration') : 'Update Your Info'; ?>
    </h1>
    <form action="/formHandler" method="post" enctype="multipart/form-data" class="space-y-8 max-w-md mx-auto">
        <div>
            <input type="text" name="name" id="name" placeholder="Full Name" value="<?= $userData['name'] ?? ''; ?>"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $err['nameErr'] ?? ''; ?></span>
        </div>
        <div>
            <input type="email" name="email" id="email" placeholder="Email Address"
                value="<?= $userData['email'] ?? ''; ?>"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $err['emailErr'] ?? ''; ?></span>
        </div>
        <div>
            <div class="flex items-center gap-3">
                <label for="profilePicture">Choose Profile Picture: </label>
                <input type="file" name="profilePicture"
                    class="w-56 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200 hover:file:cursor-pointer">
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

                            <?php if (empty($userData['image'])): ?>
                                <div class="flex gap-6 items-center px-4 py-3">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true" class="h-6 w-6 text-yellow-400">
                                            <path fill-rule="evenodd"
                                                d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <p class="font-medium">You have not uploaded your profile picture!</p>
                                </div>
                            <?php else: ?>
                                <div class="py-4">
                                    <img src="<?= $userData['image']; ?>" alt="<?= $userData['name']; ?>"
                                        class="w-52 h-52 mx-auto">
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
            <span class="text-red-600 text-sm font-medium"><?= $err['pictureErr'] ?? ''; ?></span>
        </div>
        <div>
            <textarea name="address" id="address" placeholder="Address"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500 resize-none h-28"><?= $userData['address'] ?? ''; ?></textarea>
            <span class="text-red-600 text-sm font-medium"><?= $err['addressErr'] ?? ''; ?></span>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <input type="password" name="<?= empty($_SERVER['QUERY_STRING']) ? 'password' : 'oldPassword' ?>"
                    id="password" placeholder="<?= empty($_SERVER['QUERY_STRING']) ? 'Password' : 'Old Password' ?>"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span
                    class="text-red-600 text-sm font-medium"><?= empty($_SERVER['QUERY_STRING']) ? ($err['passwordErr'] ?? '') : ($err['cnfrmPasswordErr'] ?? ''); ?></span>
            </div>
            <div>
                <input type="password" name="<?= empty($_SERVER['QUERY_STRING']) ? 'confirmPassword' : 'password' ?>"
                    id="confirmPassword"
                    placeholder="<?= empty($_SERVER['QUERY_STRING']) ? 'Confirm Password' : 'New Password' ?>"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span
                    class="text-red-600 text-sm font-medium"><?= empty($_SERVER['QUERY_STRING']) ? ($err['cnfrmPasswordErr'] ?? '') : ($err['passwordErr'] ?? ''); ?></span>
            </div>
        </div>
        <input type="hidden" name="id" value="<?= $_SERVER['QUERY_STRING']; ?>">
        <button
            name="<?= empty($_SERVER['QUERY_STRING']) ? ($uri !== 'addMember' ? 'register' : 'registerAdmin') : 'updateData'; ?>"
            id="<?= empty($_SERVER['QUERY_STRING']) ? 'register' : 'updateData' ?>"
            class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800"><?= empty($_SERVER['QUERY_STRING']) ? 'Register' : 'Update' ?></button>
    </form>
    <?php if (empty($_SERVER['QUERY_STRING']) && $uri !== 'addMember'): ?>
        <footer>
            <p class="text-center text-lg">Already have an account? <a href="/login"
                    class="text-indigo-600 font-medium">Login here</a></p>
        </footer>
    <?php endif; ?>
</main>