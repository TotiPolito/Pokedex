<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: Index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "pokedex");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numero = $_POST["numero"];
    $nombre = $_POST["nombre"];
    $tipo = $_POST["tipo"];
    $descripcion = $_POST["descripcion"];

    // Guardar imagen con el número como nombre
    $nombreImagen = $numero . ".jpg";
    $rutaDestino = "../img/" . $nombreImagen;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
        $sql = "INSERT INTO pokemons (numero, nombre, tipo, descripcion, imagen) 
                VALUES ('$numero', '$nombre', '$tipo', '$descripcion', '$rutaDestino')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Pokémon agregado correctamente.</p>";
            echo "<a href='index.php'>Volver a la Pokédex</a>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Error al subir la imagen.</p>";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Pokémon</title>
    <link rel="stylesheet" href="../css/estiloAgregarPokemon.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="index.php">
            <img src="../img/PokedexLogoBlanco.png" alt="Logo"/>
        </a>
    </div>
    <h1 class="title">Pokédex</h1>
</header>

<main class="form-agregar">
    <h2>Agregar Pokémon</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Número:</label>
        <input type="number" name="numero" required>

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <!-- Tipos -->
        <label>Tipo/s:</label>
        <div class="type-selector">
            <?php
            $tipos = ["Acero","Agua","Bicho","Dragon","Eléctrico","Fantasma","Fuego","Hada",
                "Hielo","Lucha","Normal","Planta","Psiquico","Roca","Siniestro",
                "Tierra","Veneno","Volador"];
            foreach ($tipos as $tipo) {
                echo "
        <button type='button' class='type-btn' onclick='seleccionarTipo(\"$tipo\", this)'>
            <img src='../img/Tipos/$tipo.jpg' alt='$tipo'>
        </button>";
            }
            ?>
        </div>
        <!-- Campo oculto con los tipos seleccionados -->
        <input type="hidden" id="tipo" name="tipo" required>
        <label>Descripción:</label>
        <textarea name="descripcion" rows="4" required></textarea>

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" class="btn-guardar">Guardar</button>
    </form>
</main>

<footer class="footer">
    <p>Pokédex © 2025 - Proyecto académico</p>
</footer>

<script>
    let tiposSeleccionados = [];

    function seleccionarTipo(tipo, boton) {
        const index = tiposSeleccionados.indexOf(tipo);

        if (index > -1) {
            // Si ya estaba seleccionado, lo quitamos
            tiposSeleccionados.splice(index, 1);
            boton.classList.remove("active");
        } else {
            if (tiposSeleccionados.length < 2) {
                // Agregar hasta 2
                tiposSeleccionados.push(tipo);
                boton.classList.add("active");
            } else {
                alert("Solo puedes seleccionar hasta 2 tipos.");
            }
        }

        // Guardar en el input hidden (ej: Planta/Veneno o Agua)
        document.getElementById("tipo").value = tiposSeleccionados.join("/");
    }
</script>

</body>
</html>
