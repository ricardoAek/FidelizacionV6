<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Administrar Beneficios</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Administrar Beneficios</h2><hr>
            <a href="./beneficiosCreate.php" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Agregar Beneficio
            </a>

            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Mostrando</span>
                            <select name="items_per_page" class="form-select w-auto me-2" onchange="this.form.submit()">
                                <option value="10" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                            </select>
                            <span class="ms-2">por página</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Buscar empresa" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    </div>
                </div>
            </form>

            <?php
                // Configuración de paginación y búsqueda
                $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                // URL de la API
                $api_url = "http://localhost/Mustangv6/API/Api_Beneficios.php"; // Cambia si tu API está en otro dominio

                // Obtener datos JSON
                $json_data = file_get_contents($api_url);
                $beneficios = json_decode($json_data, true);

                if (!is_array($beneficios)) {
                    $beneficios = [];
                }

                // Filtrar búsqueda
                if (!empty($search)) {
                    $beneficios = array_filter($beneficios, function($b) use ($search) {
                        return stripos($b['empresa'], $search) !== false;
                    });
                }

                // Paginación manual
                $total_items = count($beneficios);
                $total_pages = ceil($total_items / $items_per_page);
                $offset = ($current_page - 1) * $items_per_page;
                $beneficios = array_slice($beneficios, $offset, $items_per_page);
            ?>

            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Empresa</th>
                        <th>Descripción</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($beneficios)) : ?>
                        <?php foreach ($beneficios as $b) : ?>
                            <tr>
                                <td class="text-center"><?php echo $b['id']; ?></td>
                                <td><?php echo htmlspecialchars($b['empresa']); ?></td>
                                <td><?php echo htmlspecialchars($b['descripcion']); ?></td>
                                <td class="text-center"><?php echo $b['activo']; ?></td>
                                <td class="text-center">
                                    <a href="./beneficiosEdit.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./beneficiosDelete.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron beneficios.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="d-flex justify-content-between">
                <div>
                    <p>Mostrando <?php echo $current_page; ?> de <?php echo $total_pages; ?> páginas</p>
                </div>
                <div>
                    <ul class="pagination">
                        <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search); ?>&items_per_page=<?php echo $items_per_page; ?>">Anterior</a>
                        </li>
                        <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search); ?>&items_per_page=<?php echo $items_per_page; ?>">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
