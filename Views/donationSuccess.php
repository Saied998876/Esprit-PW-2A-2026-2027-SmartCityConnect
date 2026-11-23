<?php require __DIR__ . '/header.php'; ?>
<section class="section success-section">
    <div class="success-card">
        <div class="success-mark" aria-hidden="true">✓</div>
        <p class="eyebrow">Your support is on its way</p>
        <h1>Thank you, <?= e(explode(' ', trim($donation['donor_name']))[0]) ?>.</h1>
        <p class="success-copy">Your <?= e(formatMoney($donation['amount_cents'])) ?> pledge for
            <strong><?= e($donation['campaign_title']) ?></strong> brings this idea one step closer.</p>
        <div class="receipt-row"><span>Contribution reference</span><strong>#<?= (int) $donation['id'] ?></strong></div>
        <a class="button button-primary" href="index.php#campaigns">Explore more campaigns <span aria-hidden="true">→</span></a>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>