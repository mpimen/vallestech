<?php
$pageTitle = 'Dashboard';
$pageSubtitle = 'Resumen general de usuarios, roles y estado del portal.';
$pageStylesheet = '/assets/css/admin-dashboard.css';
$currentSection = 'dashboard';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="admin-hero">
    <article class="admin-hero__card">
        <div>
            <p class="admin-hero__eyebrow">Administración general</p>
            <h2>Centro de control del campus</h2>
            <p>
                Gestiona usuarios, altas, roles y actividad general desde una vista clara y operativa.
            </p>
        </div>

        <div class="admin-hero__stats">
            <div class="admin-stat">
                <strong>428</strong>
                <span>Usuarios activos</span>
            </div>
            <div class="admin-stat">
                <strong>36</strong>
                <span>Profesores</span>
            </div>
            <div class="admin-stat">
                <strong>392</strong>
                <span>Alumnos</span>
            </div>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <div class="panel-head">
            <div>
                <p class="panel-head__eyebrow">Actividad reciente</p>
                <h2>Últimos movimientos</h2>
            </div>
        </div>

        <ul class="activity-list">
            <li class="activity-item">
                <strong>Nuevo profesor creado</strong>
                <span>Ana Ríos · Departamento de Informática</span>
            </li>
            <li class="activity-item">
                <strong>Alta masiva de alumnos</strong>
                <span>Grupo 1º ASIR · 28 registros importados</span>
            </li>
            <li class="activity-item">
                <strong>Rol actualizado</strong>
                <span>Coordinador académico con permisos extendidos</span>
            </li>
            <li class="activity-item">
                <strong>Cuenta desactivada</strong>
                <span>Usuario en estado inactivo por baja temporal</span>
            </li>
        </ul>
    </article>

    <aside class="admin-side">
        <article class="side-card">
            <h3>Acciones rápidas</h3>
            <ul>
                <li><a href="/admin/crear-usuario.php">Crear nuevo usuario</a></li>
                <li><a href="/admin/alumnos.php">Ver alumnos</a></li>
                <li><a href="/admin/profesores.php">Ver profesores</a></li>
            </ul>
        </article>

        <article class="side-card">
            <h3>Estado del sistema</h3>
            <p>Portal visual operativo y preparado para conectar autenticación, sesión y permisos reales.</p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>