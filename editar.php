<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id = $id";
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) === 0) {
    echo "Producto no encontrado.";
    exit;
}

$producto = mysqli_fetch_assoc($resultado);
$mensaje  = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? "");
    $cantidad = intval($_POST['cantidad'] ?? 0);

    if ($nombre === "" || $cantidad < 0) {
        $mensaje = "Llene todos los campos correctamente.";
    } else {
        $nombre_esc = mysqli_real_escape_string($conexion, $nombre);
        $sqlUpdate  = "UPDATE productos SET nombre = '$nombre_esc', cantidad = $cantidad WHERE id = $id";
        if (mysqli_query($conexion, $sqlUpdate)) {
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Error al actualizar: " . mysqli_error($conexion);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
</head>
<body>

    <h1>Editar producto</h1>

    <?php if ($mensaje !== ""): ?>
        <p style="color:red;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Nombre del producto:</label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required><br><br>

        <label>Cantidad:</label><br>
        <input type="number" name="cantidad" min="0" value="<?php echo $producto['cantidad']; ?>" required><br><br>

        <button type="submit">Actualizar</button>
        <a href="index.php">Volver</a>
    </form>

</body>
</html>
