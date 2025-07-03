<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Administrar Premios</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>
    <div class="container mt-4">
        <h2>Administrar Premios</h2><hr>
        <a href="./premiosCreate.php" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Registrar Premio
        </a>

        <!-- Formulario búsqueda -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Mostrando</span>
                        <select name="items_per_page" class="form-select w-auto me-2">
                            <option value="10" <?= (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= (isset($_GET['items_per_page']) && $_GET['items_per_page'] == 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span class="ms-2">por página</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Buscar premio" value="<?= $_GET['search'] ?? ''; ?>">
                </div>
            </div>
        </form>

        <?php
            // Cargar datos desde API
            $api_url = "http://localhost/Mustangv6/API/Api_Premios.php";
            $json = file_get_contents($api_url);
            $premios = json_decode($json, true);

            // Filtros locales (en PHP)
            $items_per_page = $_GET['items_per_page'] ?? 10;
            $search = $_GET['search'] ?? '';

            // Filtrar por nombre
            if (!empty($search)) {
                $premios = array_filter($premios, function($premio) use ($search) {
                    return stripos($premio['Premio_Nombre'], $search) !== false;
                });
            }

            $total_items = count($premios);
            $total_pages = ceil($total_items / $items_per_page);
            $current_page = $_GET['page'] ?? 1;
            $offset = ($current_page - 1) * $items_per_page;

            // Cortar arreglo
            $premios_pagina = array_slice($premios, $offset, $items_per_page);
        ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Puntos</th>
                    <th>Disponible</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($premios_pagina as $row) { ?>
                    <tr>
                        <td class="text-center"><?= $row['PremioID']; ?></td>
                        <td><?= htmlspecialchars($row['Premio_Nombre']); ?></td>
                        <td><?= htmlspecialchars($row['Premio_Descripcion']); ?></td>
                        <td class="text-center"><?= $row['Premio_PuntosNecesarios']; ?></td>
                        <td class="text-center"><?= $row['Premio_Disponible'] ? 'Sí' : 'No'; ?></td>
                        <td class="text-center">
                            <?php if (!empty($row['Premio_Imagen_URL'])): ?>
                                <img src="<?= htmlspecialchars($row['Premio_Imagen_URL']); ?>"
                                     alt="Imagen Premio"
                                     style="max-width: 60px;">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="./premiosEdit.php?id=<?= $row['PremioID']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <a href="./premiosDelete.php?id=<?= $row['PremioID']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-between">
            <p>Mostrando página <?= $current_page; ?> de <?= $total_pages; ?></p>
            <ul class="pagination">
                <li class="page-item <?= $current_page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= urlencode($search); ?>&items_per_page=<?= $items_per_page; ?>">Anterior</a>
                </li>
                <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= urlencode($search); ?>&items_per_page=<?= $items_per_page; ?>">Siguiente</a>
                </li>
            </ul>
        </div>
    </div>

    <?php include '../Layout/footer.php'; ?>
</div>
</body>
</html>
