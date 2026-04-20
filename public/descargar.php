<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Auth/Session.php';

use Auth\Session;
Session::start();

// Validar que el usuario esté logeado (puede ser profesor o alumno)
if (!Session::get('user') && !isset($_SESSION['user'])) {
    http_response_code(403);
    exit('Acceso denegado.');
}

$materialId = (int)($_GET['id'] ?? 0);

if ($materialId <= 0) {
    exit('ID de material inválido.');
}

$pdo = getDb();

// Buscar el archivo en la base de datos
$stmt = $pdo->prepare("SELECT file_path, title FROM materials WHERE id = :id");
$stmt->execute(['id' => $materialId]);
$material = $stmt->fetch();

if (!$material) {
    http_response_code(404);
    exit('El material no existe.');
}

// Ruta absoluta de donde guardas los archivos
$baseDir = '/var/www/vallestech/storage/materials/';
$filePath = $baseDir . $material['file_path'];

if (!file_exists($filePath)) {
    http_response_code(404);
    exit('El archivo físico no se encuentra en el servidor.');
}

// Obtener la extensión para nombrar la descarga
$extension = pathinfo($filePath, PATHINFO_EXTENSION);
$downloadName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $material['title']) . '.' . $extension;

// Forzar la descarga del archivo en el navegador
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Leer el archivo y enviarlo
readfile($filePath);
exit;