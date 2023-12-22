<?php require "./templates/header.php"; ?>

<header class="bg-gray-700 px-4 py-2 h-16 sticky top-0 z-50">
    <?php if (isset($_COOKIE['message'])): ?>
        <div
            class="fixed top-0 z-50 inset-x-0 text-white text-xl flex items-center justify-center h-12 <?= isset($_COOKIE['error']) ? 'bg-red-500' : 'bg-green-500'; ?>">
            <?= $_COOKIE['message']; ?>
            <button type="button" onclick="window.location.reload()"
                class="absolute top-3 right-16 bg-gray-800 rounded-full">
                <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.877075 7.49988C0.877075 3.84219 3.84222 0.877045 7.49991 0.877045C11.1576 0.877045 14.1227 3.84219 14.1227 7.49988C14.1227 11.1575 11.1576 14.1227 7.49991 14.1227C3.84222 14.1227 0.877075 11.1575 0.877075 7.49988ZM7.49991 1.82704C4.36689 1.82704 1.82708 4.36686 1.82708 7.49988C1.82708 10.6329 4.36689 13.1727 7.49991 13.1727C10.6329 13.1727 13.1727 10.6329 13.1727 7.49988C13.1727 4.36686 10.6329 1.82704 7.49991 1.82704ZM9.85358 5.14644C10.0488 5.3417 10.0488 5.65829 9.85358 5.85355L8.20713 7.49999L9.85358 9.14644C10.0488 9.3417 10.0488 9.65829 9.85358 9.85355C9.65832 10.0488 9.34173 10.0488 9.14647 9.85355L7.50002 8.2071L5.85358 9.85355C5.65832 10.0488 5.34173 10.0488 5.14647 9.85355C4.95121 9.65829 4.95121 9.3417 5.14647 9.14644L6.79292 7.49999L5.14647 5.85355C4.95121 5.65829 4.95121 5.3417 5.14647 5.14644C5.34173 4.95118 5.65832 4.95118 5.85358 5.14644L7.50002 6.79289L9.14647 5.14644C9.34173 4.95118 9.65832 4.95118 9.85358 5.14644Z"
                        fill="currentColor"></path>
                </svg>
            </button>
        </div>
    <?php endif; ?>
    <nav class="flex justify-between items-center gap-4">
        <div class="pl-20">
            <a href="/libgen" class="flex items-center">
                <img src="/libgen/assets/images/logo.png" alt="LibGen Logo" class="h-12 w-12 object-cover">
                <span class="text-red-600 font-medium text-2xl px-3">LibGen</span>
            </a>
        </div>
        <ul class="flex items-center gap-16 text-xl text-white">
            <li class="hover:text-indigo-500"><a href="/libgen">Home</a></li>
            <li class="hover:text-indigo-500"><a href="/mybooks">My Books</a></li>
            <!-- <li class="hover:text-indigo-500"><a href="/libgen#reviews">Reviews</a></li> -->
        </ul>
        <ul class="flex items-center gap-9 pr-20">
            <?php if (isset($_SESSION['user'])): ?>
                <li class="text-white text-lg font-medium px-2 py-1 rounded-md hover:text-indigo-600">
                    <a href="/cart">
                        <svg fill="currentColor" class="w-7 h-7" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 32 32">
                            <title>cart</title>
                            <path d="M12 29c0 1.657-1.343 3-3 3s-3-1.343-3-3c0-1.657 1.343-3 3-3s3 1.343 3 3z"></path>
                            <path d="M32 29c0 1.657-1.343 3-3 3s-3-1.343-3-3c0-1.657 1.343-3 3-3s3 1.343 3 3z"></path>
                            <path
                                d="M32 16v-12h-24c0-1.105-0.895-2-2-2h-6v2h4l1.502 12.877c-0.915 0.733-1.502 1.859-1.502 3.123 0 2.209 1.791 4 4 4h24v-2h-24c-1.105 0-2-0.895-2-2 0-0.007 0-0.014 0-0.020l26-3.98z">
                            </path>
                            <title>Cart</title>
                        </svg>
                    </a>
                </li>
                <li class="text-white text-lg font-medium px-2 py-1 rounded-md hover:text-indigo-600">
                    <a href="/settings">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <g>
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path
                                    d="M5.334 4.545a9.99 9.99 0 0 1 3.542-2.048A3.993 3.993 0 0 0 12 3.999a3.993 3.993 0 0 0 3.124-1.502 9.99 9.99 0 0 1 3.542 2.048 3.993 3.993 0 0 0 .262 3.454 3.993 3.993 0 0 0 2.863 1.955 10.043 10.043 0 0 1 0 4.09c-1.16.178-2.23.86-2.863 1.955a3.993 3.993 0 0 0-.262 3.455 9.99 9.99 0 0 1-3.542 2.047A3.993 3.993 0 0 0 12 20a3.993 3.993 0 0 0-3.124 1.502 9.99 9.99 0 0 1-3.542-2.047 3.993 3.993 0 0 0-.262-3.455 3.993 3.993 0 0 0-2.863-1.954 10.043 10.043 0 0 1 0-4.091 3.993 3.993 0 0 0 2.863-1.955 3.993 3.993 0 0 0 .262-3.454zM13.5 14.597a3 3 0 1 0-3-5.196 3 3 0 0 0 3 5.196z">
                                </path>
                            </g>
                        </svg>
                    </a>
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
            <?php endif; ?>
        </ul>
    </nav>
</header>