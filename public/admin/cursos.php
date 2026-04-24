<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];
$displayName = trim((string) ($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$role = trim((string) ($currentUser['role'] ?? 'admin'));
$isAdmin = in_array(mb_strtolower($role, 'UTF-8'), ['admin', 'administrador'], true);

$pageTitle = 'Cursos';
$pageSubtitle = 'Consulta todos los cursos creados en el sistema.';
$pageStylesheet = '/assets/css/admin-courses.css';
$currentSection = 'courses';
$userName = $displayName !== '' ? $displayName : 'Administrador';
$userRole = $isAdmin ? 'Administrador' : ucfirst($role);

$courses = [];
$errors = [];

try {
    require_once __DIR__ . '/../../config/database.php';

    if (function_exists('getDb')) {
        $pdo = getDb();
    } elseif (isset($pdo) && $pdo instanceof PDO) {
        $pdo = $pdo;
    } else {
        throw new RuntimeException('No se pudo obtener la conexión con la base de datos.');
    }

    $sql = "
        SELECT
            c.id,
            c.code,
            c.group_name,
            c.academic_year,
            c.description,
            c.active,
            s.name AS subject_name,
            s.code AS subject_code,
            u.display_name AS teacher_name,
            u.username AS teacher_username,
            COUNT(e.id) AS students_count
        FROM courses c
        INNER JOIN subjects s ON s.id = c.subject_id
        INNER JOIN teachers t ON t.id = c.teacher_id
        INNER JOIN users u ON u.id = t.user_id
        LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = 'active'
        GROUP BY
            c.id,
            c.code,
            c.group_name,
            c.academic_year,
            c.description,
            c.active,
            s.name,
            s.code,
            u.display_name,
            u.username
        ORDER BY c.academic_year DESC, s.name ASC, c.group_name ASC
    ";

    $stmt = $pdo->query($sql);
    $courses = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) {
    $errors[] = $e->getMessage();
}

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="page-hero">
    <article class="admin-hero__card">
        <div>
            <p class="admin-hero__eyebrow">Administración</p>
            <h2>Listado de cursos</h2>
            <p>
                Consulta todas las asignaturas dadas de alta, con su profesor responsable,
                grupo y número de alumnos matriculados.
            </p>
        </div>

        <div class="admin-hero__stats">
            <div class="admin-stat">
                <strong><?= htmlspecialchars((string) count($courses)) ?></strong>
                <span>Cursos registrados</span>
            </div>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <div class="panel-head">
            <div>
                <p class="panel-head__eyebrow">Catálogo académico</p>
                <h2>Todos los cursos</h2>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="form-alert form-alert--error">
                <strong>No se pudo cargar el listado:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (empty($errors) && empty($courses)): ?>
            <div class="info-note">
                No hay cursos creados todavía.
            </div>
        <?php endif; ?>

        <?php if (!empty($courses)): ?>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Asignatura</th>
                            <th>Código</th>
                            <th>Grupo</th>
                            <th>Año académico</th>
                            <th>Profesor</th>
                            <th>Alumnos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <?php $isActive = (int) $course['active'] === 1; ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $course['id']) ?></td>
                                <td><?= htmlspecialchars((string) $course['subject_name']) ?></td>
                                <td><?= htmlspecialchars((string) ($course['subject_code'] ?: $course['code'])) ?></td>
                                <td><?= htmlspecialchars((string) $course['group_name']) ?></td>
                                <td><?= htmlspecialchars((string) $course['academic_year']) ?></td>
                                <td>
                                    <?= htmlspecialchars((string) $course['teacher_name']) ?><br>
                                    <small><?= htmlspecialchars((string) $course['teacher_username']) ?></small>
                                </td>
                                <td><?= htmlspecialchars((string) $course['students_count']) ?></td>
                                <td>
                                    <span class="status-badge <?= $isActive ? 'status-badge--active' : 'status-badge--inactive' ?>">
                                        <?= $isActive ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>