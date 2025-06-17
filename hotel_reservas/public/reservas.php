<?php
include '../includes/db.php';

if (
    isset($_POST['ciudad'], $_POST['tipo'], $_POST['capacidad'], $_POST['inicio'], $_POST['fin'], $_POST['personas'])
) {
    $ciudad = $_POST['ciudad'];
    $tipo = $_POST['tipo'];
    $capacidad = (int)$_POST['capacidad'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $personas = (int)$_POST['personas'];

    // Buscar hotel_id
    $hotel = $pdo->prepare("SELECT id FROM hoteles WHERE ciudad = ?");
    $hotel->execute([$ciudad]);
    $hotel_id = $hotel->fetchColumn();

    // Buscar una habitación disponible (que no esté reservada en ese rango)
    $sql = "SELECT h.id
            FROM habitaciones h
            LEFT JOIN reservas r ON h.id = r.habitacion_id
                AND (
                    r.fecha_inicio < :fin AND r.fecha_fin > :inicio
                )
            WHERE r.id IS NULL
              AND h.capacidad >= :personas
              AND h.tipo = :tipo
              AND h.hotel_id = :hotel_id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'inicio' => $inicio,
        'fin' => $fin,
        'personas' => $personas,
        'tipo' => $tipo,
        'hotel_id' => $hotel_id
    ]);

    $habitacion = $stmt->fetch();

    if ($habitacion) {
        // Insertar reserva
        $stmt = $pdo->prepare("INSERT INTO reservas (habitacion_id, fecha_inicio, fecha_fin, personas) VALUES (?, ?, ?, ?)");
        $stmt->execute([$habitacion['id'], $inicio, $fin, $personas]);

        echo "<h2>✅ Reserva confirmada</h2>";
        echo "<p>Hotel: <strong>$ciudad</strong> - Tipo: <strong>$tipo</strong> - Capacidad: $capacidad</p>";
        echo "<p>Fechas: $inicio a $fin - Personas: $personas</p>";
    } else {
        echo "<h2>❌ No se pudo hacer la reserva</h2>";
        echo "<p>No se encontró una habitación disponible en ese momento.</p>";
    }
} else {
    echo "<h2>❌ Datos incompletos para reservar</h2>";
}

echo "<br><a href='index.php'>⬅ Volver</a>";
?>
