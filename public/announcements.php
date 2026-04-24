<?php
$pageTitle = 'Avisos';
$pageSubtitle = 'Noticias y comunicaciones del campus';
$pageStylesheet = '/assets/css/announcements.css';

include __DIR__ . '/../templates/header.php';
?>

<section class="announcements-hero">
    <div class="container announcements-hero__inner">
        <span class="badge">Comunicaciones</span>
        <h1>Mantente al día con los avisos y novedades del centro.</h1>
        <p>
            Consulta información académica, recordatorios institucionales, incidencias del campus virtual y comunicaciones relevantes para alumnado y profesorado.
        </p>
    </div>
</section>

<section class="announcements-section">
    <div class="container announcements-layout">
        <div class="announcements-list">
            <article class="announcement-card announcement-card--highlight">
                <span class="announcement-card__label">Destacado</span>
                <h2>Actualización del calendario académico</h2>
                <p>
                    Durante esta semana se publicarán ajustes en fechas de actividades, entregas y sesiones programadas. La versión definitiva estará disponible en el calendario oficial del campus.
                </p>
                <div class="announcement-card__meta">Coordinación académica · Hoy</div>
            </article>

            <article class="announcement-card">
                <h2>Instrucciones para el próximo periodo de matrícula</h2>
                <p>
                    Secretaría compartirá en los próximos días la documentación requerida, los plazos de entrega y el procedimiento actualizado para formalizar la matrícula.
                </p>
                <div class="announcement-card__meta">Secretaría · Ayer</div>
            </article>

            <article class="announcement-card">
                <h2>Mantenimiento programado del campus virtual</h2>
                <p>
                    La plataforma realizará tareas de mejora y optimización en horario de baja actividad para reforzar la estabilidad y el rendimiento del servicio.
                </p>
                <div class="announcement-card__meta">Administración web · Esta semana</div>
            </article>

            <article class="announcement-card">
                <h2>Nuevas actividades y sesiones complementarias</h2>
                <p>
                    El centro incorporará nuevas propuestas formativas, charlas y actividades de apoyo vinculadas al desarrollo académico y profesional del alumnado.
                </p>
                <div class="announcement-card__meta">Vida académica · Esta semana</div>
            </article>
        </div>

        <aside class="announcements-sidebar">
            <div class="sidebar-card">
                <h3>Categorías</h3>
                <ul>
                    <li>Académico</li>
                    <li>Secretaría</li>
                    <li>Campus virtual</li>
                    <li>Actividades y eventos</li>
                </ul>
            </div>

            <div class="sidebar-card">
                <h3>Consulta rápida</h3>
                <p>
                    Revisa esta sección con frecuencia para conocer cambios de calendario, avisos del centro y comunicaciones importantes antes de acceder al área privada.
                </p>
            </div>
        </aside>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>