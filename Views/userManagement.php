<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-section">
    <div class="page-shell">
        <div class="admin-heading">
            <div>
                <p class="eyebrow">Smart City Connect · Administration</p>
                <h1>User management.</h1>
                <p>Create accounts, update details, and assign community or administrator roles.</p>
            </div>
            <a class="button button-quiet" href="admin.php">Campaign dashboard <span aria-hidden="true">↗</span></a>
        </div>
        <?php if ($notice = getMessage('notice')): ?>
            <div class="notice notice-success" role="status"><?= e($notice) ?></div>
        <?php endif; ?>
        <?php if ($error = getMessage('error')): ?>
            <div class="notice notice-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="admin-card user-create-card">
            <div class="admin-card-heading">
                <div><p class="eyebrow">01 / New account</p><h2>Create a user</h2></div>
            </div>
            <form method="post" action="users.php" class="form-stack user-create-form">
                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="action" value="create_user">
                <div class="form-row">
                    <div><label for="new-user-name">Name</label>
                        <input id="new-user-name" name="name" type="text" maxlength="100" required></div>
                    <div><label for="new-user-email">Email</label>
                        <input id="new-user-email" name="email" type="email" maxlength="254" required></div>
                </div>
                <div class="form-row">
                    <div><label for="new-user-password">Temporary password (12 to 72 characters)</label>
                        <input id="new-user-password" name="password" type="password" minlength="12" maxlength="72"
                            autocomplete="new-password" required></div>
                    <div><label for="new-user-role">Role</label>
                        <select id="new-user-role" name="role" required>
                            <option value="citizen">Citizen</option>
                            <option value="admin">Administrator</option>
                        </select></div>
                </div>
                <button class="button button-primary" type="submit">Create account <span aria-hidden="true">+</span></button>
            </form>
        </section>

        <section class="admin-card user-list-card">
            <div class="admin-card-heading">
                <div><p class="eyebrow">02 / Existing accounts</p><h2><?= count($users) ?> users</h2></div>
            </div>
            <?php if ($users === []): ?>
                <p class="muted-copy">No accounts have been created yet.</p>
            <?php else: ?>
                <div class="managed-list">
                    <?php foreach ($users as $user): ?>
                        <details class="managed-campaign user-record">
                            <summary>
                                <span>
                                    <span class="managed-campaign-title"><?= e($user['name']) ?></span>
                                    <span class="user-summary-email"><?= e($user['email']) ?></span>
                                </span>
                                <span class="role-pill"><?= e(ucfirst($user['role'])) ?></span>
                            </summary>
                            <form method="post" action="users.php" class="form-stack campaign-editor">
                                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="action" value="update_user">
                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                                <label for="user-name-<?= (int) $user['id'] ?>">Name</label>
                                <input id="user-name-<?= (int) $user['id'] ?>" name="name" type="text" maxlength="100"
                                    value="<?= e($user['name']) ?>" required>
                                <label for="user-email-<?= (int) $user['id'] ?>">Email</label>
                                <input id="user-email-<?= (int) $user['id'] ?>" name="email" type="email" maxlength="254"
                                    value="<?= e($user['email']) ?>" required>
                                <label for="user-password-<?= (int) $user['id'] ?>">Set a new password <span class="label-optional">(optional)</span></label>
                                <input id="user-password-<?= (int) $user['id'] ?>" name="password" type="password"
                                    minlength="12" maxlength="72" autocomplete="new-password">
                                <label for="user-role-<?= (int) $user['id'] ?>">Role</label>
                                <select id="user-role-<?= (int) $user['id'] ?>" name="role" required>
                                    <option value="citizen" <?= $user['role'] === 'citizen' ? 'selected' : '' ?>>Citizen</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                </select>
                                <div class="managed-actions"><button class="button button-primary" type="submit">Save changes</button></div>
                            </form>
                            <form method="post" action="users.php" class="managed-actions"
                                data-confirm="Delete this user account?">
                                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                                <button class="button button-danger" type="submit">Delete account</button>
                            </form>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
