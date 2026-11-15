<?php
require_once __DIR__ . '/../../include/auth.php';
$u = requiere_login();

$activo    = 'panel';
$bodyClass = 'auth';
require __DIR__ . '/../partials/header.php';
?>

<div class="auth-caja">
    <svg class="auth-pata" aria-hidden="true"><use href="#pata"/></svg>
    <h1>Hola, <?= e($u['nombre']) ?></h1>
    <p class="sub">Aquí aparecerán tus solicitudes de adopción.</p>

    <dl class="datos">
        <dt>Correo</dt><dd><?= e($u['correo']) ?></dd>
        <dt>Tipo de cuenta</dt><dd><?= $u['rol'] === 'admin' ? 'Administrador' : 'Adoptante' ?></dd>
    </dl>

    <a class="btn btn-block" href="mascotas.php">Ver mascotas</a>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
