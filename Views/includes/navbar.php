<header class="site-header">
    <div class="page-shell nav-shell">
        <a class="brand" href="index.php" aria-label="Smart City Connect home">
            <span class="brand-name">smart city <strong>connect</strong></span>
            <span class="brand-tagline">Smart living · Better life</span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation">
            <span></span><span></span><span class="sr-only">Toggle navigation</span>
        </button>
        <nav class="main-navigation" id="main-navigation" aria-label="Main navigation">
            <a class="<?= ($currentPage ?? '') === 'campaigns' ? 'is-active' : '' ?>" href="index.php#campaigns">Campaigns</a>
            <?php if (($currentPage ?? '') !== 'admin'): ?>
                <a href="index.php#approach">Our approach</a>
            <?php endif; ?>
            <?php if (!empty($currentUser)): ?>
                <a class="<?= ($currentPage ?? '') === 'profile' ? 'is-active' : '' ?>"
                    href="index.php?page=profile">My account</a>
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <a class="nav-admin <?= ($currentPage ?? '') === 'admin' ? 'is-active' : '' ?>" href="admin.php">Back office</a>
                    <a class="<?= ($currentPage ?? '') === 'users' ? 'is-active' : '' ?>" href="users.php">Users</a>
                <?php endif; ?>
                <form method="post" action="index.php?page=logout" class="nav-logout">
                    <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                    <button type="submit">Sign out</button>
                </form>
            <?php else: ?>
                <a class="<?= ($currentPage ?? '') === 'login' ? 'is-active' : '' ?>" href="index.php?page=login">Sign in</a>
                <a class="nav-admin" href="index.php?page=register">Create account</a>
                <a class="nav-admin" href="index.php?page=login&amp;next=admin">Back office <span aria-hidden="true">↗</span></a>
            <?php endif; ?>
        </nav>
    </div>
</header>
