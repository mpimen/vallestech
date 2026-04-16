<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Auth/Session.php';

use Auth\Session;

Session::start();

$user = Session::get('user');

if (!$user) {
    header('Location: /index.php');
    exit;
}

$userRole = $user['role'] ?? $user['userRole'] ?? $user['role_label'] ?? '';
$normalizedRole = mb_strtolower((string)$userRole, 'UTF-8');

if (!in_array($normalizedRole, ['alumno', 'student'], true)) {
    http_response_code(403);
    exit('Acceso denegado.');
}

$studentUsername = $user['username'] ?? '';
$userName = $user['name'] ?? $user['full_name'] ?? $studentUsername ?? 'Alumno';

$pageTitle = 'Materiales';
$pageSubtitle = 'Consulta los materiales de tus asignaturas';
$pageStylesheet = '/assets/css/student-dashboard.css';
$currentSection = 'materials';
$userRole = 'Alumno';

$pdo = getDb();

$stmtMaterials = $pdo->prepare("
    SELECT
        m.id,
        m.title,
        m.description,
        m.file_path,
        m.visibility,
        m.created_at,
        c.code AS course_code,
        c.name AS course_name,
        c.teacher_username
    FROM materials m
    INNER JOIN courses c ON c.id = m.course_id
    INNER JOIN enrollments e ON e.course_id = c.id
    WHERE e.student_username = :student_username
      AND e.status = 'active'
      AND m.visibility = 'course_only'
    ORDER BY m.created_at DESC, m.id DESC
");
$stmtMaterials->execute([
    ':student_username' => $studentUsername
]);
$materials = $stmtMaterials->fetchAll();

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="panel">
    <div class="panel__header">
        <div>
            <p class="panel__eyebrow">Alumno</p>
            <h2>Materiales disponibles</h2>
        </div>
    </div>

    <div class="notice-list">
        <?php if (!$materials): ?>
            <div class="notice-item">
                <strong>Sin materiales</strong>
                <span>No hay materiales publicados para tus asignaturas.</span>
            </div>
        <?php else: ?>
            <?php foreach ($materials as $material): ?>
                <div class="notice-item">
                    <strong><?= htmlspecialchars($material['title']) ?></strong>
                    <span>
                        <?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name']) ?>
                        · Profesor: <?= htmlspecialchars($material['teacher_username']) ?>
                        · <?= htmlspecialchars((string)$material['created_at']) ?>
                    </span>

                    <?php if (!empty($material['description'])): ?>
                        <p><?= nl2br(htmlspecialchars($material['description'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>