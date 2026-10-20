<?php
/**
 * Tech Hub - Content Data Store
 */

return [
    'site' => [
        'name' => 'Tech Hub',
        'title' => 'Tech & Cybersecurity Hub',
        'tagline' => 'Explorez les roadmaps professionnels, les technologies de pointe et les meilleures pratiques en cybersécurité',
        'author' => 'Félix Tovignan',
        'email' => 'vischenzisch@gmail.com',
        'github' => 'https://github.com/VISCHENZISCH',
        'linkedin' => 'https://www.linkedin.com/in/f%C3%A9lix-tovignan-95a22737a/',
        'year' => date('Y'),
    ],

    'navigation' => [
        ['label' => 'Accueil', 'href' => '#hero'],
        ['label' => 'Cybersécurité', 'href' => '#cybersecurity'],
        ['label' => 'Roadmaps', 'href' => '#roadmaps'],
        ['label' => 'Technologies', 'href' => '#technologies'],
        ['label' => 'Infrastructure', 'href' => '#infrastructure'],
        ['label' => 'Contact', 'href' => '#contact'],
    ],

    'stats' => [
        ['value' => '500+', 'label' => 'Ressources'],
        ['value' => '100+', 'label' => 'Technologies'],
        ['value' => '25+', 'label' => 'Roadmaps'],
        ['value' => '200+', 'label' => 'Guides'],
    ],

    'hero_pillars' => [
        [
            'icon' => 'shield',
            'title' => 'Cybersécurité',
            'desc' => 'Protection & Défense',
            'href' => '#cybersecurity',
            'color' => 'cyan',
        ],
        [
            'icon' => 'trending-up',
            'title' => 'Roadmaps',
            'desc' => 'Parcours Carrière',
            'href' => '#roadmaps',
            'color' => 'blue',
        ],
        [
            'icon' => 'code',
            'title' => 'Développement',
            'desc' => 'Web, Mobile & Logiciel',
            'href' => '#technologies',
            'color' => 'purple',
        ],
        [
            'icon' => 'server',
            'title' => 'Infrastructure',
            'desc' => 'Serveurs & Cloud',
            'href' => '#infrastructure',
            'color' => 'emerald',
        ],
    ],

    'cybersecurity' => [
        'badge' => 'Cybersécurité & Défense',
        'title' => 'Cybersécurité',
        'subtitle' => 'Les fondamentaux et les pratiques avancées pour protéger vos systèmes, réseaux et applications contre les cybermenaces.',
        'banner_image' => 'assets/images/cybersecurity-banner.jpg',
        'topics' => [
            [
                'icon' => 'shield',
                'title' => 'Sécurité des Réseaux',
                'desc' => 'Firewalls, IDS/IPS, VPN et segmentation réseau pour une protection périmétrique optimale.',
                'color' => 'text-blue-400',
                'badge_color' => 'blue',
                'points' => ['Firewalls NGFW & WAF', 'VPN & Zero Trust Network', 'Segmentation VLAN/Micro-seg', 'Protection Anti-DDoS'],
            ],
            [
                'icon' => 'lock',
                'title' => 'Cryptographie',
                'desc' => 'Chiffrement des données en transit et au repos, certificats SSL/TLS, PKI et gestion des clés.',
                'color' => 'text-purple-400',
                'badge_color' => 'purple',
                'points' => ['Chiffrement AES-256 & RSA', 'Hashing robuste (SHA-256, bcrypt)', 'Certificats SSL/TLS & HTTPS', 'Infrastructure PKI & HSM'],
            ],
            [
                'icon' => 'eye',
                'title' => 'Tests de Pénétration',
                'desc' => 'Audit de sécurité offensif, analyse continue de vulnérabilités et méthodologies de tests d\'intrusion éthiques.',
                'color' => 'text-cyan-400',
                'badge_color' => 'cyan',
                'points' => ['Reconnaissance OSINT', 'Scanning & Énumération', 'Exploitation OWASP Top 10', 'Rapports & Remédiation'],
            ],
            [
                'icon' => 'alert-triangle',
                'title' => 'Gestion des Incidents',
                'desc' => 'Détection précoce, réponse coordonnée et plan de reprise d\'activité face aux cyberattaques modernes.',
                'color' => 'text-red-400',
                'badge_color' => 'red',
                'points' => ['Plateformes SIEM & SOAR', 'Détection EDR/XDR', 'Threat Hunting & Renseignement', 'Plans de réponse (CSIRT/CERT)'],
            ],
            [
                'icon' => 'file-check',
                'title' => 'Conformité & Normes',
                'desc' => 'Gouvernance, conformité RGPD, ISO 27001, NIST, SOC 2 et standards internationaux de sécurité.',
                'color' => 'text-green-400',
                'badge_color' => 'green',
                'points' => ['Certification ISO/IEC 27001', 'Règlement RGPD / GDPR', 'Norme PCI-DSS bancaire', 'Framework NIST & CIS Benchmarks'],
            ],
            [
                'icon' => 'network',
                'title' => 'Sécurité Cloud',
                'desc' => 'Protection des architectures AWS, Azure, GCP et implémentation du modèle Zero Trust.',
                'color' => 'text-orange-400',
                'badge_color' => 'orange',
                'points' => ['IAM & Principe du moindre privilège', 'Sécurité des conteneurs & K8s', 'Gestion des secrets (Vault, KMS)', 'Posture de sécurité CSPM & CIEM'],
            ],
        ],
        'best_practices' => [
            'col1' => [
                'Mise à jour régulière des systèmes, firmwares et dépendances logicielles',
                'Utilisation de mots de passe complexes et d\'un gestionnaire de secrets dédié',
                'Activation systématique de l\'authentification multifacteur (MFA / 2FA)',
            ],
            'col2' => [
                'Sauvegardes chiffrées automatiques, isolées (air-gap) et testées périodiquement',
                'Sensibilisation continue des collaborateurs contre le phishing et l\'ingénierie sociale',
                'Audits de sécurité, revues de code et tests de pénétration réguliers',
            ],
        ],
    ],

    'roadmaps' => [
        'badge' => 'Évolution Professionnelle',
        'title' => 'Roadmaps Carrière',
        'subtitle' => 'Des parcours détaillés et structurés pour acquérir les compétences les plus demandées sur le marché.',
        'items' => [
            [
                'icon' => 'code-2',
                'title' => 'Développeur Full Stack',
                'duration' => '12-18 mois',
                'color' => 'text-blue-400',
                'dot_color' => 'bg-blue-400',
                'steps' => ['HTML5 / CSS3 / JavaScript Moderne', 'React / Vue.js / TypeScript', 'Node.js / Python / PHP', 'Bases de données SQL & NoSQL', 'DevOps & CI/CD Pipelines'],
            ],
            [
                'icon' => 'database',
                'title' => 'Ingénieur DevOps',
                'duration' => '10-15 mois',
                'color' => 'text-purple-400',
                'dot_color' => 'bg-purple-400',
                'steps' => ['Linux / Shell Scripting', 'Docker & Kubernetes', 'Terraform & Ansible (IaC)', 'CI/CD Pipelines (GitHub Actions/GitLab)', 'Monitoring Prometheus & Grafana'],
            ],
            [
                'icon' => 'cloud',
                'title' => 'Architecte Cloud',
                'duration' => '18-24 mois',
                'color' => 'text-cyan-400',
                'dot_color' => 'bg-cyan-400',
                'steps' => ['Fondamentaux Cloud & Virtualisation', 'AWS, Azure ou GCP (Certifications)', 'Architectures distribuées & Microservices', 'Sécurité Cloud & Zero Trust', 'FinOps & Optimisation des coûts'],
            ],
            [
                'icon' => 'smartphone',
                'title' => 'Développeur Mobile',
                'duration' => '8-12 mois',
                'color' => 'text-green-400',
                'dot_color' => 'bg-green-400',
                'steps' => ['Fondamentaux Mobile & UI Guidelines', 'React Native ou Flutter (Cross-platform)', 'Swift/SwiftUI (iOS) & Kotlin (Android)', 'APIs REST, GraphQL & Offline First', 'Déploiement App Store & Google Play'],
            ],
            [
                'icon' => 'trending-up',
                'title' => 'Expert Cybersécurité',
                'duration' => '24-36 mois',
                'color' => 'text-red-400',
                'dot_color' => 'bg-red-400',
                'steps' => ['Réseaux, Protocoles & Systèmes', 'Pentesting & Ethical Hacking', 'Cryptographie & Sécurité Applicative', 'Forensics & Analyse Malware', 'Gouvernance, Risques & Conformité'],
            ],
            [
                'icon' => 'laptop',
                'title' => 'Ingénieur Logiciel',
                'duration' => '15-20 mois',
                'color' => 'text-orange-400',
                'dot_color' => 'bg-orange-400',
                'steps' => ['Algorithmique avancée & Structures de données', 'Design Patterns & Principes SOLID', 'Architecture Clean, DDD & Hexagonale', 'Tests automatisés (TDD) & Qualité', 'Performance & Concurrence'],
            ],
        ],
    ],

    'technologies' => [
        'badge' => 'Stack & Outils',
        'title' => 'Technologies de Développement',
        'subtitle' => 'Les outils, langages et frameworks modernes pour créer des applications performantes, évolutives et fiables.',
        'banner_image' => 'assets/images/development-banner.jpg',
        'categories' => [
            [
                'icon' => 'code',
                'title' => 'Développement Web',
                'color' => 'text-blue-400',
                'techs' => [
                    ['name' => 'Frontend', 'items' => ['React', 'Vue.js', 'Angular', 'Next.js', 'Svelte', 'TypeScript', 'Tailwind CSS']],
                    ['name' => 'Backend', 'items' => ['PHP Natif', 'Laravel', 'Node.js', 'Python / Django', 'Java / Spring', 'Go', 'Ruby on Rails']],
                    ['name' => 'Outils & Bundlers', 'items' => ['Vite', 'Webpack', 'Docker', 'Git', 'Jest', 'Cypress', 'Postman']],
                ],
            ],
            [
                'icon' => 'smartphone',
                'title' => 'Développement Mobile',
                'color' => 'text-green-400',
                'techs' => [
                    ['name' => 'Cross-platform', 'items' => ['React Native', 'Flutter', 'Ionic', 'Capacitor', 'Expo']],
                    ['name' => 'Natif', 'items' => ['Swift (iOS)', 'SwiftUI', 'Kotlin (Android)', 'Jetpack Compose']],
                    ['name' => 'Backend & Services', 'items' => ['Firebase', 'Supabase', 'AWS Amplify', 'GraphQL', 'Push Notifications']],
                ],
            ],
            [
                'icon' => 'laptop',
                'title' => 'Développement Logiciel',
                'color' => 'text-purple-400',
                'techs' => [
                    ['name' => 'Langages Système', 'items' => ['Rust', 'C++', 'C#', 'Java', 'Python', 'Go']],
                    ['name' => 'Desktop Apps', 'items' => ['Tauri', 'Electron', 'Qt', '.NET MAUI', 'JavaFX']],
                    ['name' => 'Environnements & Build', 'items' => ['VS Code', 'JetBrains', 'Visual Studio', 'CMake', 'Maven / Gradle']],
                ],
            ],
            [
                'icon' => 'layers',
                'title' => 'Architecture & Patterns',
                'color' => 'text-orange-400',
                'techs' => [
                    ['name' => 'Architecture', 'items' => ['Microservices', 'Serverless', 'Event-Driven', 'Clean Architecture', 'CQRS']],
                    ['name' => 'Design Patterns', 'items' => ['MVC', 'MVVM', 'Repository', 'Factory', 'Observer', 'Singleton']],
                    ['name' => 'Bonnes Pratiques', 'items' => ['TDD', 'BDD', 'DDD', 'Principes SOLID', 'Agile / Scrum', 'Code Review']],
                ],
            ],
        ],
    ],

    'infrastructure' => [
        'badge' => 'Systèmes & Hébergement',
        'title' => 'Serveurs & Cloud',
        'subtitle' => 'Infrastructure moderne, conteneurisation et cloud computing pour héberger et scaler vos applications avec résilience.',
        'cards' => [
            [
                'title' => 'Infrastructure On-Premise',
                'subtitle' => 'Serveurs dédiés, virtualisation bare-metal & baies haute performance',
                'image' => 'assets/images/server-room.jpg',
            ],
            [
                'title' => 'Cloud Computing',
                'subtitle' => 'Scalabilité élastique, multirégion & haute disponibilité automatisée',
                'image' => 'assets/images/cloud-computing.jpg',
            ],
        ],
        'server_topics' => [
            [
                'icon' => 'server',
                'title' => 'Serveurs Web & Proxies',
                'color' => 'text-blue-400',
                'dot_color' => 'bg-blue-400',
                'items' => ['Nginx', 'Apache', 'Caddy', 'HAProxy', 'Envoy', 'Traefik', 'Load Balancers L4/L7'],
            ],
            [
                'icon' => 'database',
                'title' => 'Bases de Données',
                'color' => 'text-green-400',
                'dot_color' => 'bg-green-400',
                'items' => ['PostgreSQL', 'MySQL / MariaDB', 'MongoDB', 'Redis (In-Memory)', 'Elasticsearch', 'Cassandra'],
            ],
            [
                'icon' => 'hard-drive',
                'title' => 'Stockage & Résilience',
                'color' => 'text-purple-400',
                'dot_color' => 'bg-purple-400',
                'items' => ['Stockage Objet (S3)', 'Block Storage (SAN/EBS)', 'NAS / NFS Réseau', 'CDN Mondial', 'Plans de Sauvegarde 3-2-1'],
            ],
        ],
        'cloud_providers' => [
            [
                'icon' => 'cloud',
                'title' => 'Amazon Web Services (AWS)',
                'color' => 'text-orange-400',
                'services' => ['EC2', 'S3', 'Lambda (Serverless)', 'RDS Aurora', 'CloudFront', 'EKS (Kubernetes)', 'VPC', 'Route 53'],
            ],
            [
                'icon' => 'cloud',
                'title' => 'Microsoft Azure',
                'color' => 'text-blue-400',
                'services' => ['Virtual Machines', 'Blob Storage', 'Azure Functions', 'Azure SQL', 'AKS Kubernetes', 'Azure CDN', 'Entra ID (AD)'],
            ],
            [
                'icon' => 'cloud',
                'title' => 'Google Cloud Platform (GCP)',
                'color' => 'text-red-400',
                'services' => ['Compute Engine', 'Cloud Storage', 'Cloud Functions', 'Cloud SQL', 'GKE Engine', 'BigQuery', 'Cloud CDN'],
            ],
        ],
        'devops_tools' => [
            [
                'icon' => 'cpu',
                'title' => 'Conteneurisation & Orchestration',
                'color' => 'text-cyan-400',
                'desc' => 'Docker, Docker Compose, Podman, Kubernetes (K8s), Helm, Containerd pour des déploiements prévisibles.',
            ],
            [
                'icon' => 'network',
                'title' => 'Infrastructure as Code (IaC)',
                'color' => 'text-yellow-400',
                'desc' => 'Terraform, Ansible, OpenTofu, CloudFormation, Pulumi pour provisionner et versionner l\'infrastructure.',
            ],
            [
                'icon' => 'server',
                'title' => 'CI/CD & Automatisation',
                'color' => 'text-pink-400',
                'desc' => 'GitHub Actions, GitLab CI, Jenkins, ArgoCD, CircleCI pour tester et livrer en continu sans interruption.',
            ],
        ],
    ],
];
