<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];

$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Alumno'));
$role = trim((string)($currentUser['role'] ?? 'student'));
$username = trim((string)($currentUser['username'] ?? ''));

$pageTitle = 'Mis asignaturas';
$pageSubtitle = 'Resumen de materias, profesorado y progreso del curso.';
$pageStylesheet = '/assets/css/student-courses.css';
$currentSection = 'courses';
$userName = $displayName;
$userRole = $role === 'student' ? 'Alumno' : ucfirst($role);

$courses = [];
$totalCourses = 0;
$totalDeliveries = 0;
$averageProgress = 0;
$dbError = '';

$configPath = __DIR__ . '/../../config/database.php';

if (!file_exists($configPath)) {
    $dbError = 'No se encontró el archivo /config/database.php.';
} elseif ($username === '') {
    $dbError = 'No se encontró el username del alumno en la sesión.';
} elseif ($role !== 'student') {
    $dbError = 'El usuario actual no tiene rol de alumno.';
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
                e.status AS enrollment_status,
                s.name AS subject_name,
                tu.display_name AS teacher_name,
                COUNT(DISTINCT m.id) AS materials_count,
                COUNT(DISTINCT CASE WHEN g.published = 1 THEN g.id END) AS published_grades_count,
                AVG(CASE WHEN g.published = 1 THEN g.grade_value END) AS student_avg_grade
            FROM users u
            INNER JOIN students st
                ON st.user_id = u.id
            INNER JOIN enrollments e
                ON e.student_id = st.id
            INNER JOIN courses c
                ON c.id = e.course_id
            INNER JOIN subjects s
                ON s.id = c.subject_id
            INNER JOIN teachers t
                ON t.id = c.teacher_id
            INNER JOIN users tu
                ON tu.id = t.user_id
            LEFT JOIN materials m
                ON m.course_id = c.id
            LEFT JOIN grades g
                ON g.course_id = c.id
               AND g.student_id = st.id
            WHERE u.username = :username
              AND u.role = 'student'
              AND u.active = 1
            GROUP BY
                c.id,
                c.code,
                c.group_name,
                c.academic_year,
                c.description,
                c.active,
                e.status,
                s.name,
                tu.display_name
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
            $studentAvgGrade = isset($row['student_avg_grade']) ? (float)$row['student_avg_grade'] : null;

            $progress = 0;

            if ($studentAvgGrade !== null) {
                $progress = (int) round(min(100, $studentAvgGrade * 10));
            } else {
                $progress = min(100, ($materialsCount * 15) + ($publishedGradesCount * 10));
            }

            $title = trim((string)($row['subject_name'] ?? 'Asignatura sin nombre'));
            $teacherName = trim((string)($row['teacher_name'] ?? 'Profesor no disponible'));
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

            $status = ((int)$row['active'] === 1 && ($row['enrollment_status'] ?? '') === 'active')
                ? 'Activa'
                : 'No activa';

            $courses[] = [
                'id' => (int)$row['id'],
                'tag' => (string)($row['code'] ?? 'N/A'),
                'title' => $title,
                'description' => $description,
                'teacher' => $teacherName,
                'progress' => $progress,
                'status' => $status,
                'materials_count' => $materialsCount,
                'published_grades_count' => $publishedGradesCount,
            ];
        }
    } catch (Throwable $e) {
        $dbError = $e->getMessage();
    }
}

$totalCourses = count($courses);
$totalDeliveries = array_sum(array_map(
    static fn(array $course): int => $course['published_grades_count'] > 0 ? 1 : 0,
    $courses
));
$averageProgress = $totalCourses > 0
    ? (int) round(array_sum(array_column($courses, 'progress')) / $totalCourses)
    : 0;

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="page-hero">
    <article class="hero-card">
        <div>
            <p class="hero-card__eyebrow">Área académica</p>
            <h2>Asignaturas activas del semestre</h2>
            <p>
                Consulta las materias en curso, su estado general y el profesorado responsable desde un panel pensado para orientarte rápido.
            </p>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <strong><?= htmlspecialchars((string) $totalCourses) ?></strong>
                <span>Asignaturas</span>
            </div>
            <div class="hero-stat">
                <strong><?= htmlspecialchars((string) $totalDeliveries) ?></strong>
                <span>Con notas publicadas</span>
            </div>
            <div class="hero-stat">
                <strong><?= htmlspecialchars((string) $averageProgress) ?>%</strong>
                <span>Progreso medio</span>
            </div>
        </div>
    </article>
</section>

<section class="cards-grid">
    <?php if ($dbError !== ''): ?>
        <article class="course-card">
            <span class="course-card__tag">Base de datos</span>
            <h3>No se pudieron cargar tus asignaturas</h3>
            <p><?= htmlspecialchars($dbError) ?></p>
        </article>
    <?php elseif (empty($courses)): ?>
        <article class="course-card">
            <span class="course-card__tag">Sin datos</span>
            <h3>No tienes asignaturas matriculadas</h3>
            <p>Cuando se registren matrículas activas para tu usuario, aparecerán aquí.</p>
        </article>
    <?php else: ?>
        <?php foreach ($courses as $course): ?>
            <article class="course-card">
                <span class="course-card__tag"><?= htmlspecialchars($course['tag']) ?></span>
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <div class="course-card__meta">
                    <span>Profesor: <?= htmlspecialchars($course['teacher']) ?></span>
                    <strong><?= htmlspecialchars((string) $course['progress']) ?>%</strong>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>