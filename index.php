<?php
require_once 'conexion.php';


$busqueda = "";
if (isset($_GET['buscar']) && $_GET['buscar'] !== "") {
    $busqueda = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM productos ORDER BY id DESC";
}

$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Productos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Listado de Productos</h1>

<form method="get" action="index.php">
    <label>Buscar producto: </label>
    <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>">
    <button type="submit">Buscar</button>
    <a class="btn" href="index.php">Limpiar</a>
</form>

<a class="btn btn-agregar" href="crear.php">Agregar</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del producto</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <td>
                <a class="btn btn-editar" href="editar.php?id=<?php echo $fila['id']; ?>">Editar</a>
                <a class="btn btn-eliminar" href="eliminar.php?id=<?php echo $fila['id']; ?>"
                onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                    Eliminar
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No se encontraron productos.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
