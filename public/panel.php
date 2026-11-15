<?php
require_once __DIR__ . '/../include/auth.php';
$u = requiere_login();

$titulo = 'Mi cuenta';
require __DIR__ . '/../include/header_auth.php';
?>
<h1>Hola, <?= e($u['nombre']) ?></h1>
<div class="alerta ok">✓ Tu cuenta existe y la sesión se inició correctamente.</div>

<dl class="datos">
  <dt>ID de usuario</dt><dd><?= (int)$u['id'] ?></dd>
  <dt>Correo</dt><dd><?= e($u['correo']) ?></dd>
  <dt>Rol</dt><dd><?= e($u['rol']) ?></dd>
</dl>

<a class="btn btn-sec" href="logout.php">Cerrar sesión</a>
<?php require __DIR__ . '/../include/footer_auth.php'; ?>
