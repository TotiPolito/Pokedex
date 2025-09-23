<?php
session_start();

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: Index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "pokedex");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$tiposDisponibles = [
        "Normal","Fuego","Agua","Planta","Eléctrico","Hielo",
        "Lucha","Veneno","Tierra","Volador","Psiquico","Bicho",
        "Roca","Fantasma","Dragon","Siniestro","Acero","Hada"
];

if (isset($_GET["numero"])) {
    $numero = intval($_GET["numero"]);
    $sql = "SELECT * FROM pokemons WHERE numero = $numero";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pokemon = $result->fetch_assoc();
        $tiposPokemon = explode("/", $pokemon["tipo"]); // dividir tipo1/tipo2
    } else {
        echo "<p>Pokémon no encontrado.</p>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = intval($_POST["numero"]);
    $nombre = $conn->real_escape_string($_POST["nombre"]);

    $tipo1 = $conn->real_escape_string($_POST["tipo1"]);
    $tipo2 = isset($_POST["tipo2"]) ? $conn->real_escape_string($_POST["tipo2"]) : "";

    $tipoFinal = $tipo2 !== "" ? $tipo1 . "/" . $tipo2 : $tipo1;

    $descripcion = $conn->real_escape_string($_POST["descripcion"]);
    $imagen = $conn->real_escape_string($_POST["imagen"]);

    $sqlUpdate = "UPDATE pokemons 
                  SET nombre='$nombre', tipo='$tipoFinal', descripcion='$descripcion', imagen='$imagen' 
                  WHERE numero=$numero";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<p class='exito'>Pokémon actualizado correctamente.</p>";
        echo "<a href='Index.php'>Volver a la lista</a>";
        exit;
    } else {
        echo "<p class='error'>Error al actualizar: " . $conn->error . "</p>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Pokémon</title>
    <link rel="stylesheet" href="../css/estiloModificarPokemon.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="Index.php"><img src="../img/PokedexLogoBlanco.png" alt="Logo"/></a>
    </div>
    <h1 class="title">Modificar Pokémon</h1>
</header>

<main class="form-container">
    <form method="post" action="modificarPokemon.php?numero=<?php echo $pokemon['numero']; ?>">
        <input type="hidden" name="numero" value="<?php echo $pokemon['numero']; ?>">

        <label>Número:</label>
        <input type="text" value="<?php echo $pokemon['numero']; ?>" disabled>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $pokemon['nombre']; ?>" required>

        <label>Tipo principal:</label>
        <div class="tipo-selector">
            <?php foreach ($tiposDisponibles as $tipo): ?>
                <label class="tipo-opcion">
                    <input type="radio" name="tipo1" value="<?php echo $tipo; ?>"
                            <?php if ($tiposPokemon[0] === $tipo) echo "checked"; ?>>
                    <img src="../img/Tipos/<?php echo strtolower($tipo); ?>.jpg" alt="<?php echo $tipo; ?>">
                </label>
            <?php endforeach; ?>
        </div>

        <label>Subtipo (opcional):</label>
        <div class="tipo-selector">
            <?php foreach ($tiposDisponibles as $tipo): ?>
                <label class="tipo-opcion">
                    <input type="radio" name="tipo2" value="<?php echo $tipo; ?>"
                            <?php if (isset($tiposPokemon[1]) && $tiposPokemon[1] === $tipo) echo "checked"; ?>>
                    <img src="../img/Tipos/<?php echo strtolower($tipo); ?>.jpg" alt="<?php echo $tipo; ?>">
                </label>
            <?php endforeach; ?>
            <label class="tipo-opcion">
                <input type="radio" name="tipo2" value="" <?php if (!isset($tiposPokemon[1]) || $tiposPokemon[1] === "") echo "checked"; ?>>
                <span class="sin-subtipo">Sin subtipo</span>
            </label>
        </div>

        <label>Descripción:</label>
        <textarea name="descripcion" required><?php echo $pokemon['descripcion']; ?></textarea>

        <label>Ruta Imagen:</label>
        <input type="text" name="imagen" value="<?php echo $pokemon['imagen']; ?>">

        <div class="buttons">
            <button type="submit" class="btn-guardar">Guardar cambios</button>
            <a href="Index.php" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</main>

<footer class="footer">
    <p>Pokédex © 2025 - Proyecto académico</p>
</footer>
</body>
</html>
