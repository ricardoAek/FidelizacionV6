<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mustang - Administrar Clientes</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Administrar Clientes</h2><hr>
        <a href="./clientesCreate.php" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Registrar Cliente
        </a>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Mostrando</span>
                        <select name="items_per_page" class="form-select w-auto me-2">
                            <option value="10" <?= isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= isset($_GET['items_per_page']) && $_GET['items_per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span class="ms-2">por página</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Buscar cliente" value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
            </div>
        </form>

        <?php
            $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            // Cargar datos desde API
            $api_url = "http://localhost/Mustangv6/API/Api_Clientes.php";
            $json = file_get_contents($api_url);
            $clientes = json_decode($json, true);

            // Filtrar búsqueda
            if (!empty($search)) {
                $clientes = array_filter($clientes, function($c) use ($search) {
                    return stripos($c['nombre'] . ' ' . $c['apellidos'], $search) !== false;
                });
            }

            // Paginación manual
            $total_items = count($clientes);
            $total_pages = ceil($total_items / $items_per_page);
            $offset = ($current_page - 1) * $items_per_page;
            $clientes = array_slice($clientes, $offset, $items_per_page);
        ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)) : ?>
                    <?php foreach ($clientes as $c) : ?>
                        <tr>
                            <td class="text-center"><?= $c['UsuarioID']; ?></td>
                            <td><?= $c['Usuario_Nombre'] . ' ' . $c['Usuario_Apellidos']; ?></td>
                            <td><?= $c['Usuario_Email']; ?></td>
                            <td class="text-center"><?= $c['Usuario_Telefono']; ?></td>
                            <td class="text-center">
                                <a href="./clientesEdit.php?id=<?= $c['UsuarioID']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="./clientesDelete.php?id=<?= $c['UsuarioID']; ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="5" class="text-center">No se encontraron clientes.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <div>
                <p>Mostrando <?= $current_page; ?> de <?= $total_pages; ?> páginas</p>
            </div>
            <div>
                <ul class="pagination">
                    <li class="page-item <?= $current_page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= $search; ?>&items_per_page=<?= $items_per_page; ?>">Anterior</a>
                    </li>
                    <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= $search; ?>&items_per_page=<?= $items_per_page; ?>">Siguiente</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <br> <?php include '../Layout/footer.php'; ?>
</div>
</body>
</html>
