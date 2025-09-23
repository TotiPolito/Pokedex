<?php
$conn = new mysqli("localhost", "root", "", "pokedex");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_GET["numero"])) {
    $numero = intval($_GET["numero"]); // seguridad

    $sql = "SELECT * FROM pokemons WHERE numero = $numero";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pokemon = $result->fetch_assoc();
        $tipos = explode("/", $pokemon["tipo"]);
        $tipo1 = $tipos[0];
        $tipo2 = isset($tipos[1]) ? $tipos[1] : "";
    } else {
        echo "<p>Pokémon no encontrado.</p>";
        exit;
    }
} else {
    echo "<p>No se especificó ningún Pokémon.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pokemon["nombre"]; ?> - Pokédex</title>
    <link rel="stylesheet" href="../css/EstiloPokemon.css">
</head>
<body>

<header class="header">
    <div class="logo">
        <a href="Index.php">
            <img src="../img/PokedexLogoBlanco.png" alt="Logo" />
        </a>
    </div>
    <h1 class="title">Pokédex</h1>
</header>

<main class="pokemon-detail">
    <div class="pokemon-img-container">
        <img id="ImagenPokemon" src="<?php echo $pokemon["imagen"]; ?>" alt="<?php echo $pokemon["nombre"]; ?>" class="pokemon-img">
    </div>

    <div class="pokemon-info">
        <h2><?php echo $pokemon["numero"] . " - " . $pokemon["nombre"]; ?></h2>
        <p>
            <strong>Tipo:</strong>
            <img src="../img/Tipos/<?php echo strtolower($tipo1); ?>.jpg" alt="<?php echo $tipo1; ?>" class="tipo-img">
            <?php if ($tipo2 != ""): ?>
                <img src="../img/Tipos/<?php echo strtolower($tipo2); ?>.jpg" alt="<?php echo $tipo2; ?>" class="tipo-img">
            <?php endif; ?>
        </p>
        <p><?php echo $pokemon["descripcion"]; ?></p>

        <!-- Navegación entre pokemons -->
        <div class="nav-buttons">
            <?php
            $conn = new mysqli("localhost", "root", "", "pokedex");

            $sqlPrev = "SELECT numero FROM pokemons WHERE numero < $numero ORDER BY numero DESC LIMIT 1";
            $resPrev = $conn->query($sqlPrev);
            if ($resPrev && $resPrev->num_rows > 0) {
                $prev = $resPrev->fetch_assoc()["numero"];
                echo "<a href='Pokemon.php?numero=$prev' class='btn'>⬅ Anterior</a>";
            }

            $sqlNext = "SELECT numero FROM pokemons WHERE numero > $numero ORDER BY numero ASC LIMIT 1";
            $resNext = $conn->query($sqlNext);
            if ($resNext && $resNext->num_rows > 0) {
                $next = $resNext->fetch_assoc()["numero"];
                echo "<a href='Pokemon.php?numero=$next' class='btn'>Siguiente ➡</a>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</main>

<footer class="footer">
    <p>Pokédex © 2025 - Proyecto académico</p>
</footer>

</body>
</html>
