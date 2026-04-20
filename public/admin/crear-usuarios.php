<?php
$pageTitle = 'Crear usuario';
$pageSubtitle = 'Alta manual de alumnos y profesores en LDAP.';
$pageStylesheet = '/assets/css/admin-create-user.css';
$currentSection = 'create-user';
$userName = 'Laura Gómez';
$userRole = 'Admin';

$errors = [];
$success = '';

$form = [
    'name' => '',
    'username' => '',
    'password' => '',
    'role' => '',
];

$ldapConfigPath = dirname(__DIR__, 2) . '/config/ldap.php';
$ldapConfig = null;

if (!file_exists($ldapConfigPath)) {
    $errors['ldap'] = 'No existe el archivo LDAP en esta ruta: ' . $ldapConfigPath;
} else {
    $ldapConfig = require $ldapConfigPath;

    if (!is_array($ldapConfig)) {
        $errors['ldap'] = 'El archivo LDAP no devuelve un array válido.';
    }
}

function normalizeUsername(string $username): string
{
    return strtolower(trim($username));
}

function splitFullName(string $fullName): array
{
    $fullName = preg_replace('/\s+/', ' ', trim($fullName));

    if ($fullName === '') {
        return ['', ''];
    }

    $parts = explode(' ', $fullName);
    $givenName = array_shift($parts) ?? '';
    $sn = trim(implode(' ', $parts));

    if ($sn === '') {
        $sn = $givenName;
    }

    return [$givenName, $sn];
}

function ldapConnectFromConfig(array $config)
{
    $connection = ldap_connect($config['host'], (int) $config['port']);

    if ($connection === false) {
        throw new RuntimeException('No se pudo conectar con el servidor LDAP.');
    }

    ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

    if (!@ldap_bind($connection, $config['bind_user'], $config['bind_password'])) {
        throw new RuntimeException('Error de bind LDAP: ' . ldap_error($connection));
    }

    return $connection;
}

function buildTargetOu(string $role): string
{
    return $role === 'teacher'
        ? 'OU=Profesores,OU=Usuarios,OU=WebVallesTech'
        : 'OU=Alumnos,OU=Usuarios,OU=WebVallesTech';
}

function buildUserDn(string $cn, string $role, string $baseDn): string
{
    return 'CN=' . ldap_escape($cn, '', LDAP_ESCAPE_DN) . ',' . buildTargetOu($role) . ',' . $baseDn;
}

function buildSamAccountName(string $username): string
{
    return substr(normalizeUsername($username), 0, 20);
}

function buildUserPrincipalName(string $username, string $baseDn): string
{
    $domain = strtolower(str_replace(['DC=', ','], ['', '.'], $baseDn));
    $domain = preg_replace('/\.+/', '.', trim($domain, '.'));
    return buildSamAccountName($username) . '@' . $domain;
}

function generateUnicodePassword(string $password): string
{
    return mb_convert_encoding('"' . $password . '"', 'UTF-16LE');
}

