<?php
/**
 * Hero Section Component
 * Horizons Tech - Cybersécurité & Ingénierie
 */
?>
<section class="home-hero-section" id="hero">
    <div class="container home-hero-container">
        <div class="home-hero-content reveal">
            <h1 class="home-hero-title">
                EXPLOREZ LES<br>
                <span>HORIZONS TECH</span>
            </h1>

            <p class="home-hero-description">
                Développez votre expertise avec les meilleures roadmaps professionnelles, l'ingénierie d'intrusion offensive et la supervision de sécurité réseau de haut niveau.
            </p>

            <!-- Boutons d'Action Principaux -->
            <div class="home-hero-actions">
                <a href="#roadmaps" class="hero-btn-primary">
                    <span>Explorer les Roadmaps</span>
                </a>
                <a href="login.php" class="hero-btn-frosted">
                    <span>Espace Membre</span>
                </a>
            </div>
        </div>

        <!-- Composition Hero Droite : Serveur & Mobile -->
        <div class="home-hero-mockup-wrapper reveal">
            <div class="hero-dual-composition">
                <!-- Serveur Web -->
                <div class="hero-server-layer">
                    <picture>
                        <source srcset="assets/images/server-web.webp" type="image/webp">
                        <img src="assets/images/server-web.png" 
                             alt="Serveur & Infrastructure Tech Hub" 
                             class="hero-server-img" 
                             width="736" 
                             height="528"
                             loading="eager">
                    </picture>
                </div>

                <!-- Smartphone -->
                <div class="hero-phone-layer">
                    <picture>
                        <source srcset="assets/images/bg-hero-new.webp" type="image/webp">
                        <img src="assets/images/bg-hero-new.png" 
                             alt="Interface Mobile Tech Hub" 
                             class="hero-phone-img" 
                             width="640" 
                             height="640"
                             loading="eager">
                    </picture>
                </div>
            </div>
        </div>
    </div>
</section>
