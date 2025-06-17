
<?<php>

include '../includes/db.php';
$ciudad = $_GET['ciudad'];
$tipo = $_Get['tipo'];
$personas = (int)$_GET['personas'];
$inicio = $_GET['inicio']
$fin = $_GET['fin'];

// Buscarodr de hotel_id
$hotel = $pdo->prepare("SELECT id FROM hoteles WHERE ciudad = ?");
$hotel->execute([$ciudad]);
$hotel_id = $hotel->fetchColumn():
// Buscar tipo de habitacion
$sql ="SELECT h.* from habitaciones h
        left join reservas r ON h.id = r.habitacion_id
        and(
            r.fecha_inicio <= : fin And r.fecha_fin >= :inicio
        )
        WHERE r.id IS NULL
        AND h.capacidad >= :personas
        AND h.tipo = :tipo
        AND h.hotel_id = :hotel_id";

$stmt = $pdo->prepare($sql):        
$stmt->execute([
    'inicio' => $inicio,
    'fin' => $fin,
    'personas' => $personas,
    'tipo' => $tipo,
    'hotel_id' => $hotel_id
]);

$habitaciones =$stmt->fetchAll();
foreach ($habitaciones as $habitacion) {
    echo "ID: {$H['id']} -  Tipo: {$h['Tipo']} - Capacidad:{$h['capacidad']}>br>";
}