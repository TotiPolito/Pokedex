Codigo SQL para crear la base de datos:

CREATE DATABASE IF NOT EXISTS pokedex;
    USE pokedex;

    CREATE TABLE pokemons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE,        
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,      
    descripcion TEXT,
    imagen VARCHAR(255)             
);

Codigo para llenar la base de datos con datos al Comienzo del proyecto:

INSERT INTO pokemons 
(numero, nombre, tipo, descripcion, imagen) VALUES
(1, 'Bulbasaur', 'Planta/Veneno', 'Un extraño Pokémon con una semilla en su espalda que crece al paso del tiempo.', '../img/001.png'),
(2, 'Ivysaur', 'Planta/Veneno', 'Cuando el bulbo de su espalda crece, parece que le cuesta más mantenerse en pie.', '../img/002.png'),
(3, 'Venusaur', 'Planta/Veneno', 'La planta florece cuando absorbe energía solar. Se mueve para buscar la luz del sol.', '../img/003.png'),
(4, 'Charmander', 'Fuego', 'Prefiere las cosas calientes. Dicen que cuando llueve le sale vapor de la punta de la cola.', '../img/004.png'),
(5, 'Charmeleon', 'Fuego', 'Es muy agresivo y siempre busca enemigos. Escupe llamas que incineran todo lo que tocan.', '../img/005.png'),
(6, 'Charizard', 'Fuego/Volador', 'Escupe un fuego tan caliente que funde las rocas. Puede provocar incendios forestales.', '../img/006.png'),
(7, 'Squirtle', 'Agua', 'Cuando retrae su largo cuello en el caparazón, dispara agua a una presión increíble.', '../img/007.png'),
(8, 'Wartortle', 'Agua', 'Su cola está cubierta de un pelo abundante y espeso. Se le considera un símbolo de longevidad.', '../img/008.png'),
(9, 'Blastoise', 'Agua', 'Sus cañones de agua tienen una potencia suficiente como para perforar un muro de hormigón.', '../img/009.png');
(25, 'Pikachu', 'Eléctrico', 'Cuando se enfada, descarga la energía almacenada en las bolsas de sus mejillas.', '../img/025.png');

