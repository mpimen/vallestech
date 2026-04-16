<?php
$pageTitle = 'Perfil';
$pageSubtitle = 'Cuenta administrativa y ajustes básicos.';
$pageStylesheet = '/assets/css/admin-profile.css';
$currentSection = 'profile';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-identity">
            <div class="profile-avatar">L</div>
            <div>
                <p class="profile-hero__eyebrow">Administrador</p>
                <h2>Laura Gómez</h2>
                <p>Administración general del campus y gestión de usuarios.</p>
            </div>
        </div>

        <div class="profile-summary">
            <div class="summary-box">
                <strong>Acceso total</strong>
                <span>Permisos</span>
            </div>
            <div class="summary-box">
                <strong>Activa</strong>
                <span>Cuenta</span>
            </div>
        </div>
    </article>
</section>

<section class="profile-layout">
    <article class="profile-card">
        <div class="section-head">
            <p class="section-head__eyebrow">Datos administrativos</p>
            <h2>Configuración básica</h2>
        </div>

        <form class="profile-form" action="#" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Nombre</label>
                    <input type="text" id="first_name" name="first_name" value="Laura">
                </div>

                <div class="form-group">
                    <label for="last_name">Apellidos</label>
                    <input type="text" id="last_name" name="last_name" value="Gómez Ruiz">
                </div>

                <div class="form-group">
                    <label for="email">Correo</label>
                    <input type="email" id="email" name="email" value="laura.gomez@campus.local">
                </div>

                <div class="form-group">
                    <label for="position">Cargo</label>
                    <input type="text" id="position" name="position" value="Administración general">
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
                <li>Rol: Admin</li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Estado</h3>
            <p>Listo para conectar permisos reales, auditoría y control de sesiones.</p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>