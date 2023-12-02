<div class="flex flex-col h-4/5 justify-center space-y-10">
    <h1 class="text-center text-4xl font-semibold">Add New Category</h1>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data"
        class="space-y-8 max-w-lg mx-auto">
        <div>
            <input type="text" name="title" id="title" placeholder="Category Name"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <input type="text" name="basePrice" id="basePrice" placeholder="Base Price"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['emailErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="text" name="rent" id="rent" placeholder="Rent after 30 days"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['emailErr'] ?? ''; ?></span>
            </div>
            <div>
                <input type="text" name="fine" id="fine" placeholder="Fine Charge"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['emailErr'] ?? ''; ?></span>
            </div>
        </div>
        <button name="register" id="register"
            class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Add
            Category</button>
    </form>
</div>