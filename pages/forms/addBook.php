<div class="flex flex-col h-4/5 justify-center space-y-10">
    <h1 class="text-center text-4xl font-semibold">Add New Book</h1>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data"
        class="space-y-8 max-w-md mx-auto">
        <div>
            <input type="text" name="title" id="title" placeholder="Title"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <input type="text" name="author" id="author" placeholder="Author"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['emailErr'] ?? ''; ?></span>
            </div>
            <div>
                <select name="category" id="category"
                    class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <option value="Category1">Category 1</option>
                    <option value="category1">Category 2</option>
                </select>
                <span class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
            </div>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <label>Choose Cover Picture: </label>
                <input type="file" name="coverPicture"
                    class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-200 file:text-violet-700 hover:file:bg-violet-100 hover:file:cursor-pointer">
            </div>
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['pictureErr'] ?? ''; ?></span>
        </div>
        <div>
            <textarea name="description" id="description" placeholder="Description"
                class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500 resize-none h-28"></textarea>
            <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
        </div>
        <button name="register" id="register"
            class="w-full px-4 py-2 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-800">Add
            Book</button>
    </form>
</div>