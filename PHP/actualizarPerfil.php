<?php
session_start();
include 'conexion_BD.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Sesión no válida.'); window.location.href = '../index.php';</script>";
    exit;
}

$usuarioID = $_SESSION['usuario_id'];

// Capturar datos del formulario
$nombre      = trim($_POST['Usuario_Nombre']);
$apellidos   = trim($_POST['Usuario_Apellidos']);
$genero      = $_POST['Usuario_Genero'];
$fechaNac    = $_POST['Usuario_FechaNacimiento'];
$direccion   = trim($_POST['Usuario_Direccion']);
$ciudad      = trim($_POST['Usuario_Ciudad']);
$estado      = trim($_POST['Usuario_Estado']);
$pass1       = $_POST['Usuario_Contrasena'];
$pass2       = $_POST['Usuario_Contrasena2'];

// Validar datos esenciales
if ($pass1 !== $pass2) {
    echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
    exit;
}

// Si hay nueva contraseña, la actualizamos con hash
if (!empty($pass1)) {
    $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);
    $sql = "UPDATE Usuarios SET 
                Usuario_Nombre = ?, 
                Usuario_Apellidos = ?, 
                Usuario_Genero = ?, 
                Usuario_FechaNacimiento = ?, 
                Usuario_Direccion = ?, 
                Usuario_Ciudad = ?, 
                Usuario_Estado = ?, 
                Usuario_Contraseña = ?
            WHERE UsuarioID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre, $apellidos, $genero, $fechaNac, $direccion, $ciudad, $estado, $hashedPassword, $usuarioID);
} else {
    // Si no hay contraseña nueva
    $sql = "UPDATE Usuarios SET 
                Usuario_Nombre = ?, 
                Usuario_Apellidos = ?, 
                Usuario_Genero = ?, 
                Usuario_FechaNacimiento = ?, 
                Usuario_Direccion = ?, 
                Usuario_Ciudad = ?, 
                Usuario_Estado = ?
            WHERE UsuarioID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $nombre, $apellidos, $genero, $fechaNac, $direccion, $ciudad, $estado, $usuarioID);
}

// Ejecutar y verificar
if ($stmt->execute()) {
    echo "<script>alert('Perfil actualizado exitosamente.'); window.location.href = '../HTML/perfil.php';</script>";
} else {
    echo "<script>alert('Error al actualizar el perfil.'); window.history.back();</script>";
}
$stmt->close();
?>
