<main class="container border-x space-y-8 flex gap-0">
    <?php require "./includes/book.php"; ?>
    <div class="flex-1 space-y-6 px-6">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Summary</h2>
                <form action="" method="post">
                    <button
                        class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Add to Cart</button>
                </form>
            </div>
            <dl class="space-y-4 mx-8">
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Availability:</dt>
                    <dd>In stock</dd>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Base Price:</dt>
                    <dd>30</dd>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Rent after 30 days:</dt>
                    <dd>20</dd>
                </div>
                <div class="flex gap-2 text-lg">
                    <dt class="font-medium">Fine charge:</dt>
                    <dd>20</dd>
                </div>
            </dl>
        </article>
        <article class="space-y-8">
            <h2 class="font-semibold text-3xl">Payment</h2>
            <form action="" method="post" class="space-y-10 max-w-lg mx-auto">
                <div>
                    <input type="text" name="cardNumber" id="cardNumber" placeholder="Card Number"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
                </div>
                <div>
                    <input type="text" name="cardName" id="cardName" placeholder="Name on card"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span class="text-red-600 text-sm font-medium"><?= $registrationErr['nameErr'] ?? ''; ?></span>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <input type="text" name="expiryDate" id="expiryDate" placeholder="Expiration Date (MM/YY)"
                            class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <span
                            class="text-red-600 text-sm font-medium"><?= $registrationErr['passwordErr'] ?? ''; ?></span>
                    </div>
                    <div>
                        <input type="password" name="cvc" id="cvc" placeholder="CVC"
                            class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <span
                            class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-7">
                    <label for="returnDate" class="font-medium cursor-pointer">Choose Return Date:</label>
                    <div class="flex-1">
                        <input type="date" name="returnDate" id="returnDate" placeholder="CVC"
                            class="w-full px-4 py-2 cursor-pointer border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                        <span
                            class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
                    </div>
                </div>
                <button
                    class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Read
                    @ 20</button>
            </form>
        </article>
    </div>
</main>