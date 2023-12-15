<section
    class="min-h-full flex flex-col items-center justify-center space-y-11 <?= str_contains($_SERVER['REQUEST_URI'], 'admin') ? '' : 'h-[calc(100vh-4rem-3.5rem)]'; ?>">
    <h1 class="text-8xl font-semibold text-gray-600">404</h1>
    <p class="text-5xl font-semibold text-gray-600">Page Not Found</p>
    <?php if (!str_contains($_SERVER['REQUEST_URI'], 'admin')): ?>
        <p class="text-4xl font-semibold text-gray-600"><a href="/libgen">Looks like you are lost. Go to home page</a></p>
    <?php endif; ?>
</section>