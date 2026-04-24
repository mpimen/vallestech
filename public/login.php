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

    if ($lastUsername === '') {
        $error = 'Introduce usuario y contraseña.';
    } elseif (Auth::attempt($lastUsername, $password)) {
        Auth::redirectByRole();
    } else {
        $error = 'Credenciales no válidas o usuario sin rol asignado.';
    }
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
                <span class="badge">Campus Virtual</span>
                <h2 class="section-title" style="margin-top: 18px;">Tu campus,&nbsp;un solo acceso</h2>
                <p class="section-text">
                    Inicia sesión con tu cuenta corporativa para acceder a todos tus recursos académicos.
                </p>

                <div class="kpi-grid">
                    <div class="kpi-card">
                        <p class="kpi-card__label">Alumnos</p>
                        <p class="kpi-card__value">200+</p>
                        <p class="kpi-card__meta">Matriculados en el campus este curso.</p>
                    </div>

                    <div class="kpi-card">
                        <p class="kpi-card__label">Módulos</p>
                        <p class="kpi-card__value">12</p>
                        <p class="kpi-card__meta">Ciclos formativos disponibles.</p>
                    </div>

                    <div class="kpi-card">
                        <p class="kpi-card__label">Acceso</p>
                        <p class="kpi-card__value">24/7</p>
                        <p class="kpi-card__meta">Disponible desde cualquier dispositivo.</p>
                    </div>
                </div>
            </div>
        </article>

        <aside class="card hero-panel__aside">
            <div class="card__body">
                <h2 class="section-title">Iniciar sesión</h2>
                <p class="section-text">
                    Usa tu cuenta del dominio <code>vallestech.local</code>.
                </p>

                <?php if ($error !== ''): ?>
                    <div class="info-note" style="margin-top: 18px; background:#fef2f2; border-color:#fecaca; color:#991b1b;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form class="auth-form" action="/login.php" method="post" novalidate>
                    <div class="form-group">
                        <label class="form-label" for="username">Usuario</label>
                        <input
                            class="form-control"
                            type="text"
                            id="username"
                            name="username"
                            value="<?= htmlspecialchars($lastUsername) ?>"
                            placeholder="usuario"
                            autocomplete="username"
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
                            autocomplete="current-password"
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