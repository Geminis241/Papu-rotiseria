<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Votación Rotisería</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Registro y votación</h2>

            <form method="POST">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
              </div>

              <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" required>
              </div>

              <div class="mb-3">
                <p class="mb-2">Elegí tu combo:</p>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo1" value="Combo 1 La insuperable" required>
                  <label class="form-check-label" for="combo1">Combo 1 La insuperable</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo2" value="Combo 2 La Finoli">
                  <label class="form-check-label" for="combo2">Combo 2 La Finoli</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo3" value="Combo 3 La pesada">
                  <label class="form-check-label" for="combo3">Combo 3 La pesada</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo4" value="Combo 4 Sopa de Tubo">
                  <label class="form-check-label" for="combo4">Combo 4 Sopa de Tubo</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo5" value="Combo 5 El Patriota">
                  <label class="form-check-label" for="combo5">Combo 5 El Patriota</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="combo" id="combo6" value="Combo 6 Diablo ácido">
                  <label class="form-check-label" for="combo6">Combo 6 Diablo ácido</label>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Votar</button>
              </div>
            </form>
<?php
$conexion = new mysqli("sql109.infinityfree.com", "if0_40074031", "FhlaOT4fbU43YAW", "if0_40074031_dbat");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $dni = trim($_POST['dni']);
    $combo = $_POST['combo'];

    // Validar DNI (solo números, 6 a 12 dígitos por ejemplo)
    if (!preg_match('/^[0-9]{6,12}$/', $dni)) {
        echo "<p>DNI inválido. Solo números (6-12 dígitos).</p>";
        exit;
    }

    // Verificar si ya existe el usuario con ese DNI
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE dni=?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();
        $usuario_id = $usuario['id'];

        // Verificar si ya votó
        $stmt2 = $conexion->prepare("SELECT id FROM votos WHERE usuario_id=?");
        $stmt2->bind_param("i", $usuario_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($res2->num_rows > 0) {
            echo "<p>Ya votaste, no puedes volver a hacerlo. <a href='grafico.php'>Ver resultados</a></p>";
            exit;
        }
    } else {
        // Registrar nuevo usuario
        $stmt3 = $conexion->prepare("INSERT INTO usuarios (nombre, dni) VALUES (?, ?)");
        $stmt3->bind_param("ss", $nombre, $dni);
        if (!$stmt3->execute()) {
            echo "<p>Error al registrar usuario.</p>";
            exit;
        }
        $usuario_id = $conexion->insert_id;
    }

    // Guardar voto
    $stmt4 = $conexion->prepare("INSERT INTO votos (usuario_id, combo) VALUES (?, ?)");
    $stmt4->bind_param("is", $usuario_id, $combo);
    if ($stmt4->execute()) {
        echo "<p>Voto registrado correctamente. <a href='grafico.php'>Ver resultados</a></p>";
    } else {
        echo "<p>Error al registrar voto.</p>";
    }
    exit;
}
?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
