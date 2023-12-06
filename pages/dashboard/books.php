<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Library Books</h1>
    <div class="flex items-center gap-2">
        <form action="" method="post" class="text-gray-800 divide-gray-500 relative w-[700px]">
            <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-md">
                <select name="searchCriteria" id="searchCriteria" class="px-2 py-1 text-base outline-none"
                    aria-label="Select search criteria">
                    <option value="title">Search by Title</option>
                    <option value="author">Search by Author</option>
                </select>
                <select name="category" id="category" class="px-2 py-1 text-base outline-none border-r border-gray-500"
                    aria-label="Select category">
                    <option value="all">All</option>
                    <option value="1">Science Fiction</option>
                    <option value="2">2</option>
                </select>
                <div class="bg-gray-500 absolute right-0 inset-y-0 w-0"></div>
            </div>
            <input type="text" name="searchBox" id="searchBox" placeholder="Search Books by Title or Author"
                class="pl-[21rem] pr-4 py-1 text-base outline-none w-full rounded-md">
            <button class="absolute inset-y-0 right-0 px-2 rounded-r-md bg-slate-200" aria-label="Search">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                </svg>
            </button>
        </form>
        <span class="text-red-600 text-sm font-medium"><?= $searchErr ?? '' ?></span>
        <a href="/app/dashboard/addBook"
            class="flex items-center gap-3 px-6 py-1.5 rounded-md bg-indigo-500 hover:bg-indigo-700 text-white ml-auto">
            <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                </path>
            </svg>
            <span class="text-lg">Add New Book</span>
        </a>
    </div>
</header>
<?php $query = new DatabaseQuery(); ?>
<?php if ($query->rowCount('books') === 0): ?>
    <section class="flex-1 flex items-center justify-center gap-8">
        <h1 class="font-bold text-5xl text-gray-500">There Are No Books In LibGen...</h1>
    </section>
<?php else: ?>
    <ul class="flex-1 px-6 space-y-4 overflow-y-auto">
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Total Quantity:</dt>
                            <dd>30</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Available:</dt>
                            <dd>20</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2 font-medium">
                            <dt>Base Price (For 30 days):</dt>
                            <dd>&#x20B9;20</dd>
                        </div>
                        <div class="flex gap-2 font-medium">
                            <dt>Rent after 30 days:</dt>
                            <dd>&#x20B9;5/15 days</dd>
                        </div>
                        <div class="flex gap-2 font-medium">
                            <dt>Fine charge:</dt>
                            <dd>&#x20B9;5/day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <dt class="font-medium">Quantity:</dt>
                            <dd>30</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Base Price (For 30 days):</dt>
                            <dd>20 &#8377;</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Rent after 30 days:</dt>
                            <dd>5 &#8377; / 15 days</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Fine charge:</dt>
                            <dd>5 &#8377; / day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <dt class="font-medium">Quantity:</dt>
                            <dd>30</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Base Price (For 30 days):</dt>
                            <dd>20 Rs</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Rent after 30 days:</dt>
                            <dd>5 Rs / 15 days</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Fine charge:</dt>
                            <dd>5 Rs / day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <dt class="font-medium">Quantity:</dt>
                            <dd>30</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Base Price (For 30 days):</dt>
                            <dd>20 Rs</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Rent after 30 days:</dt>
                            <dd>5 Rs / 15 days</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Fine charge:</dt>
                            <dd>5 Rs / day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <dt class="font-medium">Quantity:</dt>
                            <dd>30</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Base Price (For 30 days):</dt>
                            <dd>20 Rs</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Rent after 30 days:</dt>
                            <dd>5 Rs / 15 days</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Fine charge:</dt>
                            <dd>5 Rs / day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
        <li class="px-5 py-3 bg-white rounded-md">
            <article class="flex items-center gap-10 h-32">
                <div class="aspect-w-16 aspect-h-9 h-full">
                    <img src="https://rukminim2.flixcart.com/image/416/416/xif0q/book/y/4/8/a-competitive-book-of-agriculture-english-language-original-imagm3rjhcc7xzdj.jpeg?q=70"
                        alt="" class="h-full w-full object-cover object-center">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <h2 class="text-2xl font-semibold">Title</h2>
                    <h3 class="text-lg font-semibold">Author</h3>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Category:</dt>
                            <dd>category 1</dd>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <dt class="font-medium">Quantity:</dt>
                            <dd>30</dd>
                        </div>
                    </dl>
                    <dl class="grid grid-cols-3 gap-8">
                        <div class="flex gap-2">
                            <dt class="font-medium">Base Price (For 30 days):</dt>
                            <dd>20 Rs</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Rent after 30 days:</dt>
                            <dd>5 Rs / 15 days</dd>
                        </div>
                        <div class="flex gap-2">
                            <dt class="font-medium">Fine charge:</dt>
                            <dd>5 Rs / day</dd>
                        </div>
                    </dl>
                </div>
                <div class="flex flex-col justify-evenly h-full ml-auto">
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                            Book Info</button>
                    </form>
                    <form action="" method="post">
                        <button
                            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
                            Book</button>
                    </form>
                </div>
            </article>
        </li>
    </ul>
<?php endif; ?>