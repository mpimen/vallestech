<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];
$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$role = trim((string)($currentUser['role'] ?? 'Admin'));

$pageTitle = 'Usuarios';
$pageSubtitle = 'Gestión general de cuentas, estados y roles.';
$pageStylesheet = '/assets/css/admin-users.css';
$currentSection = 'users';
$userName = $displayName !== '' ? $displayName : 'Administrador';
$userRole = $role !== '' ? $role : 'Admin';

$users = [];
$ldapError = '';

$config = require __DIR__ . '/../../config/ldap.php';

function ldapFetchGroupUsers($connection, string $groupDn, string $roleKey, string $roleLabel): array
{
    $result = [];
    $groupSearch = @ldap_read($connection, $groupDn, '(objectClass=group)', ['member']);

    if ($groupSearch === false) {
        return $result;
    }

    $groupEntries = ldap_get_entries($connection, $groupSearch);
    $memberDns = [];

    if (
        is_array($groupEntries) &&
        isset($groupEntries['count']) &&
        $groupEntries['count'] > 0 &&
        isset($groupEntries[0]['member'])
    ) {
        for ($i = 0; $i < $groupEntries[0]['member']['count']; $i++) {
            $memberDns[] = $groupEntries[0]['member'][$i];
        }
    }

    foreach ($memberDns as $memberDn) {
        $userSearch = @ldap_read(
            $connection,
            $memberDn,
            '(objectClass=user)',
            ['cn', 'displayname', 'givenname', 'sn', 'mail', 'samaccountname', 'department', 'useraccountcontrol']
        );

        if ($userSearch === false) {
            continue;
        }

        $entries = ldap_get_entries($connection, $userSearch);

        if (!is_array($entries) || ($entries['count'] ?? 0) < 1) {
            continue;
        }

        $entry = $entries[0];

        $name = $entry['displayname'][0]
            ?? $entry['cn'][0]
            ?? trim(($entry['givenname'][0] ?? '') . ' ' . ($entry['sn'][0] ?? ''));

        $email = $entry['mail'][0] ?? 'No disponible';
        $username = $entry['samaccountname'][0] ?? '';
        $department = $entry['department'][0] ?? 'No asignado';
        $uac = isset($entry['useraccountcontrol'][0]) ? (int) $entry['useraccountcontrol'][0] : 0;

        $isDisabled = ($uac & 2) === 2;
        $statusLabel = $isDisabled ? 'Inactivo' : 'Activo';
        $statusClass = $isDisabled ? 'inactive' : 'active';

        $result[] = [
            'username' => $username,
            'name' => $name !== '' ? $name : $username,
            'email' => $email,
            'role_key' => $roleKey,
            'role_label' => $roleLabel,
            'department' => $department,
            'status_label' => $statusLabel,
            'status_class' => $statusClass,
            'last_access' => 'No disponible',
        ];
    }

    return $result;
}

if (!extension_loaded('ldap')) {
    $ldapError = 'La extensión LDAP de PHP no está habilitada.';
} else {
    $connection = @ldap_connect($config['host'], (int) $config['port']);

    if ($connection === false) {
        $ldapError = 'No se ha podido conectar con el servidor LDAP.';
    } else {
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($connection, $config['bind_user'], $config['bind_password']);

        if ($bind === false) {
            $ldapError = 'No se ha podido autenticar la cuenta de servicio LDAP.';
        } else {
            $studentGroupDn = $config['groups']['student'] ?? '';
            $teacherGroupDn = $config['groups']['teacher'] ?? '';

            $studentUsers = $studentGroupDn !== ''
                ? ldapFetchGroupUsers($connection, $studentGroupDn, 'student', 'Alumno')
                : [];

            $teacherUsers = $teacherGroupDn !== ''
                ? ldapFetchGroupUsers($connection, $teacherGroupDn, 'teacher', 'Profesor')
                : [];

            $mergedUsers = array_merge($studentUsers, $teacherUsers);
            $indexedUsers = [];

            foreach ($mergedUsers as $user) {
                $key = strtolower(trim($user['username'] !== '' ? $user['username'] : $user['email']));

                if ($key === '') {
                    $key = md5($user['name'] . '|' . $user['role_key']);
                }

                if (!isset($indexedUsers[$key])) {
                    $indexedUsers[$key] = $user;
                } else {
                    if ($indexedUsers[$key]['role_key'] !== $user['role_key']) {
                        $indexedUsers[$key]['role_key'] = 'mixed';
                        $indexedUsers[$key]['role_label'] = 'Alumno / Profesor';
                    }
                }
            }

            $users = array_values($indexedUsers);

            usort($users, static function (array $a, array $b): int {
                return strcasecmp($a['name'], $b['name']);
            });
        }

        ldap_unbind($connection);
    }
}

$totalUsers = count($users);
$activeUsers = count(array_filter($users, static fn ($user) => $user['status_class'] === 'active'));
$inactiveUsers = $totalUsers - $activeUsers;

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="users-hero">
    <article class="users-hero__card">
        <div>
            <p class="users-hero__eyebrow">Gestión central</p>
            <h2>Listado general de usuarios</h2>
            <p>
                Administra alumnos y profesores desde una vista unificada con filtros, estados y control por rol.
            </p>
        </div>

        <div class="users-summary">
            <div class="summary-box">
                <strong><?= htmlspecialchars((string) $totalUsers) ?></strong>
                <span>Total usuarios</span>
            </div>
            <div class="summary-box">
                <strong><?= htmlspecialchars((string) $activeUsers) ?></strong>
                <span>Activos</span>
            </div>
            <div class="summary-box">
                <strong><?= htmlspecialchars((string) $inactiveUsers) ?></strong>
                <span>Inactivos</span>
            </div>
        </div>
    </article>
</section>

<section class="users-table-card">
    <?php if ($ldapError !== ''): ?>
        <div class="info-note" style="margin-bottom:20px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
            <?= htmlspecialchars($ldapError) ?>
        </div>
    <?php endif; ?>

    <div class="table-toolbar">
        <input type="text" class="table-search" placeholder="Buscar por nombre, correo o rol">
        <a href="/admin/crear-usuarios.php" class="btn btn--primary">Nuevo usuario</a>
    </div>

    <div class="table-wrap">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5">No se han encontrado usuarios en LDAP.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="role-badge role-badge--<?= htmlspecialchars($user['role_key']) ?>">
                                    <?= htmlspecialchars($user['role_label']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status status--<?= htmlspecialchars($user['status_class']) ?>">
                                    <?= htmlspecialchars($user['status_label']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['last_access']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>