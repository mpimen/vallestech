<?php
$pageTitle = 'Alumnos';
$pageSubtitle = 'Listado y seguimiento visual de estudiantes.';
$pageStylesheet = '/assets/css/admin-students.css';
$currentSection = 'students';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="entity-hero">
    <article class="entity-hero__card">
        <div>
            <p class="entity-hero__eyebrow">Alumnado</p>
            <h2>Gestión visual de estudiantes</h2>
            <p>
                Consulta rápidamente el estado, grupo y actividad reciente de los alumnos registrados.
            </p>
        </div>

        <div class="entity-summary">
            <div class="entity-stat">
                <strong>392</strong>
                <span>Alumnos totales</span>
            </div>
            <div class="entity-stat">
                <strong>374</strong>
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
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>María López</td>
                    <td>maria.lopez@campus.local</td>
                    <td>2º DAW</td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Hoy · 08:42</td>
                </tr>
                <tr>
                    <td>Javier Martín</td>
                    <td>javier.martin@campus.local</td>
                    <td>1º ASIR</td>
                    <td><span class="status status--inactive">Inactivo</span></td>
                    <td>Hace 7 días</td>
                </tr>
                <tr>
                    <td>Lucía Herrera</td>
                    <td>lucia.herrera@campus.local</td>
                    <td>2º ASIR</td>
                    <td><span class="status status--active">Activo</span></td>
                    <td>Ayer · 19:03</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>