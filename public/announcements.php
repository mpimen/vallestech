<?php
$pageTitle = 'Avisos';
$pageSubtitle = 'Noticias y comunicaciones del campus';
$pageStylesheet = '/assets/css/announcements.css';

include __DIR__ . '/../templates/header.php';
?>

<section class="announcements-hero">
    <div class="container announcements-hero__inner">
        <span class="badge">Comunicaciones</span>
        <h1>Avisos importantes del centro y novedades del campus.</h1>
        <p>
            Un espacio claro para reunir novedades académicas, recordatorios institucionales y mensajes relevantes antes de iniciar sesión.
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
                    Durante esta semana se revisarán las fechas de varias actividades y entregas generales del centro. La planificación final se publicará en el calendario oficial.
                </p>
                <div class="announcement-card__meta">Coordinación académica · Hoy</div>
            </article>

            <article class="announcement-card">
                <h2>Información sobre matrículas</h2>
                <p>
                    Secretaría publicará nuevas instrucciones para el siguiente periodo de matrícula, con detalle de documentación y plazos.
                </p>
                <div class="announcement-card__meta">Secretaría · Ayer</div>
            </article>

            <article class="announcement-card">
                <h2>Mantenimiento del campus virtual</h2>
                <p>
                    Se realizarán tareas de mejora sobre la plataforma en una franja de baja actividad para optimizar la experiencia general del portal.
                </p>
                <div class="announcement-card__meta">Administración web · Esta semana</div>
            </article>

            <article class="announcement-card">
                <h2>Nuevas actividades del centro</h2>
                <p>
                    Se incorporarán nuevas sesiones formativas y actividades complementarias orientadas a la comunidad académica.
                </p>
                <div class="announcement-card__meta">Vida universitaria · Esta semana</div>
            </article>
        </div>

        <aside class="announcements-sidebar">
            <div class="sidebar-card">
                <h3>Categorías</h3>
                <ul>
                    <li>Académico</li>
                    <li>Secretaría</li>
                    <li>Campus virtual</li>
                    <li>Eventos</li>
                </ul>
            </div>

            <div class="sidebar-card">
                <h3>Recordatorio</h3>
                <p>
                    Esta sección está pensada para mostrar información pública útil antes de acceder al área privada del campus.
                </p>
            </div>
        </aside>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>