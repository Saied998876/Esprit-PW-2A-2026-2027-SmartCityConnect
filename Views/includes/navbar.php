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
            <a class="<?= $currentPage === 'campaigns' ? 'is-active' : '' ?>" href="index.php#campaigns">Campaigns</a>
            <?php if ($currentPage !== 'admin'): ?>
                <a href="index.php#approach">Our approach</a>
            <?php endif; ?>
            <a class="nav-admin <?= $currentPage === 'admin' ? 'is-active' : '' ?>" href="admin.php">
                Back office <span aria-hidden="true">↗</span>
            </a>
        </nav>
    </div>
</header>
