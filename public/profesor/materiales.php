<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Auth/Session.php';

use Auth\Session;

Session::start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: /index.php');
    exit;
}

$userRole = $user['role'] ?? $user['userRole'] ?? $user['role_label'] ?? '';
$normalizedRole = mb_strtolower((string)$userRole, 'UTF-8');

if (!in_array($normalizedRole, ['profesor', 'teacher'], true)) {
    http_response_code(403);
    exit('Acceso denegado.');
}

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

$stmtCourses = $pdo->prepare("
    SELECT id, code, name
    FROM courses
    WHERE teacher_username = :teacher_username
      AND (active = 1 OR active IS NULL)
    ORDER BY name ASC
");
$stmtCourses->execute([
    'teacher_username' => $teacherUsername
]);
$courses = $stmtCourses->fetchAll();

$courseIds = array_map(
    static fn(array $course): int => (int)$course['id'],
    $courses
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = (int)($_POST['course_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $visibility = trim($_POST['visibility'] ?? 'course_only');

    if ($courseId <= 0) {
        $errors[] = 'Debes seleccionar una asignatura.';
    }

    if (!in_array($courseId, $courseIds, true)) {
        $errors[] = 'La asignatura seleccionada no te pertenece.';
    }

    if ($title === '') {
        $errors[] = 'El título es obligatorio.';
    }

    if (!in_array($visibility, ['course_only', 'restricted'], true)) {
        $errors[] = 'La visibilidad seleccionada no es válida.';
    }

    if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Debes subir un archivo válido.';
    }

    if (!$errors) {
        $originalName = $_FILES['material_file']['name'];
        $tmpPath = $_FILES['material_file']['tmp_name'];

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
        $storedFilename = time() . '_' . $courseId . '_' . $safeBaseName;

        if ($extension !== '') {
            $storedFilename .= '.' . $extension;
        }

        $destinationPath = $uploadDir . $storedFilename;

        if (!move_uploaded_file($tmpPath, $destinationPath)) {
            $errors[] = 'No se pudo mover el archivo al directorio de almacenamiento.';
        } else {
            $fileHash = hash_file('sha256', $destinationPath);

            $stmtInsert = $pdo->prepare("
                INSERT INTO materials (
                    course_id,
                    title,
                    description,
                    file_path,
                    file_hash,
                    visibility,
                    uploaded_by_username
                ) VALUES (
                    :course_id,
                    :title,
                    :description,
                    :file_path,
                    :file_hash,
                    :visibility,
                    :uploaded_by_username
                )
            ");

            $stmtInsert->execute([
                'course_id' => $courseId,
                'title' => $title,
                'description' => $description !== '' ? $description : null,
                'file_path' => $storedFilename,
                'file_hash' => $fileHash,
                'visibility' => $visibility,
                'uploaded_by_username' => $teacherUsername,
            ]);

            $successMessage = 'Material subido correctamente.';
        }
    }
}

$stmtMaterials = $pdo->prepare("
    SELECT
        m.id,
        m.title,
        m.description,
        m.file_path,
        m.visibility,
        m.created_at,
        c.code AS course_code,
        c.name AS course_name
    FROM materials m
    INNER JOIN courses c ON c.id = m.course_id
    WHERE c.teacher_username = :teacher_username
    ORDER BY m.created_at DESC, m.id DESC
");
$stmtMaterials->execute([
    'teacher_username' => $teacherUsername
]);
$materials = $stmtMaterials->fetchAll();

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel panel-wide">
        <div class="panel__header">
            <div>
                <p class="panel__eyebrow">Profesor</p>
                <h2>Subir material</h2>
            </div>
        </div>

        <?php if ($errors): ?>
            <div class="notice-list">
                <?php foreach ($errors as $error): ?>
                    <div class="notice-item">
                        <strong>Error</strong>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($successMessage !== ''): ?>
            <div class="notice-list">
                <div class="notice-item">
                    <strong>Correcto</strong>
                    <span><?= htmlspecialchars($successMessage) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="material-form">
            <div class="form-row">
                <label for="course_id">Asignatura</label>
                <select name="course_id" id="course_id" required>
                    <option value="">Selecciona una asignatura</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= (int)$course['id'] ?>">
                            <?= htmlspecialchars($course['code'] . ' - ' . $course['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="title">Título</label>
                <input type="text" name="title" id="title" maxlength="150" required>
            </div>

            <div class="form-row">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>

            <div class="form-row">
                <label for="visibility">Visibilidad</label>
                <select name="visibility" id="visibility" required>
                    <option value="course_only">Solo alumnos del curso</option>
                    <option value="restricted">Restringido</option>
                </select>
            </div>

            <div class="form-row">
                <label for="material_file">Archivo</label>
                <input type="file" name="material_file" id="material_file" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="panel-link-button">Subir material</button>
            </div>
        </form>
    </article>
</section>

<section class="panel">
    <div class="panel__header">
        <div>
            <p class="panel__eyebrow">Listado</p>
            <h2>Materiales subidos</h2>
        </div>
    </div>

    <div class="notice-list">
        <?php if (!$materials): ?>
            <div class="notice-item">
                <strong>Sin materiales</strong>
                <span>Todavía no has subido ningún material.</span>
            </div>
        <?php else: ?>
            <?php foreach ($materials as $material): ?>
                <div class="notice-item">
                    <strong><?= htmlspecialchars($material['title']) ?></strong>
                    <span>
                        <?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name']) ?>
                        · <?= htmlspecialchars($material['visibility']) ?>
                        · <?= htmlspecialchars((string)$material['created_at']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>