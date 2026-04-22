<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Auth/Session.php';
use Auth\Session;

Session::start();
$user = Session::get('user');

if (!$user) { die("Acceso denegado."); }

$materialId = (int)($_GET['id'] ?? 0);
if ($materialId <= 0) die('ID de material inválido.');

$pdo = getDb();
$stmt = $pdo->prepare("SELECT title, file_path, visibility, uploaded_by_teacher_id FROM materials WHERE id = ?");
$stmt->execute([$materialId]);
$material = $stmt->fetch();

if (!$material) { die('El material no existe en la base de datos.'); }

// --- RESOLUCIÓN DE RUTAS SEGÚN EL ACTIVE DIRECTORY ---
if ($material['visibility'] === 'private') {
    // Verificación de seguridad
    if ($user['id'] != $material['uploaded_by_teacher_id']) {
        die("No tienes permiso para acceder a este archivo privado.");
    }
    // Buscamos en la Home del profesor logueado
    $filePath = '/mnt/samba/homes/' . $user['username'] . '/Campus_Privado/' . $material['file_path'];
} else {
    // Carpeta pública centralizada
    $filePath = '/mnt/samba/publico/' . $material['file_path'];
}

if (!file_exists($filePath)) {
    die("El archivo físico no se encuentra en el servidor SMB.");
}

$extension = pathinfo($filePath, PATHINFO_EXTENSION);
$downloadName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $material['title']) . '.' . $extension;

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;