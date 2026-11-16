<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-auth-section">
    <div class="auth-card">
        <a class="back-link" href="index.php">← Back to campaigns</a>
        <p class="eyebrow">Smart City Connect · Join the community</p>
        <h1>Create an account.</h1>
        <p>Community accounts can support campaigns and manage their profile.</p>
        <?php if (!empty($errors)): ?>
            <div class="notice notice-error" role="alert">
                <strong>Please check the following:</strong>
                <ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>
        <form method="post" action="index.php?page=register" class="form-stack">
            <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
            <label for="name">Your name</label>
            <input id="name" name="name" type="text" maxlength="100"
                value="<?= e($oldInput['name'] ?? '') ?>" autocomplete="name" required autofocus>
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" maxlength="254"
                value="<?= e($oldInput['email'] ?? '') ?>" autocomplete="email" required>
            <label for="password">Password <span class="label-optional">(12 to 72 characters)</span></label>
            <input id="password" name="password" type="password" minlength="12" maxlength="72"
                autocomplete="new-password" required>
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                minlength="12" maxlength="72" autocomplete="new-password" required>
            <button class="button button-primary button-wide" type="submit">Create account <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-alternative">Already have an account? <a href="index.php?page=login">Sign in</a>.</p>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
