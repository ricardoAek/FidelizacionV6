<?php
require_once '../PHP/conexion_BD.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $conexion->prepare("SELECT BeneficioID, Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo FROM Beneficios WHERE BeneficioID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $response = [
                    "id" => $row['BeneficioID'],
                    "empresa" => $row['Empresa_Nombre'],
                    "descripcion" => $row['Beneficio_Descripcion'],
                    "activo" => $row['Beneficio_Activo'] ? "Sí" : "No"
                ];
            } else {
                $response = ["mensaje" => "No encontrado"];
            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $stmt->close();
        } else {
            $query = "SELECT BeneficioID, Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo FROM Beneficios";
            $result = $conexion->query($query);

            $beneficios = [];
            while ($row = $result->fetch_assoc()) {
                $beneficios[] = [
                    "id" => $row['BeneficioID'],
                    "empresa" => $row['Empresa_Nombre'],
                    "descripcion" => $row['Beneficio_Descripcion'],
                    "activo" => $row['Beneficio_Activo'] ? "Sí" : "No"
                ];
            }
            echo json_encode($beneficios, JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        $empresa = $_POST['Empresa_Nombre'] ?? '';
        $descripcion = $_POST['Beneficio_Descripcion'] ?? '';
        $activo = isset($_POST['Beneficio_Activo']) ? (int)$_POST['Beneficio_Activo'] : 0;

        $stmt = $conexion->prepare("INSERT INTO Beneficios (Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $empresa, $descripcion, $activo);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Beneficio creado correctamente"];
        } else {
            $response = ["status" => "error", "message" => $stmt->error];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $stmt->close();
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = isset($put_vars['BeneficioID']) ? (int)$put_vars['BeneficioID'] : 0;
        $empresa = $put_vars['Empresa_Nombre'] ?? '';
        $descripcion = $put_vars['Beneficio_Descripcion'] ?? '';
        $activo = isset($put_vars['Beneficio_Activo']) ? (int)$put_vars['Beneficio_Activo'] : 0;

        $stmt = $conexion->prepare("UPDATE Beneficios SET Empresa_Nombre = ?, Beneficio_Descripcion = ?, Beneficio_Activo = ? WHERE BeneficioID = ?");
        $stmt->bind_param("ssii", $empresa, $descripcion, $activo, $id);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Beneficio actualizado correctamente"];
        } else {
            $response = ["status" => "error", "message" => $stmt->error];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $stmt->close();
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $del_vars);
        $id = isset($del_vars['BeneficioID']) ? (int)$del_vars['BeneficioID'] : 0;

        $stmt = $conexion->prepare("DELETE FROM Beneficios WHERE BeneficioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Beneficio eliminado correctamente"];
        } else {
            $response = ["status" => "error", "message" => $stmt->error];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $stmt->close();
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Método no soportado"], JSON_UNESCAPED_UNICODE);
        break;
}

$conexion->close();
?>
