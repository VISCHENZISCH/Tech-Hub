<?php
/**
 * Tech Hub - Déconnexion
 */
require_once __DIR__ . '/includes/auth.php';
logoutUser();
header('Location: login.php?logged_out=1');
exit;
