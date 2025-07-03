<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Editar Premio</h2><hr>
        <?php
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "<script>alert('ID de premio no proporcionado.'); window.history.back();</script>";
                exit;
            }
        ?>
        <form id="formEditarPremio">
            <input type="hidden" id="PremioID" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" id="nombre" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Puntos Necesarios</label>
                <input class="form-control" type="number" id="puntos" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Disponible</label>
                <select class="form-control" id="disponible">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen Actual</label><br>
                <img id="imagenActual" style="max-width: 200px;">
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva Imagen (opcional)</label>
                <input class="form-control" type="file" id="imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                <img id="preview" src="#" alt="Vista previa" style="max-width: 200px; display: none; margin-top: 10px;">
            </div>

            <button class="btn btn-primary" type="submit">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
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
    document.getElementById('disponible').value = data.Premio_Disponible ? '1' : '0';
    if (data.Premio_Imagen_URL) {
        document.getElementById('imagenActual').src = data.Premio_Imagen_URL;
    } else {
        document.getElementById('imagenActual').style.display = 'none';
    }
}

function mostrarVistaPrevia(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result.split(',')[1]);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

document.getElementById('formEditarPremio').addEventListener('submit', async function(e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const puntos = document.getElementById('puntos').value;
    const disponible = document.getElementById('disponible').value;
    const imagenInput = document.getElementById('imagen');

    let imagenBase64 = null;

    if (imagenInput.files.length > 0) {
        imagenBase64 = await fileToBase64(imagenInput.files[0]);
    }

    const data = {
        PremioID: id,
        nombre: nombre,
        descripcion: descripcion,
        puntos: puntos,
        disponible: disponible,
        ...(imagenBase64 && { imagen: imagenBase64 })
    };

    await enviarUpdate(data);
});

async function enviarUpdate(data) {
    const res = await fetch('../API/Api_Premios.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const resData = await res.json();
    alert(resData.message);
    if (resData.status === "success") {
        window.location.href = "./premiosView.php";
    }
}

cargarPremio();
</script>
</body>
</html>
