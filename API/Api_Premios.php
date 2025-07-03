<?php
include '../PHP/conexion_BD.php';

// Permitir CORS si se consume desde front
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Ruta base para las imágenes
$baseUrlImagen = "http://localhost/Mustangv6/Media/productos/";

// GET: listar premios o traer uno por id
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $query = "SELECT * FROM Premios WHERE PremioID = $id";
        $result = mysqli_query($conexion, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['Premio_Imagen']) {
                $row['Premio_Imagen_URL'] = $baseUrlImagen . $row['Premio_Imagen'];
            }
            echo json_encode($row);
        } else {
            echo json_encode(["mensaje" => "Premio no encontrado"]);
        }
    } else {
        $query = "SELECT * FROM Premios";
        $result = mysqli_query($conexion, $query);
        $premios = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['Premio_Imagen']) {
                $row['Premio_Imagen_URL'] = $baseUrlImagen . $row['Premio_Imagen'];
            }
            $premios[] = $row;
        }
        echo json_encode($premios);
    }
    exit();
}

// POST: crear
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre      = trim($data['nombre'] ?? '');
    $descripcion = $data['descripcion'] ?? null;
    $puntos      = intval($data['puntos'] ?? 0);
    $disponible  = intval($data['disponible'] ?? 0);
    $imagenBase64 = $data['imagen'] ?? null;

    $imagenRuta = null;

    if ($imagenBase64) {
        $nombreLimpio = preg_replace("/[^a-zA-Z0-9_-]/", "", strtolower(str_replace(" ", "_", $nombre)));
        $nombreArchivo = $nombreLimpio . "_" . time() . ".jpg";
        $rutaDestino = __DIR__ . "/../Media/productos/" . $nombreArchivo;

        if (file_put_contents($rutaDestino, base64_decode($imagenBase64))) {
            $imagenRuta = $nombreArchivo;
        }
    }

    $stmt = $conexion->prepare("INSERT INTO Premios (Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible, Premio_Imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nombre, $descripcion, $puntos, $disponible, $imagenRuta);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Premio creado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit();
}

// PUT: actualizar
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id          = intval($data['PremioID'] ?? 0);
    $nombre      = trim($data['nombre'] ?? '');
    $descripcion = $data['descripcion'] ?? null;
    $puntos      = intval($data['puntos'] ?? 0);
    $disponible  = intval($data['disponible'] ?? 0);
    $imagenBase64 = $data['imagen'] ?? null;

    $imagenRuta = null;

    if ($imagenBase64) {
        $nombreLimpio = preg_replace("/[^a-zA-Z0-9_-]/", "", strtolower(str_replace(" ", "_", $nombre)));
        $nombreArchivo = $nombreLimpio . "_" . time() . ".jpg";
        $rutaDestino = __DIR__ . "/../Media/productos/" . $nombreArchivo;

        if (file_put_contents($rutaDestino, base64_decode($imagenBase64))) {
            $imagenRuta = $nombreArchivo;
        }
    }

    if ($imagenRuta) {
        $stmt = $conexion->prepare("UPDATE Premios SET Premio_Nombre = ?, Premio_Descripcion = ?, Premio_PuntosNecesarios = ?, Premio_Disponible = ?, Premio_Imagen = ? WHERE PremioID = ?");
        $stmt->bind_param("ssissi", $nombre, $descripcion, $puntos, $disponible, $imagenRuta, $id);
    } else {
        $stmt = $conexion->prepare("UPDATE Premios SET Premio_Nombre = ?, Premio_Descripcion = ?, Premio_PuntosNecesarios = ?, Premio_Disponible = ? WHERE PremioID = ?");
        $stmt->bind_param("ssisi", $nombre, $descripcion, $puntos, $disponible, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Premio actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit();
}

// DELETE: eliminar
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['PremioID'] ?? 0);

    // Buscar imagen
    $stmtImg = $conexion->prepare("SELECT Premio_Imagen FROM Premios WHERE PremioID = ?");
    $stmtImg->bind_param("i", $id);
    $stmtImg->execute();
    $stmtImg->bind_result($imagenRuta);
    $stmtImg->fetch();
    $stmtImg->close();

    $stmt = $conexion->prepare("DELETE FROM Premios WHERE PremioID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($imagenRuta && file_exists(__DIR__ . "/../Media/productos/" . $imagenRuta)) {
            unlink(__DIR__ . "/../Media/productos/" . $imagenRuta);
        }
        echo json_encode(["status" => "success", "message" => "Premio eliminado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
    exit();
}

// Si no coincide ningún método
echo json_encode(["status" => "error", "message" => "Método no permitido"]);
?>
