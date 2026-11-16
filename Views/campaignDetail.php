<?php require __DIR__ . '/header.php'; ?>
<?php
$goal = (int) $campaign['goal_cents'];
$raised = (int) $campaign['raised_cents'];
$progress = $goal > 0 ? min(100, (int) round($raised / $goal * 100)) : 0;
?>
<section class="section detail-section">
    <div class="page-shell">
        <a class="back-link" href="index.php#campaigns">← All campaigns</a>
        <div class="detail-grid">
            <article class="detail-main">
                <div class="campaign-art detail-art art-<?= e($campaign['category']) ?>">
                    <span class="art-tag"><?= e(ucfirst($campaign['category'])) ?> · Community project</span>
                    <span class="art-symbol" aria-hidden="true">✳</span>
                    <span class="art-lines" aria-hidden="true"></span>
                </div>
                <p class="eyebrow">A local idea with a city-wide impact</p>
                <h1><?= e($campaign['title']) ?></h1>
                <p class="detail-description"><?= nl2br(e($campaign['description'])) ?></p>
                <div class="detail-progress">
                    <div class="progress-label">
                        <span><strong><?= e(formatMoney($raised)) ?></strong> raised</span>
                        <span><?= $progress ?>% of <?= e(formatMoney($goal)) ?></span>
                    </div>
                    <div class="progress-track" role="progressbar" aria-label="Campaign funding progress"
                        aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                        <span style="width: <?= $progress ?>%"></span>
                    </div>
                </div>
                <div class="detail-values">
                    <div><strong><?= e(formatMoney($raised)) ?></strong><span>Raised so far</span></div>
                    <div><strong><?= e(formatMoney($goal)) ?></strong><span>Funding goal</span></div>
                </div>
            </article>

            <aside class="donation-panel" id="donate">
                <p class="eyebrow">Make a difference</p>
                <h2>Support this campaign.</h2>
                <p>Your contribution helps this community idea take its next step.</p>
                <?php if (!empty($errors)): ?>
                    <div class="notice notice-error" role="alert">
                        <strong>Please check the following:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post" action="index.php?page=donate" class="form-stack">
                    <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="campaign_id" value="<?= (int) $campaign['id'] ?>">
                    <label for="donor_name">Your name</label>
                    <input id="donor_name" name="donor_name" type="text" maxlength="120" autocomplete="name"
                        value="<?= e($oldInput['donor_name'] ?? '') ?>" required>
                    <label for="donor_email">Email address</label>
                    <input id="donor_email" name="donor_email" type="email" maxlength="254" autocomplete="email"
                        value="<?= e($oldInput['donor_email'] ?? '') ?>" required>
                    <label for="amount">Contribution amount</label>
                    <div class="amount-input"><span aria-hidden="true">$</span>
                        <input id="amount" name="amount" type="number" min="0.01" max="1000000" step="0.01"
                            value="<?= e($oldInput['amount'] ?? '') ?>" placeholder="25.00" required>
                    </div>
                    <label for="message">A note for the community <span class="label-optional">(optional)</span></label>
                    <textarea id="message" name="message" rows="3" maxlength="500"><?= e($oldInput['message'] ?? '') ?></textarea>
                    <button class="button button-primary button-wide" type="submit">Continue with contribution <span aria-hidden="true">→</span></button>
                </form>
                <p class="form-note">This project records community pledges. No payment details are collected on this demo.</p>
            </aside>
        </div>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>