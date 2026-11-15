<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/auth.php';

if (usuario_actual()) {
    header('Location: panel.php');
    exit;
}

$error = '';
$correo = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = strtolower(trim($_POST['correo'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!csrf_valido()) {
        $error = 'La sesión expiró. Intenta de nuevo.';
    } else {
        $st = $pdo->prepare('SELECT id, nombre, correo, password_hash, rol FROM usuarios WHERE correo = ?');
        $st->execute([$correo]);
        $u = $st->fetch();

        if ($u && password_verify($password, $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => (int)$u['id'], 'nombre' => $u['nombre'],
                'correo' => $u['correo'], 'rol' => $u['rol'],
            ];
            header('Location: panel.php');
            exit;
        }
        $error = 'Correo o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
require __DIR__ . '/../include/header_auth.php';
?>
<h1>Hola de nuevo</h1>
<p class="sub">Entra para seguir tus solicitudes de adopción.</p>

<?php if ($flash): ?><div class="alerta ok"><?= e($flash) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alerta error"><?= e($error) ?></div><?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

  <label for="correo">Correo</label>
  <input id="correo" type="email" name="correo" value="<?= e($correo) ?>" placeholder="tu@correo.com" required autocomplete="email">

  <label for="password">Contraseña</label>
  <input id="password" type="password" name="password" required autocomplete="current-password">

  <button type="submit" class="btn">Entrar</button>
</form>

<p class="pie">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
<?php require __DIR__ . '/../include/footer_auth.php'; ?>
