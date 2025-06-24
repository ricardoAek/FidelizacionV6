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
            include '../PHP/conexion_BD.php';

            $items_per_page = $_GET['items_per_page'] ?? 10;
            $search = mysqli_real_escape_string($conexion, $_GET['search'] ?? '');

            $query_count = "SELECT COUNT(*) FROM Premios WHERE Premio_Nombre LIKE '%$search%'";
            $result_count = mysqli_query($conexion, $query_count);
            $total_items = mysqli_fetch_row($result_count)[0];
            $total_pages = ceil($total_items / $items_per_page);
            $current_page = $_GET['page'] ?? 1;
            $offset = ($current_page - 1) * $items_per_page;

            $query = "SELECT PremioID, Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible, Premio_Imagen
                      FROM Premios 
                      WHERE Premio_Nombre LIKE '%$search%' 
                      LIMIT $items_per_page OFFSET $offset";
            $result = mysqli_query($conexion, $query);
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
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="text-center"><?= $row['PremioID']; ?></td>
                        <td><?= htmlspecialchars($row['Premio_Nombre']); ?></td>
                        <td><?= htmlspecialchars($row['Premio_Descripcion']); ?></td>
                        <td class="text-center"><?= $row['Premio_PuntosNecesarios']; ?></td>
                        <td class="text-center"><?= $row['Premio_Disponible'] ? 'Sí' : 'No'; ?></td>
                        <td class="text-center">
                            <?php if (!empty($row['Premio_Imagen'])): ?>
                                
                                <img src="../Media/Productos/<?= htmlspecialchars($row['Premio_Imagen']) ?>"
                                     alt="Imagen Premio"
                                     style="max-width: 60px; cursor:pointer;"
                                     data-bs-toggle="modal"
                                     data-bs-target="#modalImagen"
                                     data-img="/Media/Productos/<?= htmlspecialchars($row['Premio_Imagen']); ?>">
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
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= $search; ?>&items_per_page=<?= $items_per_page; ?>">Anterior</a>
                </li>
                <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= $search; ?>&items_per_page=<?= $items_per_page; ?>">Siguiente</a>
                </li>
            </ul>
        </div>
    </div>

    <?php include '../Layout/footer.php'; ?>
</div>

<
</body>
</html>
