<main class="container border-x space-y-6 py-8">
    <h1 class="text-center text-4xl font-semibold">New User Registration</h1>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data"
        class="space-y-8 max-w-md mx-auto">
        <div>
            <input type="text" name="name" id="name" placeholder="Full Name"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
        </div>
        <div>
            <input type="text" name="email" id="email" placeholder="Email Address"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['emailErr'] ?? ''; ?></span>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <label for="profilePicture">Choose Profile Picture: </label>
                <input type="file" name="profilePicture"
                    class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200 hover:file:cursor-pointer">
            </div>
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['pictureErr'] ?? ''; ?></span>
        </div>
        <div>
            <textarea name="address" id="address" placeholder="Address"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500 resize-none h-28"></textarea>
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <input type="password" name="password" id="password" placeholder="Password"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['passwordErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
            </div>
        </div>

        <button name="register" id="register"
            class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Register</button>
    </form>
    <footer>
        <p class="text-center text-lg">Already have an account? <a href="/login"
                class="text-indigo-600 font-medium">Login here</a></p>
    </footer>
</main>