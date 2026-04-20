<?php
declare(strict_types=1);

use Auth\Session;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Auth/Session.php';

try {
    Session::start();
    $user = Session::get('user');

    if (!$user) {
        echo "<h3>Fallo de Sesión</h3><p>No estás logeado.</p>";
        exit;
    }

    $rawRole = $user['role'] ?? $user['userRole'] ?? $user['role_label'] ?? '';
    $normalizedRole = strtolower(trim((string)$rawRole));

    if (!in_array($normalizedRole, ['profesor', 'teacher', 'prof'], true)) {
        echo "<h3>Acceso Denegado</h3><p>Tu rol actual detectado es: " . htmlspecialchars($normalizedRole) . "</p>";
        exit;
    }

    // EXTRAEMOS EL ID DEL PROFESOR (Requerido por la nueva BDD)
    $teacherId = (int)($user['id'] ?? 0);
    $teacherUsername = $user['username'] ?? '';
    $userName = $user['name'] ?? $user['full_name'] ?? $teacherUsername ?? 'Profesor';

    $pageTitle = 'Materiales';
    $pageSubtitle = 'Sube y consulta materiales de tus asignaturas';
    $pageStylesheet = '/assets/css/student-dashboard.css';
    $currentSection = 'materials';
    $userRole = 'Profesor';

    $pdo = getDb();
    $errors = [];
    $successMessage = '';
    $uploadDir = '/var/www/vallestech/storage/materials/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    // AVISO: He cambiado 'name' por 'title' en esta consulta para arreglar el Error 1054. 
    // Si tu tabla courses usa 'nombre', cámbialo aquí.
    $stmtCourses = $pdo->prepare("
        SELECT id, code, title 
        FROM courses
        WHERE teacher_username = :teacher_username
          AND (active = 1 OR active IS NULL)
        ORDER BY title ASC
    ");
    $stmtCourses->execute(['teacher_username' => $teacherUsername]);
    $courses = $stmtCourses->fetchAll();

    $courseIds = array_map(static fn(array $c): int => (int)$c['id'], $courses);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseId = (int)($_POST['course_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $visibility = trim($_POST['visibility'] ?? 'course_only'); // Ahora acepta 'private'

        if ($courseId <= 0) $errors[] = 'Debes seleccionar una asignatura.';
        if (!in_array($courseId, $courseIds, true)) $errors[] = 'La asignatura no te pertenece.';
        if ($title === '') $errors[] = 'El título es obligatorio.';
        if (!in_array($visibility, ['course_only', 'private'], true)) $errors[] = 'Visibilidad no válida.';
        
        if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Debes subir un archivo válido.';
        } else {
            // RESTRICCIÓN DE FORMATOS
            $originalName = $_FILES['material_file']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

            if (!in_array($extension, $allowedExtensions, true)) {
                $errors[] = 'Solo se permiten archivos PDF, Word o PowerPoint.';
            }
        }

        if (!$errors) {
            $tmpPath = $_FILES['material_file']['tmp_name'];
            $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $storedFilename = time() . '_' . $courseId . '_' . $safeBaseName . '.' . $extension;
            $destinationPath = $uploadDir . $storedFilename;

            if (!move_uploaded_file($tmpPath, $destinationPath)) {
                $errors[] = 'No se pudo mover el archivo al servidor.';
            } else {
                $fileHash = hash_file('sha256', $destinationPath);
                
                // INSERCIÓN ADAPTADA A LA NUEVA TABLA
                $stmtInsert = $pdo->prepare("
                    INSERT INTO materials (course_id, title, description, file_path, file_hash, visibility, uploaded_by_teacher_id) 
                    VALUES (:course_id, :title, :description, :file_path, :file_hash, :visibility, :uploaded_by_teacher_id)
                ");
                $stmtInsert->execute([
                    'course_id' => $courseId, 
                    'title' => $title, 
                    'description' => $description ?: null,
                    'file_path' => $storedFilename, 
                    'file_hash' => $fileHash, 
                    'visibility' => $visibility, 
                    'uploaded_by_teacher_id' => $teacherId, // Usamos el ID int(11)
                ]);
                $successMessage = 'Material subido correctamente.';
            }
        }
    }

    // LISTADO ADAPTADO
    $stmtMaterials = $pdo->prepare("
        SELECT m.id, m.title, m.description, m.file_path, m.visibility, m.created_at, c.code AS course_code, c.title AS course_name
        FROM materials m INNER JOIN courses c ON c.id = m.course_id
        WHERE m.uploaded_by_teacher_id = :teacher_id 
        ORDER BY m.created_at DESC, m.id DESC
    ");
    $stmtMaterials->execute(['teacher_id' => $teacherId]);
    $materials = $stmtMaterials->fetchAll();

    include __DIR__ . '/../../templates/private-header.php';
?>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel panel-wide">
        <div class="panel__header">
            <div><p class="panel__eyebrow">Profesor</p><h2>Subir material</h2></div>
        </div>

        <?php if ($errors): ?>
            <div class="notice-list">
                <?php foreach ($errors as $error): ?>
                    <div class="notice-item" style="border-left-color:#dc3545;"><strong>Error</strong><span><?= htmlspecialchars($error) ?></span></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="notice-list"><div class="notice-item" style="border-left-color:#28a745;"><strong>Correcto</strong><span><?= htmlspecialchars($successMessage) ?></span></div></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="material-form">
            <div class="form-row">
                <label>Asignatura</label>
                <select name="course_id" required>
                    <option value="">Selecciona una asignatura</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= (int)$course['id'] ?>"><?= htmlspecialchars($course['code'] . ' - ' . $course['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row"><label>Título</label><input type="text" name="title" maxlength="200" required></div>
            <div class="form-row"><label>Descripción</label><textarea name="description" rows="4"></textarea></div>
            <div class="form-row">
                <label>Visibilidad</label>
                <select name="visibility" required>
                    <option value="course_only">Solo alumnos del curso</option>
                    <option value="private">Privado (Solo yo)</option>
                </select>
            </div>
            <div class="form-row"><label>Archivo (PDF, Word, PPT)</label><input type="file" name="material_file" accept=".pdf,.doc,.docx,.ppt,.pptx" required></div>
            <div class="form-actions"><button type="submit" class="panel-link-button">Subir material</button></div>
        </form>
    </article>
</section>

<section class="panel">
    <div class="panel__header"><div><p class="panel__eyebrow">Listado</p><h2>Tus materiales</h2></div></div>
    <div class="notice-list">
        <?php foreach ($materials as $material): ?>
            <div class="notice-item">
                <strong><?= htmlspecialchars($material['title']) ?></strong>
                <span><?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name'] . ' · ' . $material['visibility']) ?></span>
                <div style="margin-top:10px;"><a href="/descargar.php?id=<?= $material['id'] ?>" target="_blank" class="panel-link-button">⬇️ Descargar</a></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php 
    include __DIR__ . '/../../templates/private-footer.php'; 

} catch (\Throwable $e) {
    die("<div style='padding:20px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
            <h3>💥 Error Crítico Detectado 💥</h3>
            <p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>
            <p><strong>Archivo:</strong> " . $e->getFile() . "</p>
            <p><strong>Línea:</strong> " . $e->getLine() . "</p>
        </div>");
}
?>