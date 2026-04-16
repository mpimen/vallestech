<?php
$pageTitle = 'Tareas';
$pageSubtitle = 'Entregas pendientes, revisión y seguimiento de actividades.';
$pageStylesheet = '/assets/css/teacher-tasks.css';
$currentSection = 'tasks';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="tasks-hero">
    <article class="tasks-hero__card">
        <div>
            <p class="tasks-hero__eyebrow">Evaluación continua</p>
            <h2>Actividades pendientes de revisar</h2>
            <p>
                Prioriza correcciones, controla plazos y mantén una visión clara de la carga de trabajo docente.
            </p>
        </div>

        <div class="tasks-hero__stats">
            <div class="task-stat">
                <strong>18</strong>
                <span>Entregas por revisar</span>
            </div>
            <div class="task-stat">
                <strong>6</strong>
                <span>Urgentes</span>
            </div>
            <div class="task-stat">
                <strong>3</strong>
                <span>Corregidas hoy</span>
            </div>
        </div>
    </article>
</section>

<section class="tasks-list">
    <article class="task-card task-card--urgent">
        <div class="task-card__top">
            <span class="task-badge task-badge--urgent">Urgente</span>
            <span class="task-date">Hoy</span>
        </div>
        <h3>Proyecto del campus virtual</h3>
        <p>2º DAW · 6 entregas pendientes de revisión funcional y visual.</p>
        <div class="task-meta">
            <span>Desarrollo Web</span>
            <strong>Corrección prioritaria</strong>
        </div>
    </article>

    <article class="task-card">
        <div class="task-card__top">
            <span class="task-badge">En revisión</span>
            <span class="task-date">Esta semana</span>
        </div>
        <h3>Práctica de autenticación LDAP</h3>
        <p>2º ASIR · 8 entregas con validación de seguridad y estructura técnica.</p>
        <div class="task-meta">
            <span>Seguridad en Redes</span>
            <strong>Estado activo</strong>
        </div>
    </article>

    <article class="task-card task-card--done">
        <div class="task-card__top">
            <span class="task-badge task-badge--done">Completada</span>
            <span class="task-date">Ayer</span>
        </div>
        <h3>Informe de sistemas</h3>
        <p>1º Sistemas · Revisión ya cerrada y lista para publicar notas.</p>
        <div class="task-meta">
            <span>Administración de Sistemas</span>
            <strong>Corrección finalizada</strong>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>