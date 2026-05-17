<?php
// 1. Inicialización de la sala (5x5)
// Si ya existe la sala en el post, la capturamos; si no, la creamos vacía ('L')
if (isset($_POST['escenario'])) {
    $escenario = unserialize($_POST['escenario']);
} else {
    $escenario = array_fill(1, 5, array_fill(1, 5, 'L'));
}

$mensaje_error = "";

// 2. Procesamiento de la Transacción
if (isset($_POST['enviar'])) {
    $fila = (int) $_POST['fila'];
    $puesto = (int) $_POST['puesto'];
    $accion = $_POST['accion'];

    // Validar que las coordenadas existan
    if ($fila >= 1 && $fila <= 5 && $puesto >= 1 && $puesto <= 5) {
        $estado_actual = $escenario[$fila][$puesto];

        // Lógica de validación según las reglas del taller
        if ($estado_actual == 'V') {
            $mensaje_error = "Operación no válida: Un puesto vendido (V) no puede ser modificado.";
        } elseif ($estado_actual == 'L' && $accion == 'liberar') {
            $mensaje_error = "El puesto ya se encuentra libre.";
        } elseif ($estado_actual == 'R' && $accion == 'reservar') {
            $mensaje_error = "El puesto ya está reservado.";
        } else {
            // Aplicar cambios permitidos
            if ($accion == 'reservar')
                $escenario[$fila][$puesto] = 'R';
            if ($accion == 'comprar')
                $escenario[$fila][$puesto] = 'V';
            if ($accion == 'liberar')
                $escenario[$fila][$puesto] = 'L';
        }
    } else {
        $mensaje_error = "Por favor, ingrese una fila y puesto válidos (1 a 5).";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de Teatro</title>
        <style>
            body {
    font-family: sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    }
            table {
    border-collapse: collapse;
    margin-bottom: 20px;
    }
            td {
    border: 1px solid #000;
    width: 40px;
    height: 40px;
    text-align: center;
    font-weight: bold;
    }
            .controles {
    border: 1px solid #ccc;
    padding: 20px;
    background: #f9f9f9;
    }
            .error {
    color: red;
    font-weight: bold;
    }
        </style>
        <script>
            // Validación básica con JavaScript antes de enviar
            function validar(msg) {
                if (msg !== "") {
                    alert(msg);
                }
            }
        </script>
    </head>
    <body onload="validar('<?php echo $mensaje_error; ?>')">

        <h1>Gestión de Sillas - Teatro</h1>

        <!-- 3. Interfaz del Teatro -->
        <table>
            <tr>
                <th></th>
                <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
            </tr>
                <?php foreach ($escenario as $numFila => $puestos): ?>
                <tr>
                    <th><?php echo $numFila; ?></th>
                <?php foreach ($puestos as $estado): ?>
                        <td><?php echo $estado; ?></td>
    <?php endforeach; ?>
                </tr>
<?php endforeach; ?>
        </table>

        <!-- 4. Formulario de Controles -->
        <div class="controles">
            <form method="POST">
                <!-- Campo oculto para mantener el estado de la matriz -->
                <input type="hidden" name="escenario" value='<?php echo serialize($escenario); ?>'>

                <label>Fila:</label>
                <input type="text" name="fila" size="2" required>

                <label>Puesto:</label>
                <input type="text" name="puesto" size="2" required>

                <br><br>
                <input type="radio" name="accion" value="reservar" checked> Reservar
                <input type="radio" name="accion" value="comprar"> Comprar
                <input type="radio" name="accion" value="liberar"> Liberar

                <br><br>
                <button type="submit" name="enviar">Enviar</button>
                <button type="reset">Borrar</button>
            </form>
        </div>

    </body>
</html>
