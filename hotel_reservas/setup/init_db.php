<?php
$db_path = __DIR__ . '/../db/hotel.sqlite';

if (!file_exists(dirname($db_path))) {
    mkdir(dirname($db_path), 0777, true);
}

$pdo = new PDO("sqlite:$db_path");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//// Borrar si ya existe
$pdo->exec("DROP TABLE IF EXISTS reservas");
$pdo->exec("DROP TABLE IF EXISTS habitaciones");
$pdo->exec("DROP TABLE IF EXISTS hoteles");

////// Crear tablas
$pdo->exec("
    CREATE TABLE hoteles (
        id INTEGER PRIMARY KEY,
        nombre TEXT NOT NULL,
        ciudad TEXT NOT NULL
    );
");

$pdo->exec("
    CREATE TABLE habitaciones (
        id INTEGER PRIMARY KEY,
        hotel_id INTEGER,
        tipo TEXT,
        capacidad INTEGER,
        FOREIGN KEY (hotel_id) REFERENCES hoteles(id)
    );
");

$pdo->exec("
    CREATE TABLE reservas (
        id INTEGER PRIMARY KEY,
        habitacion_id INTEGER,
        fecha_inicio DATE,
        fecha_fin DATE,
        personas INTEGER,
        FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id)
    );
");

////// Insertar hoteles
$hoteles = [
    ['Hotel Barranquilla', 'Barranquilla'],
    ['Hotel Cali', 'Cali'],
    ['Hotel Cartagena', 'Cartagena'],
    ['Hotel Bogotá', 'Bogotá']
];
$stmt = $pdo->prepare("INSERT INTO hoteles (nombre, ciudad) VALUES (?, ?)");
foreach ($hoteles as $h) {
    $stmt->execute($h);
}

////// Insertar habitaciones (por ciudad)
$habitaciones = [
    // Barranquilla: 30 estandar, 3 premium (4 personas)
    ['Barranquilla', 'estandar', 4, 30],
    ['Barranquilla', 'premium', 4, 3],
    // Cali: 20 premium, 2 vip (6 personas)
    ['Cali', 'premium', 6, 20],
    ['Cali', 'vip', 6, 2],
    // Cartagena: 10 estandar, 1 premium (8 personas)
    ['Cartagena', 'estandar', 8, 10],
    ['Cartagena', 'premium', 8, 1],
    // Bogotá: 20 estandar, 20 premium, 2 vip (6 personas)
    ['Bogotá', 'estandar', 6, 20],
    ['Bogotá', 'premium', 6, 20],
    ['Bogotá', 'vip', 6, 2]
];
foreach ($habitaciones as $data) {
    [$ciudad, $tipo, $capacidad, $cantidad] = $data;
    $hotel_id = $pdo->query("SELECT id FROM hoteles WHERE ciudad = '$ciudad'")->fetchColumn();
    $stmt = $pdo->prepare("INSERT INTO habitaciones (hotel_id, tipo, capacidad) VALUES (?, ?, ?)");
    for ($i = 0; $i < $cantidad; $i++) {
        $stmt->execute([$hotel_id, $tipo, $capacidad]);
    }
}

echo "Base de datos creada y poblada correctamente.";
