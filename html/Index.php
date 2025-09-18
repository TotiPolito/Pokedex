<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pokédex</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<!-- Header -->
<header class="header">
    <div class="logo">
        <a href="Index.php">
            <img src="../img/PokedexLogoBlanco.png" alt="Logo"/>
        </a>
    </div>
    <h1 class="title">Pokédex</h1>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <!-- Formulario de login -->
        <form class="login-form" method="post" action="login.php">
            <label>
                <input type="text" placeholder="Usuario" name="usuario" required>
            </label>
            <label>
                <input type="password" placeholder="Password" name="password" required>
            </label>
            <button type="submit">Ingresar</button>
        </form>
    <?php else: ?>
        <!-- Usuario logueado -->
        <div class="user-info">
            <p>Bienvenido, <b><?php echo $_SESSION['usuario']; ?></b> (<?php echo $_SESSION['rol']; ?>)</p>
            <form method="post" action="logout.php" style="display:inline;">
                <button type="submit" class="btn-eliminar">Cerrar sesión</button>
            </form>
        </div>
    <?php endif; ?>
</header>

<!-- Buscador -->
<section class="search">
    <form method="get" action="">
        <label for="buscarPokemon"></label>
        <input type="text" name="busqueda" placeholder="Buscar Pokémon..." id="buscarPokemon">
        <button type="submit" id="btnBuscar">¿Quién es este Pokémon?</button>
        <a href="index.php" class="btn-limpiar">Limpiar búsqueda</a>
    </form>
</section>

<!-- Lista de Pokémon -->
<main class="pokemon-list">
    <h2>Lista de Pokémon</h2>
    <br>
    <?php
    $conn = new mysqli("localhost", "root", "", "pokedex");
    if ($conn->connect_error) {
        die("Conexion Fallida: " . $conn->connect_error);
    }

    // Si hay búsqueda
    if (isset($_GET['busqueda']) && $_GET['busqueda'] !== '') {
        $busqueda = $conn->real_escape_string($_GET['busqueda']);
        $sql = "SELECT * FROM pokemons
            WHERE numero = '$busqueda'
            OR nombre LIKE '%$busqueda%'
            ORDER BY CAST(numero AS UNSIGNED) ASC";
    } else {
        $sql = "SELECT * FROM pokemons ORDER BY CAST(numero AS UNSIGNED) ASC";
    }

    $result = mysqli_query($conn, $sql);
    $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($datos) > 0) {
        echo "<table class='pokemon-table'>";
        echo "<tr>
        <th>Imagen</th>
        <th>Número</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>SubTipo</th>";

        // Solo mostramos la columna Acciones si es admin
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            echo "<th>Acciones</th>";
        }

        echo "</tr>";

        foreach ($datos as $dato) {
            $tipos = explode("/", $dato["tipo"]);
            $tipo1 = $tipos[0];
            $tipo2 = isset($tipos[1]) ? $tipos[1] : "";

            echo "<tr>";
            echo "<td><img src='" . $dato["imagen"] . "' alt='" . $dato["nombre"] . "' class='pokemon-img'></td>";
            echo "<td>" . $dato["numero"] . "</td>";
            echo "<td><a href='pokemon.php?numero=" . $dato["numero"] . "'>" . $dato["nombre"] . "</a></td>";
            echo "<td>" . mostrarTipo($tipo1) . "</td>";
            echo "<td>" . mostrarTipo($tipo2) . "</td>";

            // Acciones solo si es admin
            if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
                echo "<td>
                        <form action='modificarPokemon.php' method='get' style='display:inline;'>
                            <input type='hidden' name='numero' value='" . $dato["numero"] . "'>
                            <button type='submit' class='btn-modificar'>Modificar</button>
                        </form>
                        <form action='eliminarPokemon.php' method='post' style='display:inline;' onsubmit=\"return confirm('¿Seguro que quieres eliminar a " . $dato["nombre"] . "?');\">
                            <input type='hidden' name='numero' value='" . $dato["numero"] . "'>
                            <button type='submit' class='btn-eliminar'>Eliminar</button>
                        </form>
                      </td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p><b>Pokémon no existente</b></p>";
    }

    mysqli_close($conn);

    function mostrarTipo($tipo) {
        if ($tipo == "") {
            return "";
        }
        $ruta = "../img/Tipos/" . strtolower($tipo) . ".jpg";
        if (file_exists($ruta)) {
            return "<img src='$ruta' alt='$tipo' class='tipo-img'>";
        } else {
            return $tipo;
        }
    }
    ?>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <div class="add-pokemon-container">
            <form action="agregarPokemon.php" method="get">
                <button type="submit" class="btn-agregar">Agregar Pokémon</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<!-- Footer -->
<footer class="footer">
    <p>Pokédex © 2025 - Proyecto académico</p>
</footer>
</body>
</html>
