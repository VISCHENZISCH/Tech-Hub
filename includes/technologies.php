<?php
/**
 * Technologies Section Component
 */
$techSection = $data['technologies'];
?>
<section id="technologies" class="section-padding">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header reveal">
            <div class="badge-pill blue">
                <?= render_icon('code', 'w-4 h-4', 2) ?>
                <span><?= htmlspecialchars($techSection['badge']) ?></span>
            </div>
            <h2 class="section-title"><?= htmlspecialchars($techSection['title']) ?></h2>
            <p class="section-subtitle"><?= htmlspecialchars($techSection['subtitle']) ?></p>
        </div>

        <!-- Featured Banner -->
        <div class="featured-banner reveal delay-2">
            <img src="<?= htmlspecialchars($techSection['banner_image']) ?>" alt="Technologies & Développement" loading="lazy">
            <div class="featured-banner-overlay">
                <div class="featured-banner-title">
                    Écosystème Moderne de Développement Web, Mobile & Logiciel
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="tech-categories-grid">
            <?php foreach ($techSection['categories'] as $catIdx => $category): ?>
                <?php $colorName = str_replace(['text-', '-400'], '', $category['color']); ?>
                <div class="glass-card hover-lift category-card reveal delay-<?= ($catIdx % 2) + 1 ?>">
                    <div class="category-header">
                        <div class="topic-icon-wrap" style="color: var(--<?= $colorName ?>-400);">
                            <?= render_icon($category['icon'], 'w-6 h-6', 1.75) ?>
                        </div>
                        <h3 class="category-title"><?= htmlspecialchars($category['title']) ?></h3>
                    </div>

                    <div class="category-body">
                        <?php foreach ($category['techs'] as $group): ?>
                            <div class="subtech-group">
                                <h4 class="subtech-title" style="color: var(--<?= $colorName ?>-400);">
                                    <?= htmlspecialchars($group['name']) ?>
                                </h4>
                                <div class="tech-badges-wrap">
                                    <?php foreach ($group['items'] as $item): ?>
                                        <span class="tech-pill" title="<?= htmlspecialchars($item) ?>">
                                            <?= htmlspecialchars($item) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