function createAdUser($ldap, array $config, array $form): string
{
    [$givenName, $sn] = splitFullName($form['name']);
    $cn = trim($form['name']);
    $samAccountName = buildSamAccountName($form['username']);
    $userPrincipalName = buildUserPrincipalName($form['username'], $config['base_dn']);
    $userDn = buildUserDn($cn, $form['role'], $config['base_dn']);

    $searchFilter = sprintf('(sAMAccountName=%s)', ldap_escape($samAccountName, '', LDAP_ESCAPE_FILTER));
    $search = @ldap_search($ldap, $config['base_dn'], $searchFilter, ['dn']);
    if ($search !== false && ldap_count_entries($ldap, $search) > 0) {
        throw new RuntimeException('Ese nombre de usuario ya existe en Active Directory.');
    }

    $entry = [
        'cn' => $cn,
        'displayName' => $cn,
        'givenName' => $givenName,
        'sn' => $sn,
        'objectClass' => ['top', 'person', 'organizationalPerson', 'user'],
        'sAMAccountName' => $samAccountName,
        'userPrincipalName' => $userPrincipalName,
        'name' => $cn,
    ];

    if (!@ldap_add($ldap, $userDn, $entry)) {
        throw new RuntimeException('No se pudo crear el usuario: ' . ldap_error($ldap));
    }

    if (!@ldap_mod_replace($ldap, $userDn, [
        'unicodePwd' => generateUnicodePassword($form['password'])
    ])) {
        @ldap_delete($ldap, $userDn);
        throw new RuntimeException('Usuario creado pero no se pudo asignar la contraseña: ' . ldap_error($ldap));
    }

    if (!@ldap_mod_replace($ldap, $userDn, [
        'userAccountControl' => '512',
        'pwdLastSet' => '-1',
    ])) {
        @ldap_delete($ldap, $userDn);
        throw new RuntimeException('Usuario creado pero no se pudo habilitar la cuenta: ' . ldap_error($ldap));
    }

    if (!empty($config['groups'][$form['role']])) {
        $groupDn = $config['groups'][$form['role']];
        if (!@ldap_mod_add($ldap, $groupDn, ['member' => $userDn])) {
            @ldap_delete($ldap, $userDn);
            throw new RuntimeException('Usuario creado pero no se pudo añadir al grupo: ' . ldap_error($ldap));
        }
    }

    return $userDn;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name'] = trim($_POST['name'] ?? '');
    $form['username'] = normalizeUsername($_POST['username'] ?? '');
    $form['password'] = $_POST['password'] ?? '';
    $form['role'] = trim($_POST['role'] ?? '');

    if ($form['name'] === '') {
        $errors['name'] = 'Introduce el nombre completo.';
    } elseif (mb_strlen($form['name']) < 3) {
        $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
    }

    if ($form['username'] === '') {
        $errors['username'] = 'Introduce el nombre de usuario.';
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $form['username'])) {
        $errors['username'] = 'El usuario solo puede tener letras, números, punto, guion y guion bajo.';
    }

    if ($form['password'] === '') {
        $errors['password'] = 'Introduce una contraseña.';
    } elseif (strlen($form['password']) < 8) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    if (!in_array($form['role'], ['student', 'teacher'], true)) {
        $errors['role'] = 'Selecciona Alumno o Profesor.';
    }

    if ($ldapConfig === null) {
        $errors['ldap'] = $errors['ldap'] ?? 'No se pudo cargar la configuración LDAP.';
    }

    if (empty($errors)) {
        $ldap = null;

        try {
            $ldap = ldapConnectFromConfig($ldapConfig);
            $userDn = createAdUser($ldap, $ldapConfig, $form);
            $roleLabel = $form['role'] === 'teacher' ? 'Profesor' : 'Alumno';
            $success = 'Usuario creado correctamente en Active Directory con rol ' . $roleLabel . ' y DN ' . $userDn . '.';
            $form = [
                'name' => '',
                'username' => '',
                'password' => '',
                'role' => '',
            ];
        } catch (Throwable $e) {
            $errors['ldap'] = $e->getMessage();
        } finally {
            if ($ldap) {
                ldap_unbind($ldap);
            }
        }
    }
}

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="create-hero">
    <article class="create-hero__card">
        <div>
            <p class="create-hero__eyebrow">Alta de cuentas</p>
            <h2>Crear nuevo usuario del campus</h2>
            <p>
                Completa los datos mínimos y el sistema creará el usuario en tu Active Directory.
            </p>
        </div>

        <div class="create-summary">
            <div class="summary-pill">
                <strong>AD</strong>
                <span>Alta individual real</span>
            </div>
            <div class="summary-pill">
                <strong>Origen</strong>
                <span>Config reutilizada desde ldap.php</span>
            </div>
        </div>
    </article>
</section>

<section class="create-layout">
    <article class="form-card">
        <div class="section-head">
            <p class="section-head__eyebrow">Formulario</p>
            <h2>Datos mínimos del usuario</h2>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="info-note" style="margin-bottom: 20px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
                Revisa los campos marcados o el mensaje de Active Directory.
            </div>
        <?php endif; ?>

        <?php if (isset($errors['ldap'])): ?>
            <div class="info-note" style="margin-bottom: 20px; background:#fff7ed; border-color:#fdba74; color:#9a3412;">
                <?= htmlspecialchars($errors['ldap']) ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="info-note" style="margin-bottom: 20px; background:#ecfdf5; border-color:#a7f3d0; color:#065f46;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form class="user-form" action="" method="post" novalidate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Laura Gómez Ruiz"
                        value="<?= htmlspecialchars($form['name']) ?>"
                    >
                    <?php if (isset($errors['name'])): ?>
                        <small style="color:#b91c1c;"><?= htmlspecialchars($errors['name']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="laura.gomez"
                        value="<?= htmlspecialchars($form['username']) ?>"
                    >
                    <?php if (isset($errors['username'])): ?>
                        <small style="color:#b91c1c;"><?= htmlspecialchars($errors['username']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña inicial</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                    >
                    <?php if (isset($errors['password'])): ?>
                        <small style="color:#b91c1c;"><?= htmlspecialchars($errors['password']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select id="role" name="role">
                        <option value="">Selecciona un rol</option>
                        <option value="student" <?= $form['role'] === 'student' ? 'selected' : '' ?>>Alumno</option>
                        <option value="teacher" <?= $form['role'] === 'teacher' ? 'selected' : '' ?>>Profesor</option>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <small style="color:#b91c1c;"><?= htmlspecialchars($errors['role']) ?></small>
                    <?php endif; ?>
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
            <li>La conexión se carga desde <code>/config/ldap.php</code>.</li>
            <li>Los alumnos se crean en <code>OU=Alumnos,OU=Usuarios,OU=WebVallesTech</code>.</li>
            <li>Los profesores se crean en <code>OU=Profesores,OU=Usuarios,OU=WebVallesTech</code>.</li>
            <li>Después del alta, el usuario se añade al grupo del rol configurado en <code>groups</code>.</li>
        </ul>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>