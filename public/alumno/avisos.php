<?php
$pageTitle = 'Avisos';
$pageSubtitle = 'Comunicaciones académicas e institucionales relevantes.';
$pageStylesheet = '/assets/css/student-notices.css';
$currentSection = 'notices';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="notices-hero">
    <article class="notices-hero__card">
        <div>
            <p class="notices-hero__eyebrow">Comunicaciones</p>
            <h2>Todo lo importante, en un solo sitio</h2>
            <p>
                Esta vista centraliza avisos, anuncios y recordatorios para facilitar el seguimiento académico diario.
            </p>
        </div>

        <div class="notices-summary">
            <div class="summary-pill">
                <strong>3</strong>
                <span>Nuevos avisos</span>
            </div>
            <div class="summary-pill">
                <strong>1</strong>
                <span>Urgente</span>
            </div>
            <div class="summary-pill">
                <strong>12</strong>
                <span>Histórico reciente</span>
            </div>
        </div>
    </article>
</section>

<section class="notices-layout">
    <div class="notices-list">
        <article class="notice-card notice-card--important">
            <div class="notice-card__top">
                <span class="notice-badge notice-badge--important">Importante</span>
                <span class="notice-date">Hoy · 09:10</span>
            </div>
            <h3>Cambio de aula para la práctica de laboratorio</h3>
            <p>
                La sesión de esta tarde se traslada al laboratorio B-04. El acceso al material sigue disponible desde el campus virtual.
            </p>
            <div class="notice-meta">
                <span>Coordinación académica</span>
                <strong>Lectura recomendada</strong>
            </div>
        </article>

        <article class="notice-card">
            <div class="notice-card__top">
                <span class="notice-badge">General</span>
                <span class="notice-date">Ayer · 17:40</span>
            </div>
            <h3>Nueva guía para la entrega del proyecto final</h3>
            <p>
                Ya está disponible una versión revisada de la guía de proyecto con aclaraciones sobre estructura, defensa y evaluación.
            </p>
            <div class="notice-meta">
                <span>Desarrollo Web</span>
                <strong>Nuevo documento</strong>
            </div>
        </article>

        <article class="notice-card">
            <div class="notice-card__top">
                <span class="notice-badge">Recordatorio</span>
                <span class="notice-date">Esta semana</span>
            </div>
            <h3>Revisión del calendario de evaluaciones</h3>
            <p>
                Se actualizarán varias fechas del tramo final de evaluación. Conviene revisar el calendario del alumno durante los próximos días.
            </p>
            <div class="notice-meta">
                <span>Secretaría docente</span>
                <strong>Calendario afectado</strong>
            </div>
        </article>

        <article class="notice-card">
            <div class="notice-card__top">
                <span class="notice-badge">Curso</span>
                <span class="notice-date">Esta semana</span>
            </div>
            <h3>Entrega habilitada en el módulo de Seguridad</h3>
            <p>
                Ya se puede subir la práctica LDAP desde la sección de tareas. El sistema mostrará el estado de la entrega una vez enviada.
            </p>
            <div class="notice-meta">
                <span>Seguridad en Redes</span>
                <strong>Entrega abierta</strong>
            </div>
        </article>
    </div>

    <aside class="notices-sidebar">
        <article class="sidebar-card">
            <h3>Filtros sugeridos</h3>
            <ul>
                <li>Importantes</li>
                <li>Asignaturas</li>
                <li>Secretaría</li>
                <li>Recordatorios</li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Consejo</h3>
            <p>
                Usa esta vista como centro de lectura rápida y deja el correo para comunicaciones más extensas o formales.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>