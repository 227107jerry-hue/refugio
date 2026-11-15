<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../include/auth.php';

function edad_texto($meses): string
{
    if ($meses === null) {
        return 'Edad no indicada';
    }
    $meses = (int) $meses;
    if ($meses < 12) {
        return $meses . ($meses === 1 ? ' mes' : ' meses');
    }
    $anios = intdiv($meses, 12);
    return $anios . ($anios === 1 ? ' año' : ' años');
}

$mascotas = [];
try {
    $mascotas = $pdo->query(
        'SELECT m.id, m.nombre, m.edad_meses, m.tamano, m.imagen, m.apto_ninos, m.apto_mascotas,
                s.nombre AS especie
           FROM mascotas m
           JOIN especies s ON s.id = m.especie_id
          WHERE m.disponible = 1
          ORDER BY m.id DESC
          LIMIT 6'
    )->fetchAll();
} catch (PDOException $ex) {
    $mascotas = [];
}

$usuario  = usuario_actual();
$bodyClass = 'inicio';
require __DIR__ . '/../partials/header.php';
?>

<section class="home">
    <div class="contenedor home-in">
        <div class="home-texto">
            <h1>Un hogar para cada huellita.</h1>
            <p class="home-sub">Conoce a los perros y gatos del refugio y envía tu solicitud de adopción en línea.</p>
            <div class="home-acciones">
                <a class="btn" href="mascotas.php">Ver mascotas</a>
                <?php if ($usuario): ?>
                    <a class="btn btn-sec" href="panel.php">Mi cuenta</a>
                <?php else: ?>
                    <a class="btn btn-sec" href="registro.php">Crear cuenta</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="home-arte" aria-hidden="true">
            <svg class="pa pa1"><use href="#pata"/></svg>
            <svg class="pa pa2"><use href="#pata"/></svg>
            <svg class="pa pa3"><use href="#pata"/></svg>
            <svg class="pa pa4"><use href="#pata"/></svg>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
