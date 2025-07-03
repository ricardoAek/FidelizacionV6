<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Crear Beneficio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Crear Beneficio</h2><hr>
            <form id="formCrear">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Empresa</label>
                        <input class="form-control" name="Empresa_Nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activo</label>
                        <select class="form-control" name="Beneficio_Activo" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción del Beneficio</label>
                        <textarea class="form-control" name="Beneficio_Descripcion" rows="3"></textarea>
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Beneficio
                </button>
            </form>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <script>
        const form = document.getElementById('formCrear');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('../API/api_beneficios.php', {
                method: 'POST',
                body: formData
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
