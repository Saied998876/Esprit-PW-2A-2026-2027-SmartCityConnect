<?php require __DIR__ . '/header.php'; ?>
<section class="section message-section">
    <div class="empty-state">
        <span class="empty-icon" aria-hidden="true">!</span>
        <h1><?= e($pageTitle) ?></h1>
        <p><?= e($message ?? 'The page you are looking for could not be found.') ?></p>
        <a class="button button-primary" href="index.php">Back to campaigns <span aria-hidden="true">→</span></a>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
