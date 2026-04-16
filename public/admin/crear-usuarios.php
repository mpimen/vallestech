<?php
$pageTitle = 'Crear usuario';
$pageSubtitle = 'Alta manual de alumnos y profesores.';
$pageStylesheet = '/assets/css/admin-create-user.css';
$currentSection = 'create-user';
$userName = 'Laura Gómez';
$userRole = 'Admin';

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="create-hero">
    <article class="create-hero__card">
        <div>
            <p class="create-hero__eyebrow">Alta de cuentas</p>
            <h2>Crear nuevo usuario del campus</h2>
            <p>
                Prepara desde aquí el alta de alumnos y profesores antes de enlazarlo con LDAP o almacenamiento real.
            </p>
        </div>

        <div class="create-summary">
            <div class="summary-pill">
                <strong>Manual</strong>
                <span>Alta individual</span>
            </div>
            <div class="summary-pill">
                <strong>Roles</strong>
                <span>Alumno y profesor</span>
            </div>
        </div>
    </article>
</section>

<section class="create-layout">
    <article class="form-card">
        <div class="section-head">
            <p class="section-head__eyebrow">Formulario</p>
            <h2>Datos del usuario</h2>
        </div>

        <form class="user-form" action="#" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Nombre</label>
                    <input type="text" id="first_name" name="first_name" placeholder="Laura">
                </div>

                <div class="form-group">
                    <label for="last_name">Apellidos</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Gómez Ruiz">
                </div>

                <div class="form-group">
                    <label for="email">Correo institucional</label>
                    <input type="email" id="email" name="email" placeholder="usuario@campus.local">
                </div>

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select id="role" name="role">
                        <option value="">Selecciona un rol</option>
                        <option value="student">Alumno</option>
                        <option value="teacher">Profesor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Departamento o grupo</label>
                    <input type="text" id="department" name="department" placeholder="Informática / 2º DAW">
                </div>

                <div class="form-group">
                    <label for="status">Estado inicial</label>
                    <select id="status" name="status">
                        <option value="active">Activo</option>
                        <option value="pending">Pendiente</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Crear usuario</button>
            </div>
        </form>
    </article>

    <aside class="info-card">
        <h3>Notas</h3>
        <ul>
            <li>Preparado para alta manual visual.</li>
            <li>Después podrás conectarlo a LDAP o base de datos.</li>
            <li>El rol marcará la navegación y permisos futuros.</li>
        </ul>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>