<?php
include '../includes/db.php';

$ciudad = $_GET['ciudad'];
$tipo = $_GET['tipo'];
$personas = (int)$_GET['personas'];
$inicio = $_GET['inicio'];
$fin = $_GET['fin'];

// Buscar ID del hotel según ciudad
$hotel = $pdo->prepare("SELECT id FROM hoteles WHERE ciudad = ?");
$hotel->execute([$ciudad]);
$hotel_id = $hotel->fetchColumn();

// Buscar habitaciones disponibles
$sql = "SELECT h.*, ht.nombre AS hotel_nombre, ht.ciudad AS ciudad
        FROM habitaciones h
        JOIN hoteles ht ON h.hotel_id = ht.id
        LEFT JOIN reservas r ON h.id = r.habitacion_id
            AND (
                r.fecha_inicio <= :fin AND r.fecha_fin >= :inicio
            )
        WHERE r.id IS NULL
        AND h.capacidad >= :personas
        AND h.tipo = :tipo
        AND h.hotel_id = :hotel_id";


$stmt = $pdo->prepare($sql);        
$stmt->execute([
    'inicio' => $inicio,
    'fin' => $fin,
    'personas' => $personas,
    'tipo' => $tipo,
    'hotel_id' => $hotel_id
]);

$habitaciones = $stmt->fetchAll();

echo "<h2>Resumen de disponibilidad</h2>";

if (count($habitaciones) > 0) {
    // Agrupar por hotel + ciudad + tipo + capacidad
    $resumen = [];

    foreach ($habitaciones as $h) {
        $key = $h['hotel_nombre'] . '|' . $h['ciudad'] . '|' . $h['tipo'] . '|' . $h['capacidad'];
        if (!isset($resumen[$key])) {
            $resumen[$key] = 0;
        }
        $resumen[$key]++;
    }

    foreach ($resumen as $clave => $cantidad) {
        [$hotel_nombre, $ciudad, $tipo, $capacidad] = explode('|', $clave);

        echo "
        <div style='display: flex; align-items: center; margin-bottom: 10px;'>
            <img src='https://img.freepik.com/vector-gratis/ubicacion_53876-25530.jpg' alt='Mapa Hotel' width='60' style='margin-right: 10px; border-radius: 6px;'>
            <div>
                <p style='margin: 0; font-size: 16px;'><strong>$hotel_nombre</strong> <span style='color: #555;'>($ciudad)</span></p>
            </div>
        </div>
        ";
        echo "🤏 $cantidad habitaciones disponibles - Tipo: <strong>$tipo</strong> - Capacidad: <strong>$capacidad</strong> personas<br>";
        echo "📆 Fechas: <strong>$inicio</strong> a <strong>$fin</strong></p><hr>";
    }

} else {
    // 🔍 Verificar si hay habitaciones con ese tipo y capacidad en ese hotel, sin importar reserva
    $verificacion = $pdo->prepare("
        SELECT COUNT(*) FROM habitaciones 
        WHERE hotel_id = :hotel_id AND tipo = :tipo AND capacidad >= :personas
    ");
    $verificacion->execute([
        'hotel_id' => $hotel_id,
        'tipo' => $tipo,
        'personas' => $personas
    ]);
    $total_posibles = $verificacion->fetchColumn();

    if ($total_posibles > 0) {
        echo "<p>⚠️ <strong>Alerta:</strong> todas las habitaciones de tipo <strong>$tipo</strong> con capacidad para <strong>$personas</strong> personas están ocupadas entre <strong>$inicio</strong> y <strong>$fin</strong>.</p>";
    } else {
        echo "<p>⚠️ <strong>Alerta:</strong> no existen habitaciones de tipo <strong>$tipo</strong> con capacidad para <strong>$personas</strong> personas en este hotel.</p>";
    }
}

echo "<br><a href='index.php'>⬅ Volver</a>";
?>
