<?php
/** @var string $title */
/** @var string $content */
/** @var string $currentPage */
/** @var bool $isAdminPage */
/** @var array|null $flash */
/** @var array|null $user */
require __DIR__ . '/partials/head.php';
?>
<div class="app-shell<?= $isAdminPage ? ' admin-shell' : '' ?>">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>
    <div class="main-column">
        <?php require __DIR__ . '/partials/header.php'; ?>
        <main class="main-content">
            <?php if ($flash !== null): ?>
                <div class="notice notice-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
            <?php endif; ?>
            <?= $content ?>
        </main>
        <?php require __DIR__ . '/partials/footer.php'; ?>
    </div>
</div>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
