<?php
global $conceptos;
require_once "datos.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
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
    <meta charset="UTF-8">
</head>
<body>
<table border="1">
    <tr>
        <th></th>
        <th>Uds</th>
        <th>Referencia</th>
        <th>Concepto</th>
        <th>Precio ud.</th>
        <th>Subtotal</th>
    </tr>

    <?php
    $num = 0;
    $cantidad = 0;
    $bruto = 0;
    $iva = 0;
    $descuento = 0;
    $precio_articulo = 0;
    foreach ($conceptos as $producto) {
        $num++;
        $cantidad += $producto["unidades"];
        $precio_articulo = $producto['unidades'] * $producto['precio_unidad'];
        $bruto += $precio_articulo;
        echo "<tr>";
        echo "<td>" . $num . "</td>";
        echo "<td>" . $producto['unidades'] . "</td>";
        echo "<td>" . $producto['referencia'] . "</td>";
        echo "<td>" . $producto['concepto'] . "</td>";
        echo "<td>" . number_format($producto['precio_unidad'], 2) . " €" . "</td>";
        echo "<td>" . number_format($precio_articulo, 2) . " €" . "</td>";
        echo "</tr>";
    }
    if($bruto>3000){
        $descuento=$bruto*-.2;
    }elseif ($bruto>=2000){
        $descuento=$bruto*-.1;
    }else{
        $descuento=0;
    }

    $iva=($bruto+$descuento)*.21;
    echo "<tr><td></td><td>" . $cantidad . "</td>";
    echo "<td colspan='3' class='bruto'>Bruto:</td>";
    echo "<td >" . number_format($bruto, 2) . " €" . "</td></tr>";
    echo "<tr><td colspan='5' class='bruto'>Descuento (10%):</td>";
    echo "<td >" . number_format($descuento, 2) . " €" . "</td></tr>";
    echo "<tr><td colspan='5' class='bruto'>IVA:</td>";
    echo "<td >" . number_format($iva, 2) . " €" . "</td></tr>";
    echo "<tr><td colspan='5' class='bruto'>Neto:</td>";
    echo "<td >" . number_format($bruto+$descuento+$iva, 2) . " €" . "</td></tr>";
    ?>
</table>


</body>
</html>