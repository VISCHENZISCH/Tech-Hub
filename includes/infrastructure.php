<?php
/**
 * Infrastructure Section Component
 */
$infra = $data['infrastructure'];
?>
<section id="infrastructure" class="section-padding">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header reveal">
            <div class="badge-pill purple">
                <?= render_icon('server', 'w-4 h-4', 2) ?>
                <span><?= htmlspecialchars($infra['badge']) ?></span>
            </div>
            <h2 class="section-title"><?= htmlspecialchars($infra['title']) ?></h2>
            <p class="section-subtitle"><?= htmlspecialchars($infra['subtitle']) ?></p>
        </div>

        <!-- Dual Cards (On-Premise vs Cloud) -->
        <div class="infra-dual-grid reveal delay-1">
            <?php foreach ($infra['cards'] as $card): ?>
                <div class="infra-image-card">
                    <img src="<?= htmlspecialchars($card['image']) ?>" alt="<?= htmlspecialchars($card['title']) ?>" loading="lazy">
                    <div class="infra-image-overlay">
                        <h3 class="infra-overlay-title"><?= htmlspecialchars($card['title']) ?></h3>
                        <p class="infra-overlay-sub"><?= htmlspecialchars($card['subtitle']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 1. Technologies Serveurs -->
        <div class="reveal delay-2" style="margin-bottom: 3.5rem;">
            <h3 class="subheading-center">Technologies Serveurs & Systèmes</h3>
            <div class="infra-grid-3">
                <?php foreach ($infra['server_topics'] as $topic): ?>
                    <?php $color = str_replace(['text-', '-400'], '', $topic['color']); ?>
                    <div class="glass-card hover-lift">
                        <div class="topic-header">
                            <div class="topic-icon-wrap" style="color: var(--<?= $color ?>-400);">
                                <?= render_icon($topic['icon'], 'w-6 h-6', 1.75) ?>
                            </div>
                            <h4 class="topic-title"><?= htmlspecialchars($topic['title']) ?></h4>
                        </div>
                        <div class="infra-item-list">
                            <?php foreach ($topic['items'] as $item): ?>
                                <div class="infra-item-row">
                                    <span class="point-dot" style="background-color: var(--<?= $color ?>-400);"></span>
                                    <span><?= htmlspecialchars($item) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 2. Fournisseurs Cloud Majeurs -->
        <div class="reveal delay-3" style="margin-bottom: 3.5rem;">
            <h3 class="subheading-center">Plateformes Cloud Majeures</h3>
            <div class="infra-grid-3">
                <?php foreach ($infra['cloud_providers'] as $provider): ?>
                    <?php $color = str_replace(['text-', '-400'], '', $provider['color']); ?>
                    <div class="glass-card hover-lift">
                        <div class="topic-header">
                            <div class="topic-icon-wrap" style="color: var(--<?= $color ?>-400);">
                                <?= render_icon($provider['icon'], 'w-6 h-6', 1.75) ?>
                            </div>
                            <h4 class="topic-title"><?= htmlspecialchars($provider['title']) ?></h4>
                        </div>
                        <div class="tech-badges-wrap">
                            <?php foreach ($provider['services'] as $svc): ?>
                                <span class="tech-pill"><?= htmlspecialchars($svc) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 3. DevOps & Automatisation -->
        <div class="reveal delay-4">
            <h3 class="subheading-center">DevOps, IaC & Automatisation</h3>
            <div class="infra-grid-3">
                <?php foreach ($infra['devops_tools'] as $tool): ?>
                    <?php $color = str_replace(['text-', '-400'], '', $tool['color']); ?>
                    <div class="glass-card hover-lift">
                        <div class="topic-header">
                            <div class="topic-icon-wrap" style="color: var(--<?= $color ?>-400);">
                                <?= render_icon($tool['icon'], 'w-6 h-6', 1.75) ?>
                            </div>
                            <h4 class="topic-title"><?= htmlspecialchars($tool['title']) ?></h4>
                        </div>
                        <p class="topic-desc"><?= htmlspecialchars($tool['desc']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
