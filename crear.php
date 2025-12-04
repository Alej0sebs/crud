<?php
require_once 'conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? "");
    $cantidad = intval($_POST['cantidad'] ?? 0);

    if ($nombre === "" || $cantidad < 0) {
        $mensaje = "Llene todos los campos correctamente.";
    } else {
        $nombre_esc = mysqli_real_escape_string($conexion, $nombre);

        
        $sqlExiste = "SELECT cantidad FROM productos WHERE nombre = '$nombre_esc'";
        $resultado = mysqli_query($conexion, $sqlExiste);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
           
            $producto = mysqli_fetch_assoc($resultado);
            $nuevaCantidad = $producto['cantidad'] + $cantidad;

            $sqlUpdate = "UPDATE productos SET cantidad = $nuevaCantidad WHERE nombre = '$nombre_esc'";
            if (mysqli_query($conexion, $sqlUpdate)) {
                header("Location: index.php");
                exit;
            } else {
                $mensaje = "Error al actualizar: " . mysqli_error($conexion);
            }

        } else {
            
            $sqlInsert = "INSERT INTO productos (nombre, cantidad) VALUES ('$nombre_esc', $cantidad)";
            if (mysqli_query($conexion, $sqlInsert)) {
                header("Location: index.php");
                exit;
            } else {
                $mensaje = "Error al guardar: " . mysqli_error($conexion);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar producto</title>
</head>
<body>

<h1>Agregar producto</h1>

<?php if ($mensaje !== ""): ?>
    <p style="color:red;"><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="post" action="crear.php">
    <label>Nombre del producto:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Cantidad:</label><br>
    <input type="number" name="cantidad" min="0" required><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Volver</a>
</form>

</body>
</html>
