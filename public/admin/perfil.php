<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];

$fullName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$email = trim((string)($currentUser['email'] ?? $currentUser['mail'] ?? 'No disponible'));
$role = trim((string)($currentUser['role'] ?? 'Admin'));
$position = trim((string)($currentUser['position'] ?? $currentUser['job_title'] ?? 'Administración general'));
$status = trim((string)($currentUser['status'] ?? 'Activa'));
$language = trim((string)($currentUser['language'] ?? 'Español'));
$timezone = trim((string)($currentUser['timezone'] ?? 'Europe/Madrid'));

$nameParts = preg_split('/\s+/', $fullName) ?: [];
$firstName = $nameParts[0] ?? $fullName;
$lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'No disponible';
$avatarLetter = strtoupper(mb_substr($fullName !== '' ? $fullName : 'A', 0, 1));

$pageTitle = 'Perfil';
$pageSubtitle = 'Cuenta administrativa y ajustes básicos.';
$pageStylesheet = '/assets/css/admin-profile.css';
$currentSection = 'profile';
$userName = $fullName !== '' ? $fullName : 'Administrador';
$userRole = $role !== '' ? $role : 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-identity">
            <div class="profile-avatar"><?= htmlspecialchars($avatarLetter) ?></div>
            <div>
                <p class="profile-hero__eyebrow">Administrador</p>
                <h2><?= htmlspecialchars($fullName) ?></h2>
                <p><?= htmlspecialchars($position) ?></p>
            </div>
        </div>

        <div class="profile-summary">
            <div class="summary-box">
                <strong>Acceso total</strong>
                <span>Permisos</span>
            </div>
            <div class="summary-box">
                <strong><?= htmlspecialchars($status) ?></strong>
                <span>Cuenta</span>
            </div>
        </div>
    </article>
</section>

<section class="profile-layout">
    <article class="profile-card">
        <div class="section-head">
            <p class="section-head__eyebrow">Datos administrativos</p>
            <h2>Perfil del usuario</h2>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Nombre</label>
                <div class="profile-value"><?= htmlspecialchars($firstName) ?></div>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <div class="profile-value"><?= htmlspecialchars($lastName) ?></div>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <div class="profile-value"><?= htmlspecialchars($email) ?></div>
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <div class="profile-value"><?= htmlspecialchars($position) ?></div>
            </div>
        </div>
    </article>

    <aside class="profile-sidebar">
        <article class="sidebar-card">
            <h3>Ajustes rápidos</h3>
            <ul>
                <li>Idioma: <?= htmlspecialchars($language) ?></li>
                <li>Zona horaria: <?= htmlspecialchars($timezone) ?></li>
                <li>Rol: <?= htmlspecialchars($role) ?></li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Estado</h3>
            <p>La información visible corresponde al usuario autenticado en la sesión actual.</p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>