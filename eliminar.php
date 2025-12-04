<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM productos WHERE id = $id";
mysqli_query($conexion, $sql);

header("Location: index.php");
exit;
