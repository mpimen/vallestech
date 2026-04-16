<?php
$pageTitle = 'Profesores';
$pageSubtitle = 'Listado visual de docentes y áreas asignadas.';
$pageStylesheet = '/assets/css/admin-teachers.css';
$currentSection = 'teachers';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="entity-hero">
    <article class="entity-hero__card">
        <div>
            <p class="entity-hero__eyebrow">Profesorado</p>
            <h2>Gestión visual de docentes</h2>
            <p>
                Revisa profesores, departamentos y estado operativo de las cuentas docentes.
            </p>
        </div>

        <div class="entity-summary">
            <div class="entity-stat">
                <strong>36</strong>
                <span>Profesores totales</span>
            </div>
            <div class="entity-stat">
                <strong>34</strong>
                <span>Activos</span>
            </div>
        </div>
    </article>
</section>

<section class="entity-table-card">
    <div class="table-wrap">
        <table class="entity-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pedro Sánchez</td>
                    <td>pedro.sanchez@campus.local</td>
                    <td>Informática</td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Ayer · 18:20</td>
                </tr>
                <tr>
                    <td>Ana Ríos</td>
                    <td>ana.rios@campus.local</td>
                    <td>Sistemas</td>
                    <td><span class="status status--pending">Pendiente</span></td>
                    <td>Sin acceso</td>
                </tr>
                <tr>
                    <td>Miguel Torres</td>
                    <td>miguel.torres@campus.local</td>
                    <td>Redes</td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Hoy · 07:55</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>