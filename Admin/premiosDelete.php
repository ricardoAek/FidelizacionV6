<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Eliminar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Eliminar Premio</h2><hr>
        <?php
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "<script>alert('ID de premio no proporcionado.'); window.history.back();</script>";
                exit;
            }
        ?>
        <form id="formEliminarPremio">
            <input type="hidden" id="PremioID" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" id="nombre" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3" readonly></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Puntos Necesarios</label>
                <input class="form-control" id="puntos" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Disponible</label>
                <input class="form-control" id="disponible" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen</label><br>
                <img id="imagen" style="max-width: 200px;">
            </div>

            <div class="mt-4">
                <button class="btn btn-danger" type="submit">
                    <i class="fas fa-trash-alt"></i> Eliminar Premio
                </button>
                <a href="./premiosView.php" class="btn btn-secondary ms-2">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <br><?php include '../Layout/footer.php'; ?>
</div>

<script>
const id = document.getElementById('PremioID').value;

async function cargarPremio() {
    const res = await fetch(`../API/Api_Premios.php?id=${id}`);
    const data = await res.json();
    if (data.mensaje) {
        alert(data.mensaje);
        window.location.href = "./premiosView.php";
        return;
    }
    document.getElementById('nombre').value = data.Premio_Nombre;
    document.getElementById('descripcion').value = data.Premio_Descripcion;
    document.getElementById('puntos').value = data.Premio_PuntosNecesarios;
    document.getElementById('disponible').value = data.Premio_Disponible ? 'Sí' : 'No';
    if (data.Premio_Imagen_URL) {
        document.getElementById('imagen').src = data.Premio_Imagen_URL;
    } else {
        document.getElementById('imagen').style.display = 'none';
    }
}

document.getElementById('formEliminarPremio').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!confirm("¿Estás seguro de eliminar este premio?")) return;

    const data = { PremioID: id };
    const res = await fetch('../API/Api_Premios.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const resData = await res.json();
    alert(resData.message);
    if (resData.status === "success") {
        window.location.href = "./premiosView.php";
    }
});

cargarPremio();
</script>
</body>
</html>
