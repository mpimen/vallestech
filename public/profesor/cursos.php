<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];

$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Profesor'));
$role = trim((string)($currentUser['role'] ?? 'teacher'));
$username = trim((string)($currentUser['username'] ?? ''));

$pageTitle = 'Asignaturas';
$pageSubtitle = 'Materias, grupos y organización docente.';
$pageStylesheet = '/assets/css/teacher-courses.css';
$currentSection = 'courses';
$userName = $displayName;
$userRole = $role === 'teacher' ? 'Profesor' : ucfirst($role);

$courses = [];
$totalCourses = 0;
$totalStudents = 0;
$averageProgress = 0;
$dbError = '';

$configPath = __DIR__ . '/../../config/database.php';

if (!file_exists($configPath)) {
    $dbError = 'No se encontró el archivo /config/database.php.';
} elseif ($username === '') {
    $dbError = 'No se encontró el username del profesor en la sesión.';
} elseif ($role !== 'teacher') {
    $dbError = 'El usuario actual no tiene rol de profesor.';
} else {
    try {
        require_once $configPath;

        if (!function_exists('getDb')) {
            throw new RuntimeException('El archivo /config/database.php no define la función getDb().');
        }

        $pdo = getDb();

        $sql = "
            SELECT
                c.id,
                c.code,
                c.group_name,
                c.academic_year,
                c.description,
                c.active,
                s.name AS subject_name,
                COUNT(DISTINCT CASE WHEN e.status = 'active' THEN e.student_id END) AS students_count,
                COUNT(DISTINCT m.id) AS materials_count,
                COUNT(DISTINCT CASE WHEN g.published = 1 THEN g.id END) AS published_grades_count
            FROM users u
            INNER JOIN teachers t
                ON t.user_id = u.id
            INNER JOIN courses c
                ON c.teacher_id = t.id
            INNER JOIN subjects s
                ON s.id = c.subject_id
            LEFT JOIN enrollments e
                ON e.course_id = c.id
            LEFT JOIN materials m
                ON m.course_id = c.id
            LEFT JOIN grades g
                ON g.course_id = c.id
            WHERE u.username = :username
              AND u.role = 'teacher'
              AND u.active = 1
            GROUP BY
                c.id,
                c.code,
                c.group_name,
                c.academic_year,
                c.description,
                c.active,
                s.name
            ORDER BY c.active DESC, s.name ASC, c.group_name ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
        ]);

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $materialsCount = (int)($row['materials_count'] ?? 0);
            $publishedGradesCount = (int)($row['published_grades_count'] ?? 0);
            $studentsCount = (int)($row['students_count'] ?? 0);

            $progress = min(100, ($materialsCount * 15) + ($publishedGradesCount * 10));

            $title = trim((string)($row['subject_name'] ?? 'Asignatura sin nombre'));
            $groupName = trim((string)($row['group_name'] ?? ''));
            $academicYear = trim((string)($row['academic_year'] ?? ''));
            $description = trim((string)($row['description'] ?? ''));

            $extraInfoParts = [];

            if ($groupName !== '') {
                $extraInfoParts[] = 'Grupo ' . $groupName;
            }

            if ($academicYear !== '') {
                $extraInfoParts[] = 'Curso ' . $academicYear;
            }

            if ($description === '') {
                $description = 'Sin descripción disponible.';
            }

            if (!empty($extraInfoParts)) {
                $description .= ' (' . implode(' · ', $extraInfoParts) . ')';
            }

            $courses[] = [
                'id' => (int)$row['id'],
                'tag' => (string)($row['code'] ?? 'N/A'),
                'title' => $title,
                'description' => $description,
                'students' => $studentsCount,
                'progress' => $progress,
                'status' => ((int)$row['active'] === 1) ? 'Activa' : 'Inactiva',
                'accent' => ((int)$row['active'] === 1) ? 'primary' : 'warning',
            ];
        }
    } catch (Throwable $e) {
        $dbError = $e->getMessage();
    }
}

$totalCourses = count($courses);
$totalStudents = array_sum(array_column($courses, 'students'));
$averageProgress = $totalCourses > 0
    ? (int) round(array_sum(array_column($courses, 'progress')) / $totalCourses)
    : 0;

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="teacher-hero">
    <article class="teacher-hero__card">
        <div>
            <p class="teacher-hero__eyebrow">Docencia</p>
            <h2>Asignaturas y grupos a tu cargo</h2>
            <p>
                Esta vista resume la carga docente, el estado de los grupos y el avance general de cada materia.
            </p>
        </div>

        <div class="teacher-hero__stats">
            <div class="teacher-stat">
                <strong><?= htmlspecialchars((string) $totalCourses) ?></strong>
                <span>Asignaturas activas</span>
            </div>
            <div class="teacher-stat">
                <strong><?= htmlspecialchars((string) $totalStudents) ?></strong>
                <span>Estudiantes totales</span>
            </div>
            <div class="teacher-stat">
                <strong><?= htmlspecialchars((string) $averageProgress) ?>%</strong>
                <span>Progreso medio</span>
            </div>
        </div>
    </article>
</section>

<section class="teacher-cards">
    <?php if ($dbError !== ''): ?>
        <article class="course-card">
            <span class="course-card__tag">Base de datos</span>
            <h3>No se pudieron cargar las asignaturas</h3>
            <p><?= htmlspecialchars($dbError) ?></p>
        </article>
    <?php elseif (empty($courses)): ?>
        <article class="course-card">
            <span class="course-card__tag">Sin datos</span>
            <h3>No tienes asignaturas asignadas</h3>
            <p>Cuando se añadan cursos en la base de datos con tu usuario como profesor, aparecerán aquí.</p>
        </article>
    <?php else: ?>
        <?php foreach ($courses as $course): ?>
            <article class="course-card course-card--<?= htmlspecialchars($course['accent']) ?>">
                <span class="course-card__tag"><?= htmlspecialchars($course['tag']) ?></span>
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p><?= htmlspecialchars($course['description']) ?></p>

                <div class="course-card__meta">
                    <span><?= htmlspecialchars((string) $course['students']) ?> estudiantes</span>
                    <strong><?= htmlspecialchars((string) $course['progress']) ?>% completado</strong>
                </div>

                <div class="course-card__footer">
                    <span class="course-card__status"><?= htmlspecialchars($course['status']) ?></span>
                    <a href="curso.php?id=<?= (int)$course['id'] ?>" class="course-card__link">Entrar</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>