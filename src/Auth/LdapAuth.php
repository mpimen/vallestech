<?php
// src/Auth/LdapAuth.php

namespace Auth;

class LdapAuth
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function authenticate(string $username, string $password): ?array
    {
        $username = trim($username);

        if ($username === '' || $password === '') {
            return null;
        }

        $conn = ldap_connect($this->config['host'], $this->config['port']);
        if (!$conn) {
            throw new \Exception('No se pudo conectar con el servidor LDAP.');
        }

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind(
            $conn,
            $this->config['bind_user'],
            $this->config['bind_password']
        );

        if (!$bind) {
            throw new \Exception('Falló el bind técnico LDAP.');
        }

        $escapedUsername = ldap_escape($username, '', LDAP_ESCAPE_FILTER);

        $filter = sprintf(
            $this->config['user_filter'],
            $escapedUsername
        );

        $attributes = [
            'dn',
            'cn',
            'displayName',
            'mail',
            'sAMAccountName',
            'memberOf'
        ];

        $search = @ldap_search(
            $conn,
            $this->config['base_dn'],
            $filter,
            $attributes
        );

        if (!$search) {
            throw new \Exception('Falló la búsqueda LDAP.');
        }

        $entries = ldap_get_entries($conn, $search);

        if (!isset($entries['count']) || $entries['count'] < 1) {
            ldap_close($conn);
            return null;
        }

        $entry = $entries[0];
        $userDn = $entry['dn'] ?? null;

        if (!$userDn) {
            ldap_close($conn);
            return null;
        }

        $userBind = @ldap_bind($conn, $userDn, $password);

        if (!$userBind) {
            ldap_close($conn);
            return null;
        }

        $groups = $this->extractGroups($entry);
        $role = $this->detectRoleFromGroups($groups);

        $user = [
            'dn' => $userDn,
            'username' => $entry['samaccountname'][0] ?? $username,
            'display_name' => $entry['displayname'][0] ?? ($entry['cn'][0] ?? $username),
            'mail' => $entry['mail'][0] ?? '',
            'role' => $role,
            'groups' => $groups,
        ];

        ldap_close($conn);

        return $user;
    }

 private function detectRoleFromGroups(array $groups): string
{
    $studentGroup = strtolower(trim($this->config['groups']['student'] ?? ''));
    $teacherGroup = strtolower(trim($this->config['groups']['teacher'] ?? ''));

    foreach ($groups as $groupDn) {
        $currentGroup = strtolower(trim($groupDn));

        if ($teacherGroup !== '' && $currentGroup === $teacherGroup) {
            return 'Profesor';
        }

        if ($studentGroup !== '' && $currentGroup === $studentGroup) {
            return 'Alumno';
        }
    }

    return 'Usuario';
}

    private function extractGroups(array $entry): array
    {
        $groups = [];

        if (!isset($entry['memberof'])) {
            return $groups;
        }

        $count = $entry['memberof']['count'] ?? 0;

        for ($i = 0; $i < $count; $i++) {
            if (!empty($entry['memberof'][$i])) {
                $groups[] = $entry['memberof'][$i];
            }
        }

        return $groups;
    }
}