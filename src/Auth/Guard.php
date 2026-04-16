<?php

namespace Auth;

class Guard
{
    public static function requireAuth(): void
    {
        Session::start();

        if (!Session::has('user')) {
            header('Location: /login.php');
            exit;
        }
    }

    public static function requireRole(array|string $roles): void
    {
        self::requireAuth();

        $roles = (array) $roles;
        $user = Session::get('user');

        if (!in_array($user['role'] ?? '', $roles, true)) {
            header('Location: /login.php');
            exit;
        }
    }
}