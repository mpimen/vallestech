<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];

$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Profesor'));
$role = trim((string)($currentUser['role'] ?? 'Profesor'));

$pageTitle = 'Dashboard del profesor';
$pageSubtitle = 'Vista general de actividad docente, grupos y seguimiento.';
$pageStylesheet = '/assets/css/teacher-dashboard.css';
$currentSection = 'dashboard';
$userName = $displayName !== '' ? $displayName : 'Profesor';
$userRole = $role !== '' ? $role : 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="stats-grid">
    <article class="stat-card">
        <p class="stat-card__label">Grupos activos</p>
        <strong>5</strong>
        <span>Semestre en curso</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Entregas por revisar</p>
        <strong>18</strong>
        <span>Últimas 48 horas</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Sesiones hoy</p>
        <strong>3</strong>
        <span>Docencia planificada</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Mensajes nuevos</p>
        <strong>7</strong>
        <span>Coordinación y alumnado</span>
    </article>
</section>

<section class="dashboard-grid">
    <article class="panel panel--hero">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Resumen docente</p>
                <h2>Controla tu actividad del día</h2>
            </div>
            <a href="#" class="panel-link">Ver agenda</a>
        </div>

        <p class="panel__text">
            Hoy tienes dos clases teóricas, una revisión de entregas y una reunión breve de coordinación de departamento.
        </p>

        <div class="timeline-list">
            <div class="timeline-item">
                <strong>08:30 · Revisión de tareas</strong>
                <span>Corrección de entregas del módulo de redes.</span>
            </div>
            <div class="timeline-item">
                <strong>11:00 · Clase DAW</strong>
                <span>Sesión de arquitectura PHP y organización del proyecto.</span>
            </div>
            <div class="timeline-item">
                <strong>15:30 · Reunión de coordinación</strong>
                <span>Actualización de calendarios y seguimiento de grupos.</span>
            </div>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Seguimiento</p>
                <h2>Por revisar</h2>
            </div>
        </div>

        <div class="task-list">
            <div class="task-item">
                <div>
                    <strong>Práctica de despliegue</strong>
                    <span>2º ASIR · 8 entregas</span>
                </div>
                <em>Pendiente</em>
            </div>

            <div class="task-item">
                <div>
                    <strong>Proyecto web del campus</strong>
                    <span>2º DAW · 6 entregas</span>
                </div>
                <em>En curso</em>
            </div>

            <div class="task-item">
                <div>
                    <strong>Actividad de monitorización</strong>
                    <span>1º Sistemas · 4 entregas</span>
                </div>
                <em>Hoy</em>
            </div>
        </div>
    </article>
</section>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Rendimiento</p>
                <h2>Estado de grupos</h2>
            </div>
        </div>

        <div class="progress-list">
            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>2º DAW</strong>
                    <span>82% progreso</span>
                </div>
                <div class="progress-bar"><span style="width: 82%;"></span></div>
            </div>

            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>2º ASIR</strong>
                    <span>74% progreso</span>
                </div>
                <div class="progress-bar"><span style="width: 74%;"></span></div>
            </div>

            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>1º Sistemas</strong>
                    <span>69% progreso</span>
                </div>
                <div class="progress-bar"><span style="width: 69%;"></span></div>
            </div>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Avisos internos</p>
                <h2>Centro y coordinación</h2>
            </div>
        </div>

        <div class="notice-list">
            <div class="notice-item">
                <strong>Actualización de fechas de evaluación</strong>
                <span>Publicado hoy</span>
            </div>
            <div class="notice-item">
                <strong>Nuevo comunicado del equipo directivo</strong>
                <span>Ayer</span>
            </div>
            <div class="notice-item">
                <strong>Revisión de documentación docente</strong>
                <span>Esta semana</span>
            </div>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>