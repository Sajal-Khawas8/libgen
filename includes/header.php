<?php require "./templates/header.php"; ?>

<header class="bg-sky-400 px-4 py-2 h-16 sticky top-0 z-50">
    <nav class="flex justify-around items-center gap-4">
        <div class="">
            <a href="/libgen" class="flex items-center">
                <img src="/libgen/assets/images/logo.png" alt="LibGen Logo" class="h-12 w-8 object-cover">
                <span class="text-red-800 font-medium text-2xl px-3">LibGen</span>
            </a>
        </div>
        <ul class="flex items-center gap-7 text-xl text-white">
            <li class="hover:text-indigo-800"><a href="/libgen">Home</a></li>
            <li class="hover:text-indigo-800"><a href="/mybooks">My Books</a></li>
            <li>
                <form action="" method="post" class="text-gray-800 divide-x divide-gray-500 relative w-[700px]">
                    <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-lg">
                        <select name="searchCriteria" id="searchCriteria" class="px-2 py-1 text-base outline-none"
                            aria-label="Select search criteria">
                            <option value="title">Search by Title</option>
                            <option value="author">Search by Author</option>
                        </select>
                        <select name="category" id="category"
                            class="px-2 py-1 text-base outline-none border-r border-gray-500"
                            aria-label="Select category">
                            <option value="all">All</option>
                            <option value="1">Science Fiction</option>
                            <option value="2">2</option>
                        </select>
                        <div class="bg-gray-500 absolute right-0 inset-y-0 w-0"></div>
                    </div>
                    <input type="text" name="searchBox" id="searchBox" placeholder="Search Books by Title or Author"
                        class="pl-[21rem] pr-4 py-1 text-base outline-none w-full rounded-lg">
                    <button class="absolute inset-y-0 right-0 px-2 rounded-r-lg" aria-label="Search">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </button>
                </form>
            </li>
        </ul>
        <ul class="flex items-center gap-3">
            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/admin">Dashboard</a>
                </li>
            <?php elseif (isset($_COOKIE['user'])): ?>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/settings">Settings</a>
                </li>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/login">cart</a>
                </li>
            <?php else: ?>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/signUp">Sign Up</a>
                </li>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/login">Login</a>
                </li>
                <li
                    class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                    <a href="/masterLogin">Admin Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>