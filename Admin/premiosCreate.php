<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Crear Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Crear Premio</h2><hr>
        <form id="formCrearPremio">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre del Premio</label>
                    <input class="form-control" id="nombre" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Puntos Necesarios</label>
                    <input class="form-control" type="number" id="puntos" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3"></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Disponible</label>
                    <select class="form-control" id="disponible" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Imagen (opcional)</label>
                    <input class="form-control" type="file" id="imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                    <img id="preview" src="#" style="max-width:200px; display:none; margin-top:10px;">
                </div>
            </div>

            <button class="btn btn-secondary" type="submit">
                <i class="fas fa-save"></i> Guardar Premio
            </button>
        </form>
    </div>

    <br><?php include '../Layout/footer.php'; ?>
</div>

<script>
    function mostrarVistaPrevia(event) {
        const [file] = event.target.files;
        const preview = document.getElementById('preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    document.getElementById('formCrearPremio').addEventListener('submit', async function(e) {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const puntos = parseInt(document.getElementById('puntos').value, 10);
        const disponible = parseInt(document.getElementById('disponible').value, 10);
        const inputFile = document.getElementById('imagen');
        let imagenBase64 = null;

        if (inputFile.files.length > 0) {
            // Convertir la imagen a base64
            imagenBase64 = await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result.split(',')[1]);
                reader.onerror = () => resolve(null);
                reader.readAsDataURL(inputFile.files[0]);
            });
        }

        // Construir payload
        const payload = { nombre, descripcion, puntos, disponible };
        if (imagenBase64) payload.imagen = imagenBase64;

        // Enviar a la API
        try {
            const res = await fetch('../API/Api_Premios.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            alert(data.message);
            if (data.status === 'success') {
                window.location.href = './premiosView.php';
            }
        } catch (err) {
            alert('Error al conectar con la API: ' + err);
        }
    });
</script>
</body>
</html>
