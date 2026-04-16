<?php
$pageTitle = 'Inicio';
$pageSubtitle = 'Portal público del campus';

include __DIR__ . '/../templates/header.php';
?>

<section class="hero">
    <div class="container hero__grid">
        <article class="hero__content">
            <span class="badge">Campus académico</span>
            <h1 class="hero__title">Un portal universitario más claro, serio y actual.</h1>
            <p class="hero__text">
                Consulta la oferta formativa, mantente al día de los avisos del centro y accede a tu espacio académico desde una experiencia pensada para estudiantes y profesorado.
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
            <h2 class="panel-title">Todo empieza aquí</h2>
            <p class="panel-text">
                La entrada pública del campus orienta al usuario antes de iniciar sesión y reúne la información más útil del entorno académico.
            </p>

            <ul class="info-list">
                <li>
                    <strong>Oferta académica</strong>
                    <span>Acceso rápido a estudios, itinerarios y líneas formativas.</span>
                </li>
                <li>
                    <strong>Avisos del centro</strong>
                    <span>Noticias, cambios de horario y comunicaciones destacadas.</span>
                </li>
                <li>
                    <strong>Área personal</strong>
                    <span>Entrada centralizada al espacio de alumnado y profesorado.</span>
                </li>
            </ul>
        </aside>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__head">
            <div>
                <p class="section__eyebrow">Servicios del portal</p>
                <h2 class="section__title">Una base visual más seria para un campus real</h2>
            </div>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card__icon">01</div>
                <h3>Navegación clara</h3>
                <p>La portada da contexto rápido y facilita el acceso a los apartados públicos más importantes del sitio.</p>
            </article>

            <article class="card">
                <div class="card__icon">02</div>
                <h3>Identidad académica</h3>
                <p>El diseño mezcla tono institucional con una capa digital más actual para que no parezca una web genérica.</p>
            </article>

            <article class="card">
                <div class="card__icon">03</div>
                <h3>Preparada para crecer</h3>
                <p>Esta parte pública está pensada para convivir después con login, dashboards y módulos privados sin rehacer todo.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="showcase">
            <div class="showcase__media"></div>

            <article class="showcase__content">
                <p class="section__eyebrow">Experiencia de acceso</p>
                <h3>Un punto de entrada útil para cualquier usuario</h3>
                <p>
                    Antes de autenticarse, cualquier visitante puede entender qué ofrece el campus, revisar comunicaciones del centro y localizar rápidamente la puerta de entrada al entorno académico privado.
                </p>

                <ul class="feature-list">
                    <li>Acceso claro a estudios, avisos y login</li>
                    <li>Diseño responsive con mejor jerarquía visual</li>
                    <li>Base elegante para crecer hacia dashboards y módulos</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__head">
            <div>
                <p class="section__eyebrow">Avisos destacados</p>
                <h2 class="section__title">Información pública del centro</h2>
            </div>
        </div>

        <div class="grid-3">
            <article class="notice-card">
                <h3>Actualización del calendario</h3>
                <p>Durante esta semana se publicarán cambios en la planificación académica de varias actividades del campus.</p>
                <div class="notice-meta">Coordinación académica</div>
            </article>

            <article class="notice-card">
                <h3>Nueva información de acceso</h3>
                <p>El portal centralizará el acceso al área personal con asignaturas, tareas, avisos y recursos del curso.</p>
                <div class="notice-meta">Campus virtual</div>
            </article>

            <article class="notice-card">
                <h3>Comunicados institucionales</h3>
                <p>La sección pública se utilizará para mostrar novedades relevantes antes de iniciar sesión.</p>
                <div class="notice-meta">Secretaría del centro</div>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>