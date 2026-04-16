<?php

namespace Auth;

class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $ldap = new LdapAuthenticator();
        $user = $ldap->authenticate($username, $password);

        if ($user === null) {
            return false;
        }

        Session::regenerate();
        Session::set('user', $user);
        session_write_close();

        return true;
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function redirectByRole(): void
    {
        $user = self::user();

        if (!$user) {
            header('Location: /login.php');
            exit;
        }

        if (($user['role'] ?? '') === 'teacher') {
            header('Location: /profesor/dashboard.php');
            exit;
        }

        if (($user['role'] ?? '') === 'admin') {
            header('Location: /admin/dashboard.php');
            exit;
        }

        header('Location: /alumno/dashboard.php');
        exit;
    }
}