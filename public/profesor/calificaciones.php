<?php
$pageTitle = 'Calificaciones';
$pageSubtitle = 'Revisión de resultados, evaluación y publicación de notas.';
$pageStylesheet = '/assets/css/teacher-grades.css';
$currentSection = 'grades';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="grades-hero">
    <article class="grades-hero__card">
        <div>
            <p class="grades-hero__eyebrow">Evaluación</p>
            <h2>Seguimiento de resultados por grupo</h2>
            <p>
                Consulta el estado de corrección y preparación de calificaciones antes de su publicación.
            </p>
        </div>

        <div class="grades-summary">
            <div class="summary-stat">
                <strong>3</strong>
                <span>Grupos en revisión</span>
            </div>
            <div class="summary-stat">
                <strong>27</strong>
                <span>Notas preparadas</span>
            </div>
            <div class="summary-stat">
                <strong>8.1</strong>
                <span>Media global</span>
            </div>
        </div>
    </article>
</section>

<section class="grades-table-card">
    <div class="table-head">
        <div>
            <p class="table-head__eyebrow">Panel docente</p>
            <h2>Estado de calificación</h2>
        </div>
    </div>

    <div class="grades-table-wrap">
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Grupo</th>
                    <th>Actividad</th>
                    <th>Entregas</th>
                    <th>Estado</th>
                    <th>Media</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2º DAW</td>
                    <td>Proyecto frontend</td>
                    <td>32</td>
                    <td><span class="status status--active">En revisión</span></td>
                    <td><strong>8.3</strong></td>
                </tr>
                <tr>
                    <td>2º ASIR</td>
                    <td>Práctica LDAP</td>
                    <td>24</td>
                    <td><span class="status status--pending">Pendiente</span></td>
                    <td><strong>-</strong></td>
                </tr>
                <tr>
                    <td>1º Sistemas</td>
                    <td>Informe técnico</td>
                    <td>28</td>
                    <td><span class="status status--done">Publicado</span></td>
                    <td><strong>7.8</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>