<?php
declare(strict_types=1);

use Auth\Session;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Auth/Session.php';

try {
    Session::start();
    $user = Session::get('user');

    if (!$user) {
        header('Location: /index.php');
        exit;
    }

    $rawRole = $user['role'] ?? $user['userRole'] ?? $user['role_label'] ?? '';
    if (!in_array(strtolower(trim((string)$rawRole)), ['profesor', 'teacher', 'prof'], true)) {
        http_response_code(403);
        exit('Acceso denegado.');
    }

    $teacherUsername = $user['username'] ?? ''; // El nombre de usuario del AD
    $userName = $user['name'] ?? $user['full_name'] ?? $teacherUsername ?? 'Profesor';
    
    $pdo = getDb();
    
    // --- LÓGICA DE CREACIÓN AUTOMÁTICA DE USUARIO (JIT Provisioning) ---
    // Buscamos si el profesor ya existe en la base de datos local
    $stmtFindTeacher = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
    $stmtFindTeacher->execute(['username' => $teacherUsername]);
    $dbTeacherId = $stmtFindTeacher->fetchColumn();

    // Si no existe, lo creamos automáticamente
    if (!$dbTeacherId) {
        try {
            // Usamos 'teacher' para respetar el ENUM y pasamos el display_name
            $stmtInsertUser = $pdo->prepare("INSERT INTO users (username, display_name, role) VALUES (:username, :display_name, 'teacher')");
            $stmtInsertUser->execute([
                'username'     => $teacherUsername,
                'display_name' => $userName
            ]);
            $dbTeacherId = $pdo->lastInsertId();
        } catch (\PDOException $e) {
            die("<div style='padding:20px; background:#f8d7da; color:#721c24; font-family:sans-serif;'>
                 <strong>Error al autocompletar el perfil del nuevo profesor:</strong> " . $e->getMessage() . "<br>
                 <em>Verifica que la tabla no exija otros campos obligatorios (como email).</em></div>");
        }
    }

    $teacherId = (int)$dbTeacherId;
    // -------------------------------------------------------------------

    $errors = [];
    $successMessage = '';

    // --- RUTAS FÍSICAS (Adaptadas a Samba y Active Directory) ---
    $baseSambaPublic = '/mnt/samba/publico/';
    // Guardamos en: /home/usuario/Campus_Privado/
    $baseSambaPrivate = '/mnt/samba/homes/' . $teacherUsername . '/Campus_Privado/';

    // Obtenemos las asignaturas del profesor usando su ID
    $stmtCourses = $pdo->prepare("SELECT id, code, group_name AS course_name FROM courses WHERE teacher_id = ? AND (active = 1 OR active IS NULL)");
    $stmtCourses->execute([$teacherId]);
    $courses = $stmtCourses->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseId = (int)($_POST['course_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $visibility = trim($_POST['visibility'] ?? 'course_only');

        // Validamos que el profesor imparta esa asignatura
        $courseIds = array_column($courses, 'id');

        if ($courseId <= 0 || !in_array($courseId, $courseIds, false)) {
             $errors[] = 'Debes seleccionar una asignatura válida que te pertenezca.';
        }

        if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Archivo no válido o no se ha seleccionado ninguno.';
        } else {
            $originalName = $_FILES['material_file']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

            if (!in_array($extension, $allowedExtensions, true)) {
                $errors[] = 'Solo se permiten archivos PDF, Word o PowerPoint.';
            }
        }

        if (!$errors) {
            $targetDir = ($visibility === 'private') ? $baseSambaPrivate : $baseSambaPublic;
            
            // Crea la subcarpeta "Campus_Privado" dentro de la home del profe si no existe
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $fileName = time() . '_' . $courseId . '_' . $safeBaseName . '.' . $extension;
            
            if (move_uploaded_file($_FILES['material_file']['tmp_name'], $targetDir . $fileName)) {
                // ... (código de éxito) ...
            } else {
                $errorReal = error_get_last();
                $mensajeError = $errorReal ? $errorReal['message'] : 'Desconocido';
                $errors[] = "Error al guardar en: $targetDir. Razón del sistema: " . $mensajeError;
            }
        }
    }

    // Listar todos los materiales de este profesor
    $stmtMaterials = $pdo->prepare("
        SELECT m.id, m.title, m.file_path, m.visibility, m.created_at, c.code AS course_code, c.group_name AS course_name
        FROM materials m INNER JOIN courses c ON c.id = m.course_id
        WHERE m.uploaded_by_teacher_id = ? ORDER BY m.id DESC
    ");
    $stmtMaterials->execute([$teacherId]);
    $allMaterials = $stmtMaterials->fetchAll();

    $publicMaterials = array_filter($allMaterials, fn($m) => $m['visibility'] === 'course_only');
    $privateMaterials = array_filter($allMaterials, fn($m) => $m['visibility'] === 'private');

    // Variables de plantilla
    $pageTitle = 'Materiales';
    $pageSubtitle = 'Sube y consulta materiales de tus asignaturas';
    $pageStylesheet = '/assets/css/student-dashboard.css';
    $currentSection = 'materials';
    $userRole = 'Profesor';

    include __DIR__ . '/../../templates/private-header.php';
?>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel panel-wide">
        <div class="panel__header"><div><p class="panel__eyebrow">Profesor</p><h2>Subir material</h2></div></div>

        <?php if ($errors): foreach ($errors as $error): ?>
            <div class="notice-item" style="border-left-color:#dc3545;"><strong>Error</strong><span><?= htmlspecialchars($error) ?></span></div>
        <?php endforeach; endif; ?>
        
        <?php if ($successMessage): ?>
            <div class="notice-item" style="border-left-color:#28a745;"><strong>Correcto</strong><span><?= htmlspecialchars($successMessage) ?></span></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="material-form">
            <div class="form-row">
                <label>Asignatura</label>
                <select name="course_id" required>
                    <option value="">Selecciona una asignatura</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= (int)$course['id'] ?>"><?= htmlspecialchars($course['code'] . ' - ' . $course['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row"><label>Título</label><input type="text" name="title" required></div>
            <div class="form-row">
                <label>Visibilidad (Destino en Servidor de Archivos)</label>
                <select name="visibility" required>
                    <option value="course_only">Público (Para los alumnos de la asignatura)</option>
                    <option value="private">Privado (En mi unidad de red personal H:)</option>
                </select>
            </div>
            <div class="form-row"><label>Archivo (PDF, Word, PPT)</label><input type="file" name="material_file" accept=".pdf,.doc,.docx,.ppt,.pptx" required></div>
            <div class="form-actions"><button type="submit" class="panel-link-button">Subir material</button></div>
        </form>
    </article>
</section>

<section class="panel" style="margin-bottom: 20px;">
    <div class="panel__header"><div><p class="panel__eyebrow">Mi Unidad Personal</p><h2>Archivos Privados</h2></div></div>
    <div class="notice-list">
        <?php if (!$privateMaterials): ?>
             <div class="notice-item"><span>No tienes archivos en tu carpeta privada.</span></div>
        <?php else: ?>
            <?php foreach ($privateMaterials as $material): ?>
                <div class="notice-item" style="border-left-color: #ffc107;">
                    <strong>🔒 <?= htmlspecialchars($material['title']) ?></strong>
                    <span><?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name']) ?></span>
                    <div style="margin-top:10px;"><a href="/descargar.php?id=<?= $material['id'] ?>" target="_blank" class="panel-link-button">⬇️ Descargar Privado</a></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="panel">
    <div class="panel__header"><div><p class="panel__eyebrow">Directorio Público</p><h2>Archivos de Asignaturas</h2></div></div>
    <div class="notice-list">
        <?php if (!$publicMaterials): ?>
             <div class="notice-item"><span>No has subido archivos públicos.</span></div>
        <?php else: ?>
            <?php foreach ($publicMaterials as $material): ?>
                <div class="notice-item" style="border-left-color: #17a2b8;">
                    <strong>🌍 <?= htmlspecialchars($material['title']) ?></strong>
                    <span><?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name']) ?></span>
                    <div style="margin-top:10px;"><a href="/descargar.php?id=<?= $material['id'] ?>" target="_blank" class="panel-link-button">⬇️ Descargar Público</a></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; } catch (\Throwable $e) { die("<div style='padding:20px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'><h3>💥 Error Crítico Detectado 💥</h3><p><strong>Mensaje:</strong> " . $e->getMessage() . "</p><p><strong>Línea:</strong> " . $e->getLine() . "</p></div>"); } ?>