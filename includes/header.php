<?php
/**
 * Header & HTML head template
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['site']['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($data['site']['tagline']) ?>">
    <meta name="author" content="Tech Hub">
    
    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($data['site']['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($data['site']['tagline']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($data['cybersecurity']['banner_image']) ?>">
    
    <!-- Favicon (SVG Shield) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2322d3ee' stroke-width='2'><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/></svg>">
    
    <!-- Google Fonts (Saira & Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Design System Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Animated Ambient Background Layer -->
    <div class="bg-canvas" aria-hidden="true">
        <div class="bg-grid"></div>
        <div class="bg-orb orb-1"></div>
        <div class="bg-orb orb-2"></div>
        <div class="bg-orb orb-3"></div>
    </div>
