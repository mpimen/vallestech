<?php
$pageTitle = 'Mis asignaturas';
$pageSubtitle = 'Resumen de materias, profesorado y progreso del curso.';
$pageStylesheet = '/assets/css/student-courses.css';
$currentSection = 'courses';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="page-hero">
    <article class="hero-card">
        <div>
            <p class="hero-card__eyebrow">Área académica</p>
            <h2>Asignaturas activas del semestre</h2>
            <p>
                Consulta las materias en curso, su estado general y el profesorado responsable desde un panel pensado para orientarte rápido.
            </p>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <strong>6</strong>
                <span>Asignaturas</span>
            </div>
            <div class="hero-stat">
                <strong>3</strong>
                <span>Con entregas esta semana</span>
            </div>
            <div class="hero-stat">
                <strong>81%</strong>
                <span>Progreso medio</span>
            </div>
        </div>
    </article>
</section>

<section class="cards-grid">
    <article class="course-card">
        <span class="course-card__tag">Desarrollo</span>
        <h3>Desarrollo de Aplicaciones Web</h3>
        <p>Arquitectura PHP, componentes visuales, organización del proyecto y despliegue.</p>
        <div class="course-card__meta">
            <span>Profesor: Ana Martínez</span>
            <strong>78%</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">Seguridad</span>
        <h3>Seguridad en Redes</h3>
        <p>LDAP, hardening, servicios, monitorización y control de acceso en entornos reales.</p>
        <div class="course-card__meta">
            <span>Profesor: David Molina</span>
            <strong>65%</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">Infraestructura</span>
        <h3>Administración de Sistemas</h3>
        <p>Gestión de servidores, virtualización, automatización y servicios corporativos.</p>
        <div class="course-card__meta">
            <span>Profesor: Laura Sánchez</span>
            <strong>84%</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">Bases de datos</span>
        <h3>Modelado y Persistencia</h3>
        <p>Diseño relacional, consultas SQL y explotación de información académica.</p>
        <div class="course-card__meta">
            <span>Profesor: Sergio Ruiz</span>
            <strong>74%</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">Proyecto</span>
        <h3>Proyecto Integrado</h3>
        <p>Diseño global del campus virtual con entregas parciales y defensa final.</p>
        <div class="course-card__meta">
            <span>Profesor: Marta Gil</span>
            <strong>69%</strong>
        </div>
    </article>

    <article class="course-card">
        <span class="course-card__tag">Transversal</span>
        <h3>Documentación Técnica</h3>
        <p>Memorias, diagramas, justificación técnica y presentación profesional del trabajo.</p>
        <div class="course-card__meta">
            <span>Profesor: Clara Núñez</span>
            <strong>88%</strong>
        </div>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>