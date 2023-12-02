<main class="container border-x flex gap-0 min-h-[calc(100vh-4rem-3.5rem)]">
    <section class="flex-1 space-y-8 px-6 py-4 bg-slate-100">
        <h1 class="font-semibold text-3xl">Books in Cart</h1>
        <ul class="mx-auto space-y-6">
            <li class="px-5 py-3 bg-white rounded-md">
                <article class="flex items-center gap-10 h-32">
                    <div class="aspect-w-16 aspect-h-9 h-full">
                        <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                            alt="" class="h-full w-full object-cover object-center">
                    </div>
                    <div class="flex-1 flex flex-col justify-between h-full">
                        <div class="flex justify-between">
                            <h2 class="text-2xl font-semibold">Title</h2>
                            <form action="" method="post">
                                <button class="p-1 bg-red-500 text-white rounded-md">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                        </path>
                                        <title>Remove this book</title>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <h3 class="text-lg font-semibold">Author</h3>
                        <dl class="grid grid-cols-2 gap-8">
                            <div class="flex gap-2">
                                <dt class="font-medium">Category:</dt>
                                <dd>category 1</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="font-medium">Availability:</dt>
                                <dd>In stock</dd>
                            </div>
                        </dl>
                        <dl class="grid grid-cols-2 gap-8">
                            <div class="flex gap-2">
                                <dt class="font-medium">Base Price (For 30 days):</dt>
                                <dd>₹20</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="font-medium">Rent after 30 days:</dt>
                                <dd>₹5/15 days</dd>
                            </div>
                        </dl>
                    </div>
                </article>
            </li>
        </ul>
    </section>
    <section class="flex-1 space-y-8 px-6 py-4">
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
                    <span class="text-red-600 text-sm font-medium"><?= $registrationErr['passwordErr'] ?? ''; ?></span>
                </div>
                <div>
                    <input type="password" name="cvc" id="cvc" placeholder="CVC"
                        class="w-full px-4 py-2 border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span
                        class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
                </div>
            </div>
            <div class="flex items-center gap-7">
                <label for="returnDate" class="font-medium cursor-pointer">Choose Return Date of book1:</label>
                <div class="flex-1">
                    <input type="date" name="returnDate" id="returnDate" placeholder="CVC"
                        class="w-full px-4 py-2 cursor-pointer border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span
                        class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
                </div>
            </div>
            <div class="flex items-center gap-7">
                <label for="returnDate" class="font-medium cursor-pointer">Choose Return Date of book2:</label>
                <div class="flex-1">
                    <input type="date" name="returnDate" id="returnDate" placeholder="CVC"
                        class="w-full px-4 py-2 cursor-pointer border border-gray-600 rounded outline-indigo-600 placeholder:text-gray-500">
                    <span
                        class="text-red-600 text-sm font-medium"><?= $registrationErr['cnfrmPasswordErr'] ?? ''; ?></span>
                </div>
            </div>
            <button
                class="px-4 py-1 bg-indigo-600 text-white text-lg font-medium rounded-md w-full hover:bg-indigo-800">Read All
                @ 20</button>
        </form>
    </section>
</main>