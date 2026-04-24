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
                Formación técnica orientada a tecnología, sistemas, desarrollo y competencias digitales. Programas con estructura clara, enfoque práctico y seguimiento desde el campus virtual.
            </p>
        </div>

        <aside class="courses-hero__panel">
            <h2>¿Cómo funciona?</h2>
            <ul>
                <li>
                    <strong>Modalidad presencial y semipresencial</strong><br>
                    <span>Elige el formato que mejor se adapta a tu ritmo.</span>
                </li>
                <li>
                    <strong>Itinerarios técnicos y aplicados</strong><br>
                    <span>Cada programa está orientado a salidas profesionales reales.</span>
                </li>
                <li>
                    <strong>Seguimiento desde el campus virtual</strong><br>
                    <span>Accede a tus recursos, tareas y notas en cualquier momento.</span>
                </li>
            </ul>
        </aside>
    </div>
</section>

<section class="courses-section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="section-eyebrow">Programas disponibles</p>
                <h2>Áreas formativas del centro</h2>
            </div>
        </div>

        <div class="course-grid">
            <article class="course-card">
                <span class="course-card__tag">Tecnología</span>
                <h3>Desarrollo de Aplicaciones Web</h3>
                <p>Frontend, backend, bases de datos y despliegue. Aprenderás a construir aplicaciones completas desde cero.</p>
                <div class="course-card__meta">2 cursos &middot; Ciclo Superior</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Infraestructura</span>
                <h3>Administración de Sistemas y Redes</h3>
                <p>Servidores, redes, virtualización, seguridad y monitorización de entornos profesionales.</p>
                <div class="course-card__meta">2 cursos &middot; Ciclo Superior</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Datos</span>
                <h3>Analítica y Gestión de Información</h3>
                <p>Tratamiento, modelado y visualización de datos aplicados a entornos empresariales reales.</p>
                <div class="course-card__meta">1–2 cursos &middot; Especialización</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Ciberseguridad</span>
                <h3>Seguridad de Sistemas</h3>
                <p>Hardening, control de acceso, auditoría y defensa de infraestructuras frente a amenazas.</p>
                <div class="course-card__meta">1 curso &middot; Especialización técnica</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Software</span>
                <h3>Programación Orientada a Proyectos</h3>
                <p>Diseño modular, entregas iterativas y organización del código siguiendo metodologías profesionales.</p>
                <div class="course-card__meta">Aprendizaje práctico &middot; Transversal</div>
            </article>

            <article class="course-card">
                <span class="course-card__tag">Competencias</span>
                <h3>Herramientas Digitales</h3>
                <p>Productividad, documentación técnica y trabajo colaborativo en entornos digitales modernos.</p>
                <div class="course-card__meta">Base común &middot; Todos los perfiles</div>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>