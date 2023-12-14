<?php
if (isset($_COOKIE['data'])) {
    $data = unserialize($_COOKIE['data']);
}
?>
<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">Admins</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <input type="text" name="adminName" id="adminName" placeholder="Search admins by Name or Email"
                value="<?= $data['adminName'] ?? ''; ?>" class="px-4 py-2 text-lg outline-none w-full rounded-md">
            <button name="searchAdmin"
                class="absolute inset-y-0 right-0 px-3 rounded-r-md bg-slate-200 hover:bg-indigo-600 hover:text-white"
                aria-label="Search">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                </svg>
            </button>
        </form>
        <span class="text-red-600 text-sm font-medium"><?= $_COOKIE['err'] ?? ''; ?></span>
        <a href="/admin/addMember"
            class="flex items-center gap-3 px-6 py-1.5 rounded-md bg-indigo-500 hover:bg-indigo-700 text-white ml-auto">
            <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                </path>
            </svg>
            <span class="text-lg">Add New Admin</span>
        </a>
    </div>
</header>
<?php if ($_SERVER['QUERY_STRING']): ?>
    <?php
    $config = require "./core/config.php";
    $adminIds = openssl_decrypt($_SERVER['QUERY_STRING'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
    if ($adminIds):
        $adminIds = explode("&", $adminIds);
        unset($adminIds[0]);
        ?>
        <div class="flex-1 px-6  overflow-y-auto">
            <ul class="grid grid-cols-2 gap-24">
                <?php
                foreach ($adminIds as $adminId):
                    $admin = $query->selectOne('users', $adminId);
                    ?>

                    <li class="px-5 py-3 bg-white rounded-md h-fit">
                        <article class="flex justify-between">
                            <?php if (empty($admin['image'])): ?>
                                <div class="w-40 h-40 rounded-md mx-auto bg-gray-600 flex items-center justify-center">
                                    <svg class="w-60 h-60 text-slate-100" xmlns="http://www.w3.org/2000/svg" docname="user.svg"
                                        version="0.48.4 r9939" x="0px" y="0px" viewBox="0 0 1200 1200"
                                        enable-background="new 0 0 1200 1200" xml:space="preserve" fill="currentColor">
                                        <path id="path25031" connector-curvature="0"
                                            d="M939.574,858.383c-157.341-57.318-207.64-105.702-207.64-209.298 c0-62.17,51.555-102.462,69.128-155.744c17.575-53.283,27.741-116.367,36.191-162.256c8.451-45.889,11.809-63.638,16.404-112.532 C859.276,157.532,818.426,0,600,0C381.639,0,340.659,157.532,346.404,218.553c4.596,48.894,7.972,66.645,16.404,112.532 c8.433,45.888,18.5,108.969,36.063,162.256c17.562,53.286,69.19,93.574,69.19,155.744c0,103.596-50.298,151.979-207.638,209.298 C102.511,915.83,0,972.479,0,1012.5c0,39.957,0,187.5,0,187.5h1200c0,0,0-147.543,0-187.5S1097.426,915.894,939.574,858.383 L939.574,858.383z">
                                        </path>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div class="h-40 w-40 rounded-md">
                                    <img src="/libgen/assets/uploads/images/users/<?= $admin['image'] ?>" alt="<?= $admin['name'] ?>"
                                        class="h-full w-full object-cover rounded-md">
                                </div>
                            <?php endif; ?>
                            <div class="space-y-3">
                                <h2 class="font-semibold text-2xl"><?= $admin['name']; ?></h2>
                                <p class="font-medium text-lg"><a href="mailto:<?= $admin['email']; ?>"><?= $admin['email']; ?></a>
                                </p>
                                <dl class="max-w-[15rem]">
                                    <dt class="font-medium">Address:</dt>
                                    <dd>
                                        <address class="not-italic"><?= $admin['address']; ?></address>
                                    </dd>
                                </dl>
                            </div>
                            <?php if ($admin['uuid'] !== $_COOKIE['user']): ?>
                                <div class="space-y-4">
                                    <?php
                                    $config = require "./core/config.php";
                                    $uuid = openssl_encrypt($admin['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])
                                        ?>
                                    <form action="/formHandler" method="post">
                                        <input type="hidden" name="id" value="<?= $uuid; ?>">
                                        <button name="<?= $admin['isSuper'] ? 'removeSuperAdmin' : 'makeSuperAdmin' ?>"
                                            <?= $userData['isSuper'] ? '' : 'disabled' ?>
                                            class="p-1 <?= $admin['isSuper'] ? 'bg-orange-500' : 'bg-indigo-500' ?> text-white rounded-md disabled:bg-indigo-300">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <g>
                                                    <rect fill="none" height="24" width="24"></rect>
                                                    <rect fill="none" height="24" width="24"></rect>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M17,11c0.34,0,0.67,0.04,1,0.09V7.58c0-0.8-0.47-1.52-1.2-1.83l-5.5-2.4c-0.51-0.22-1.09-0.22-1.6,0l-5.5,2.4 C3.47,6.07,3,6.79,3,7.58v3.6c0,4.54,3.2,8.79,7.5,9.82c0.55-0.13,1.08-0.32,1.6-0.55C11.41,19.47,11,18.28,11,17 C11,13.69,13.69,11,17,11z">
                                                        </path>
                                                        <path
                                                            d="M17,13c-2.21,0-4,1.79-4,4c0,2.21,1.79,4,4,4s4-1.79,4-4C21,14.79,19.21,13,17,13z M17,14.38c0.62,0,1.12,0.51,1.12,1.12 s-0.51,1.12-1.12,1.12s-1.12-0.51-1.12-1.12S16.38,14.38,17,14.38z M17,19.75c-0.93,0-1.74-0.46-2.24-1.17 c0.05-0.72,1.51-1.08,2.24-1.08s2.19,0.36,2.24,1.08C18.74,19.29,17.93,19.75,17,19.75z">
                                                        </path>
                                                    </g>
                                                </g>
                                                <title><?= $admin['isSuper'] ? 'Remove as Super Admin' : 'Make Super Admin' ?></title>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="/formHandler" method="post">
                                        <input type="hidden" name="id" value="<?= $uuid; ?>">
                                        <button name="removeAdmin" <?= $userData['isSuper'] ? '' : 'disabled' ?>
                                            class="p-1 bg-red-500 text-white rounded-md disabled:bg-red-300">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                                </path>
                                                <title>Remove Admin</title>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </article>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="flex-1 flex items-center justify-center">
            <h3 class="font-bold text-5xl text-gray-500">No Data Found...</h3>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="flex-1 px-6  overflow-y-auto">
        <ul class="grid grid-cols-2 gap-16">
            <?php
            $admins = $query->selectAllSpecific('users', 1, 'role');
            foreach ($admins as $admin):
                ?>
                <?php if ($admin['active']): ?>
                    <li class="px-5 py-3 bg-white rounded-md h-fit">
                        <article class="flex gap-10">
                            <?php if (empty($admin['image'])): ?>
                                <div class="w-40 h-40 rounded-md mx-auto bg-gray-600 flex items-center justify-center">
                                    <svg class="w-60 h-60 text-slate-100" xmlns="http://www.w3.org/2000/svg" docname="user.svg"
                                        version="0.48.4 r9939" x="0px" y="0px" viewBox="0 0 1200 1200"
                                        enable-background="new 0 0 1200 1200" xml:space="preserve" fill="currentColor">
                                        <path id="path25031" connector-curvature="0"
                                            d="M939.574,858.383c-157.341-57.318-207.64-105.702-207.64-209.298 c0-62.17,51.555-102.462,69.128-155.744c17.575-53.283,27.741-116.367,36.191-162.256c8.451-45.889,11.809-63.638,16.404-112.532 C859.276,157.532,818.426,0,600,0C381.639,0,340.659,157.532,346.404,218.553c4.596,48.894,7.972,66.645,16.404,112.532 c8.433,45.888,18.5,108.969,36.063,162.256c17.562,53.286,69.19,93.574,69.19,155.744c0,103.596-50.298,151.979-207.638,209.298 C102.511,915.83,0,972.479,0,1012.5c0,39.957,0,187.5,0,187.5h1200c0,0,0-147.543,0-187.5S1097.426,915.894,939.574,858.383 L939.574,858.383z">
                                        </path>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div class="h-40 w-40 rounded-md">
                                    <img src="/libgen/assets/uploads/images/users/<?= $admin['image'] ?>" alt="<?= $admin['name'] ?>"
                                        class="h-full w-full object-cover rounded-md">
                                </div>
                            <?php endif; ?>
                            <div class="space-y-3">
                                <h2 class="font-semibold text-2xl"><?= $admin['name']; ?></h2>
                                <p class="font-medium text-lg"><a href="mailto:<?= $admin['email']; ?>"><?= $admin['email']; ?></a>
                                </p>
                                <dl class="max-w-[15rem]">
                                    <dt class="font-medium">Address:</dt>
                                    <dd>
                                        <address class="not-italic"><?= $admin['address']; ?></address>
                                    </dd>
                                </dl>
                            </div>
                            <?php if ($admin['uuid'] !== $_COOKIE['user']): ?>
                                <div class="space-y-4">
                                    <?php
                                    $config = require "./core/config.php";
                                    $uuid = openssl_encrypt($admin['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv'])
                                        ?>
                                    <form action="/formHandler" method="post">
                                        <input type="hidden" name="id" value="<?= $uuid; ?>">
                                        <button name="<?= $admin['isSuper'] ? 'removeSuperAdmin' : 'makeSuperAdmin' ?>"
                                            <?= $userData['isSuper'] ? '' : 'disabled' ?>
                                            class="p-1 <?= $admin['isSuper'] ? 'bg-orange-500' : 'bg-indigo-500' ?> text-white rounded-md disabled:bg-indigo-300">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <g>
                                                    <rect fill="none" height="24" width="24"></rect>
                                                    <rect fill="none" height="24" width="24"></rect>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M17,11c0.34,0,0.67,0.04,1,0.09V7.58c0-0.8-0.47-1.52-1.2-1.83l-5.5-2.4c-0.51-0.22-1.09-0.22-1.6,0l-5.5,2.4 C3.47,6.07,3,6.79,3,7.58v3.6c0,4.54,3.2,8.79,7.5,9.82c0.55-0.13,1.08-0.32,1.6-0.55C11.41,19.47,11,18.28,11,17 C11,13.69,13.69,11,17,11z">
                                                        </path>
                                                        <path
                                                            d="M17,13c-2.21,0-4,1.79-4,4c0,2.21,1.79,4,4,4s4-1.79,4-4C21,14.79,19.21,13,17,13z M17,14.38c0.62,0,1.12,0.51,1.12,1.12 s-0.51,1.12-1.12,1.12s-1.12-0.51-1.12-1.12S16.38,14.38,17,14.38z M17,19.75c-0.93,0-1.74-0.46-2.24-1.17 c0.05-0.72,1.51-1.08,2.24-1.08s2.19,0.36,2.24,1.08C18.74,19.29,17.93,19.75,17,19.75z">
                                                        </path>
                                                    </g>
                                                </g>
                                                <title><?= $admin['isSuper'] ? 'Remove as Super Admin' : 'Make Super Admin' ?></title>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="/formHandler" method="post">
                                        <input type="hidden" name="id" value="<?= $uuid; ?>">
                                        <button name="removeAdmin" <?= $userData['isSuper'] ? '' : 'disabled' ?>
                                            class="p-1 bg-red-500 text-white rounded-md disabled:bg-red-300">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
                                                </path>
                                                <title>Remove Admin</title>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </article>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>