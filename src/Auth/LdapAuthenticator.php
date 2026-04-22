<?php

namespace Auth;

class LdapAuthenticator
{
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/ldap.php';
    }

    public function authenticate(string $username, string $password): ?array
    {
        $username = trim($username);

        if ($username === '' || $password === '') {
            return null;
        }

        $connection = ldap_connect($this->config['host'], (int) $this->config['port']);

        if ($connection === false) {
            return null;
        }

        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

        $bindDn = $this->buildBindDn($this->config['bind_user']);

        // Bind del servicio (para poder buscar en el AD)
        $serviceBind = @ldap_bind(
            $connection,
            $bindDn,
            $this->config['bind_password']
        );

        if ($serviceBind === false) {
            return null;
        }

        // [!] VULNERABILIDAD 1: INYECCIÓN LDAP
        // Eliminamos $safeUsername = ldap_escape($username, '', LDAP_ESCAPE_FILTER);
        // Concatenamos la entrada del usuario directamente.
        $filter = sprintf($this->config['user_filter'], $username);

        $search = @ldap_search(
            $connection,
            $this->config['base_dn'],
            $filter,
            ['dn', 'cn', 'displayName', 'mail', 'memberOf', 'sAMAccountName']
        );

        if ($search === false) {
            return null;
        }

        $entries = ldap_get_entries($connection, $search);

        if (!isset($entries['count']) || (int) $entries['count'] < 1) {
            return null;
        }

        // Tomamos el primer usuario que devuelva la búsqueda maliciosa
        $entry = $entries[0];
        $userDn = $entry['dn'] ?? null;

        if (!$userDn) {
            return null;
        }

        // [!] VULNERABILIDAD 2: BROKEN AUTHENTICATION
        // El desarrollador original validaba la contraseña con el siguiente bloque.
        // Lo comentamos para simular que confían ciegamente en el resultado del ldap_search().

        $userBind = @ldap_bind($connection, $userDn, $password);

        if ($userBind === false) {
            return null;
        }

        $role = $this->resolveRole($entry);

        if ($role === null) {
            $role = 'student';
        }

        return [
            'username' => $entry['samaccountname'][0] ?? $username,
            'name' => $entry['displayname'][0] ?? $entry['cn'][0] ?? $username,
            'email' => $entry['mail'][0] ?? '',
            'dn' => $userDn,
            'role' => $role,
            'role_label' => $this->mapRoleLabel($role),
        ];
    }

    private function buildBindDn(string $bindUser): string
    {
        if (strpos($bindUser, '@') !== false || strpos($bindUser, '\\') !== false || strpos($bindUser, ',') !== false) {
            return $bindUser;
        }

        return $bindUser . '@' . $this->extractDomainFqdn($this->config['base_dn']);
    }

    private function extractDomainFqdn(string $baseDn): string
    {
        preg_match_all('/DC=([^,]+)/i', $baseDn, $matches);

        return implode('.', $matches[1] ?? []);
    }

    private function resolveRole(array $entry): ?string
    {
        $memberOf = [];

        if (isset($entry['memberof']) && is_array($entry['memberof'])) {
            for ($i = 0; $i < ($entry['memberof']['count'] ?? 0); $i++) {
                $memberOf[] = strtolower($entry['memberof'][$i]);
            }
        }

        foreach ($this->config['groups'] as $role => $groupDn) {
            if (in_array(strtolower($groupDn), $memberOf, true)) {
                return $role;
            }
        }

        return null;
    }

    private function mapRoleLabel(string $role): string
    {
        if ($role === 'student') {
            return 'Alumno';
        }

        if ($role === 'teacher') {
            return 'Profesor';
        }

        if ($role === 'admin') {
            return 'Administrador';
        }

        return ucfirst($role);
    }
}