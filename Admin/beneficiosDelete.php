<?php
include '../PHP/session.php';
include '../PHP/conexion_BD.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID no válido'); window.location.href = './beneficiosView.php';</script>";
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conexion->prepare("SELECT Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo FROM Beneficios WHERE BeneficioID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Beneficio no encontrado'); window.location.href = './beneficiosView.php';</script>";
    exit;
}

$beneficio = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Eliminar Beneficio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Eliminar Beneficio</h2><hr>
            <form id="formEliminar">
                <input type="hidden" name="BeneficioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Empresa</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($beneficio['Empresa_Nombre']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activo</label>
                        <input class="form-control" value="<?php echo $beneficio['Beneficio_Activo'] ? 'Sí' : 'No'; ?>" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($beneficio['Beneficio_Descripcion']); ?></textarea>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Beneficio
                    </button>
                    <a href="beneficiosView.php" class="btn btn-secondary ms-2">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <script>
        const form = document.getElementById('formEliminar');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            fetch('../API/api_beneficios.php', {
                method: 'DELETE',
                body: params,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'beneficiosView.php';
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        });
    </script>
</body>
</html>
