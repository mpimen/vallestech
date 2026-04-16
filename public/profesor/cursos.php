<?php
$pageTitle = 'Asignaturas';
$pageSubtitle = 'Materias, grupos y organización docente.';
$pageStylesheet = '/assets/css/teacher-courses.css';
$currentSection = 'courses';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="teacher-hero">
    <article class="teacher-hero__card">
        <div>
            <p class="teacher-hero__eyebrow">Docencia</p>
            <h2>Asignaturas y grupos a tu cargo</h2>
            <p>
                Esta vista resume la carga docente, el estado de los grupos y el avance general de cada materia.
            </p>
        </div>

        <div class="teacher-hero__stats">
            <div class="teacher-stat">
                <strong>5</strong>
                <span>Grupos activos</span>
            </div>
            <div class="teacher-stat">
                <strong>3</strong>
                <span>Materias principales</span>
            </div>
            <div class="teacher-stat">
                <strong>82%</strong>
                <span>Progreso medio</span>
            </div>
        </div>
    </article>
</section>

<section class="teacher-cards">
    <article class="course-card">
        <span class="course-card__tag">2º DAW</span>
        <h3>Desarrollo de Aplicaciones Web</h3>
        <p>Grupo orientado a arquitectura PHP, interfaces y proyectos modulares.</p>
        <div class="course-card__meta">
            <span>32 estudiantes</span>
            <strong>78% completado</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">2º ASIR</span>
        <h3>Seguridad en Redes</h3>
        <p>Prácticas sobre LDAP, control de acceso, monitorización y despliegue seguro.</p>
        <div class="course-card__meta">
            <span>24 estudiantes</span>
            <strong>74% completado</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">1º Sistemas</span>
        <h3>Administración de Sistemas</h3>
        <p>Infraestructura, virtualización, servicios y operación técnica del entorno.</p>
        <div class="course-card__meta">
            <span>28 estudiantes</span>
            <strong>69% completado</strong>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>