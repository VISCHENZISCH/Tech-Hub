<?php
/**
 * Cybersecurity Section Component
 */
$cyber = $data['cybersecurity'];
?>
<section id="cybersecurity" class="section-padding">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header reveal">
            <div class="badge-pill red">
                <?= render_icon('shield', 'w-4 h-4', 2) ?>
                <span><?= htmlspecialchars($cyber['badge']) ?></span>
            </div>
            <h2 class="section-title"><?= htmlspecialchars($cyber['title']) ?></h2>
            <p class="section-subtitle"><?= htmlspecialchars($cyber['subtitle']) ?></p>
        </div>

        <!-- Featured Banner -->
        <div class="featured-banner reveal delay-1">
            <img src="<?= htmlspecialchars($cyber['banner_image']) ?>" alt="Cybersécurité & Réseaux" loading="lazy">
            <div class="featured-banner-overlay">
                <div class="featured-banner-title">
                    Architecture de Protection & Renseignement sur les Menaces
                </div>
            </div>
        </div>

        <!-- Topics Grid -->
        <div class="topics-grid">
            <?php foreach ($cyber['topics'] as $index => $topic): ?>
                <div class="glass-card hover-lift reveal delay-<?= ($index % 3) + 1 ?>">
                    <div class="topic-header">
                        <div class="topic-icon-wrap" style="color: var(--<?= $topic['badge_color'] ?>-400);">
                            <?= render_icon($topic['icon'], 'w-6 h-6', 1.75) ?>
                        </div>
                        <h3 class="topic-title"><?= htmlspecialchars($topic['title']) ?></h3>
                    </div>
                    <p class="topic-desc"><?= htmlspecialchars($topic['desc']) ?></p>
                    
                    <?php if (!empty($topic['points'])): ?>
                        <ul class="points-list">
                            <?php foreach ($topic['points'] as $point): ?>
                                <li class="point-item">
                                    <span class="point-dot" style="background-color: var(--<?= $topic['badge_color'] ?>-400);"></span>
                                    <span><?= htmlspecialchars($point) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Essential Best Practices -->
        <div class="best-practices-box reveal delay-2">
            <h3 class="practices-title">Bonnes Pratiques Essentielles</h3>
            <div class="practices-grid">
                <div class="practice-col">
                    <?php foreach ($cyber['best_practices']['col1'] as $item): ?>
                        <div class="practice-item">
                            <span class="practice-check"><?= render_icon('check', 'w-5 h-5', 2.5) ?></span>
                            <span><?= htmlspecialchars($item) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="practice-col">
                    <?php foreach ($cyber['best_practices']['col2'] as $item): ?>
                        <div class="practice-item">
                            <span class="practice-check"><?= render_icon('check', 'w-5 h-5', 2.5) ?></span>
                            <span><?= htmlspecialchars($item) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
