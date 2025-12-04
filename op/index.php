<?php
require_once "conexion.php";

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM productos WHERE id=$id");
    header("Location:index.php");
    exit;
}

if (isset($_POST['guardar'])) {
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $cantidad = intval($_POST['cantidad']);
    if ($nombre !== "" && $cantidad >= 0) {
        $res = mysqli_query($conexion, "SELECT id, cantidad FROM productos WHERE nombre='$nombre'");
        if ($res && mysqli_num_rows($res) > 0) {
            $p = mysqli_fetch_assoc($res);
            $nueva = $p['cantidad'] + $cantidad;
            mysqli_query($conexion, "UPDATE productos SET cantidad=$nueva WHERE id=".$p['id']);
        } else {
            mysqli_query($conexion, "INSERT INTO productos (nombre, cantidad) VALUES ('$nombre',$cantidad)");
        }
    }
    header("Location:index.php");
    exit;
}

if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $cantidad = intval($_POST['cantidad']);
    if ($nombre !== "" && $cantidad >= 0) {
        mysqli_query($conexion, "UPDATE productos SET nombre='$nombre', cantidad=$cantidad WHERE id=$id");
    }
    header("Location:index.php");
    exit;
}

$busqueda = "";
if (!empty($_GET['buscar'])) {
    $busqueda = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM productos ORDER BY id DESC";
}
$productos = mysqli_query($conexion, $sql);

$mostrarFormulario = false;
$editar = null;
if (isset($_GET['nuevo'])) {
    $mostrarFormulario = true;
}
if (isset($_GET['editar'])) {
    $mostrarFormulario = true;
    $idE = intval($_GET['editar']);
    $q = mysqli_query($conexion, "SELECT * FROM productos WHERE id=$idE LIMIT 1");
    if (mysqli_num_rows($q) > 0) $editar = mysqli_fetch_assoc($q);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>CRUD Productos</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>CRUD Productos</h1>

<form method="get">
    <input type="text" name="buscar" placeholder="Buscar" value="<?php echo htmlspecialchars($busqueda); ?>">
    <button type="submit">Buscar</button>
    <a class="btn" href="index.php">Limpiar</a>
</form>

<a class="btn btn-guardar" href="?nuevo=1">Agregar producto</a>

<?php if ($mostrarFormulario): ?>
<h2><?php echo $editar ? "Editar" : "Agregar"; ?></h2>

<form method="post" action="">
    <?php if ($editar): ?>
        <input type="hidden" name="id" value="<?php echo $editar['id']; ?>">
    <?php endif; ?>

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required value="<?php echo $editar ? htmlspecialchars($editar['nombre']) : ""; ?>">
    <br><br>

    <label>Cantidad:</label><br>
    <input type="number" name="cantidad" min="0" required value="<?php echo $editar ? $editar['cantidad'] : ""; ?>">
    <br><br>

    <?php if ($editar): ?>
        <button type="submit" name="actualizar" class="btn btn-guardar">Actualizar</button>
        <a class="btn" href="index.php">Cancelar</a>
    <?php else: ?>
        <button type="submit" name="guardar" class="btn btn-guardar">Guardar</button>
    <?php endif; ?>
</form>
<?php endif; ?>

<h2>Lista de productos</h2>

<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Cantidad</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php if (mysqli_num_rows($productos) > 0): ?>
<?php while ($p = mysqli_fetch_assoc($productos)): ?>
<tr>
<td><?php echo $p['id']; ?></td>
<td><?php echo htmlspecialchars($p['nombre']); ?></td>
<td><?php echo $p['cantidad']; ?></td>
<td>
<a class="btn btn-editar" href="?editar=<?php echo $p['id']; ?>">Editar</a>
<a class="btn btn-eliminar" href="?eliminar=<?php echo $p['id']; ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="4">Sin productos</td></tr>
<?php endif; ?>
</tbody>
</table>

</body>
</html>
