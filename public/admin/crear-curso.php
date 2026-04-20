<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$projectRoot = dirname(__DIR__, 2);
$configPath = $projectRoot . '/config/database.php';
$headerPath = $projectRoot . '/templates/private-header.php';
$footerPath = $projectRoot . '/templates/private-footer.php';

$currentUser = $_SESSION['user'] ?? [];

$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$role = trim((string)($currentUser['role'] ?? 'admin'));

$pageTitle = 'Crear curso';
$pageSubtitle = 'Alta de asignaturas, asignación de profesorado y matrícula de alumnos.';
$pageStylesheet = '/assets/css/admin-create-course.css';
$currentSection = 'create-course';
$userName = $displayName;
$userRole = $role === 'admin' ? 'Administrador' : ucfirst($role);

$teachers = [];
$students = [];
$errors = [];
$successMessage = '';

$subjectName = '';
$subjectCode = '';
$groupName = '';
$academicYear = date('Y') . '/' . (date('Y') + 1);
$description = '';
$teacherId = '';
$selectedStudents = [];

if (!file_exists($configPath)) {
    $errors[] = 'No se encontró el archivo /config/database.php.';
} elseif (!file_exists($headerPath) || !file_exists($footerPath)) {
    $errors[] = 'No se encontraron las plantillas del layout privado.';
} else {
    require_once $configPath;

    if (!function_exists('getDb')) {
        $errors[] = 'El archivo /config/database.php no define la función getDb().';
    } else {
        try {
            $pdo = getDb();

            $teachersStmt = $pdo->query("
                SELECT t.id, u.display_name, u.username
                FROM teachers t
                INNER JOIN users u ON u.id = t.user_id
                WHERE u.active = 1
                ORDER BY u.display_name ASC, u.username ASC
            ");
            $teachers = $teachersStmt->fetchAll();

            $studentsStmt = $pdo->query("
                SELECT st.id, u.display_name, u.username
                FROM students st
                INNER JOIN users u ON u.id = st.user_id
                WHERE u.active = 1
                ORDER BY u.display_name ASC, u.username ASC
            ");
            $students = $studentsStmt->fetchAll();
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $subjectName = trim((string)($_POST['subject_name'] ?? ''));
    $subjectCode = strtoupper(trim((string)($_POST['subject_code'] ?? '')));
    $groupName = trim((string)($_POST['group_name'] ?? ''));
    $academicYear = trim((string)($_POST['academic_year'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $teacherId = trim((string)($_POST['teacher_id'] ?? ''));
    $selectedStudents = array_map('intval', $_POST['student_ids'] ?? []);

    if ($role !== 'admin') {
        $errors[] = 'Solo un administrador puede crear cursos.';
    }

    if ($subjectName === '') {
        $errors[] = 'El nombre de la asignatura es obligatorio.';
    }

    if ($subjectCode === '') {
        $errors[] = 'El código de la asignatura es obligatorio.';
    }

    if ($groupName === '') {
        $errors[] = 'El grupo es obligatorio.';
    }

    if ($academicYear === '') {
        $errors[] = 'El año académico es obligatorio.';
    }

    if ($teacherId === '' || !ctype_digit($teacherId)) {
        $errors[] = 'Debes seleccionar un profesor.';
    }

    if (empty($selectedStudents)) {
        $errors[] = 'Debes seleccionar al menos un alumno.';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $subjectStmt = $pdo->prepare("SELECT id FROM subjects WHERE code = :code LIMIT 1");
            $subjectStmt->execute([':code' => $subjectCode]);
            $existingSubject = $subjectStmt->fetch();

            if ($existingSubject) {
                $subjectId = (int)$existingSubject['id'];
                $updateSubjectStmt = $pdo->prepare("UPDATE subjects SET name = :name WHERE id = :id");
                $updateSubjectStmt->execute([':name' => $subjectName, ':id' => $subjectId]);
            } else {
                $insertSubjectStmt = $pdo->prepare("INSERT INTO subjects (name, code) VALUES (:name, :code)");
                $insertSubjectStmt->execute([':name' => $subjectName, ':code' => $subjectCode]);
                $subjectId = (int)$pdo->lastInsertId();
            }

            $insertCourseStmt = $pdo->prepare("
                INSERT INTO courses (subject_id, teacher_id, code, group_name, academic_year, description, active)
                VALUES (:subject_id, :teacher_id, :code, :group_name, :academic_year, :description, 1)
            ");
            $insertCourseStmt->execute([
                ':subject_id' => $subjectId,
                ':teacher_id' => (int)$teacherId,
                ':code' => $subjectCode,
                ':group_name' => $groupName,
                ':academic_year' => $academicYear,
                ':description' => $description !== '' ? $description : null,
            ]);

            $courseId = (int)$pdo->lastInsertId();

            $insertEnrollmentStmt = $pdo->prepare("
                INSERT INTO enrollments (student_id, course_id, status)
                VALUES (:student_id, :course_id, 'active')
            ");

            foreach (array_unique($selectedStudents) as $studentId) {
                $insertEnrollmentStmt->execute([
                    ':student_id' => $studentId,
                    ':course_id' => $courseId,
                ]);
            }

            $pdo->commit();

            $successMessage = 'Curso creado correctamente con su profesor y alumnos asignados.';

            $subjectName = '';
            $subjectCode = '';
            $groupName = '';
            $academicYear = date('Y') . '/' . (date('Y') + 1);
            $description = '';
            $teacherId = '';
            $selectedStudents = [];
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = $e->getMessage();
        }
    }
}

include $headerPath;
?>

<section class="page-hero">
    <article class="admin-hero__card">
        <div>
            <p class="admin-hero__eyebrow">Administración</p>
            <h2>Crear curso</h2>
            <p>Da de alta una asignatura, asigna profesorado y matricula alumnos desde una sola pantalla.</p>
        </div>
        <div class="admin-hero__stats">
            <div class="admin-stat">
                <strong><?= count($teachers) ?></strong>
                <span>Profesores disponibles</span>
            </div>
            <div class="admin-stat">
                <strong><?= count($students) ?></strong>
                <span>Alumnos disponibles</span>
            </div>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <div class="panel-head">
            <p class="panel-head__eyebrow">Nuevo curso</p>
            <h2>Formulario de creación</h2>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="form-alert form-alert--error">
                <strong>No se pudo guardar el curso:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        ><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($successMessage !== ''): ?>
            <div class="form-alert form-alert--success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-grid">
                abel>
                    <span>Nombre de la asignatura</span>
                    <input type="text" name="subject_name" value="<?= htmlspecialchars($subjectName) ?>" required>
                </label>

                abel>
                    <span>Código de la asignatura</span>
                    <input type="text" name="subject_code" value="<?= htmlspecialchars($subjectCode) ?>" required>
                </label>

                abel>
                    <span>Grupo</span>
                    <input type="text" name="group_name" value="<?= htmlspecialchars($groupName) ?>" required>
                </label>

                abel>
                    <span>Año académico</span>
                    <input type="text" name="academic_year" value="<?= htmlspecialchars($academicYear) ?>" required>
                </label>

                abel class="form-grid__full">
                    <span>Profesor asignado</span>
                    <select name="teacher_id" required>
                        <option value="">Selecciona un profesor</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= (int)$teacher['id'] ?>" <?= $teacherId === (string)$teacher['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($teacher['display_name'] . ' (' . $teacher['username'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                abel class="form-grid__full">
                    <span>Descripción</span>
                    <textarea name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
                </label>
            </div>

            <div class="students-picker">
                <div class="panel-head">
                    <p class="panel-head__eyebrow">Matrícula</p>
                    <h2>Selecciona alumnos</h2>
                </div>

                <div class="students-list">
                    <?php if (empty($students)): ?>
                        <p>No hay alumnos disponibles para matricular.</p>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            abel class="student-option">
                                <input
                                    type="checkbox"
                                    name="student_ids[]"
                                    value="<?= (int)$student['id'] ?>"
                                    <?= in_array((int)$student['id'], $selectedStudents, true) ? 'checked' : '' ?>
                                >
                                <span><?= htmlspecialchars($student['display_name'] . ' (' . $student['username'] . ')') ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Crear curso</button>
            </div>
        </form>
    </article>

    <aside class="admin-side">
        <section class="side-card">
            <h3>Flujo recomendado</h3>
            <ul>
                ><a href="/admin/cursos.php">Ver cursos</a></li>
                ><a href="/admin/crear-curso.php">Crear nuevo curso</a></li>
                ><a href="/admin/usuarios.php">Gestionar usuarios</a></li>
            </ul>
        </section>
    </aside>
</section>

<?php include $footerPath; ?>