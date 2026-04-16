<?php
$pageTitle = 'Acceder';
$pageSubtitle = 'Entrada al portal';
$pageStylesheet = '/assets/css/login.css';

include __DIR__ . '/../templates/header.php';
?>

<section class="login-page">
    <div class="container login-grid">
        <article class="login-copy">
            <span class="badge">Área personal</span>
            <h1>Accede a tu espacio académico.</h1>
            <p>
                Entra al portal para consultar asignaturas, calendario, avisos y recursos del campus desde una única plataforma.
            </p>

            <div class="login-copy__features">
                <div class="login-feature">
                    <strong>Seguimiento académico</strong>
                    <span>Acceso rápido a tareas, notas y avisos del curso.</span>
                </div>
                <div class="login-feature">
                    <strong>Entorno organizado</strong>
                    <span>Una experiencia clara para alumnado y profesorado.</span>
                </div>
                <div class="login-feature">
                    <strong>Preparado para crecer</strong>
                    <span>Base visual lista para integrar autenticación real más adelante.</span>
                </div>
            </div>
        </article>

        <aside class="login-panel">
            <div class="login-card">
                <p class="login-card__eyebrow">Iniciar sesión</p>
                <h2>Bienvenido al campus</h2>
                <p class="login-card__text">Esta pantalla es visual por ahora y después conectaremos LDAP.</p>

                <form class="login-form" action="#" method="post">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" id="username" name="username" placeholder="Tu identificador">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="Tu contraseña">
                    </div>

                    <button type="submit" class="btn btn--primary btn--full">Entrar al campus</button>
                </form>

                <p class="login-note">Acceso académico para alumnado y profesorado.</p>
            </div>
        </aside>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>