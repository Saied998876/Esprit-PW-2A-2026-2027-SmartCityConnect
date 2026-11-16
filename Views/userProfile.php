<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-section">
    <div class="page-shell">
        <div class="admin-heading">
            <div>
                <p class="eyebrow">Smart City Connect · My account</p>
                <h1>Profile settings.</h1>
                <p>Update your account details and password.</p>
            </div>
            <?php if ($currentUser['role'] === 'admin'): ?>
                <a class="button button-quiet" href="admin.php">Open back office <span aria-hidden="true">↗</span></a>
            <?php endif; ?>
        </div>
        <?php if ($notice = getMessage('notice')): ?>
            <div class="notice notice-success" role="status"><?= e($notice) ?></div>
        <?php endif; ?>
        <?php if ($error = getMessage('error')): ?>
            <div class="notice notice-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="notice notice-error" role="alert">
                <strong>Please check the following:</strong>
                <ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>
        <section class="admin-card profile-card">
            <p class="eyebrow">Account role · <?= e(ucfirst($currentUser['role'])) ?></p>
            <form method="post" action="index.php?page=profile" class="form-stack">
                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                <label for="name">Your name</label>
                <input id="name" name="name" type="text" maxlength="100"
                    value="<?= e($oldInput['name'] ?? $currentUser['name']) ?>" autocomplete="name" required>
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" maxlength="254"
                    value="<?= e($oldInput['email'] ?? $currentUser['email']) ?>" autocomplete="email" required>
                <h2>Change password <span class="label-optional">(optional)</span></h2>
                <label for="current_password">Current password</label>
                <input id="current_password" name="current_password" type="password" maxlength="72"
                    autocomplete="current-password">
                <label for="new_password">New password</label>
                <input id="new_password" name="new_password" type="password" minlength="12" maxlength="72"
                    autocomplete="new-password">
                <label for="password_confirmation">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    minlength="12" maxlength="72" autocomplete="new-password">
                <button class="button button-primary" type="submit">Save profile</button>
            </form>
        </section>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
