<?php
$pageTitle = 'Estudios';
$pageSubtitle = 'Oferta académica del campus';
$pageStylesheet = '/assets/css/courses.css';

include __DIR__ . '/../templates/header.php';
?>

<section class="courses-hero">
    <div class="container courses-hero__grid">
        <div class="courses-hero__content">
            <span class="badge">Oferta académica</span>
            <h1>Encuentra el itinerario que mejor encaja contigo.</h1>
            <p>
                Descubre programas orientados a tecnología, sistemas, desarrollo y competencias digitales con una estructura clara y enfoque práctico.
            </p>
        </div>

        <aside class="courses-hero__panel">
            <h2>Información rápida</h2>
            <ul>
                <li>Modalidad presencial y semipresencial</li>
                <li>Itinerarios técnicos y aplicados</li>
                <li>Seguimiento desde el campus virtual</li>
            </ul>
        </aside>
    </div>
</section>

<section class="courses-section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="section-eyebrow">Programas destacados</p>
                <h2>Áreas formativas</h2>
            </div>
        </div>

        <div class="course-grid">
            <article class="course-card">
                <span class="course-card__tag">Tecnología</span>
                <h3>Desarrollo de Aplicaciones Web</h3>
                <p>Formación centrada en frontend, backend, bases de datos y despliegue web.</p>
                <div class="course-card__meta">Duración estimada: 2 cursos</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Infraestructura</span>
                <h3>Administración de Sistemas y Redes</h3>
                <p>Entorno orientado a servidores, redes, virtualización, seguridad y monitorización.</p>
                <div class="course-card__meta">Duración estimada: 2 cursos</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Datos</span>
                <h3>Analítica y Gestión de Información</h3>
                <p>Introducción a tratamiento de datos, modelado, visualización y uso aplicado.</p>
                <div class="course-card__meta">Duración estimada: 1-2 cursos</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Ciberseguridad</span>
                <h3>Seguridad de Sistemas</h3>
                <p>Buenas prácticas, hardening, control de acceso, auditoría y defensa básica.</p>
                <div class="course-card__meta">Especialización técnica</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Software</span>
                <h3>Programación Orientada a Proyectos</h3>
                <p>Diseño modular, trabajo por entregas y organización profesional del código.</p>
                <div class="course-card__meta">Aprendizaje práctico</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Competencias</span>
                <h3>Herramientas Digitales</h3>
                <p>Recursos transversales para productividad, documentación y trabajo colaborativo.</p>
                <div class="course-card__meta">Base común</div>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>