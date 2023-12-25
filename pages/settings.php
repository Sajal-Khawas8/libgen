<?php
if (!isset($_SESSION['user']) || $_SESSION['user'][1] != 1) {
    header("Location: /login");
    exit;
}
?>
<main class="container border-x space-y-8 min-h-[calc(100vh-4rem-3.5rem)] py-12">
    <section class="space-y-8">
        <h1 class="text-center text-4xl font-semibold">Account Settings</h1>
        <?php require "./includes/settings.php"; ?>
    </section>
</main>