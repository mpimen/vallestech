<?php
$pageTitle = 'Calificaciones';
$pageSubtitle = 'Consulta tus notas, estado de evaluación y resultados por asignatura.';
$pageStylesheet = '/assets/css/student-grades.css';
$currentSection = 'grades';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="grades-summary">
    <article class="summary-card">
        <div>
            <p class="summary-card__eyebrow">Resultados académicos</p>
            <h2>Tu evolución general del semestre</h2>
            <p>
                Revisa el estado actual de tus asignaturas con una vista clara de notas parciales, entregas y media acumulada.
            </p>
        </div>

        <div class="summary-side">
            <strong>8.2</strong>
            <span>Media global</span>
        </div>
    </article>
</section>

<section class="grades-table-section">
    <article class="grades-table-card">
        <div class="table-head">
            <div>
                <p class="table-head__eyebrow">Detalle por asignatura</p>
                <h2>Notas y evaluación</h2>
            </div>
        </div>

        <div class="grades-table-wrap">
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Asignatura</th>
                        <th>Actividad</th>
                        <th>Entrega</th>
                        <th>Estado</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Desarrollo Web</td>
                        <td>Proyecto frontend</td>
                        <td>14 abril</td>
                        <td><span class="status status--done">Corregido</span></td>
                        <td><strong>8.7</strong></td>
                    </tr>
                    <tr>
                        <td>Seguridad en Redes</td>
                        <td>Práctica LDAP</td>
                        <td>18 abril</td>
                        <td><span class="status status--pending">Pendiente</span></td>
                        <td><strong>-</strong></td>
                    </tr>
                    <tr>
                        <td>Administración de Sistemas</td>
                        <td>Informe técnico</td>
                        <td>10 abril</td>
                        <td><span class="status status--done">Corregido</span></td>
                        <td><strong>7.9</strong></td>
                    </tr>
                    <tr>
                        <td>Bases de Datos</td>
                        <td>Modelo relacional</td>
                        <td>12 abril</td>
                        <td><span class="status status--done">Corregido</span></td>
                        <td><strong>8.4</strong></td>
                    </tr>
                    <tr>
                        <td>Proyecto Integrado</td>
                        <td>Entrega parcial</td>
                        <td>21 abril</td>
                        <td><span class="status status--active">En curso</span></td>
                        <td><strong>-</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>