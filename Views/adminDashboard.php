<?php require __DIR__ . '/header.php'; ?>
<section class="section admin-section">
    <div class="page-shell">
        <div class="admin-heading">
            <div>
                <p class="eyebrow">Smart City Connect · Admin</p>
                <h1>Campaign dashboard.</h1>
                <p>Manage local initiatives and review recent community pledges.</p>
            </div>
            <a class="button button-quiet" href="users.php">Manage users <span aria-hidden="true">↗</span></a>
        </div>

        <?php if ($notice = getMessage('notice')): ?>
            <div class="notice notice-success" role="status"><?= e($notice) ?></div>
        <?php endif; ?>
        <?php if ($error = getMessage('error')): ?>
            <div class="notice notice-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="stat-grid">
            <article class="stat-card"><span>Active &amp; closed campaigns</span><strong><?= count($campaigns) ?></strong></article>
            <article class="stat-card"><span>Total community pledges</span><strong><?= (int) $totals['donation_count'] ?></strong></article>
            <article class="stat-card"><span>Total raised</span><strong><?= e(formatMoney($totals['raised_cents'])) ?></strong></article>
        </div>

        <div class="admin-columns">
            <section class="admin-card" id="campaigns">
                <div class="admin-card-heading">
                    <div><p class="eyebrow">01 / Campaigns</p><h2>Manage campaigns</h2></div>
                </div>
                <form method="post" action="admin.php" class="form-stack campaign-editor">
                    <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="action" value="save_campaign">
                    <h3>Create a campaign</h3>
                    <label for="new-title">Campaign title</label>
                    <input id="new-title" name="title" type="text" maxlength="140" required>
                    <label for="new-description">Description</label>
                    <textarea id="new-description" name="description" rows="3" maxlength="2000" required></textarea>
                    <div class="form-row">
                        <div><label for="new-category">Category</label>
                            <select id="new-category" name="category">
                                <option value="green">Green city</option>
                                <option value="energy">Clean energy</option>
                                <option value="community">Community</option>
                            </select>
                        </div>
                        <div><label for="new-goal">Funding goal ($)</label>
                            <input id="new-goal" name="goal" type="number" min="0.01" max="10000000" step="0.01" required>
                        </div>
                    </div>
                    <button class="button button-primary" type="submit">Add campaign <span aria-hidden="true">+</span></button>
                </form>

                <div class="managed-list">
                    <?php foreach ($campaigns as $campaign): ?>
                        <details class="managed-campaign">
                            <summary>
                                <span class="managed-campaign-title"><?= e($campaign['title']) ?></span>
                                <span class="status-pill status-<?= e($campaign['status']) ?>"><?= e(ucfirst($campaign['status'])) ?></span>
                            </summary>
                            <form method="post" action="admin.php" class="form-stack campaign-editor">
                                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="action" value="save_campaign">
                                <input type="hidden" name="id" value="<?= (int) $campaign['id'] ?>">
                                <label for="title-<?= (int) $campaign['id'] ?>">Campaign title</label>
                                <input id="title-<?= (int) $campaign['id'] ?>" name="title" type="text" maxlength="140"
                                    value="<?= e($campaign['title']) ?>" required>
                                <label for="description-<?= (int) $campaign['id'] ?>">Description</label>
                                <textarea id="description-<?= (int) $campaign['id'] ?>" name="description" rows="3"
                                    maxlength="2000" required><?= e($campaign['description']) ?></textarea>
                                <div class="form-row">
                                    <div><label for="category-<?= (int) $campaign['id'] ?>">Category</label>
                                        <select id="category-<?= (int) $campaign['id'] ?>" name="category">
                                            <?php foreach (['green' => 'Green city', 'energy' => 'Clean energy', 'community' => 'Community'] as $value => $label): ?>
                                                <option value="<?= e($value) ?>" <?= $campaign['category'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div><label for="goal-<?= (int) $campaign['id'] ?>">Funding goal ($)</label>
                                        <input id="goal-<?= (int) $campaign['id'] ?>" name="goal" type="number" min="0.01" max="10000000"
                                            step="0.01" value="<?= e(number_format((int) $campaign['goal_cents'] / 100, 2, '.', '')) ?>" required>
                                    </div>
                                </div>
                                <label for="status-<?= (int) $campaign['id'] ?>">Status</label>
                                <select id="status-<?= (int) $campaign['id'] ?>" name="status">
                                    <option value="active" <?= $campaign['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="closed" <?= $campaign['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                                <div class="managed-actions">
                                    <button class="button button-primary" type="submit">Save changes</button>
                                </div>
                            </form>
                            <form method="post" action="admin.php" class="managed-actions"
                                data-confirm="Delete this campaign and its donation records?">
                                <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="action" value="delete_campaign">
                                <input type="hidden" name="campaign_id" value="<?= (int) $campaign['id'] ?>">
                                <button class="button button-danger" type="submit">Delete campaign</button>
                            </form>
                        </details>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="admin-card donation-admin-card" id="donations">
                <div class="admin-card-heading">
                    <div><p class="eyebrow">02 / Community support</p><h2>Recent pledges</h2></div>
                </div>
                <?php if ($donations === []): ?>
                    <p class="muted-copy">Pledges will appear here as neighbours support a campaign.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Contributor</th><th>Campaign</th><th>Amount</th><th>Date</th></tr></thead>
                            <tbody>
                                <?php foreach ($donations as $donation): ?>
                                    <tr>
                                        <td><strong><?= e($donation['donor_name']) ?></strong><span><?= e($donation['donor_email']) ?></span></td>
                                        <td><?= e($donation['campaign_title']) ?></td>
                                        <td><?= e(formatMoney($donation['amount_cents'])) ?></td>
                                        <td><?= e(date('M j, Y', strtotime($donation['created_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>