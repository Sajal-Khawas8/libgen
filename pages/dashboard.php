<?php
setcookie('prevPage', $uri);
if(!isset($_SESSION['isAdmin'])) {
    $_SESSION['refresh'] = true;
    header("Location: /masterLogin");
    exit;
}
require "./templates/header.php"; ?>
<div class="flex gap-0 h-screen">
    <aside class="w-64 h-full px-3 py-2.5 text-lg space-y-8 hidden lg:block">
        <header class="mx-auto my-3 w-fit">
            <a href="/libgen" class="flex items-center">
                <img src="/libgen/assets/images/logo.png" alt="LibGen Logo" class="h-12 w-8 object-cover">
                <span class="text-red-800 font-medium text-2xl px-3">LibGen</span>
            </a>
        </header>
        <nav>
            <ul class="space-y-7">
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"></path>
                    </svg>
                    <span>Home</span>
                    <a href="/libgen" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center">
                    <h3 class="text-indigo-600 font-semibold">General</h3>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 24 24"
                        fill="currentColor">
                        <title>BookStack</title>
                        <path
                            d="M.3013 17.6146c-.1299-.3387-.5228-1.5119-.1337-2.4314l9.8273 5.6738a.329.329 0 0 0 .3299 0L24 12.9616v2.3542l-13.8401 7.9906-9.8586-5.6918zM.1911 8.9628c-.2882.8769.0149 2.0581.1236 2.4261l9.8452 5.6841L24 9.0823V6.7275L10.3248 14.623a.329.329 0 0 1-.3299 0L.1911 8.9628zm13.1698-1.9361c-.1819.1113-.4394.0015-.4852-.2064l-.2805-1.1336-2.1254-.1752a.33.33 0 0 1-.1378-.6145l5.5782-3.2207-1.7021-.9826L.6979 8.4935l9.462 5.463 13.5104-7.8004-4.401-2.5407-5.9084 3.4113zm-.1821-1.7286.2321.938 5.1984-3.0014-2.0395-1.1775-4.994 2.8834 1.3099.108a.3302.3302 0 0 1 .2931.2495zM24 9.845l-13.6752 7.8954a.329.329 0 0 1-.3299 0L.1678 12.0667c-.3891.919.003 2.0914.1332 2.4311l9.8589 5.692L24 12.1993V9.845z">
                        </path>
                    </svg>
                    <span>Books</span>
                    <a href="/admin" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M14.363 5.22a4.22 4.22 0 11-8.439 0 4.22 4.22 0 018.439 0zM2.67 14.469c1.385-1.09 4.141-2.853 7.474-2.853 3.332 0 6.089 1.764 7.474 2.853.618.486.81 1.308.567 2.056l-.333 1.02A2.11 2.11 0 0115.846 19H4.441a2.11 2.11 0 01-2.005-1.455l-.333-1.02c-.245-.748-.052-1.57.567-2.056z"
                            fill="currentColor"></path>
                    </svg>
                    <span>Readers</span>
                    <a href="/admin/readers" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z">
                        </path>
                    </svg>
                    <span>Rented Books</span>
                    <a href="/admin/rentedBooks" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="none"
                        viewBox="0 0 24 24">
                        <path
                            d="M4 11h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1zm10 0h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1zM4 21h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1zm13 0c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4z">
                        </path>
                    </svg>
                    <span>Book Categories</span>
                    <a href="/admin/categories" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" fill="none">
                        <path
                            d="M5.75391 5C3.68284 5 2.00391 6.67893 2.00391 8.75V9.5H26.0005V8.75C26.0005 6.67893 24.3216 5 22.2505 5H5.75391ZM2.00391 19.2501V11H26.0005V19.2501C26.0005 21.3211 24.3216 23.0001 22.2505 23.0001H5.75391C3.68284 23.0001 2.00391 21.3211 2.00391 19.2501ZM18.25 16.5C17.8358 16.5 17.5 16.8358 17.5 17.25C17.5 17.6642 17.8358 18 18.25 18H21.75C22.1642 18 22.5 17.6642 22.5 17.25C22.5 16.8358 22.1642 16.5 21.75 16.5H18.25Z"
                            fill="currentColor"></path>
                    </svg>
                    <span>Payments</span>
                    <a href="/admin/payment" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center">
                    <h3 class="text-indigo-600 font-semibold">Others</h3>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path id="path6"
                            d="M12,1,3,5v6c0,5.55,3.84,10.74,9,12,5.16-1.26,9-6.45,9-12V5Zm0,3.9a3,3,0,1,1-3,3A3,3,0,0,1,12,4.9Zm0,7.9c2,0,6,1.09,6,3.08a7.2,7.2,0,0,1-12,0C6,13.89,10,12.8,12,12.8Z">
                        </path>
                    </svg>
                    <span>Team</span>
                    <a href="/admin/team" class="absolute inset-0"></a>
                </li>
                <li class="flex items-center relative">
                    <svg class="w-7 h-7 mx-4" height="24" width="24" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                        </path>
                    </svg>
                    <span>Settings</span>
                    <a href="/admin/settings" class="absolute inset-0"></a>
                </li>
            </ul>
        </nav>
        <footer class="mt-6 font-semibold leading-9 rounded-md bg-indigo-600 text-white hover:bg-indigo-800">
            <form action="/formHandler" method="post">
                <button name="logout" id="logout" class="flex items-center w-full">
                    <svg class="w-7 h-7 mx-4" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                        enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
                        <g>
                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                        </g>
                        <g>
                            <g>
                                <polygon points="5,5 12,5 12,3 3,3 3,21 12,21 12,19 5,19"></polygon>
                                <polygon points="21,12 17,8 17,11 9,11 9,13 17,13 17,16"></polygon>
                            </g>
                        </g>
                    </svg>
                    <span>Log Out</span>
                </button>
            </form>
        </footer>
    </aside>

    <main class="flex-1 bg-gray-100 overflow-y-hidden flex flex-col relative">
        <header class="flex justify-between items-center text-sm py-2.5 px-6">
            <?php
            $query=new DatabaseQuery();
            $userData=$query->selectOne('users', $_COOKIE['user'], 'uuid');
            $svgBgColors = ['text-stone-600', 'text-red-500', 'text-red-700', 'text-orange-500', 'text-orange-700', 'text-amber-400', 'text-amber-700', 'text-yellow-400', 'text-yellow-600', 'text-lime-400', 'text-lime-600', 'text-green-500', 'text-green-700', 'text-teal-400', 'text-cyan-400', 'text-cyan-600', 'text-sky-500', 'text-sky-700', 'text-blue-600', 'text-blue-800', 'text-indigo-600', 'text-fuchsia-500', 'text-rose-500'];
            ?>
            <h3 class="text-lg font-medium">Welcome, <?= $userData['name'] ?></h3>
            <?php if (empty($userData['image'])): ?>
            <svg class="w-10 h-10 <?= $svgBgColors[array_rand($svgBgColors)] ?>" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                    clip-rule="evenodd"></path>
                <title id="userIconTitle">Admin</title>
            </svg>
            <?php else: ?>
                <div class="w-10 h-10 rounded-full">
                    <img src="/libgen/assets/uploads/images/<?= $userData['image'] ?>" alt="<?= $userData['name'] ?>" class="w-full h-full object-cover rounded-full">
                </div>
            <?php endif; ?>
        </header>
        <section class="flex-1 flex flex-col space-y-5 overflow-y-hidden">
            <?php
            $dashboardRouter = new Router();
            $dashboardRouter->define([
                '' => './pages/dashboard/books.php',
                'readers' => './pages/dashboard/users.php',
                'rentedBooks' => './pages/dashboard/rentedBooks.php',
                'categories' => './pages/dashboard/categories.php',
                'payment' => './pages/dashboard/payment.php',
                'team' => './pages/dashboard/admin.php',
                'settings' => './pages/dashboard/settings.php',
                'addBook' => './pages/forms/addBook.php',
                'addCategory' => './pages/forms/addCategory.php',
                'addMember' => './pages/forms/registration.php',
                'settings/update' => './pages/forms/registration.php'
            ]);
            $uri = trim(str_ireplace('admin', '', $uri), '/');
            require $dashboardRouter->direct($uri);
            ?>
        </section>
    </main>
</div>