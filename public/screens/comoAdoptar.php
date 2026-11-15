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

<section id="como-adoptar" class="seccion seccion-salvia">
    <div class="contenedor">
        <h2>Cómo adoptar</h2>
        <ol class="pasos">
            <li>
                <h3>Crea tu cuenta</h3>
                <p>Con tu correo y un teléfono de contacto, para que el refugio pueda ubicarte.</p>
            </li>
            <li>
                <h3>Elige a tu compañero</h3>
                <p>Revisa su edad, tamaño y con quién se lleva bien para encontrar a la mascota que encaja con tu casa.</p>
            </li>
            <li>
                <h3>Envía tu solicitud</h3>
                <p>Cuéntanos por qué quieres adoptar. El refugio revisa cada solicitud y se pone en contacto contigo.</p>
            </li>
        </ol>
    </div>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
