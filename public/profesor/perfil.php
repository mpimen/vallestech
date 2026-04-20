<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];

$fullName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Profesor'));
$email = trim((string)($currentUser['email'] ?? $currentUser['mail'] ?? 'No disponible'));
$role = trim((string)($currentUser['role'] ?? 'Profesor'));
$department = trim((string)($currentUser['department'] ?? $currentUser['area'] ?? 'Departamento no asignado'));
$status = trim((string)($currentUser['status'] ?? 'Activo'));
$phone = trim((string)($currentUser['phone'] ?? 'No disponible'));
$language = trim((string)($currentUser['language'] ?? 'Español'));
$timezone = trim((string)($currentUser['timezone'] ?? 'Europe/Madrid'));

$nameParts = preg_split('/\s+/', $fullName) ?: [];
$firstName = $nameParts[0] ?? $fullName;
$lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'No disponible';
$avatarLetter = strtoupper(mb_substr($fullName !== '' ? $fullName : 'P', 0, 1));

$pageTitle = 'Perfil';
$pageSubtitle = 'Datos personales, cuenta docente y ajustes básicos.';
$pageStylesheet = '/assets/css/teacher-profile.css';
$currentSection = 'profile';
$userName = $fullName !== '' ? $fullName : 'Profesor';
$userRole = $role !== '' ? $role : 'Profesor';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-hero__identity">
            <div class="profile-avatar"><?= htmlspecialchars($avatarLetter) ?></div>
            <div>
                <p class="profile-hero__eyebrow">Cuenta del profesorado</p>
                <h2><?= htmlspecialchars($fullName) ?></h2>
                <p>
                    <?= htmlspecialchars($role) ?> · <?= htmlspecialchars($department) ?> · Estado <?= htmlspecialchars(mb_strtolower($status)) ?>
                </p>
            </div>
        </div>

        <div class="profile-hero__summary">
            <div class="summary-box">
                <strong><?= htmlspecialchars($department) ?></strong>
                <span>Departamento</span>
            </div>
            <div class="summary-box">
                <strong><?= htmlspecialchars($status) ?></strong>
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
                <label>Correo corporativo</label>
                <div class="profile-value"><?= htmlspecialchars($email) ?></div>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <div class="profile-value"><?= htmlspecialchars($phone) ?></div>
            </div>
        </div>
    </article>

    <aside class="profile-sidebar">
        <article class="sidebar-card">
            <h3>Cuenta</h3>
            <ul>
                <li>Rol: <?= htmlspecialchars($role) ?></li>
                <li>Idioma: <?= htmlspecialchars($language) ?></li>
                <li>Zona horaria: <?= htmlspecialchars($timezone) ?></li>
            </ul>
        </article>

        <article class="sidebar-card">
            <h3>Sesión</h3>
            <p>
                Esta vista muestra la información disponible del profesor autenticado en la sesión actual.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>