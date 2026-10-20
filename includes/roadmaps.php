<?php
/**
 * Roadmaps Section Component
 */
$roadmaps = $data['roadmaps'];
?>
<section id="roadmaps" class="section-padding">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header reveal">
            <div class="badge-pill cyan">
                <?= render_icon('trending-up', 'w-4 h-4', 2) ?>
                <span><?= htmlspecialchars($roadmaps['badge']) ?></span>
            </div>
            <h2 class="section-title"><?= htmlspecialchars($roadmaps['title']) ?></h2>
            <p class="section-subtitle"><?= htmlspecialchars($roadmaps['subtitle']) ?></p>
        </div>

        <!-- Career Roadmaps Grid -->
        <div class="roadmaps-grid">
            <?php foreach ($roadmaps['items'] as $index => $item): ?>
                <div class="glass-card hover-lift roadmap-card reveal delay-<?= ($index % 3) + 1 ?>">
                    <div class="roadmap-header">
                        <div class="roadmap-title-row">
                            <div class="topic-icon-wrap" style="color: var(--<?= str_replace(['text-', '-400'], '', $item['color']) ?>-400);">
                                <?= render_icon($item['icon'], 'w-6 h-6', 1.75) ?>
                            </div>
                            <h3 class="topic-title"><?= htmlspecialchars($item['title']) ?></h3>
                        </div>
                        <span class="duration-tag"><?= htmlspecialchars($item['duration']) ?></span>
                    </div>

                    <!-- Steps List -->
                    <div class="roadmap-steps">
                        <?php foreach ($item['steps'] as $stepIdx => $step): ?>
                            <div class="step-row">
                                <span class="step-num"><?= $stepIdx + 1 ?></span>
                                <span><?= htmlspecialchars($step) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
