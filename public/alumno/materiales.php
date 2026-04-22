<?php
declare(strict_types=1);

// Importaciones siempre fuera del try
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
    $normalizedRole = strtolower(trim((string)$rawRole));

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

    // Consulta adaptada: c.group_name como course_name y restringido a 'course_only'
    // Nota: Si la tabla enrollments usa student_id en vez de student_username, dímelo
    $stmtMaterials = $pdo->prepare("
        SELECT m.id, m.title, m.description, m.file_path, m.created_at, c.code AS course_code, c.group_name AS course_name
        FROM materials m
        INNER JOIN courses c ON c.id = m.course_id
        INNER JOIN enrollments e ON e.course_id = c.id
        WHERE e.student_username = :student_username 
          AND e.status = 'active' 
          AND m.visibility = 'course_only'
        ORDER BY m.created_at DESC, m.id DESC
    ");
    $stmtMaterials->execute([':student_username' => $studentUsername]);
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
                <span>No hay materiales públicos publicados para tus asignaturas.</span>
            </div>
        <?php else: ?>
            <?php foreach ($materials as $material): ?>
                <div class="notice-item" style="border-left-color: #17a2b8;">
                    <strong>🌍 <?= htmlspecialchars($material['title']) ?></strong>
                    <span><?= htmlspecialchars($material['course_code'] . ' · ' . $material['course_name']) ?></span>
                    
                    <?php if (!empty($material['description'])): ?>
                        <p style="margin: 5px 0; color: #666; font-size: 0.9em;">
                            <?= nl2br(htmlspecialchars($material['description'])) ?>
                        </p>
                    <?php endif; ?>
                    
                    <div style="margin-top:10px;">
                        <a href="/descargar.php?id=<?= $material['id'] ?>" target="_blank" class="panel-link-button">⬇️ Descargar Archivo</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php 
    include __DIR__ . '/../../templates/private-footer.php'; 

} catch (\Throwable $e) {
    die("<div style='padding:20px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
            <h3>💥 Error Crítico Detectado 💥</h3>
            <p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>
            <p><strong>Línea:</strong> " . $e->getLine() . "</p>
        </div>");
}
?>