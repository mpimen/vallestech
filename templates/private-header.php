<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Panel privado';
}

if (!isset($pageSubtitle)) {
    $pageSubtitle = '';
}

if (!isset($pageStylesheet)) {
    $pageStylesheet = '/assets/css/student-dashboard.css';
}

if (!isset($currentSection)) {
    $currentSection = 'dashboard';
}

if (!isset($userName)) {
    $userName = 'Usuario Demo';
}

if (!isset($userRole)) {
    $userRole = 'Alumno';
}

$normalizedRole = mb_strtolower($userRole, 'UTF-8');

if ($normalizedRole === 'profesor') {
    $menuItems = [
        'dashboard' => ['label' => 'Dashboard', 'href' => '/profesor/dashboard.php'],
        'courses' => ['label' => 'Asignaturas', 'href' => '/profesor/cursos.php'],
        'tasks' => ['label' => 'Tareas', 'href' => '/profesor/tareas.php'],
        'grades' => ['label' => 'Calificaciones', 'href' => '/profesor/calificaciones.php'],
        'calendar' => ['label' => 'Calendario', 'href' => '/profesor/calendario.php'],
        'notices' => ['label' => 'Avisos', 'href' => '/profesor/avisos.php'],
        'profile' => ['label' => 'Perfil', 'href' => '/profesor/perfil.php'],
    ];
} elseif ($normalizedRole === 'admin' || $normalizedRole === 'administrador') {
    $menuItems = [
        'dashboard' => ['label' => 'Dashboard', 'href' => '/admin/dashboard.php'],
        'users' => ['label' => 'Usuarios', 'href' => '/admin/usuarios.php'],
        'create-user' => ['label' => 'Crear usuario', 'href' => '/admin/crear-usuario.php'],
        'students' => ['label' => 'Alumnos', 'href' => '/admin/alumnos.php'],
        'teachers' => ['label' => 'Profesores', 'href' => '/admin/profesores.php'],
        'profile' => ['label' => 'Perfil', 'href' => '/admin/perfil.php'],
    ];
} else {
    $menuItems = [
        'dashboard' => ['label' => 'Dashboard', 'href' => '/alumno/dashboard.php'],
        'courses' => ['label' => 'Asignaturas', 'href' => '/alumno/cursos.php'],
        'tasks' => ['label' => 'Tareas', 'href' => '/alumno/tareas.php'],
        'grades' => ['label' => 'Calificaciones', 'href' => '/alumno/calificaciones.php'],
        'calendar' => ['label' => 'Calendario', 'href' => '/alumno/calendario.php'],
        'notices' => ['label' => 'Avisos', 'href' => '/alumno/avisos.php'],
        'profile' => ['label' => 'Perfil', 'href' => '/alumno/perfil.php'],
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Campus Virtual</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($pageStylesheet) ?>">
</head>
<body>
<a class="skip-link" href="#main-content">Saltar al contenido</a>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar__brand">
            <a href="/index.php" class="brand">
                <span class="brand__mark">CV</span>
                <span class="brand__copy">
                    <strong>Campus Virtual</strong>
                    <small><?= htmlspecialchars($userRole) ?></small>
                </span>
            </a>
        </div>

        <nav class="sidebar__nav" aria-label="Navegación privada">
            <ul>
                <?php foreach ($menuItems as $key => $item): ?>
                    <li>
                        <a href="<?= htmlspecialchars($item['href']) ?>" class="<?= $currentSection === $key ? 'is-active' : '' ?>">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <li>
                    <a href="/logout.php" class="logout-link">Cerrar sesión</a>
                </li>
            </ul>
        </nav>

        <div class="sidebar__footer">
            <a href="/index.php">Volver a la parte pública</a>
        </div>
    </aside>

    <div class="dashboard-main">
        <header class="dashboard-topbar">
            <div>
                <p class="dashboard-topbar__eyebrow"><?= htmlspecialchars($userRole) ?></p>
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <?php if ($pageSubtitle !== ''): ?>
                    <p class="dashboard-topbar__subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>

            <div class="dashboard-user">
                <span class="dashboard-user__avatar"><?= htmlspecialchars(mb_substr($userName, 0, 1)) ?></span>
                <div>
                    <strong><?= htmlspecialchars($userName) ?></strong>
                    <small><?= htmlspecialchars($userRole) ?></small>
                </div>
            </div>
        </header>

        <main id="main-content" class="dashboard-content">