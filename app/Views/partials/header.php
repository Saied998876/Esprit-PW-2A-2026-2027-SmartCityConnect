<header class="topbar">
    <a class="brand" href="<?= e(page_url('forum')) ?>" aria-label="Smart City Connect, forum home">
        <span class="brand-mark" aria-hidden="true">S</span><span>Smart City Connect</span>
    </a>
    <nav class="top-nav" aria-label="Main navigation">
        <a class="<?= $currentPage === 'forum' ? 'active' : '' ?>" href="<?= e(page_url('forum')) ?>">Discussions</a>
        <a class="<?= $isAdminPage ? 'active' : '' ?>" href="<?= e(page_url('admin')) ?>">Moderation</a>
    </nav>
    <span class="access-label">Open access</span>
</header>
