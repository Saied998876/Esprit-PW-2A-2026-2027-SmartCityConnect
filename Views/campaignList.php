<?php require __DIR__ . '/header.php'; ?>

<section class="hero">
    <div class="page-shell hero-grid">
        <div class="hero-copy">
            <p class="eyebrow"><span class="eyebrow-line"></span> Digital transformation · Smart cities</p>
            <h1>A smarter city<br><span>starts together.</span></h1>
            <p class="hero-lead">Back practical ideas that make our streets greener, cleaner and more connected. Every contribution moves a neighbourhood forward.</p>
            <a class="button button-primary" href="#campaigns">Explore campaigns <span aria-hidden="true">↓</span></a>
            <p class="hero-note"><span class="note-mark" aria-hidden="true">✳</span> Community-powered change, made visible.</p>
        </div>
        <div class="city-art" role="img" aria-label="Illustration of a connected, greener city">
            <div class="city-sun"></div>
            <div class="city-orbit orbit-one"></div>
            <div class="city-orbit orbit-two"></div>
            <div class="city-building building-one"><i></i><i></i><i></i><i></i></div>
            <div class="city-building building-two"><i></i><i></i><i></i><i></i></div>
            <div class="city-building building-three"><i></i><i></i><i></i><i></i></div>
            <div class="city-building building-four"><i></i><i></i><i></i><i></i></div>
            <span class="city-label">Connected communities</span>
            <span class="city-pulse pulse-one"></span>
            <span class="city-pulse pulse-two"></span>
            <span class="city-pulse pulse-three"></span>
            <div class="city-caption"><span>Ideas in motion</span><strong>SMART LIVING</strong><span>Better, together</span></div>
        </div>
    </div>
</section>

<section class="section campaigns-section" id="campaigns">
    <div class="page-shell">
        <div class="section-heading">
            <div>
                <p class="eyebrow">01 / Make an impact</p>
                <h2>Ideas worth growing.</h2>
            </div>
            <p>Choose a project close to home. Your support helps turn a good idea into a better everyday.</p>
        </div>

        <?php if ($campaigns === []): ?>
            <div class="empty-state">
                <span class="empty-icon" aria-hidden="true">✳</span>
                <h3>New ideas are on the way.</h3>
                <p>There are no active campaigns at the moment. Please check back soon.</p>
            </div>
        <?php else: ?>
            <div class="campaign-grid">
                <?php foreach ($campaigns as $campaign):
                    $goal = (int) $campaign['goal_cents'];
                    $raised = (int) $campaign['raised_cents'];
                    $progress = $goal > 0 ? min(100, (int) round($raised / $goal * 100)) : 0;
                    $category = $campaign['category'];
                    if ($category !== 'green' && $category !== 'energy' && $category !== 'community') {
                        $category = 'community';
                    }

                    if ($category === 'green') {
                        $icon = '✳';
                    } elseif ($category === 'energy') {
                        $icon = '☼';
                    } else {
                        $icon = '⌂';
                    }
                    ?>
                    <article class="campaign-card">
                        <div class="campaign-art art-<?= e($category) ?>">
                            <span class="art-tag"><?= e(ucfirst($category)) ?> · Community project</span>
                            <span class="art-symbol" aria-hidden="true"><?= $icon ?></span>
                            <span class="art-lines" aria-hidden="true"></span>
                        </div>
                        <div class="campaign-card-body">
                            <h3><?= e($campaign['title']) ?></h3>
                            <p><?= e($campaign['description']) ?></p>
                            <div class="progress-label">
                                <span><?= e(formatMoney($raised)) ?> raised</span>
                                <span><?= $progress ?>%</span>
                            </div>
                            <div class="progress-track" role="progressbar"
                                aria-label="Campaign funding progress"
                                aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                                <span style="width: <?= $progress ?>%"></span>
                            </div>
                            <div class="campaign-card-footer">
                                <span>Goal <strong><?= e(formatMoney($goal)) ?></strong></span>
                                <a class="text-link" href="index.php?page=campaign&amp;id=<?= (int) $campaign['id'] ?>">
                                    Support this idea <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section approach-section" id="approach">
    <div class="page-shell approach-grid">
        <div>
            <p class="eyebrow">02 / Our approach</p>
            <h2>Better city.<br><span>Shared effort.</span></h2>
            <p class="approach-copy">Smart City Connect brings neighbours and local initiatives together around meaningful, measurable progress.</p>
        </div>
        <div class="steps-list">
            <article><span>01</span><div><h3>Choose a cause</h3><p>Find a project that matters to you.</p></div></article>
            <article><span>02</span><div><h3>Make it possible</h3><p>Every contribution adds momentum.</p></div></article>
            <article><span>03</span><div><h3>Grow together</h3><p>Local ideas become shared impact.</p></div></article>
        </div>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>