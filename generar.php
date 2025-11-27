<?php
session_start();
//session_destroy();

if (!isset($_SESSION['ud01_12'])) {
    $albaran = [];
    $numero_version = 1;
} else {
    $albaran = $_SESSION['ud01_12']['albaran'];
    $numero_version = $_SESSION['ud01_12']['version'];
}
if (isset($_POST['reset'])) {
    $albaran = [];
    $numero_version++;
    $_SESSION['ud01_12']['albaran'] = $albaran;
    $_SESSION['ud01_12']['version'] = $numero_version;
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        table {
            text-align: center;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .bruto {
            text-align: right;
        }
    </style>
</head>
<body>
<?php
if (isset($_POST["agregar"])) {
    $esta = 0;
    $ref = $_POST["ref"];
    $concepto = $_POST["concepto"];
    $unidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    foreach ($albaran as &$producto) {
        if ($producto['ref'] === $ref) {
            $producto['cantidades'] += $unidad;
            $esta = 1;
        }
    }
    if ($esta != 1) {
        $albaran[] = ['ref' => $ref, 'concepto' => $concepto, 'cantidades' => $unidad, 'precio' => $precio];
    }
    $numero_version++;
}
if (isset($_POST['mas'])) {
    $referencia = $_POST['referencia'];

    foreach ($albaran as &$producto) {
        if ($producto["ref"] === $referencia) {
            $producto['cantidades']++;
            $numero_version++;
        }
    }
}
if (isset($_POST['menos'])) {
    $referencia = $_POST['referencia'];
    foreach ($albaran as &$producto) {
        if ($producto['ref'] === $referencia) {
            if ($producto['cantidades'] === 0) {
            } else {
                $producto['cantidades']--;
                $numero_version++;
            }

        }
    }
}
if (isset($_POST['confirmar'])) {
    $referencia = $_POST['referencia'];
    echo "<form method='post'>
                    <input type='hidden' name='referencia' value='$referencia'>
                    <button type='submit' name='si'>Si</button><button type='submit' name='no'>No</button>";

}
if (isset($_POST['si'])) {
    $referencia = $_POST['referencia'];
    foreach ($albaran as &$producto) {
        if ($producto['ref'] === $referencia) {
            $producto['cantidades'] = 0;
            $numero_version++;
            break;
        }
    }
}
if (isset($_POST['no'])) {
    $referencia = $_POST['referencia'];
    $numero_version++;
}

?>
<h1>Albarán (Versión <?php echo $numero_version ?>)</h1>
<?php
if (count($albaran) != 0) {
    $num = 0;
    $cantidad = 0;
    $bruto = 0;
    $iva = 0;
    $descuento = 0;
    $precio_articulo = 0;
    foreach ($albaran as $producto) {
        $cantidad += $producto["cantidades"];
    }
    if ($producto['cantidades'] > 0) {
        $cantidad=0;
        echo "<table border='1'>
    <tr>
        <th></th>
        <th>Uds</th>
        <th>Referencia</th>
        <th>Concepto</th>
        <th>Precio ud.</th>
        <th>Subtotal</th>
        <th></th>
    </tr>";
    foreach ($albaran as $producto) {
        $num++;
        $cantidad += $producto["cantidades"];
        $precio_articulo = $producto['cantidades'] * $producto['precio'];
        $bruto += $precio_articulo;

            echo "<tr>";
            echo "<td>" . $num . "</td>";
            echo "<td>";
            echo "<form method='post'>
                    <input type='hidden' name='referencia' value='{$producto["ref"]}'>
                    <button type='submit' name='mas'>+</button>"
                    . $producto['cantidades'] .
                    "<button type='submit' name='menos'>-</button>";
            echo "<td > " . $producto['ref'] . "</td > ";
            echo "<td > " . $producto['concepto'] . "</td > ";
            echo "<td > " . number_format($producto['precio'], 2) . " €" . " </td > ";
            echo "<td > " . number_format($precio_articulo, 2) . " €" . " </td > ";
            echo "<td >";
            echo "<button type='submit' name='confirmar'>Eliminar</button></td> ";
            echo "</form>";
            echo "</tr> ";
        }

    }
    if ($bruto > 3000) {
        $descuento = $bruto * -.2;
    } elseif ($bruto >= 2000) {
        $descuento = $bruto * -.1;
    } else {
        $descuento = 0;
    }
    if ($cantidad != 0) {
        $iva = ($bruto + $descuento) * .21;
        echo "<tr ><td ></td ><td > " . $cantidad . "</td > ";
        echo "<td colspan = '3' class='bruto' > Bruto:</td > ";
        echo "<td > " . number_format($bruto, 2) . " €" . " </td ><td ></td ></tr > ";
        echo "<tr ><td colspan = '5' class='bruto' > Descuento(10 %):</td > ";
        echo "<td > " . number_format($descuento, 2) . " €" . " </td ><td ></td ></tr > ";
        echo "<tr ><td colspan = '5' class='bruto' > IVA:</td > ";
        echo "<td > " . number_format($iva, 2) . " €" . " </td ><td ></td ></tr > ";
        echo "<tr ><td colspan = '5' class='bruto' > Neto:</td > ";
        echo "<td > " . number_format($bruto + $descuento + $iva, 2) . " €" . " </td ><td ></td ></tr > ";
    }
    echo "</table>";
}
?>

<form method="post">
    Referencia: <input type="text" required name="ref">
    Concepto: <input type="text" required name="concepto"><br>
    Unidades: <input type="number" min="1" name="cantidad">
    Precio unidad: <input type="number" min="0" name="precio"><br>

    <button type="submit" name="agregar">Nuevo concepto</button>
</form>
<form method="post">
    <button type="submit" name="reset">Limpiar albarán</button>
</form>


</body>
</html>
<?php
$_SESSION['ud01_12']['albaran'] = $albaran;
$_SESSION['ud01_12']['version'] = $numero_version;
?>
