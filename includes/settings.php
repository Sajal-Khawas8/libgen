<?php
$query = new DatabaseQuery();
$user = $query->selectOne('users', $_SESSION['user']['id'], 'uuid');
?>
<article
    class="mx-auto <?= ($_SESSION['user']['role'] != 1) ? 'bg-white' : 'bg-slate-200' ?> rounded-md px-4 py-6 w-4/5 max-w-md space-y-4 relative">
    <?php if (empty($user['image'])): ?>
        <div class="w-60 h-60 rounded-md mx-auto bg-gray-600 flex items-center justify-center">
            <svg class="w-60 h-60 text-white" xmlns="http://www.w3.org/2000/svg" docname="user.svg" version="0.48.4 r9939"
                x="0px" y="0px" viewBox="0 0 1200 1200" enable-background="new 0 0 1200 1200" xml:space="preserve"
                fill="currentColor">
                <path id="path25031" connector-curvature="0"
                    d="M939.574,858.383c-157.341-57.318-207.64-105.702-207.64-209.298 c0-62.17,51.555-102.462,69.128-155.744c17.575-53.283,27.741-116.367,36.191-162.256c8.451-45.889,11.809-63.638,16.404-112.532 C859.276,157.532,818.426,0,600,0C381.639,0,340.659,157.532,346.404,218.553c4.596,48.894,7.972,66.645,16.404,112.532 c8.433,45.888,18.5,108.969,36.063,162.256c17.562,53.286,69.19,93.574,69.19,155.744c0,103.596-50.298,151.979-207.638,209.298 C102.511,915.83,0,972.479,0,1012.5c0,39.957,0,187.5,0,187.5h1200c0,0,0-147.543,0-187.5S1097.426,915.894,939.574,858.383 L939.574,858.383z">
                </path>
            </svg>
        </div>
    <?php else: ?>
        <div class="w-60 h-60 rounded-md mx-auto border border-gray-400">
            <img src="<?= $user['image'] ?>" alt="<?= $user['name']; ?>" class="w-full h-full object-cover rounded-md">
        </div>
    <?php endif; ?>
    <div class="space-y-3">
        <h2 class="font-semibold text-2xl text-center"><?= $user['name']; ?></h2>
        <dl class="px-6 overflow-hidden">
            <div class="grid grid-cols-3 gap-2">
                <dt class="font-medium">Email:</dt>
                <dd class="col-span-2"><a href="mailto:<?= $user['email']; ?>"><?= $user['email']; ?></a></dd>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <dt class="font-medium">Address:</dt>
                <dd class="col-span-2 line-clamp-3"><?= $user['address']; ?></dd>
            </div>
        </dl>
    </div>
    <div class="space-y-6">
        <?php $config = require "./core/config.php"; ?>
        <div class="flex gap-10">
            <form action="/formHandler" method="post" class="flex-1">
                <input type="hidden" name="id"
                    value="<?= openssl_encrypt($user['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>">
                <button name="<?= $_SESSION['user']['role'] != 1 ? 'updateAdmin' : 'updateUser' ?>"
                    class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Update
                    Info</button>
            </form>
            <form action="/formHandler" method="post" class="flex-1">
                <button name="logout"
                    class="px-3 py-1 bg-indigo-600 text-white text-lg rounded-md hover:bg-indigo-800 min-w-full">Log
                    Out</button>
            </form>
        </div>
        <button type="button" onclick="document.getElementById('deleteModal').style.display='flex'"
            class="px-3 py-1 bg-red-600 text-white text-lg rounded-md hover:bg-red-700 min-w-full">Delete
            Account</button>
    </div>
    <div id="deleteModal" class="absolute inset-0 bg-gray-500/60 !mt-0 hidden flex-col justify-center items-center space-y-20">
        <div class="text-center">
            <p class="font-semibold text-3xl">Are you sure?</p>
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" class="absolute top-6 right-6">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill="currentColor"
                        d="M3.21878052,2.15447998 L9.99678993,8.92744993 L16.7026814,2.22182541 C17.1598053,1.8145752 17.6339389,2.05757141 17.8218994,2.2625885 C18.0098599,2.46760559 18.1171875,2.95117187 17.7781746,3.29731856 L11.0707899,10.0014499 L17.7781746,16.7026814 C18.0764771,16.9529419 18.0764771,17.4433594 17.8370056,17.7165527 C17.5975342,17.9897461 17.1575623,18.148407 16.7415466,17.8244324 L9.99678993,11.0754499 L3.24360657,17.8271179 C2.948349,18.0919647 2.46049253,18.038208 2.21878052,17.7746429 C1.9770685,17.5110779 1.8853302,17.0549164 2.19441469,16.7330362 L8.92278993,10.0014499 L2.22182541,3.29731856 C1.97729492,3.02648926 1.89189987,2.53264694 2.22182541,2.22182541 C2.55175094,1.91100387 3.04367065,1.95437622 3.21878052,2.15447998 Z">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex gap-20 items-center">
            <form action="/formHandler" method="post"
                class="flex-1 font-medium text-lg rounded-md">
                <input type="hidden" name="id"
                    value="<?= openssl_encrypt($user['uuid'], $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']) ?>">
                <button name="deleteAccount"
                    class="px-4 py-2 bg-white text-red-600 text-lg rounded-md hover:bg-red-600 hover:text-white min-w-full">Delete
                    Account</button>
            </form>
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'"
                class="px-4 py-2 bg-white text-black font-medium text-lg rounded-md">Cancel</button>
        </div>
    </div>
</article>