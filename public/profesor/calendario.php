<?php
$pageTitle = 'Calendario';
$pageSubtitle = 'Horario docente, reuniones y planificación semanal.';
$pageStylesheet = '/assets/css/teacher-calendar.css';
$currentSection = 'calendar';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="calendar-hero">
    <article class="calendar-hero__card">
        <div>
            <p class="calendar-hero__eyebrow">Agenda docente</p>
            <h2>Tu planificación semanal</h2>
            <p>
                Reúne clases, correcciones, tutorías y coordinación en una sola vista operativa.
            </p>
        </div>

        <div class="calendar-summary">
            <div class="summary-card">
                <strong>3</strong>
                <span>Clases hoy</span>
            </div>
            <div class="summary-card">
                <strong>2</strong>
                <span>Tutorías</span>
            </div>
            <div class="summary-card">
                <strong>1</strong>
                <span>Reunión</span>
            </div>
        </div>
    </article>
</section>

<section class="calendar-board">
    <article class="calendar-panel">
        <div class="panel-head">
            <div>
                <p class="panel-head__eyebrow">Semana actual</p>
                <h2>Horario del profesorado</h2>
            </div>
            <span class="panel-head__range">15 - 21 abril</span>
        </div>

        <div class="calendar-grid">
            <div class="day-column">
                <div class="day-column__head">
                    <strong>Lunes</strong>
                    <span>15 abril</span>
                </div>
                <div class="event-card">
                    <strong>08:30 · 2º DAW</strong>
                    <span>Desarrollo Web</span>
                </div>
                <div class="event-card">
                    <strong>12:30 · Tutoría</strong>
                    <span>Proyecto integrado</span>
                </div>
            </div>

            <div class="day-column">
                <div class="day-column__head">
                    <strong>Martes</strong>
                    <span>16 abril</span>
                </div>
                <div class="event-card">
                    <strong>10:00 · 2º ASIR</strong>
                    <span>Seguridad en Redes</span>
                </div>
                <div class="event-card event-card--accent">
                    <strong>17:00 · Revisión</strong>
                    <span>Corrección de entregas</span>
                </div>
            </div>

            <div class="day-column">
                <div class="day-column__head">
                    <strong>Miércoles</strong>
                    <span>17 abril</span>
                </div>
                <div class="event-card">
                    <strong>09:30 · 1º Sistemas</strong>
                    <span>Administración de Sistemas</span>
                </div>
                <div class="event-card">
                    <strong>15:30 · Reunión</strong>
                    <span>Coordinación académica</span>
                </div>
            </div>

            <div class="day-column">
                <div class="day-column__head">
                    <strong>Jueves</strong>
                    <span>18 abril</span>
                </div>
                <div class="event-card">
                    <strong>11:00 · DAW</strong>
                    <span>Seguimiento de proyecto</span>
                </div>
                <div class="event-card">
                    <strong>16:00 · ASIR</strong>
                    <span>Práctica de autenticación</span>
                </div>
            </div>

            <div class="day-column">
                <div class="day-column__head">
                    <strong>Viernes</strong>
                    <span>19 abril</span>
                </div>
                <div class="event-card">
                    <strong>08:00 · Revisión global</strong>
                    <span>Preparación de notas</span>
                </div>
                <div class="event-card">
                    <strong>12:00 · Cierre semanal</strong>
                    <span>Planificación siguiente semana</span>
                </div>
            </div>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>