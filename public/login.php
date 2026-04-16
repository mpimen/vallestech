<?php
require_once __DIR__ . '/../src/Auth/Session.php';
require_once __DIR__ . '/../src/Auth/LdapAuthenticator.php';
require_once __DIR__ . '/../src/Auth/Auth.php';

use Auth\Auth;
use Auth\Session;

Session::start();

if (Auth::check()) {
    Auth::redirectByRole();
}

$error = '';
$lastUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastUsername = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (Auth::attempt($lastUsername, $password)) {
        Auth::redirectByRole();
    }

    $error = 'Credenciales no válidas o usuario sin rol asignado.';
}

$pageTitle = 'Login';
$pageSubtitle = 'Accede con tu cuenta corporativa del dominio.';
$user = null;

include __DIR__ . '/../templates/header.php';
?>

<section class="auth-shell">
    <div class="auth-grid">
        <article class="card hero-panel__content">
            <div class="card__body">
                <span class="badge">Active Directory</span>
                <h2 class="section-title" style="margin-top: 18px;">Acceso al campus con autenticación corporativa</h2>
                <p class="section-text">
                    Inicia sesión con tu usuario del dominio para acceder a tu panel de alumno o profesor.
                </p>

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <p class="kpi-card__label">Directorio</p>
                        <p class="kpi-card__value">AD</p>
                        <p class="kpi-card__meta">Validación centralizada contra Active Directory.</p>
                    </div>

                    <div class="kpi-card">
                        <p class="kpi-card__label">Roles</p>
                        <p class="kpi-card__value">2</p>
                        <p class="kpi-card__meta">Alumno y profesor resueltos por grupos LDAP.</p>
                    </div>

                    <div class="kpi-card">
                        <p class="kpi-card__label">Sesión</p>
                        <p class="kpi-card__value">PHP</p>
                        <p class="kpi-card__meta">Persistencia en sesión tras autenticación correcta.</p>
                    </div>
                </div>
            </div>
        </article>

        <aside class="card hero-panel__aside">
            <div class="card__body">
                <h2 class="section-title">Iniciar sesión</h2>
                <p class="section-text">
                    Usa tu cuenta del dominio `vallestech.local`.
                </p>

                <?php if ($error !== ''): ?>
                    <div class="info-note" style="margin-top: 18px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form class="auth-form" action="/login.php" method="post">
                    <div class="form-group">
                        <label class="form-label" for="username">Usuario</label>
                        <input
                            class="form-control"
                            type="text"
                            id="username"
                            name="username"
                            value="<?= htmlspecialchars($lastUsername) ?>"
                            placeholder="usuario"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <input
                            class="form-control"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <button class="btn btn--primary" type="submit">Entrar al campus</button>
                </form>
            </div>
        </aside>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>