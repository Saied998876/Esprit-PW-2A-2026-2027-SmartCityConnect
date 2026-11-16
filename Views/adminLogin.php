<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-auth-section">
    <div class="auth-card">
        <a class="back-link" href="index.php">← Back to the site</a>
        <p class="eyebrow">Smart City Connect · Admin</p>
        <h1>Welcome back.</h1>
        <p>Sign in to manage campaigns and review community pledges.</p>
        <?php if (!empty($loginError)): ?>
            <div class="notice notice-error" role="alert"><?= e($loginError) ?></div>
        <?php endif; ?>
        <form method="post" action="admin.php" class="form-stack">
            <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="action" value="login">
            <label for="password">Admin password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required autofocus>
            <button class="button button-primary button-wide" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>