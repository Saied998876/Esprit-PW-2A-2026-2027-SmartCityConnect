<?php if ($isAdminPage): ?>
<aside class="admin-sidebar">
    <p class="sidebar-label">PUBLIC MODERATION</p>
    <a class="sidebar-link <?= $currentPage === 'admin' ? 'active' : '' ?>" href="<?= e(page_url('admin')) ?>"><span aria-hidden="true">▤</span>Moderation queue</a>
    <a class="sidebar-link" href="<?= e(page_url('forum')) ?>"><span aria-hidden="true">◉</span>Back to forum</a>
    <div class="sidebar-help"><span class="help-mark">i</span><p>Deleting a post also removes all of its comments.</p></div>
</aside>
<?php endif; ?>
