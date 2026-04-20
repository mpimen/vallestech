<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? [];
$displayName = trim((string)($currentUser['display_name'] ?? $currentUser['name'] ?? 'Administrador'));
$role = trim((string)($currentUser['role'] ?? 'Admin'));

$pageTitle = 'Dashboard';
$pageSubtitle = 'Resumen general de usuarios, roles y estado del portal.';
$pageStylesheet = '/assets/css/admin-dashboard.css';
$currentSection = 'dashboard';
$userName = $displayName !== '' ? $displayName : 'Administrador';
$userRole = $role !== '' ? $role : 'Admin';

$ldapError = '';
$stats = [
    'users_total' => 0,
    'students_total' => 0,
    'teachers_total' => 0,
    'active_total' => 0,
    'inactive_total' => 0,
];

$recentActivity = [];

$config = require __DIR__ . '/../../config/ldap.php';

function ldapFetchGroupUsersCount($connection, string $groupDn): array
{
    $groupSearch = @ldap_read($connection, $groupDn, '(objectClass=group)', ['member']);

    if ($groupSearch === false) {
        return [0, 0];
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

    $total = 0;
    $active = 0;

    foreach ($memberDns as $memberDn) {
        $userSearch = @ldap_read(
            $connection,
            $memberDn,
            '(objectClass=user)',
            ['useraccountcontrol']
        );

        if ($userSearch === false) {
            continue;
        }

        $entries = ldap_get_entries($connection, $userSearch);

        if (!is_array($entries) || ($entries['count'] ?? 0) < 1) {
            continue;
        }

        $entry = $entries[0];
        $uac = isset($entry['useraccountcontrol'][0]) ? (int) $entry['useraccountcontrol'][0] : 0;
        $isDisabled = ($uac & 2) === 2;

        $total++;
        if (!$isDisabled) {
            $active++;
        }
    }

    return [$total, $active];
}

if (extension_loaded('ldap')) {
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
            [$studentsTotal, $studentsActive] = ldapFetchGroupUsersCount($connection, $config['groups']['student'] ?? '');
            [$teachersTotal, $teachersActive] = ldapFetchGroupUsersCount($connection, $config['groups']['teacher'] ?? '');

            $stats['students_total'] = $studentsTotal;
            $stats['teachers_total'] = $teachersTotal;
            $stats['users_total'] = $studentsTotal + $teachersTotal;
            $stats['active_total'] = $studentsActive + $teachersActive;
            $stats['inactive_total'] = $stats['users_total'] - $stats['active_total'];
        }

        ldap_unbind($connection);
    }
} else {
    $ldapError = 'La extensión LDAP de PHP no está habilitada.';
}

try {
    require_once __DIR__ . '/../../config/database.php';

    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->query("
            SELECT action, objecttype, objectid, result, createdat, details
            FROM auditevents
            ORDER BY createdat DESC
            LIMIT 4
        ");
        $recentActivity = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
} catch (Throwable $e) {
    $recentActivity = [];
}

include __DIR__ . '/../../templates/private-header.php';
?>

<section class="admin-hero">
    <article class="admin-hero__card">
        <div>
            <p class="admin-hero__eyebrow">Administración general</p>
            <h2>Centro de control del campus</h2>
            <p>
                Gestiona usuarios, altas, roles y actividad general desde una vista clara y operativa.
            </p>
        </div>

        <div class="admin-hero__stats">
            <div class="admin-stat">
                <strong><?= htmlspecialchars((string) $stats['users_total']) ?></strong>
                <span>Usuarios totales</span>
            </div>
            <div class="admin-stat">
                <strong><?= htmlspecialchars((string) $stats['teachers_total']) ?></strong>
                <span>Profesores</span>
            </div>
            <div class="admin-stat">
                <strong><?= htmlspecialchars((string) $stats['students_total']) ?></strong>
                <span>Alumnos</span>
            </div>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <div class="panel-head">
            <div>
                <p class="panel-head__eyebrow">Actividad reciente</p>
                <h2>Últimos movimientos</h2>
            </div>
        </div>

        <?php if ($ldapError !== ''): ?>
            <div class="info-note" style="margin-bottom:20px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
                <?= htmlspecialchars($ldapError) ?>
            </div>
        <?php endif; ?>

        <ul class="activity-list">
            <?php if (empty($recentActivity)): ?>
                <li class="activity-item">
                    <strong>No hay actividad registrada</strong>
                    <span>El panel no ha encontrado eventos recientes en auditevents.</span>
                </li>
            <?php else: ?>
                <?php foreach ($recentActivity as $event): ?>
                    <li class="activity-item">
                        <strong><?= htmlspecialchars((string)($event['action'] ?? 'Evento')) ?></strong>
                        <span>
                            <?= htmlspecialchars((string)($event['objecttype'] ?? '')) ?>
                            <?= !empty($event['objectid']) ? ' · ' . htmlspecialchars((string)$event['objectid']) : '' ?>
                            <?= !empty($event['result']) ? ' · ' . htmlspecialchars((string)$event['result']) : '' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </article>

    <aside class="admin-side">
        <article class="side-card">
            <h3>Acciones rápidas</h3>
            <ul>
                <li><a href="/admin/crear-usuarios.php">Crear nuevo usuario</a></li>
                <li><a href="/admin/alumnos.php">Ver alumnos</a></li>
                <li><a href="/admin/profesores.php">Ver profesores</a></li>
            </ul>
        </article>

        <article class="side-card">
            <h3>Estado del sistema</h3>
            <p>
                Activos: <?= htmlspecialchars((string) $stats['active_total']) ?> ·
                Inactivos: <?= htmlspecialchars((string) $stats['inactive_total']) ?>
            </p>
        </article>
    </aside>
</section>

<?php include __DIR__ . '/../../templates/private-footer.php'; ?>