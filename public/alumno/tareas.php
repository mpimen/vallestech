<?php
$pageTitle = 'Mis tareas';
$pageSubtitle = 'Entregas, estados y prioridades del curso.';
$pageStylesheet = '/assets/css/student-tasks.css';
$currentSection = 'tasks';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="tasks-overview">
    <div class="overview-card">
        <div>
            <p class="overview-card__eyebrow">Seguimiento de entregas</p>
            <h2>Tu carga de trabajo de esta semana</h2>
            <p>
                Revisa qué tareas están pendientes, cuáles están en curso y qué entregas requieren atención inmediata.
            </p>
        </div>

        <div class="overview-metrics">
            <div class="metric-pill">
                <strong>4</strong>
                <span>Pendientes</span>
            </div>
            <div class="metric-pill">
                <strong>2</strong>
                <span>Urgentes</span>
            </div>
            <div class="metric-pill">
                <strong>1</strong>
                <span>Completada hoy</span>
            </div>
        </div>
    </div>
</section>

<section class="tasks-list">
    <article class="task-card task-card--urgent">
        <div class="task-card__top">
            <span class="task-status task-status--urgent">Urgente</span>
            <span class="task-date">Entrega: 18 abril</span>
        </div>
        <h3>Práctica de autenticación LDAP</h3>
        <p>Implementar login seguro y documentar flujo de acceso para el proyecto del campus.</p>
        <div class="task-meta">
            <span>Seguridad en Redes</span>
            <strong>Alta prioridad</strong>
        </div>
    </article>

    <article class="task-card task-card--active">
        <div class="task-card__top">
            <span class="task-status task-status--active">En curso</span>
            <span class="task-date">Entrega: 21 abril</span>
        </div>
        <h3>Interfaz pública del portal</h3>
        <p>Diseñar portada, página de estudios, avisos y acceso visual del campus virtual.</p>
        <div class="task-meta">
            <span>Desarrollo Web</span>
            <strong>Media prioridad</strong>
        </div>
    </article>

    <article class="task-card">
        <div class="task-card__top">
            <span class="task-status">Pendiente</span>
            <span class="task-date">Entrega: 24 abril</span>
        </div>
        <h3>Informe de auditoría</h3>
        <p>Preparar una memoria técnica con hallazgos, riesgos y recomendaciones sobre la práctica.</p>
        <div class="task-meta">
            <span>Administración de Sistemas</span>
            <strong>Revisión final</strong>
        </div>
    </article>

    <article class="task-card task-card--done">
        <div class="task-card__top">
            <span class="task-status task-status--done">Completada</span>
            <span class="task-date">Entregada hoy</span>
        </div>
        <h3>Modelo de base de datos</h3>
        <p>Diseño del esquema relacional del campus con usuarios, cursos, notas y auditoría.</p>
        <div class="task-meta">
            <span>Bases de Datos</span>
            <strong>Enviada</strong>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>