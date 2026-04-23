<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Auth/Session.php';
use Auth\Session;

Session::start();
$user = Session::get('user');
if (!$user) die("Acceso denegado.");

$id = (int)($_GET['id'] ?? 0);
$type = $_GET['type'] ?? 'material';
$action = $_GET['action'] ?? 'preview'; 
$username = $user['username'] ?? '';

$pdo = getDb();

// 1. RESOLUCIÓN DE RUTAS SEGÚN EL TIPO
if ($type === 'avatar') {
    // Para los avatares, usamos el nombre del archivo directamente
    // Usamos basename() por seguridad extrema para evitar ataques de ruta
    $fileName = basename($_GET['file'] ?? '');
    $physicalPath = '/mnt/samba/publico/avatars/' . $fileName;
    $fileTitle = "Avatar de " . $username;
    
    // Para avatares en un <img>, la acción por defecto debe ser 'raw'
    if (!isset($_GET['action'])) {
        $action = 'raw';
    }
} elseif ($type === 'submission') {
    // Buscar ID local del alumno
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmtUser->execute([$username]);
    $localUserId = $stmtUser->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM submissions WHERE id = ? AND student_id = ?");
    $stmt->execute([$id, $localUserId]);
    $file = $stmt->fetch();
    if (!$file) die("Tarea no encontrada o sin permiso.");
    
    $fileTitle = $file['title'];
    $physicalPath = '/mnt/samba/homes/' . $username . '/Entregas_Tareas/' . $file['file_path'];
} else {
    // Materiales (Profesores/Públicos)
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmtUser->execute([$username]);
    $localUserId = $stmtUser->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();
    
    if (!$file) die("El archivo no existe en la base de datos.");

    $fileTitle = $file['title'];
    if ($file['visibility'] === 'private') {
        if ($localUserId != $file['uploaded_by_teacher_id'] && $localUserId != ($file['student_id'] ?? 0)) {
            die("No tienes permiso para acceder a este archivo privado.");
        }
        $physicalPath = '/mnt/samba/homes/' . $username . '/Campus_Privado/' . $file['file_path'];
    } else {
        $physicalPath = '/mnt/samba/publico/' . $file['file_path'];
    }
}

// 2. VERIFICACIÓN FÍSICA
if (!file_exists($physicalPath) || is_dir($physicalPath)) {
    die("El archivo físico no se encuentra en el servidor Samba.");
}

$extension = strtolower(pathinfo($physicalPath, PATHINFO_EXTENSION));
$fileSizeKB = round(filesize($physicalPath) / 1024, 2);

// Mapeo MIME
$mimeTypes = [
    'pdf'  => 'application/pdf',
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
    'mp4'  => 'video/mp4',
    'php'  => 'text/html' // Ejecución si es raw
];
$mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

// --- ACCIÓN: DESCARGAR O RAW (Bytes puros) ---
if ($action === 'download' || $action === 'raw') {
    
    // Si es PHP y acción RAW, lo ejecutamos (Metodología Lab)
    if ($action === 'raw' && $extension === 'php') {
        $dirOrig = getcwd();
        chdir(dirname($physicalPath));
        include $physicalPath;
        chdir($dirOrig);
        exit;
    }
    
    header('Content-Type: ' . $mimeType);
    if ($action === 'download') {
        header('Content-Disposition: attachment; filename="' . basename($physicalPath) . '"');
    } else {
        header('Content-Disposition: inline; filename="' . basename($physicalPath) . '"');
    }
    readfile($physicalPath);
    exit;
}

// --- ACCIÓN: VISTA PREVIA (HTML) ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa: <?= htmlspecialchars($fileTitle) ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .preview-container { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 900px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; cursor: pointer; border: none; }
        .btn-blue { background: #266df0; color: white; }
        .preview-content { border: 1px solid #ddd; background: #fafafa; min-height: 400px; display: flex; justify-content: center; align-items: center; }
        iframe { width: 100%; height: 70vh; border: none; }
        img { max-width: 100%; max-height: 70vh; }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="header">
            <div>
                <h2 style="margin:0;"><?= htmlspecialchars($fileTitle) ?></h2>
                <small>.<?= strtoupper($extension) ?> | <?= $fileSizeKB ?> KB</small>
            </div>
            <a href="?<?= $_SERVER['QUERY_STRING'] ?>&action=download" class="btn btn-blue">⬇️ Descargar archivo</a>
        </div>
        <div class="preview-content">
            <?php 
            $rawUrl = "?{$_SERVER['QUERY_STRING']}&action=raw";
            if (in_array($extension, ['png','jpg','jpeg','gif','webp'])) echo "<img src='$rawUrl'>";
            elseif (in_array($extension, ['pdf','php'])) echo "<iframe src='$rawUrl'></iframe>";
            else echo "<p>Vista previa no disponible para este formato.</p>";
            ?>
        </div>
    </div>
</body>
</html>