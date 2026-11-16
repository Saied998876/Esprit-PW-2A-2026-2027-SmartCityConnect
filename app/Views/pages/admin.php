<section class="page-heading admin-page-heading"><div><p class="eyebrow">Open moderation · No account required</p><h1>Keep the forum helpful.</h1><p>Review posts and comments, then remove inappropriate content. Anyone can moderate.</p></div><div class="admin-metrics"><div><strong><?= (int) $postCount ?></strong><span>posts</span></div><div><strong><?= (int) $commentCount ?></strong><span>comments</span></div></div></section>

<section class="moderation-section" aria-labelledby="posts-heading">
    <div class="section-heading"><div><p class="eyebrow">Published content</p><h2 id="posts-heading">All posts</h2></div><label class="search-box"><span aria-hidden="true">⌕</span><span class="sr-only">Search posts to moderate</span><input id="admin-post-search" type="search" placeholder="Filter posts"></label></div>
    <?php if ($posts === []): ?><div class="empty-state compact-empty"><h3>No posts to moderate.</h3><p>New discussions will appear here.</p></div><?php endif; ?>
    <div class="moderation-list" id="admin-post-list">
        <?php foreach ($posts as $post): ?>
            <article class="moderation-card" data-searchable="<?= e(mb_strtolower($post['title'] . ' ' . $post['body'] . ' ' . $post['author']['name'] . ' ' . $post['category'])) ?>">
                <div class="moderation-card-main"><div class="post-meta"><span class="category-tag"><?= e($post['category']) ?></span><time datetime="<?= e($post['created_at']) ?>"><?= e(format_date($post['created_at'])) ?></time></div><h3><?= e($post['title']) ?></h3><p class="moderation-excerpt"><?= nl2br(e($post['body'])) ?></p><div class="moderation-byline">By <strong><?= e($post['author']['name']) ?></strong> · <?= count($post['comments']) ?> comment<?= count($post['comments']) === 1 ? '' : 's' ?></div></div>
                <form action="<?= e(page_url('admin-delete-post')) ?>" method="post" data-confirm="Delete this post and all its comments?"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>"><button class="button button-danger" type="submit">Delete post</button></form>
            </article>
        <?php endforeach; ?>
    </div>
    <p class="no-search-results" id="admin-no-results" hidden>No posts match your search.</p>
</section>

<section class="moderation-section comments-moderation" aria-labelledby="comments-heading">
    <div class="section-heading"><div><p class="eyebrow">Community replies</p><h2 id="comments-heading">Comment management</h2></div></div>
    <?php $hasComments = false; foreach ($posts as $post) { if ($post['comments'] !== []) { $hasComments = true; break; } } ?>
    <?php if (!$hasComments): ?><div class="empty-state compact-empty"><h3>No comments yet.</h3><p>Published replies will appear here.</p></div><?php else: ?>
        <div class="comment-moderation-list">
            <?php foreach ($posts as $post): foreach ($post['comments'] as $comment): ?>
                <article class="comment-moderation-row"><div class="comment-moderation-copy"><div class="comment-meta"><strong><?= e($comment['author']['name']) ?></strong><time datetime="<?= e($comment['created_at']) ?>"><?= e(format_date($comment['created_at'])) ?></time></div><p><?= nl2br(e($comment['body'])) ?></p><a href="<?= e(page_url('forum') . '#post-' . $post['id']) ?>">On post: <?= e($post['title']) ?> ↗</a></div><form action="<?= e(page_url('admin-delete-comment')) ?>" method="post" data-confirm="Delete this comment?"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>"><button class="button button-danger button-outline-danger" type="submit">Remove</button></form></article>
            <?php endforeach; endforeach; ?>
        </div>
    <?php endif; ?>
</section>
