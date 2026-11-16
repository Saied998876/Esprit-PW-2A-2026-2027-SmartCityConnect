<section class="page-heading compact-heading"><div><p class="eyebrow">Open editing</p><h1>Edit post</h1><p>Any visitor can edit posts.</p></div><a class="back-link" href="<?= e(page_url('forum')) ?>">← Back to forum</a></section>
<section class="edit-panel">
    <form class="form-stack" action="<?= e(page_url('update-post')) ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
        <div class="field-group"><label for="edit-title">Title <span class="required-mark">*</span></label><input id="edit-title" name="title" maxlength="140" value="<?= e($post['title']) ?>" required></div>
        <div class="field-group"><label for="edit-category">Category</label><select id="edit-category" name="category"><?php foreach ($categories as $category): ?><option value="<?= e($category) ?>" <?= $post['category'] === $category ? 'selected' : '' ?>><?= e($category) ?></option><?php endforeach; ?></select></div>
        <div class="field-group"><label for="edit-body">Your message <span class="required-mark">*</span></label><textarea id="edit-body" name="body" maxlength="5000" rows="10" required><?= e($post['body']) ?></textarea><span class="field-hint"><span data-count-for="edit-body"><?= mb_strlen($post['body']) ?></span> / 5,000</span></div>
        <div class="form-actions"><a class="button button-quiet" href="<?= e(page_url('forum')) ?>">Cancel</a><button class="button button-primary" type="submit">Save changes <span aria-hidden="true">→</span></button></div>
    </form>
</section>
