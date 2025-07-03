<?php
require_once '../PHP/conexion_BD.php';
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Función para generar tarjeta única
function generarTarjetaUnica($conexion) {
    do {
        $bloques = [];
        for ($i = 0; $i < 4; $i++) {
            $bloques[] = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        $tarjeta = implode('-', $bloques);

        $consulta = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Tarjeta = '$tarjeta'");
    } while (mysqli_num_rows($consulta) > 0);

    return $tarjeta;
}

switch ($method) {
    case 'GET':
        if ($id) {
            $query = "SELECT * FROM Usuarios WHERE UsuarioID = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            echo json_encode($data ? $data : ["message" => "Cliente no encontrado"]);
            $stmt->close();
        } else {
            $query = "SELECT * FROM Usuarios";
            $result = mysqli_query($conexion, $query);

            $clientes = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $clientes[] = $row;
            }
            echo json_encode($clientes);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['email']) || !isset($input['telefono']) || !isset($input['contrasena'])) {
            echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios"]);
            exit;
        }

        // Verificar email
        $email = $input['email'];
        $tel = $input['telefono'];
        $checkEmail = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Email = '$email'");
        if (mysqli_num_rows($checkEmail) > 0) {
            echo json_encode(["status" => "error", "message" => "Correo ya registrado"]);
            exit;
        }

        // Verificar teléfono
        $checkTel = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Telefono = '$tel'");
        if (mysqli_num_rows($checkTel) > 0) {
            echo json_encode(["status" => "error", "message" => "Teléfono ya registrado"]);
            exit;
        }

        $passwordHash = password_hash($input['contrasena'], PASSWORD_BCRYPT);
        $tarjeta = generarTarjetaUnica($conexion);
        $puntos = 250;

        $stmt = $conexion->prepare("INSERT INTO Usuarios (
            Usuario_Nombre, Usuario_Apellidos, Usuario_Telefono, Usuario_Email, Usuario_Contraseña,
            Usuario_Genero, Usuario_FechaNacimiento, Usuario_Direccion, Usuario_Ciudad, Usuario_Estado,
            Usuario_Tarjeta, Usuario_Puntos
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssssssi",
            $input['nombre'], $input['apellidos'], $input['telefono'], $input['email'], $passwordHash,
            $input['genero'], $input['fechaNacimiento'], $input['direccion'], $input['ciudad'], $input['estado'],
            $tarjeta, $puntos
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cliente creado", "tarjeta" => $tarjeta, "puntos" => $puntos]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = isset($put_vars['UsuarioID']) ? intval($put_vars['UsuarioID']) : 0;

        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID requerido"]);
            exit;
        }

        $nombre = $put_vars['Usuario_Nombre'] ?? '';
        $apellidos = $put_vars['Usuario_Apellidos'] ?? '';
        $telefono = $put_vars['Usuario_Telefono'] ?? '';
        $email = $put_vars['Usuario_Email'] ?? '';
        $genero = $put_vars['Usuario_Genero'] ?? '';
        $fechaNacimiento = $put_vars['Usuario_FechaNacimiento'] ?? '';
        $direccion = $put_vars['Usuario_Direccion'] ?? '';
        $ciudad = $put_vars['Usuario_Ciudad'] ?? '';
        $estado = $put_vars['Usuario_Estado'] ?? '';
        $puntos = isset($put_vars['Usuario_Puntos']) ? intval($put_vars['Usuario_Puntos']) : 0;
        $nuevaContrasena = $put_vars['Usuario_Contraseña'] ?? '';

        if (!empty($nuevaContrasena)) {
            $passwordHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

            $sql = "UPDATE Usuarios SET Usuario_Nombre=?, Usuario_Apellidos=?, Usuario_Telefono=?, Usuario_Email=?, Usuario_Genero=?, Usuario_FechaNacimiento=?, Usuario_Direccion=?, Usuario_Ciudad=?, Usuario_Estado=?, Usuario_Contraseña=?, Usuario_Puntos=? WHERE UsuarioID=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssssssii", $nombre, $apellidos, $telefono, $email, $genero, $fechaNacimiento, $direccion, $ciudad, $estado, $passwordHash, $puntos, $id);
        } else {
            $sql = "UPDATE Usuarios SET Usuario_Nombre=?, Usuario_Apellidos=?, Usuario_Telefono=?, Usuario_Email=?, Usuario_Genero=?, Usuario_FechaNacimiento=?, Usuario_Direccion=?, Usuario_Ciudad=?, Usuario_Estado=?, Usuario_Puntos=? WHERE UsuarioID=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssssiii", $nombre, $apellidos, $telefono, $email, $genero, $fechaNacimiento, $direccion, $ciudad, $estado, $puntos, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cliente actualizado"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $del_vars);
        $id = isset($del_vars['UsuarioID']) ? intval($del_vars['UsuarioID']) : 0;

        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID requerido"]);
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM Usuarios WHERE UsuarioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cliente eliminado"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Método no soportado"]);
        break;
}

$conexion->close();
?>
