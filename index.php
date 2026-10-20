<?php
/**
 * Tech Hub - Architecture PHP Native
 * 
 * Version PHP pure sans framework lourd, respectant intégralement
 * le design cyberpunk glassmorphism, la réactivité et les performances.
 */

// Strict error reporting in development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load icon rendering helper & auth session
require_once __DIR__ . '/includes/icons.php';
require_once __DIR__ . '/includes/auth.php';

// Load application data store
$data = require __DIR__ . '/data/content.php';

// Assemble page components
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navigation.php';
require __DIR__ . '/includes/hero.php';
require __DIR__ . '/includes/cybersecurity.php';
require __DIR__ . '/includes/roadmaps.php';
require __DIR__ . '/includes/technologies.php';
require __DIR__ . '/includes/infrastructure.php';
require __DIR__ . '/includes/footer.php';
