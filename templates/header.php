<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Campus Virtual';
}

if (!isset($pageSubtitle)) {
    $pageSubtitle = '';
}

if (!isset($pageStylesheet)) {
    $pageStylesheet = '/assets/css/public.css';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Campus Virtual</title>
    <meta name="description" content="Portal académico del campus">
    <link rel="stylesheet" href="<?= htmlspecialchars($pageStylesheet) ?>">
</head>
<body>
<a class="skip-link" href="#main-content">Saltar al contenido</a>

<header class="site-header">
    <div class="container site-header__inner">
        <a href="/index.php" class="site-brand">
            <span class="site-brand__logo">CV</span>
            <span class="site-brand__text">
                <strong>Campus Virtual</strong>
                <small>Portal académico</small>
            </span>
        </a>

        <nav class="site-nav" aria-label="Navegación principal">
            <a href="/index.php">Inicio</a>
            <a href="/courses.php">Estudios</a>
            <a href="/announcements.php">Avisos</a>
            <a class="btn btn--small btn--primary" href="/login.php">Acceder</a>
        </nav>
    </div>
</header>

<main id="main-content" class="site-main">