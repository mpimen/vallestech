<?php
$pageTitle = 'Dashboard del alumno';
$pageSubtitle = 'Tu espacio personal de estudio y seguimiento académico.';
$pageStylesheet = '/assets/css/student-dashboard.css';
$currentSection = 'dashboard';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="stats-grid">
    <article class="stat-card">
        <p class="stat-card__label">Asignaturas activas</p>
        <strong>6</strong>
        <span>Semestre actual</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Tareas pendientes</p>
        <strong>4</strong>
        <span>2 con entrega próxima</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Media provisional</p>
        <strong>8.2</strong>
        <span>Rendimiento estable</span>
    </article>

    <article class="stat-card">
        <p class="stat-card__label">Asistencia</p>
        <strong>93%</strong>
        <span>Último seguimiento</span>
    </article>
</section>

<section class="dashboard-grid">
    <article class="panel panel--hero">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Hoy en el campus</p>
                <h2>Organiza tu jornada académica</h2>
            </div>
            <a href="/alumno/calendario.php" class="panel-link">Ver calendario</a>
        </div>

        <p class="panel__text">
            Tienes una entrega próxima, una sesión de laboratorio por la tarde y un nuevo aviso publicado por coordinación.
        </p>

        <div class="timeline-list">
            <div class="timeline-item">
                <strong>09:00 · Desarrollo Web</strong>
                <span>Revisión del proyecto del módulo frontend.</span>
            </div>
            <div class="timeline-item">
                <strong>12:00 · Seguridad en Redes</strong>
                <span>Entrega parcial de práctica de monitorización.</span>
            </div>
            <div class="timeline-item">
                <strong>16:00 · Laboratorio</strong>
                <span>Trabajo guiado sobre despliegue y servicios.</span>
            </div>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Próximas entregas</p>
                <h2>Tareas cercanas</h2>
            </div>
            <a href="/alumno/tareas.php" class="panel-link">Ver tareas</a>
        </div>

        <div class="task-list">
            <div class="task-item">
                <div>
                    <strong>Práctica de LDAP</strong>
                    <span>Seguridad en Redes</span>
                </div>
                <em>18 abril</em>
            </div>

            <div class="task-item">
                <div>
                    <strong>Interfaz del campus</strong>
                    <span>Desarrollo Web</span>
                </div>
                <em>21 abril</em>
            </div>

            <div class="task-item">
                <div>
                    <strong>Informe de auditoría</strong>
                    <span>Sistemas</span>
                </div>
                <em>24 abril</em>
            </div>
        </div>
    </article>
</section>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Asignaturas</p>
                <h2>Progreso del curso</h2>
            </div>
            <a href="/alumno/cursos.php" class="panel-link">Ver asignaturas</a>
        </div>

        <div class="progress-list">
            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>Desarrollo Web</strong>
                    <span>78%</span>
                </div>
                <div class="progress-bar"><span style="width: 78%;"></span></div>
            </div>

            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>Seguridad en Redes</strong>
                    <span>65%</span>
                </div>
                <div class="progress-bar"><span style="width: 65%;"></span></div>
            </div>

            <div class="progress-row">
                <div class="progress-row__head">
                    <strong>Administración de Sistemas</strong>
                    <span>84%</span>
                </div>
                <div class="progress-bar"><span style="width: 84%;"></span></div>
            </div>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Avisos</p>
                <h2>Comunicaciones recientes</h2>
            </div>
            <a href="/alumno/avisos.php" class="panel-link">Ver avisos</a>
        </div>

        <div class="notice-list">
            <div class="notice-item">
                <strong>Cambio de aula en la sesión de laboratorio</strong>
                <span>Actualizado hace 2 horas</span>
            </div>
            <div class="notice-item">
                <strong>Nueva guía para la entrega del proyecto final</strong>
                <span>Publicado ayer</span>
            </div>
            <div class="notice-item">
                <strong>Revisión del calendario de evaluaciones</strong>
                <span>Esta semana</span>
            </div>
        </div>
    </article>
</section>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Consulta rápida</p>
                <h2>Accesos directos</h2>
            </div>
        </div>

        <div class="quick-links">
            <a class="quick-link-card" href="/alumno/cursos.php">
                <strong>Asignaturas</strong>
                <span>Consulta materias y progreso.</span>
            </a>

            <a class="quick-link-card" href="/alumno/tareas.php">
                <strong>Tareas</strong>
                <span>Revisa entregas y prioridades.</span>
            </a>

            <a class="quick-link-card" href="/alumno/calificaciones.php">
                <strong>Calificaciones</strong>
                <span>Accede a resultados y notas.</span>
            </a>

            <a class="quick-link-card" href="/alumno/perfil.php">
                <strong>Perfil</strong>
                <span>Actualiza tu información personal.</span>
            </a>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Rendimiento</p>
                <h2>Resumen académico</h2>
            </div>
            <a href="/alumno/calificaciones.php" class="panel-link">Ver notas</a>
        </div>

        <div class="notice-list">
            <div class="notice-item">
                <strong>Media actual: 8.2</strong>
                <span>Buen ritmo general del semestre.</span>
            </div>
            <div class="notice-item">
                <strong>Asignatura destacada: Documentación Técnica</strong>
                <span>Mejor resultado provisional con 8.8.</span>
            </div>
            <div class="notice-item">
                <strong>Área a reforzar: Seguridad en Redes</strong>
                <span>Conviene priorizar la próxima entrega.</span>
            </div>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>