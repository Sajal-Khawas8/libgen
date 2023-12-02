<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Books given on Rent</h1>
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
    </div>
</header>
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
            <button type="button"
                class="ml-auto bg-indigo-500 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700"
                onclick="document.getElementById('modal-encryptedId').style.display='flex'">View
                Readers</button>
        </article>
        <div id="modal-encryptedId" class="absolute inset-0 bg-gray-200/90 justify-center px-6 py-4 hidden">
            <article class="space-y-4 flex flex-col">
                <h2 class="font-semibold text-2xl text-center">Readers of Title</h2>
                <div class="flex-1 overflow-auto">
                    <table class="text-center border border-b-2 border-gray-800 border-separate border-spacing-0">
                        <thead class="sticky top-0 bg-indigo-500 text-white">
                            <tr>
                                <th rowspan="2" class="border-2 border-r border-gray-800 px-1">S. No.</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Name</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-40">Email</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-52">Address</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Issue Date</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Due Date</th>
                                <th colspan="4" class="border-2 border-l border-gray-800 px-1">Rent</th>
                            </tr>
                            <tr>
                                <th class="border-x border-b-2 border-gray-800 px-1 w-28">Base Price</th>
                                <th class="border-x border-b-2 border-gray-800 px-1 w-28">Rent after 30 days</th>
                                <th class="border-x border-b-2 border-gray-800 px-1 w-28">Fine after due date</th>
                                <th class="border-x border-b-2 border-r-2 border-gray-800 px-1 w-28">Total Rent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-indigo-200 even:bg-indigo-300">
                                <td class="border border-l-2 border-gray-800 p-2">1.</td>
                                <td class="border border-gray-800 p-2">Sajal</td>
                                <td class="border border-gray-800 p-2 max-w-[10rem] truncate">
                                    <a href="mailto:">sajal@gmail.com</a>
                                </td>
                                <td class="border border-gray-800 p-2">
                                    <address class="not-italic">John Doe
                                        123 Main Street
                                        Apt 4B
                                        Citytown, State 12345
                                        Country
                                    </address>
                                </td>
                                <td class="border border-gray-800 p-2">13-11-2023</td>
                                <td class="border border-gray-800 p-2">12-12-2023</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30/15 days</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30/day</td>
                                <td class="border border-r-2 border-gray-800 p-2">&#x20B9;30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>
            <button type="button" class="absolute top-5 right-8"
                onclick="document.getElementById('modal-encryptedId').style.display='none'">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill="currentColor"
                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                    </path>
                </svg>
            </button>
        </div>
    </li>
</ul>