<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Eliminar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>
    <div class="container mt-4">
        <h2>Eliminar Cliente</h2><hr>
        <?php
            if (!isset($_GET['id'])) {
                die("ID de cliente no especificado.");
            }
            $id = intval($_GET['id']);
            $json = file_get_contents("http://localhost/Mustangv6/API/Api_Clientes.php?id=$id");
            $row = json_decode($json, true);
            if (!$row || isset($row['mensaje'])) {
                die("Cliente no encontrado.");
            }
        ?>
        <form id="clienteDeleteForm">
            <input type="hidden" name="UsuarioID" value="<?= $row['UsuarioID']; ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($row['Usuario_Nombre']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($row['Usuario_Apellidos']); ?>">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-danger" type="submit">
                    <i class="fas fa-trash-alt"></i> Eliminar Cliente
                </button>
                <a href="../Admin/clientesView.php" class="btn btn-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
    <br>
    <?php include '../Layout/footer.php'; ?>
</div>

<script>
document.getElementById('clienteDeleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = new URLSearchParams();
    for (const pair of formData) {
        data.append(pair[0], pair[1]);
    }

    fetch('http://localhost/Mustangv6/API/Api_Clientes.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message);
        if (res.status === 'success') {
            window.location.href = '../Admin/clientesView.php';
        }
    })
    .catch(err => alert('Error: ' + err));
});
</script>
</body>
</html>
