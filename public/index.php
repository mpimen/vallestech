<?php
$pageTitle = 'Inicio';
$pageSubtitle = 'Portal público del campus';

include __DIR__ . '/../templates/header.php';
?>

<section class="hero">
    <div class="container hero__grid">
        <article class="hero__content">
            <span class="badge">Campus Vallestech</span>
            <h1 class="hero__title">Tu espacio académico, siempre disponible.</h1>
            <p class="hero__text">
                Consulta la oferta formativa, mantente al día con los avisos del centro y accede a tu área personal desde cualquier dispositivo.
            </p>

            <div class="hero__actions">
                <a class="btn btn--primary" href="/login.php">Acceder al campus</a>
                <a class="btn btn--secondary" href="/courses.php">Explorar estudios</a>
            </div>

            <div class="hero__stats">
                <div class="hero__stat">
                    <strong>18</strong>
                    <span>Programas académicos</span>
                </div>
                <div class="hero__stat">
                    <strong>42</strong>
                    <span>Asignaturas activas</span>
                </div>
                <div class="hero__stat">
                    <strong>24/7</strong>
                    <span>Acceso al portal</span>
                </div>
            </div>
        </article>

        <aside class="hero__panel">
            <p class="panel-kicker">Vida académica</p>
            <h2 class="panel-title">Todo lo que necesitas en un solo lugar</h2>
            <p class="panel-text">
                Accede a tus estudios, consulta los últimos avisos del centro y entra a tu área personal desde aquí.
            </p>

            <ul class="info-list">
                <li>
                    <strong>Oferta académica</strong>
                    <span>Ciclos formativos, itinerarios y toda la información de los programas.</span>
                </li>
                <li>
                    <strong>Avisos del centro</strong>
                    <span>Noticias, cambios de horario y comunicaciones importantes al día.</span>
                </li>
                <li>
                    <strong>Área personal</strong>
                    <span>Acceso directo al espacio privado de alumnado y profesorado.</span>
                </li>
            </ul>
        </aside>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__head">
            <div>
                <p class="section__eyebrow">¿Qué ofrece el portal?</p>
                <h2 class="section__title">Diseñado para estudiantes y profesorado</h2>
            </div>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card__icon">📚</div>
                <h3>Oferta formativa</h3>
                <p>Consulta todos los ciclos y programas disponibles, sus itinerarios y la información de cada módulo.</p>
            </article>

            <article class="card">
                <div class="card__icon">🔔</div>
                <h3>Avisos en tiempo real</h3>
                <p>El centro publica aquí novedades, cambios de horario y comunicados institucionales para toda la comunidad.</p>
            </article>

            <article class="card">
                <div class="card__icon">🔐</div>
                <h3>Acceso seguro</h3>
                <p>Inicia sesión con tu cuenta corporativa del dominio para acceder a tu panel personalizado de alumno o profesor.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="showcase">
            <div class="showcase__media"></div>

            <article class="showcase__content">
                <p class="section__eyebrow">Área personal</p>
                <h3>Un panel adaptado a tu rol en el centro</h3>
                <p>
                    Alumnos y profesores disponen de espacios diferenciados con la información y herramientas que cada perfil necesita cada día.
                </p>

                <ul class="feature-list">
                    <li>Asignaturas, tareas y recursos organizados por curso</li>
                    <li>Avisos y comunicaciones directamente en tu panel</li>
                    <li>Acceso desde cualquier dispositivo, en cualquier momento</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__head">
            <div>
                <p class="section__eyebrow">Últimas noticias</p>
                <h2 class="section__title">Información pública del centro</h2>
            </div>
            <a class="btn btn--secondary btn--small" href="/notices.php">Ver todos los avisos</a>
        </div>

        <div class="grid-3">
            <article class="notice-card">
                <h3>Actualización del calendario académico</h3>
                <p>Se han publicado cambios en la planificación de varias actividades del campus para el segundo trimestre.</p>
                <div class="notice-meta">Coordinación académica &middot; Abril 2026</div>
            </article>

            <article class="notice-card">
                <h3>El portal centraliza el acceso al área personal</h3>
                <p>Asignaturas, tareas, avisos y recursos del curso ya están disponibles desde un único punto de entrada.</p>
                <div class="notice-meta">Campus virtual &middot; Abril 2026</div>
            </article>

            <article class="notice-card">
                <h3>Periodo de matriculación abierto</h3>
                <p>Los plazos de preinscripción y matrícula para el próximo curso ya están publicados en secretaría.</p>
                <div class="notice-meta">Secretaría del centro &middot; Abril 2026</div>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>