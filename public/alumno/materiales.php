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
    if (!in_array(strtolower(trim((string)$rawRole)), ['alumno', 'student'], true)) {
        http_response_code(403);
        exit('Acceso denegado.');
    }

    $studentUsername = $user['username'] ?? '';
    $userName = $user['name'] ?? $user['full_name'] ?? $studentUsername ?? 'Alumno';
    
    $pdo = getDb();
    
    // --- JIT PROVISIONING ---
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmtUser->execute([$studentUsername]);
    $dbStudentId = $stmtUser->fetchColumn();

    if (!$dbStudentId) {
        $stmtInsert = $pdo->prepare("INSERT INTO users (username, display_name, role) VALUES (?, ?, 'student')");
        $stmtInsert->execute([$studentUsername, $userName]);
        $dbStudentId = $pdo->lastInsertId();
    }
    $studentId = (int)$dbStudentId;

    $errors = [];
    $successMessage = '';

    // --- RUTAS SAMBA ---
    $baseSambaPublic = '/mnt/samba/publico/';
    $baseSambaPrivate = '/mnt/samba/homes/' . $studentUsername . '/Campus_Privado/';
    $baseSambaSubmissions = '/mnt/samba/homes/' . $studentUsername . '/Entregas_Tareas/';

    // Obtener cursos matriculados
    $stmtCourses = $pdo->prepare("
        SELECT c.id, c.code, c.group_name 
        FROM courses c
        INNER JOIN enrollments e ON e.course_id = c.id
        WHERE e.student_id = ? AND e.status = 'active'
    ");
    $stmtCourses->execute([$studentId]);
    $myCourses = $stmtCourses->fetchAll();

    // --- PROCESAMIENTO (SUBIR PRIVADO / ENTREGAR TAREA / BORRAR) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        // 1. ELIMINAR ARCHIVO PRIVADO
        if ($action === 'delete_private') {
            $fileId = (int)$_POST['file_id'];
            // CORREGIDO: Buscamos por student_id
            $stmtFile = $pdo->prepare("SELECT file_path FROM materials WHERE id = ? AND student_id = ? AND visibility = 'private'");
            $stmtFile->execute([$fileId, $studentId]);
            $file = $stmtFile->fetch();
            
            if ($file) {
                if (file_exists($baseSambaPrivate . $file['file_path'])) {
                    unlink($baseSambaPrivate . $file['file_path']);
                }
                $pdo->prepare("DELETE FROM materials WHERE id = ?")->execute([$fileId]);
                $successMessage = "Archivo personal eliminado.";
            }
        }
        
        // 2. SUBIR PRIVADO O ENTREGAR TAREA
        elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $courseId = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
            $isTask = ($action === 'submit_task');
            
            if ($isTask && !$courseId) {
                $errors[] = "Debes seleccionar una asignatura obligatoriamente para entregar una tarea.";
            } else {
                $targetDir = $isTask ? $baseSambaSubmissions : $baseSambaPrivate;
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . ($isTask ? 'task_' : 'priv_') . bin2hex(random_bytes(4)) . '.' . $ext;

                if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $fileName)) {
                    try {
                        if ($isTask) {
                            $stmt = $pdo->prepare("INSERT INTO submissions (course_id, student_id, title, file_path) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$courseId, $studentId, $_POST['title'], $fileName]);
                            $successMessage = "¡Tarea entregada correctamente al profesor!";
                        } else {
                            // CORREGIDO: Usamos student_id en lugar de uploaded_by_teacher_id
                            $stmt = $pdo->prepare("INSERT INTO materials (course_id, student_id, title, file_path, visibility) VALUES (?, ?, ?, ?, 'private')");
                            $stmt->execute([$courseId, $studentId, $_POST['title'], $fileName]);
                            $successMessage = "Archivo guardado en tu unidad personal (Samba).";
                        }
                    } catch (\PDOException $e) {
                        @unlink($targetDir . $fileName);
                        $errors[] = "Error en la base de datos: " . $e->getMessage();
                    }
                } else {
                    $errors[] = "Error al escribir en Samba. Revisa los permisos.";
                }
            }
        }
    }

    // --- CARGAR DATOS PARA LA VISTA ---
    $stmtMat = $pdo->prepare("
        SELECT m.id, m.title, m.file_path, m.created_at, c.code FROM materials m 
        JOIN courses c ON m.course_id = c.id 
        JOIN enrollments e ON e.course_id = c.id
        WHERE e.student_id = ? AND m.visibility = 'course_only'
    ");
    $stmtMat->execute([$studentId]);
    $teacherMaterials = $stmtMat->fetchAll();

    // Archivos privados (Samba Home) - CORREGIDO: Filtramos por student_id
    $stmtPriv = $pdo->prepare("SELECT m.*, c.code AS course_code FROM materials m LEFT JOIN courses c ON m.course_id = c.id WHERE m.student_id = ? AND m.visibility = 'private'");
    $stmtPriv->execute([$studentId]);
    $myPrivateFiles = $stmtPriv->fetchAll();

    $stmtSub = $pdo->prepare("SELECT s.*, c.code FROM submissions s JOIN courses c ON s.course_id = c.id WHERE s.student_id = ?");
    $stmtSub->execute([$studentId]);
    $mySubmissions = $stmtSub->fetchAll();

    $pageTitle = 'Mis Materiales y Tareas';
    $pageStylesheet = '/assets/css/student-dashboard.css';
    include __DIR__ . '/../../templates/private-header.php';
?>

<section class="dashboard-grid dashboard-grid--secondary">
    <article class="panel">
        <div class="panel__header"><h2>Gestión de Archivos</h2></div>
        
        <?php if ($errors): foreach ($errors as $error): ?>
            <div class="notice-item" style="border-left-color:#dc3545;"><strong>Error</strong><span><?= htmlspecialchars($error) ?></span></div>
        <?php endforeach; endif; ?>
        <?php if ($successMessage): ?><div class="notice-item" style="border-left-color:#28a745;"><?= $successMessage ?></div><?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="material-form">
            <label>Acción</label>
            <select name="action" id="action-select" onchange="toggleCourseSelect()">
                <option value="submit_task">📤 Entregar Tarea a Profesor</option>
                <option value="upload_private">🔒 Guardar en mi Unidad Personal (Samba)</option>
            </select>

            <div id="course-row">
                <label>Asignatura</label>
                <select name="course_id" id="course-select">
                    <option value="">-- Selecciona una asignatura --</option>
                    <?php foreach($myCourses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['code'] . ' - ' . $c['group_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <label>Nombre del archivo</label>
            <input type="text" name="title" required>

            <label>Seleccionar Archivo</label>
            <input type="file" name="file" required>

            <button type="submit" class="panel-link-button">Ejecutar Acción</button>
        </form>
    </article>

    <article class="panel">
        <div class="panel__header"><h2>Material de Asignaturas</h2></div>
        <div class="notice-list">
            <?php if (!$teacherMaterials): ?>
                <div class="notice-item"><span>No hay materiales públicos para tus asignaturas.</span></div>
            <?php else: ?>
                <?php foreach($teacherMaterials as $m): ?>
                    <div class="notice-item" style="border-left-color:#17a2b8;">
                        <strong>🌍 <?= htmlspecialchars($m['title']) ?></strong>
                        <small><?= htmlspecialchars($m['code']) ?></small>
                        <div style="margin-top:10px;"><a href="/descargar.php?id=<?= $m['id'] ?>" class="panel-link-button" target="_blank">⬇️ Descargar</a></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>
</section>

<div class="dashboard-grid" style="margin-top:20px;">
    <article class="panel">
        <div class="panel__header"><h2>Tareas Entregadas</h2></div>
        <div class="notice-list">
            <?php if (!$mySubmissions): ?>
                <div class="notice-item"><span>No has entregado ninguna tarea todavía.</span></div>
            <?php else: ?>
                <?php foreach($mySubmissions as $s): ?>
                    <div class="notice-item" style="border-left-color:#6f42c1;">
                        <strong>✅ <?= htmlspecialchars($s['title']) ?></strong>
                        <small>Enviado el: <?= $s['created_at'] ?> (<?= htmlspecialchars($s['code']) ?>)</small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>

    <article class="panel">
        <div class="panel__header"><h2>Mi Unidad Personal (H:)</h2></div>
        <div class="notice-list">
            <?php if (!$myPrivateFiles): ?>
                <div class="notice-item"><span>No tienes archivos en tu carpeta privada.</span></div>
            <?php else: ?>
                <?php foreach($myPrivateFiles as $p): ?>
                    <div class="notice-item" style="border-left-color:#ffc107;">
                        <strong>🔒 <?= htmlspecialchars($p['title']) ?></strong>
                        <small><?= htmlspecialchars($p['course_code'] ?? 'Archivo Libre') ?></small>
                        <div style="display:flex; gap:10px; margin-top:10px; align-items: center;">
                            <a href="/descargar.php?id=<?= $p['id'] ?>" class="panel-link-button" target="_blank">⬇️ Bajar</a>
                            <form method="post" style="margin: 0;" onsubmit="return confirm('¿Borrar este archivo para siempre de tu Samba?')">
                                <input type="hidden" name="action" value="delete_private">
                                <input type="hidden" name="file_id" value="<?= $p['id'] ?>">
                                <button type="submit" class="panel-link-button" style="background-color: transparent; color: #dc3545; border-color: #dc3545;">🗑️ Borrar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>
</div>

<script>
function toggleCourseSelect() {
    const action = document.getElementById('action-select').value;
    const courseRow = document.getElementById('course-row');
    const courseSelect = document.getElementById('course-select');
    
    if (action === 'submit_task') {
        courseRow.style.display = 'block';
        courseSelect.required = true;
    } else {
        courseRow.style.display = 'none';
        courseSelect.required = false;
        courseSelect.value = ""; 
    }
}
toggleCourseSelect(); 
</script>

<?php 
    include __DIR__ . '/../../templates/private-footer.php'; 
} catch (\Throwable $e) { 
    die("<div style='padding:20px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'><h3>💥 Error Crítico Detectado 💥</h3><p><strong>Mensaje:</strong> " . $e->getMessage() . "</p><p><strong>Línea:</strong> " . $e->getLine() . "</p></div>"); 
} 
?>