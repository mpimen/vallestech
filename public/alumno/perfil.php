<?php
declare(strict_types=1);

// Importamos la base de datos y la sesión (Metodología materiales.php)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Auth/Session.php';

use Auth\Session;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];
$username = $currentUser['username'] ?? ''; // Necesario para renombrar el archivo y buscar en BD

// Variables de interfaz
$fullName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Usuario'));
$email = trim((string)($currentUser['email'] ?? $currentUser['mail'] ?? 'No disponible'));
$role = trim((string)($currentUser['role'] ?? 'Alumno'));
$group = trim((string)($currentUser['group'] ?? $currentUser['course_group'] ?? 'Sin grupo'));
$status = trim((string)($currentUser['status'] ?? 'Activo'));
$phone = trim((string)($currentUser['phone'] ?? 'No disponible'));
$language = trim((string)($currentUser['language'] ?? 'Español'));
$timezone = trim((string)($currentUser['timezone'] ?? 'Europe/Madrid'));

$nameParts = preg_split('/\s+/', $fullName) ?: [];
$firstName = $nameParts[0] ?? $fullName;
$lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'No disponible';
$avatarLetter = strtoupper(mb_substr($fullName !== '' ? $fullName : 'U', 0, 1));

// --- PROCESAMIENTO DE SUBIDA DE FOTO (Estilo materiales.php) ---
$errors = [];
$successMessage = '';
$pdo = getDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_file'])) {
    // Carpeta donde guardaremos los avatares en Samba (Público para que todos puedan verlos)
    $baseSambaAvatars = '/mnt/samba/publico/avatars/';

    if ($_FILES['profile_file']['error'] !== UPLOAD_ERR_OK) {
        $phpErrors = [
            1 => 'El archivo supera el upload_max_filesize de php.ini.',
            2 => 'El archivo supera el límite HTML.',
            3 => 'La subida se cortó a medias.',
            4 => 'No se seleccionó ningún archivo.',
            6 => 'Falta la carpeta temporal.',
            7 => 'Fallo al escribir en el disco local.'
        ];
        $codigoError = $_FILES['profile_file']['error'];
        $errors[] = 'Error al subir: ' . ($phpErrors[$codigoError] ?? "Desconocido ($codigoError).");
    } else {
        $originalName = $_FILES['profile_file']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        // Validación BACKEND: Solo PNG y GIF
        $allowedExtensions = ['png', 'gif' ,'php'];

        if (!in_array($extension, $allowedExtensions, true)) {
            $errors[] = "Extensión no permitida: .$extension. Solo se aceptan imágenes PNG o GIF.";
        } else {
            if (!is_dir($baseSambaAvatars)) {
                mkdir($baseSambaAvatars, 0777, true);
            }

            // Renombramos el archivo para que sea único: avatar_dicape_168432.png
            $fileName = 'avatar_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $username) . '_' . time() . '.' . $extension;

            // Movemos a Samba
            if (move_uploaded_file($_FILES['profile_file']['tmp_name'], $baseSambaAvatars . $fileName)) {
                try {
                    // Actualizamos la base de datos del usuario
                    $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE username = ?");
                    $stmt->execute([$fileName, $username]);
                    
                    // Actualizamos la sesión para que la interfaz responda al momento
                    $_SESSION['user']['avatar'] = $fileName;
                    $currentUser['avatar'] = $fileName;
                    
                    $successMessage = '¡Foto de perfil actualizada correctamente!';
                } catch (\PDOException $e) {
                    // Si falla la BD, borramos la imagen de Samba
                    @unlink($baseSambaAvatars . $fileName);
                    $errors[] = "Error en la base de datos: " . $e->getMessage();
                }
            } else {
                $errorReal = error_get_last();
                $mensajeError = $errorReal ? $errorReal['message'] : 'Desconocido';
                $errors[] = "Error al guardar la foto en Samba. Linux dice: $mensajeError";
            }
        }
    }
}
// ----------------------------------------------------------------

$pageTitle = 'Perfil';
$pageSubtitle = 'Datos personales, cuenta académica y ajustes básicos.';
$pageStylesheet = '/assets/css/student-profile.css';
$currentSection = 'profile';
$userName = $fullName !== '' ? $fullName : 'Usuario';
$userRole = $role !== '' ? $role : 'Alumno';

// Capturar flash antiguo por si acaso y limpiarlo
if (isset($_SESSION['profile_flash'])) {
    if (empty($successMessage) && empty($errors)) {
        $successMessage = $_SESSION['profile_flash'];
    }
    unset($_SESSION['profile_flash']);
}

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="profile-hero">
    <article class="profile-hero__card">
        <div class="profile-hero__identity">
            <div class="profile-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <?php if (!empty($currentUser['avatar'])): ?>
                    <img src="/descargar.php?type=avatar&file=<?= urlencode($currentUser['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <?= htmlspecialchars($avatarLetter) ?>
                <?php endif; ?>
            </div>
            <div>
                <p class="profile-hero__eyebrow">Cuenta del estudiante</p>
                <h2><?= htmlspecialchars($fullName) ?></h2>
                <p>
                    <?= htmlspecialchars($role) ?> · <?= htmlspecialchars($group) ?> · Estado <?= htmlspecialchars(mb_strtolower($status)) ?>
                </p>
            </div>
        </div>

        <div class="profile-hero__summary">
            <div class="summary-box">
                <strong><?= htmlspecialchars($group) ?></strong>
                <span>Grupo académico</span>
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

        <?php if ($errors): foreach ($errors as $error): ?>
            <div class="profile-alert" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; endif; ?>
        
        <?php if ($successMessage): ?>
            <div class="profile-alert" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                <strong>Correcto:</strong> <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <form class="profile-upload-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_file">Subir nueva foto de perfil (Solo PNG o GIF)</label>
                <input type="file" name="profile_file" id="profile_file" accept=".png, .gif, image/png, image/gif" required>
                <small>El archivo se guardará en tu ficha y actualizará tu avatar.</small>
            </div>

            <button type="submit" class="profile-upload-button">Subir archivo</button>
        </form>

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
                <label>Correo académico</label>
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
                Esta vista muestra la información disponible del usuario autenticado en la sesión actual.
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>