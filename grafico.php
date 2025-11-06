<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultados de la votación</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h2>Resultados</h2>
  <canvas id="myChart"></canvas>

//Aca estaba el script

  <br>
  <a href="index.php">Volver al formulario</a>
<?php
$conexion = new mysqli("sql109.infinityfree.com", "if0_40074031", "FhlaOT4fbU43YAW", "if0_40074031_dbat");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Definir combos fijos
$combosFijos = [
    "Combo 1 La insuperable",
    "Combo 2 La Finoli",
    "Combo 3 La pesada",
    "Combo 4 Sopa de Tubo",
    "Combo 5 El Patriota",
    "Combo 6 Diablo ácido"
];

// Traer conteo de votos
$resultado = $conexion->query("SELECT combo, COUNT(*) as cantidad FROM votos GROUP BY combo");

// Crear array con 0 por defecto
$conteos = array_fill_keys($combosFijos, 0);

// Rellenar con los valores que realmente existen
while ($fila = $resultado->fetch_assoc()) {
    $conteos[$fila['combo']] = (int)$fila['cantidad'];
}

// Separar claves y valores para Chart.js
$combos = array_keys($conteos);
$cantidades = array_values($conteos);
?>

  <script>
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($combos); ?>,
        datasets: [{
          label: 'Cantidad de votos',
          data: <?php echo json_encode($cantidades); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>

</body>
</html>
