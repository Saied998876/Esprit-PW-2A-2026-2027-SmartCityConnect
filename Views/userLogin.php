<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-auth-section">
    <div class="auth-card">
        <a class="back-link" href="index.php">← Back to campaigns</a>
        <p class="eyebrow">Smart City Connect · Account</p>
        <h1>Welcome back.</h1>
        <p>Sign in to your account. Administrators can also manage the back office.</p>
        <?php if ($notice = getMessage('notice')): ?>
            <div class="notice notice-success" role="status"><?= e($notice) ?></div>
        <?php endif; ?>
        <?php if ($error = getMessage('error')): ?>
            <div class="notice notice-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($loginError)): ?>
            <div class="notice notice-error" role="alert"><?= e($loginError) ?></div>
        <?php endif; ?>
        <form method="post" action="index.php?page=login" class="form-stack">
            <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="next" value="<?= e($next) ?>">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" maxlength="254"
                value="<?= e($oldInput['email'] ?? '') ?>" autocomplete="email" required autofocus>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" maxlength="72"
                autocomplete="current-password" required>
            <button class="button button-primary button-wide" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-alternative">New to Smart City Connect? <a href="index.php?page=register">Create an account</a>.</p>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
