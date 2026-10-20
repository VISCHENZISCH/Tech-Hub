<?php
/**
 * Footer & Contact Component
 */
$contactSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // Basic sanitization & simulated contact submission in native PHP
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $contactSuccess = true;
    }
}
?>
<footer id="contact" class="site-footer">
    <div class="container">
        <!-- Interactive Contact Section Card -->
        <div class="contact-card-wrap reveal">
            <div class="glass-card">
                <div class="contact-form-grid">
                    <!-- Left: Contact Details -->
                    <div class="contact-info-col">
                        <div class="badge-pill cyan" style="align-self: flex-start;">
                            <?= render_icon('mail', 'w-4 h-4', 2) ?>
                            <span>Nous Contacter</span>
                        </div>
                        <h3 class="contact-title">Entrons en Contact</h3>
                        <p class="contact-desc">
                            Une question sur un parcours, un projet d'infrastructure ou une recommandation technologique ? Écrivez-nous directement.
                        </p>

                        <div class="contact-methods">
                            <a href="mailto:<?= htmlspecialchars($data['site']['email']) ?>" class="contact-method-item" title="Envoyer un email">
                                <?= render_icon('mail', 'w-5 h-5 text-cyan-400', 1.75) ?>
                                <span><?= htmlspecialchars($data['site']['email']) ?></span>
                            </a>
                            <a href="<?= htmlspecialchars($data['site']['github']) ?>" target="_blank" rel="noopener noreferrer" class="contact-method-item" title="Profil GitHub">
                                <?= render_icon('github', 'w-5 h-5 text-cyan-400', 1.75) ?>
                                <span>github.com/VISCHENZISCH</span>
                            </a>
                            <a href="<?= htmlspecialchars($data['site']['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="contact-method-item" title="Profil LinkedIn">
                                <?= render_icon('linkedin', 'w-5 h-5 text-cyan-400', 1.75) ?>
                                <span>linkedin.com/in/félix-tovignan</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Native PHP Interactive Form -->
                    <div class="contact-form-col">
                        <?php if ($contactSuccess): ?>
                            <div class="alert-message success" role="alert">
                                <?= render_icon('check', 'w-5 h-5', 2.5) ?>
                                <div>
                                    <strong>Merci <?= htmlspecialchars($name) ?> !</strong> Votre message a bien été pris en compte. Notre équipe vous répondra rapidement.
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="#contact">
                            <div class="form-group">
                                <label for="form_name" class="form-label">Votre Nom</label>
                                <input 
                                    type="text" 
                                    id="form_name" 
                                    name="name" 
                                    class="form-input" 
                                    placeholder="Alexandre Martin" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="form_email" class="form-label">Adresse Email</label>
                                <input 
                                    type="email" 
                                    id="form_email" 
                                    name="email" 
                                    class="form-input" 
                                    placeholder="alexandre@domaine.com" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="form_message" class="form-label">Votre Message</label>
                                <textarea 
                                    id="form_message" 
                                    name="message" 
                                    class="form-textarea" 
                                    placeholder="Décrivez votre besoin ou votre question..." 
                                    required
                                ></textarea>
                            </div>

                            <button type="submit" name="contact_submit" value="1" class="btn-primary" style="width: 100%; justify-content: center;">
                                <span>Envoyer le Message</span>
                                <span class="btn-icon"><?= render_icon('arrow-right', 'w-5 h-5', 2) ?></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Links -->
        <div class="footer-links-grid reveal delay-1">
            <!-- Brand Info -->
            <div>
                <a href="#cybersecurity" class="brand-logo" style="margin-bottom: 1rem;">
                    <div class="logo-icon-box">
                        <div class="logo-glow"></div>
                        <?= render_icon('shield', 'logo-icon', 1.75) ?>
                    </div>
                    <div class="logo-text">
                        <span class="logo-text-accent">Tech</span> Hub
                    </div>
                </a>
                <p class="footer-brand-desc">
                    Votre portail complet pour la cybersécurité, les roadmaps métiers, les technologies modernes et l'infrastructure cloud.
                </p>
                <div class="social-links">
                    <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="GitHub">
                        <?= render_icon('github', 'w-5 h-5', 1.75) ?>
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="LinkedIn">
                        <?= render_icon('linkedin', 'w-5 h-5', 1.75) ?>
                    </a>
                    <a href="mailto:<?= htmlspecialchars($data['site']['email']) ?>" class="social-btn" aria-label="Email">
                        <?= render_icon('mail', 'w-5 h-5', 1.75) ?>
                    </a>
                </div>
            </div>

            <!-- Resources Navigation -->
            <div>
                <h4 class="footer-col-title">Ressources</h4>
                <ul class="footer-nav-list">
                    <li><a href="#cybersecurity" class="footer-nav-link">Cybersécurité</a></li>
                    <li><a href="#roadmaps" class="footer-nav-link">Roadmaps Carrière</a></li>
                    <li><a href="#technologies" class="footer-nav-link">Technologies</a></li>
                    <li><a href="#infrastructure" class="footer-nav-link">Infrastructure Cloud</a></li>
                </ul>
            </div>

            <!-- Community & Legal -->
            <div>
                <h4 class="footer-col-title">Plateforme</h4>
                <ul class="footer-nav-list">
                    <li><a href="#cybersecurity" class="footer-nav-link">Cybersécurité</a></li>
                    <li><a href="#contact" class="footer-nav-link">Contact & Support</a></li>
                    <li><a href="#" class="footer-nav-link">Documentation API</a></li>
                    <li><a href="#" class="footer-nav-link">Confidentialité & RGPD</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-copyright">
            <p>&copy; <?= htmlspecialchars($data['site']['year']) ?> <?= htmlspecialchars($data['site']['name']) ?>. Tous droits réservés. Développé en PHP Natif.</p>
        </div>
    </div>
</footer>

<!-- Vanilla JS Application Script -->
<script src="assets/js/app.js"></script>
</body>
</html>
