<?php
$pageTitle = 'Calendario';
$pageSubtitle = 'Planificación semanal, entregas y eventos académicos.';
$pageStylesheet = '/assets/css/student-calendar.css';
$currentSection = 'calendar';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="calendar-hero">
    <article class="calendar-hero__card">
        <div>
            <p class="calendar-hero__eyebrow">Planificación académica</p>
            <h2>Tu agenda semanal del campus</h2>
            <p>
                Consulta clases, entregas y recordatorios desde una vista clara, pensada para priorizar lo importante sin sobrecargar la pantalla.
            </p>
        </div>

        <div class="calendar-hero__stats">
            <div class="calendar-stat">
                <strong>5</strong>
                <span>Eventos hoy</span>
            </div>
            <div class="calendar-stat">
                <strong>2</strong>
                <span>Entregas esta semana</span>
            </div>
            <div class="calendar-stat">
                <strong>1</strong>
                <span>Reunión de tutoría</span>
            </div>
        </div>
    </article>
</section>

<section class="calendar-layout">
    <article class="calendar-panel">
        <div class="panel-head">
            <div>
                <p class="panel-head__eyebrow">Semana actual</p>
                <h2>Horario y actividades</h2>
            </div>
            <span class="panel-head__range">15 - 21 abril</span>
        </div>

        <div class="calendar-grid">
            <div class="calendar-column">
                <div class="calendar-column__day">
                    <strong>Lunes</strong>
                    <span>15 abril</span>
                </div>
                <div class="calendar-entry">
                    <strong>09:00 · Desarrollo Web</strong>
                    <span>Sala A-12</span>
                </div>
                <div class="calendar-entry">
                    <strong>12:00 · Tutoría de proyecto</strong>
                    <span>Seguimiento parcial</span>
                </div>
            </div>

            <div class="calendar-column">
                <div class="calendar-column__day">
                    <strong>Martes</strong>
                    <span>16 abril</span>
                </div>
                <div class="calendar-entry">
                    <strong>10:00 · Sistemas</strong>
                    <span>Laboratorio B-04</span>
                </div>
                <div class="calendar-entry">
                    <strong>17:00 · Entrega parcial</strong>
                    <span>Proyecto integrado</span>
                </div>
            </div>

            <div class="calendar-column">
                <div class="calendar-column__day">
                    <strong>Miércoles</strong>
                    <span>17 abril</span>
                </div>
                <div class="calendar-entry">
                    <strong>09:30 · Bases de Datos</strong>
                    <span>Aula C-10</span>
                </div>
                <div class="calendar-entry">
                    <strong>13:00 · Documentación Técnica</strong>
                    <span>Revisión de memoria</span>
                </div>
            </div>

            <div class="calendar-column">
                <div class="calendar-column__day">
                    <strong>Jueves</strong>
                    <span>18 abril</span>
                </div>
                <div class="calendar-entry calendar-entry--accent">
                    <strong>11:59 · Práctica LDAP</strong>
                    <span>Fecha límite de entrega</span>
                </div>
                <div class="calendar-entry">
                    <strong>16:00 · Seguridad en Redes</strong>
                    <span>Sala de prácticas</span>
                </div>
            </div>

            <div class="calendar-column">
                <div class="calendar-column__day">
                    <strong>Viernes</strong>
                    <span>19 abril</span>
                </div>
                <div class="calendar-entry">
                    <strong>08:30 · Revisión general</strong>
                    <span>Trabajo autónomo</span>
                </div>
                <div class="calendar-entry">
                    <strong>12:30 · Coordinación</strong>
                    <span>Aviso del grupo</span>
                </div>
            </div>
        </div>
    </article>

    <aside class="calendar-sidebar">
        <article class="sidebar-card">
            <h3>Próximo recordatorio</h3>
            <p>Entrega de práctica LDAP el jueves antes de las 11:59.</p>
        </article>

        <article class="sidebar-card">
            <h3>Bloques del día</h3>
            <ul>
                <li>Mañana: clases presenciales</li>
                <li>Mediodía: trabajo en laboratorio</li>
                <li>Tarde: revisión de tareas</li>
            </ul>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>