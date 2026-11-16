<section class="forum-intro">
    <div>
        <p class="eyebrow">The Smart City Connect community</p>
        <h1>Ideas flow<br><em>when we talk.</em></h1>
        <p>Ask questions, share your projects, and connect with the community.</p>
    </div>
    <div class="intro-stamp" aria-hidden="true"><span>SMART CITY</span><strong>01</strong></div>
</section>

<div class="forum-columns">
    <section class="feed-column" aria-label="Forum discussions">
        <div class="feed-toolbar">
            <div><p class="eyebrow">The feed</p><h2>Recent discussions</h2></div>
            <label class="search-box"><span aria-hidden="true">⌕</span><span class="sr-only">Search posts</span><input id="post-search" type="search" placeholder="Search"></label>
        </div>

        <?php if ($posts === []): ?>
            <div class="empty-state"><span class="empty-icon" aria-hidden="true">✳</span><h3>The forum starts here.</h3><p>Create the first post to start the conversation.</p></div>
        <?php endif; ?>

        <div class="post-list" id="post-list">
            <?php foreach ($posts as $post): ?>
                <article class="post-card" id="post-<?= (int) $post['id'] ?>" data-searchable="<?= e(mb_strtolower($post['title'] . ' ' . $post['body'] . ' ' . $post['category'] . ' ' . $post['author']['name'])) ?>">
                    <div class="post-meta">
                        <span class="category-tag category-<?= e(mb_strtolower(str_replace(' ', '-', $post['category']))) ?>"><?= e($post['category']) ?></span>
                        <time datetime="<?= e($post['created_at']) ?>"><?= e(format_date($post['created_at'])) ?></time>
                    </div>
                    <div class="post-author-row">
                        <span class="avatar-initial"><?= e(mb_strtoupper(mb_substr($post['author']['name'], 0, 1))) ?></span>
                        <span><strong><?= e($post['author']['name']) ?></strong><small>Community contribution<?= $post['updated_at'] ? ' · edited' : '' ?></small></span>
                        <div class="post-actions">
                            <a class="icon-action" href="<?= e(page_url('edit-post', ['id' => $post['id']])) ?>" aria-label="Edit post" title="Edit">✎</a>
                            <form action="<?= e(page_url('delete-post')) ?>" method="post" data-confirm="Delete this post and its comments?">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                                <button class="icon-action danger-action" type="submit" aria-label="Delete post" title="Delete">×</button>
                            </form>
                        </div>
                    </div>
                    <h3 class="post-title"><?= e($post['title']) ?></h3>
                    <div class="post-body"><?= nl2br(e($post['body'])) ?></div>
                    <div class="post-divider"></div>
                    <div class="comments-heading"><strong><?= count($post['comments']) ?> comment<?= count($post['comments']) === 1 ? '' : 's' ?></strong><span>Discussion</span></div>
                    <?php if ($post['comments'] !== []): ?>
                        <div class="comment-list">
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="comment-item">
                                    <span class="avatar-tiny"><?= e(mb_strtoupper(mb_substr($comment['author']['name'], 0, 1))) ?></span>
                                    <div class="comment-content"><div class="comment-meta"><strong><?= e($comment['author']['name']) ?></strong><time datetime="<?= e($comment['created_at']) ?>"><?= e(format_date($comment['created_at'])) ?></time></div><p><?= nl2br(e($comment['body'])) ?></p></div>
                                    <form action="<?= e(page_url('delete-comment')) ?>" method="post" data-confirm="Delete this comment?">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">
                                        <button class="comment-delete" type="submit" aria-label="Delete comment">Delete</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form class="comment-form" action="<?= e(page_url('add-comment')) ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                        <label class="sr-only" for="comment-author-<?= (int) $post['id'] ?>">Your name</label>
                        <input id="comment-author-<?= (int) $post['id'] ?>" name="author" maxlength="80" placeholder="Your name" required>
                        <label class="sr-only" for="comment-<?= (int) $post['id'] ?>">Add a comment</label>
                        <input id="comment-<?= (int) $post['id'] ?>" name="body" maxlength="1000" placeholder="Write a comment..." required>
                        <button type="submit" aria-label="Send comment" title="Send">↗</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="no-search-results" id="no-search-results" hidden>No posts match your search.</p>
    </section>

    <aside class="compose-column">
        <div class="compose-heading"><span class="compose-icon" aria-hidden="true">+</span><div><p class="eyebrow">Have your say</p><h2>Create a post</h2></div></div>
        <p class="compose-description">Post under a display name; no account is required.</p>
        <form class="form-stack" action="<?= e(page_url('create-post')) ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="field-group"><label for="post-author">Your name <span class="required-mark">*</span></label><input id="post-author" name="author" maxlength="80" placeholder="Display name" required></div>
            <div class="field-group"><label for="post-title">Title <span class="required-mark">*</span></label><input id="post-title" name="title" maxlength="140" placeholder="Give it a clear title" required></div>
            <div class="field-group"><label for="post-category">Category</label><select id="post-category" name="category"><?php foreach ($categories as $category): ?><option value="<?= e($category) ?>"><?= e($category) ?></option><?php endforeach; ?></select></div>
            <div class="field-group"><label for="post-body">Your message <span class="required-mark">*</span></label><textarea id="post-body" name="body" maxlength="5000" rows="6" placeholder="Write your post here..." required></textarea><span class="field-hint"><span data-count-for="post-body">0</span> / 5,000</span></div>
            <button class="button button-primary button-full" type="submit">Publish post <span aria-hidden="true">↗</span></button>
        </form>
        <div class="community-guideline"><span aria-hidden="true">✳</span><p><strong>Open moderation.</strong><br>Anyone can edit or remove content.</p></div>
    </aside>
</div>
