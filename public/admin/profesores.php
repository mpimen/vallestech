<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];
$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$role = trim((string)($currentUser['role'] ?? 'Admin'));

$pageTitle = 'Profesores';
$pageSubtitle = 'Listado visual de docentes y áreas asignadas.';
$pageStylesheet = '/assets/css/admin-teachers.css';
$currentSection = 'teachers';
$userName = $displayName !== '' ? $displayName : 'Administrador';
$userRole = $role !== '' ? $role : 'Admin';

$teachers = [];
$ldapError = '';

$config = require __DIR__ . '/../../config/ldap.php';

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
            $groupDn = $config['groups']['teacher'] ?? '';

            $groupSearch = @ldap_read(
                $connection,
                $groupDn,
                '(objectClass=group)',
                ['member']
            );

            if ($groupSearch === false) {
                $ldapError = 'No se ha podido leer el grupo de profesores en LDAP.';
            } else {
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
                    $department = $entry['department'][0] ?? 'No asignado';
                    $username = $entry['samaccountname'][0] ?? '';
                    $uac = isset($entry['useraccountcontrol'][0]) ? (int) $entry['useraccountcontrol'][0] : 0;

                    $isDisabled = ($uac & 2) === 2;
                    $statusLabel = $isDisabled ? 'Inactivo' : 'Activo';
                    $statusClass = $isDisabled ? 'inactive' : 'active';

                    $teachers[] = [
                        'name' => $name !== '' ? $name : $username,
                        'email' => $email,
                        'department' => $department,
                        'status_label' => $statusLabel,
                        'status_class' => $statusClass,
                        'last_access' => 'No disponible',
                    ];
                }
            }
        }

        ldap_unbind($connection);
    }
}

$totalTeachers = count($teachers);
$activeTeachers = count(array_filter($teachers, static fn ($teacher) => $teacher['status_class'] === 'active'));

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="entity-hero">
    <article class="entity-hero__card">
        <div>
            <p class="entity-hero__eyebrow">Profesorado</p>
            <h2>Gestión visual de docentes</h2>
            <p>
                Revisa profesores, departamentos y estado operativo de las cuentas docentes.
            </p>
        </div>

        <div class="entity-summary">
            <div class="entity-stat">
                <strong><?= htmlspecialchars((string) $totalTeachers) ?></strong>
                <span>Profesores totales</span>
            </div>
            <div class="entity-stat">
                <strong><?= htmlspecialchars((string) $activeTeachers) ?></strong>
                <span>Activos</span>
            </div>
        </div>
    </article>
</section>

<section class="entity-table-card">
    <?php if ($ldapError !== ''): ?>
        <div class="info-note" style="margin-bottom:20px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
            <?= htmlspecialchars($ldapError) ?>
        </div>
    <?php endif; ?>

    <div class="table-wrap">
        <table class="entity-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="5">No se han encontrado profesores en LDAP.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= htmlspecialchars($teacher['name']) ?></td>
                            <td><?= htmlspecialchars($teacher['email']) ?></td>
                            <td><?= htmlspecialchars($teacher['department']) ?></td>
                            <td>
                                <span class="status status--<?= htmlspecialchars($teacher['status_class']) ?>">
                                    <?= htmlspecialchars($teacher['status_label']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($teacher['last_access']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>