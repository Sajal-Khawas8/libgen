<?php
$users = [
    [
        'id' => 1,
        'name' => 'Sajal'
    ],
    [
        'id' => 2,
        'name' => 'Neeraj'
    ]
];

$books = [
    [
        'userid' => 1,
        'book' => 'abc',
    ],
    [
        'userid' => 1,
        'book' => 'xyz',
    ],
];

?>
<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">LibGen Readers</h1>
    <div class="flex items-center gap-2">
        <form action="" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <input type="text" name="searchBox" id="searchBox" placeholder="Search readers by email"
                class="px-4 py-1 text-base outline-none w-full rounded-md">
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
<ul class="flex-1 overflow-y-auto px-6 flex gap-8 flex-wrap">
    <li class="bg-white rounded-md p-2 h-fit">
        <article class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-2xl">Sajal Khawas</h2>
                <form action="" method="post">
                    <button>
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg"
                            enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
                            <g>
                                <rect fill="none" height="24" width="24"></rect>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M18,11c0.7,0,1.37,0.1,2,0.29V10c0-1.1-0.9-2-2-2h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10 c0,1.1,0.9,2,2,2h6.26C11.47,20.87,11,19.49,11,18C11,14.13,14.13,11,18,11z M8.9,6c0-1.71,1.39-3.1,3.1-3.1s3.1,1.39,3.1,3.1v2 H8.9V6z">
                                    </path>
                                    <path
                                        d="M18,13c-2.76,0-5,2.24-5,5s2.24,5,5,5s5-2.24,5-5S20.76,13,18,13z M18,15c0.83,0,1.5,0.67,1.5,1.5S18.83,18,18,18 s-1.5-0.67-1.5-1.5S17.17,15,18,15z M18,21c-1.03,0-1.94-0.52-2.48-1.32C16.25,19.26,17.09,19,18,19s1.75,0.26,2.48,0.68 C19.94,20.48,19.03,21,18,21z">
                                    </path>
                                </g>
                            </g>
                            <title>Block this user</title>
                        </svg>
                    </button>
                </form>
            </div>
            <h3 class="font-medium text-lg"><a href="mailto:">sajal@gmail.com</a></h3>
            <dl class="w-64">
                <dt class="font-medium">Address:</dt>
                <dd>
                    <address class="not-italic">John Doe
                        123 Main Street
                        Apt 4B
                        Citytown, State 12345
                        Country
                    </address>
                </dd>
            </dl>
            <div class="flex items-center justify-between gap-12">
                <dl class="flex gap-2">
                    <dt class="font-medium">Total Books taken on Rent:</dt>
                    <dd>5</dd>
                </dl>
                <button type="button"
                    class="bg-indigo-500 text-white px-4 py-1 rounded-md font-medium hover:bg-indigo-700" onclick="document.getElementById('modal-encryptedId').style.display='flex'">View
                    Books</button>
            </div>
        </article>
        <div id="modal-encryptedId" class="absolute inset-0 bg-gray-200/90 justify-center px-6 py-4 hidden">
            <article class="space-y-4 flex flex-col">
                <h2 class="font-semibold text-2xl text-center">Books taken on Rent by Sajal</h2>
                <div class="flex-1 overflow-auto">
                    <table class="text-center border border-b-2 border-gray-800 border-separate border-spacing-0">
                        <thead class="sticky top-0 bg-indigo-500 text-white">
                            <tr>
                                <th rowspan="2" class="border-2 border-r border-gray-800 px-1">S. No.</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-64">Books</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Issue Date</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Due Date</th>
                                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-2">Return Date</th>
                                <th colspan="4" class="border-2 border-l border-gray-800 px-1">Rent</th>
                            </tr>
                            <tr>
                                <th class="border-x border-b-2 border-gray-800 px-1">Base Price</th>
                                <th class="border-x border-b-2 border-gray-800 px-1">Rent after 30 days</th>
                                <th class="border-x border-b-2 border-gray-800 px-1">Fine after due date</th>
                                <th class="border-x border-b-2 border-r-2 border-gray-800 px-1">Total Rent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-indigo-200 even:bg-indigo-300">
                                <td class="border border-l-2 border-gray-800 p-2">1.</td>
                                <td class="text-left border border-gray-800 p-2">Three Men in a boat</td>
                                <td class="border border-gray-800 p-2">13-11-2023</td>
                                <td class="border border-gray-800 p-2">12-12-2023</td>
                                <td class="border border-gray-800 p-2">13-12-2023</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30/15 days</td>
                                <td class="border border-gray-800 p-2">&#x20B9;30/day</td>
                                <td class="border border-r-2 border-gray-800 p-2">&#x20B9;30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>
            <button type="button" class="absolute top-5 right-8" onclick="document.getElementById('modal-encryptedId').style.display='none'">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill="currentColor"
                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                    </path>
                </svg>
            </button>
        </div>
    </li>
</ul>