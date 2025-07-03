<?php include '../PHP/session.php';
 include '../PHP/conexion_BD.php'; 
 
// Obtener datos del usuario logueado
$usuarioID = $_SESSION['usuario_id'];

$sqlUsuario = "SELECT * FROM Usuarios WHERE UsuarioID = ?";
$stmtUsuario = $conexion->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $usuarioID);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();

if ($resultUsuario->num_rows > 0) {
    $usuario = $resultUsuario->fetch_assoc();
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href = 'logout.php';</script>";
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Perfil</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h3>Datos Personales</h3><hr>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Nombre'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Apellidos'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Telefono'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Email'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Género</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Genero'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_FechaNacimiento'] ?? ''); ?>" readonly>
                </div>
            </div>

            <h3>Datos de Ubicación</h3><hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Direccion'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ciudad</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Ciudad'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Estado'] ?? ''); ?>" readonly>
                </div>
            </div>

            <a href="./editarPerfil.php" class="btn btn-secondary mt-3">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </a>
        </div>

        <div class="container mt-4">
            <h3>Tarjeta Cliente </h3><hr>
            <div class="card text-white" style="background-color: #3c3c52; max-width: 400px; border: none; border-radius: 10px;">
                <div class="card-body">
                    <h5 class="card-title">Número de Tarjeta</h5>
                    <p class="card-text fs-5">
                        <?php echo htmlspecialchars($usuario['Usuario_Tarjeta'] ?? 'No disponible'); ?>
                    </p>

                    <h5 class="card-title mt-3">Puntos</h5>
                    <p class="card-text fs-5">
                        <?php echo htmlspecialchars($usuario['Usuario_Puntos'] ?? '0'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <h3>Historial de Premios Canjeados</h3><hr>
            <?php
                // Asegúrate de tener conexión activa
                $usuarioID = $_SESSION['usuario_id'];

                $sqlHistorial = "
                    SELECT P.Premio_Nombre, P.Premio_PuntosNecesarios, C.FechaCanje
                    FROM Canjes C
                    INNER JOIN Premios P ON C.PremioID = P.PremioID
                    WHERE C.UsuarioID = ?
                    ORDER BY C.FechaCanje DESC
                ";

                $stmt = $conexion->prepare($sqlHistorial);
                $stmt->bind_param("i", $usuarioID);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0): ?>
                    <ul class="list-group mt-3">
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <li class="list-group-item">
                                Canjeó "<strong><?php echo htmlspecialchars($row['Premio_Nombre']); ?></strong>" el día 
                                <strong><?php echo date('d/m/Y H:i', strtotime($row['FechaCanje'])); ?></strong>
                                con un valor de 
                                <strong><?php echo intval($row['Premio_PuntosNecesarios']); ?> puntos</strong>.
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info mt-3" role="alert">
                        Aún no has canjeado ningún premio.
                    </div>
            <?php endif; ?>
        </div>

        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
