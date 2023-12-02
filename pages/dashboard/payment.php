<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Payment Information</h1>
    <div class="flex items-center gap-2">
        <form action="" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <input type="text" name="searchBox" id="searchBox" placeholder="Search payments by user email"
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
<div class="flex-1 overflow-auto">
    <table class="mx-4 border-separate border-spacing-0 text-center border border-b-2 border-gray-800">
        <thead class="sticky top-0 bg-indigo-500 text-white">
            <tr>
                <th rowspan="2" class="border-2 border-r border-gray-800 px-1 w-40">Payment ID</th>
                <th colspan="2" class="border-x border-y-2 border-gray-800 px-1">Reader</th>
                <th colspan="4" class="border-x border-y-2 border-gray-800 px-1">Book</th>
                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-24">Amount</th>
                <th rowspan="2" class="border-x border-y-2 border-gray-800 px-1 w-32">Transaction Date</th>
            </tr>
            <tr>
                <th class="border-x border-b-2 border-gray-800 px-1 w-44">Name</th>
                <th class="border-x border-b-2 border-gray-800 px-1 w-44">Email</th>
                <th class="border-x border-b-2 border-gray-800 px-1 w-44">Title</th>
                <th class="border-x border-b-2 border-gray-800 px-1 w-44">Author</th>
                <th class="border-x border-b-2 border-gray-800 px-1 w-40">Category</th>
                <th class="border-x border-b-2 border-gray-800 px-1 w-40">Rent</th>
            </tr>
        </thead>
        <tbody class="max-h-48 overflow-auto">
            <tr class="odd:bg-indigo-200 even:bg-indigo-300">
                <td rowspan="2" class="border border-l-2 border-gray-800 p-2">fjg454vh</td>
                <td rowspan="2" class="border border-gray-800 p-2">Sajal Khawas</td>
                <td rowspan="2" class="border border-gray-800 p-2 max-w-[11rem] truncate"><a
                        href="mailto:">sajal@gmail.com</a></td>
                <td class="border border-gray-800 p-2">Three Men in a Boat</td>
                <td class="border border-gray-800 p-2">Jerome K. Jerome</td>
                <td class="border border-gray-800 p-2">Category 1</td>
                <td class="border border-gray-800 p-2">&#x20B9;30</td>
                <td rowspan="2" class="border border-gray-800 p-2">&#x20B9;30</td>
                <td rowspan="2" class="border border-gray-800 p-2">30-11-2023</td>
            </tr>
            <tr>
                <td class="border border-gray-800 p-2">Three Men in a Boat</td>
                <td class="border border-gray-800 p-2">Jerome K. Jerome</td>
                <td class="border border-gray-800 p-2">Category 2</td>
                <td class="border border-gray-800 p-2">&#x20B9;30</td>
            </tr>
        </tbody>
    </table>
</div>