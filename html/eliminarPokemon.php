<?php
$conn = new mysqli("localhost", "root", "", "pokedex");
if ($conn->connect_error) {
    die("Conexion Fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numero'])) {
    $numero = intval($_POST['numero']);

    // 1. Buscar la ruta de la imagen
    $sql = "SELECT imagen FROM pokemons WHERE numero = $numero";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagenRuta = $row['imagen'];

        // 2. Eliminar registro de la BD
        $deleteSql = "DELETE FROM pokemons WHERE numero = $numero";
        if ($conn->query($deleteSql) === TRUE) {
            // 3. Eliminar la imagen del servidor si existe
            if (file_exists($imagenRuta)) {
                unlink($imagenRuta);
            }
            // Redirigir al index
            header("Location: Index.php?mensaje=eliminado");
            exit;
        } else {
            echo "Error al eliminar: " . $conn->error;
        }
    } else {
        echo "Pokémon no encontrado.";
    }
}

$conn->close();
?>
