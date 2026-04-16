<?php
$pageTitle = 'Perfil';
$pageSubtitle = 'Datos personales, cuenta académica y ajustes básicos.';
$pageStylesheet = '/assets/css/student-profile.css';
$currentSection = 'profile';
$userName = 'Carlos Pérez';
$userRole = 'Alumno';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-hero__identity">
            <div class="profile-avatar">C</div>
            <div>
                <p class="profile-hero__eyebrow">Cuenta del estudiante</p>
                <h2>Carlos Pérez</h2>
                <p>Alumno de Desarrollo de Aplicaciones Web · Curso actual en seguimiento activo.</p>
            </div>
        </div>

        <div class="profile-hero__summary">
            <div class="summary-box">
                <strong>2º DAW</strong>
                <span>Grupo académico</span>
            </div>
            <div class="summary-box">
                <strong>Activo</strong>
                <span>Estado de cuenta</span>
            </div>
        </div>
    </article>
</section>

<section class="profile-layout">
    <article class="profile-card">
        <div class="section-head">
            <div>
                <p class="section-head__eyebrow">Información personal</p>
                <h2>Datos básicos</h2>
            </div>
        </div>

        <form class="profile-form" action="#" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Nombre</label>
                    <input type="text" id="first_name" name="first_name" value="Carlos">
                </div>

                <div class="form-group">
                    <label for="last_name">Apellidos</label>
                    <input type="text" id="last_name" name="last_name" value="Pérez Gómez">
                </div>

                <div class="form-group">
                    <label for="email">Correo académico</label>
                    <input type="email" id="email" name="email" value="carlos.perez@campus.local">
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" id="phone" name="phone" value="+34 600 123 456">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Guardar cambios</button>
            </div>
        </form>
    </article>

    <aside class="profile-sidebar">
        <article class="sidebar-card">
            <h3>Cuenta</h3>
            <ul>
                <li>Rol: Alumno</li>
                <li>Idioma: Español</li>
                <li>Zona horaria: Europe/Madrid</li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Siguiente fase</h3>
            <p>
                Esta vista queda preparada para enlazar después con datos reales de sesión, LDAP y edición persistente del perfil.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>