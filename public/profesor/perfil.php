<?php
$pageTitle = 'Perfil';
$pageSubtitle = 'Datos personales, cuenta docente y ajustes básicos.';
$pageStylesheet = '/assets/css/teacher-profile.css';
$currentSection = 'profile';
$userName = 'Ana Martínez';
$userRole = 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-identity">
            <div class="profile-avatar">A</div>
            <div>
                <p class="profile-hero__eyebrow">Cuenta docente</p>
                <h2>Ana Martínez</h2>
                <p>Profesora de Desarrollo Web y Seguridad en Redes · Coordinación de grupos activa.</p>
            </div>
        </div>

        <div class="profile-summary">
            <div class="summary-box">
                <strong>Docente activa</strong>
                <span>Estado</span>
            </div>
            <div class="summary-box">
                <strong>3 áreas</strong>
                <span>Responsabilidad académica</span>
            </div>
        </div>
    </article>
</section>

<section class="profile-layout">
    <article class="profile-card">
        <div class="section-head">
            <div>
                <p class="section-head__eyebrow">Información profesional</p>
                <h2>Datos de cuenta</h2>
            </div>
        </div>

        <form class="profile-form" action="#" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Nombre</label>
                    <input type="text" id="first_name" name="first_name" value="Ana">
                </div>

                <div class="form-group">
                    <label for="last_name">Apellidos</label>
                    <input type="text" id="last_name" name="last_name" value="Martínez López">
                </div>

                <div class="form-group">
                    <label for="email">Correo docente</label>
                    <input type="email" id="email" name="email" value="ana.martinez@campus.local">
                </div>

                <div class="form-group">
                    <label for="department">Departamento</label>
                    <input type="text" id="department" name="department" value="Informática y Sistemas">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Guardar cambios</button>
            </div>
        </form>
    </article>

    <aside class="profile-sidebar">
        <article class="sidebar-card">
            <h3>Ajustes rápidos</h3>
            <ul>
                <li>Idioma: Español</li>
                <li>Zona horaria: Europe/Madrid</li>
                <li>Perfil: Profesor</li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Preparado para integrar</h3>
            <p>
                Esta vista queda lista para enlazar con cuenta real, notificaciones, LDAP y permisos por rol.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>