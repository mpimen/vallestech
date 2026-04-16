<?php
$pageTitle = 'Avisos';
$pageSubtitle = 'Comunicaciones internas, recordatorios y coordinación docente.';
$pageStylesheet = '/assets/css/teacher-notices.css';
$currentSection = 'notices';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="notices-hero">
    <article class="notices-hero__card">
        <div>
            <p class="notices-hero__eyebrow">Centro de avisos</p>
            <h2>Mensajes clave del entorno docente</h2>
            <p>
                Los teacher portals suelen integrar anuncios, recordatorios, calendario y tareas como núcleo del trabajo diario del profesorado. Esta vista sigue ese enfoque operativo. [web:128][web:132]
            </p>
        </div>

        <div class="notices-summary">
            <div class="summary-pill">
                <strong>4</strong>
                <span>Nuevos avisos</span>
            </div>
            <div class="summary-pill">
                <strong>2</strong>
                <span>Prioritarios</span>
            </div>
            <div class="summary-pill">
                <strong>7</strong>
                <span>Pendientes de leer</span>
            </div>
        </div>
    </article>
</section>

<section class="notices-layout">
    <div class="notices-list">
        <article class="notice-card notice-card--important">
            <div class="notice-card__top">
                <span class="notice-badge notice-badge--important">Prioritario</span>
                <span class="notice-date">Hoy · 08:15</span>
            </div>
            <h3>Revisión de fechas de evaluación final</h3>
            <p>
                Se han actualizado varias fechas de cierre y publicación de actas. Conviene revisar el calendario docente antes del final de semana.
            </p>
            <div class="notice-meta">
                <span>Jefatura de estudios</span>
                <strong>Acción recomendada</strong>
            </div>
        </article>

        <article class="notice-card">
            <div class="notice-card__top">
                <span class="notice-badge">General</span>
                <span class="notice-date">Ayer · 18:20</span>
            </div>
            <h3>Nuevo documento de coordinación</h3>
            <p>
                Está disponible una nueva guía interna para homogeneizar criterios de evaluación y entregas entre grupos.
            </p>
            <div class="notice-meta">
                <span>Coordinación académica</span>
                <strong>Documento adjunto</strong>
            </div>
        </article>

        <article class="notice-card">
            <div class="notice-card__top">
                <span class="notice-badge">Curso</span>
                <span class="notice-date">Esta semana</span>
            </div>
            <h3>Entrega masiva pendiente de corrección</h3>
            <p>
                El sistema registra un bloque de entregas abiertas en 2º DAW y recomienda priorizar su revisión.
            </p>
            <div class="notice-meta">
                <span>Desarrollo Web</span>
                <strong>Seguimiento activo</strong>
            </div>
        </article>
    </div>

    <aside class="notices-sidebar">
        <article class="sidebar-card">
            <h3>Etiquetas</h3>
            <ul>
                <li>Centro</li>
                <li>Coordinación</li>
                <li>Evaluación</li>
                <li>Grupos</li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Nota</h3>
            <p>
                Esta pantalla queda lista para conectarse después con avisos reales generados por coordinación o por la lógica de negocio.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>