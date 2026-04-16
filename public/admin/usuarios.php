<?php
$pageTitle = 'Usuarios';
$pageSubtitle = 'Gestión general de cuentas, estados y roles.';
$pageStylesheet = '/assets/css/admin-users.css';
$currentSection = 'users';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="users-hero">
    <article class="users-hero__card">
        <div>
            <p class="users-hero__eyebrow">Gestión central</p>
            <h2>Listado general de usuarios</h2>
            <p>
                Administra alumnos y profesores desde una vista unificada con filtros, estados y control por rol.
            </p>
        </div>

        <div class="users-summary">
            <div class="summary-box">
                <strong>428</strong>
                <span>Total usuarios</span>
            </div>
            <div class="summary-box">
                <strong>401</strong>
                <span>Activos</span>
            </div>
            <div class="summary-box">
                <strong>27</strong>
                <span>Pendientes o inactivos</span>
            </div>
        </div>
    </article>
</section>

<section class="users-table-card">
    <div class="table-toolbar">
        <input type="text" class="table-search" placeholder="Buscar por nombre, correo o rol">
        <a href="/admin/crear-usuario.php" class="btn btn--primary">Nuevo usuario</a>
    </div>

    <div class="table-wrap">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>María López</td>
                    <td>maria.lopez@campus.local</td>
                    <td><span class="role-badge role-badge--student">Alumno</span></td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Hoy · 08:42</td>
                </tr>
                <tr>
                    <td>Pedro Sánchez</td>
                    <td>pedro.sanchez@campus.local</td>
                    <td><span class="role-badge role-badge--teacher">Profesor</span></td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Ayer · 18:20</td>
                </tr>
                <tr>
                    <td>Ana Ríos</td>
                    <td>ana.rios@campus.local</td>
                    <td><span class="role-badge role-badge--teacher">Profesor</span></td>
                    <td><span class="status status--pending">Pendiente</span></td>
                    <td>Sin acceso</td>
                </tr>
                <tr>
                    <td>Javier Martín</td>
                    <td>javier.martin@campus.local</td>
                    <td><span class="role-badge role-badge--student">Alumno</span></td>
                    <td><span class="status status--inactive">Inactivo</span></td>
                    <td>Hace 7 días</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>