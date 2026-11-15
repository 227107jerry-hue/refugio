<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/auth.php';

if (usuario_actual()) {
    header('Location: panel.php');
    exit;
}

$errores = [];
$old = ['nombre' => '', 'telefono' => '', 'correo' => '', 'rol' => 'usuario'];
$roles = ['usuario' => 'Usuario (adoptante)', 'admin' => 'Administrador'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nombre']   = trim($_POST['nombre'] ?? '');
    $old['telefono'] = trim($_POST['telefono'] ?? '');
    $old['correo']   = strtolower(trim($_POST['correo'] ?? ''));
    $old['rol'] = $_POST['rol'] ?? '';
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!csrf_valido()) {
        $errores[0] = 'La sesión expiró. Intenta de nuevo.';
    }
    if ($old['nombre'] === '' || mb_strlen($old['nombre']) > 100) {
        $errores['nombre'] = 'Escribe tu nombre (máx. 100 caracteres).';
    }
    if (!filter_var($old['correo'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['correo']) > 150) {
        $errores['correo'] = 'Escribe un correo válido.';
    }
    if ($old['telefono'] !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $old['telefono'])) {
        $errores['telefono'] = 'Teléfono no válido (7 a 20 dígitos).';
    }
    if (!array_key_exists($old['rol'], $roles)) {
        $errores['rol'] = 'Elige un rol válido.';
    }
    if (strlen($password) < 8) {
        $errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    }
    if ($password !== $password2) {
        $errores['password2'] = 'Las contraseñas no coinciden.';
    }

    if (!$errores) {
        $st = $pdo->prepare('SELECT 1 FROM usuarios WHERE correo = ?');
        $st->execute([$old['correo']]);
        if ($st->fetch()) {
            $errores['correo'] = 'Ya existe una cuenta con ese correo.';
        }
    }

    if (!$errores) {
        $st = $pdo->prepare(
            'INSERT INTO usuarios (nombre, telefono, correo, password_hash, rol)
             VALUES (?, ?, ?, ?, ?)'
        );
        $st->execute([
            $old['nombre'],
            $old['telefono'] !== '' ? $old['telefono'] : null,
            $old['correo'],
            password_hash($password, PASSWORD_DEFAULT),
            $old['rol'],
        ]);
        $_SESSION['flash'] = '¡Cuenta creada! Ya puedes iniciar sesión.';
        header('Location: login.php');
        exit;
    }
}

$titulo = 'Crear cuenta';
require __DIR__ . '/../include/header_auth.php';
?>
<h1>Crea tu cuenta</h1>
<p class="sub">Regístrate para seguir tus solicitudes de adopción.</p>

<?php if (isset($errores[0])): ?>
  <div class="alerta error"><?= e($errores[0]) ?></div>
<?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

  <label for="nombre">Nombre</label>
  <input id="nombre" name="nombre" value="<?= e($old['nombre']) ?>" maxlength="100" required autocomplete="name">
  <?php if (isset($errores['nombre'])): ?><p class="campo-error"><?= e($errores['nombre']) ?></p><?php endif; ?>

  <label for="correo">Correo</label>
  <input id="correo" type="email" name="correo" value="<?= e($old['correo']) ?>" placeholder="tu@correo.com" maxlength="150" required autocomplete="email">
  <?php if (isset($errores['correo'])): ?><p class="campo-error"><?= e($errores['correo']) ?></p><?php endif; ?>

  <label for="telefono">Teléfono <span class="opc">(opcional)</span></label>
  <input id="telefono" type="tel" name="telefono" value="<?= e($old['telefono']) ?>" maxlength="20" autocomplete="tel">
  <?php if (isset($errores['telefono'])): ?><p class="campo-error"><?= e($errores['telefono']) ?></p><?php endif; ?>

  <label for="rol">Rol</label>
  <select id="rol" name="rol" required>
    <?php foreach ($roles as $valor => $texto): ?>
      <option value="<?= e($valor) ?>" <?= $old['rol'] === $valor ? 'selected' : '' ?>><?= e($texto) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errores['rol'])): ?><p class="campo-error"><?= e($errores['rol']) ?></p><?php endif; ?>

  <label for="password">Contraseña</label>
  <input id="password" type="password" name="password" minlength="8" required autocomplete="new-password">
  <?php if (isset($errores['password'])): ?><p class="campo-error"><?= e($errores['password']) ?></p><?php endif; ?>

  <label for="password2">Repite la contraseña</label>
  <input id="password2" type="password" name="password2" minlength="8" required autocomplete="new-password">
  <?php if (isset($errores['password2'])): ?><p class="campo-error"><?= e($errores['password2']) ?></p><?php endif; ?>

  <button type="submit" class="btn">Crear cuenta</button>
</form>

<p class="pie">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
<?php require __DIR__ . '/../include/footer_auth.php'; ?>
