-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 03:35:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `figuras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `idproductoencarrito` int(11) NOT NULL,
  `idusuario` int(20) NOT NULL,
  `idproducto` int(20) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `idcompra` int(20) NOT NULL,
  `usuario` int(20) NOT NULL,
  `producto` int(20) NOT NULL,
  `cantidad` int(20) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`idcompra`, `usuario`, `producto`, `cantidad`, `fecha`) VALUES
(1, 10, 1, 3, '2025-05-11 22:29:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproducto` int(20) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `cantidad_almacen` int(100) NOT NULL,
  `fabricante` varchar(100) NOT NULL,
  `origen` varchar(100) NOT NULL,
  `fotos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idproducto`, `nombre`, `descripcion`, `precio`, `cantidad_almacen`, `fabricante`, `origen`, `fotos`) VALUES
(1, 'Rena Ryugu', 'Personaje principal de Higurashi', 2000, 23, 'Goodsmile', 'Japon', 'rena.jpg'),
(2, 'Vader', 'Lord Sith mano derecha del Emperador Palpatine', 8000, 66, 'Hot-toys', 'USA', 'vader.jpg'),
(3, 'Pyra', 'Personaje de Xenoblade 2', 2500, 33, 'Goodsmile', 'Japon', 'pyra.jpg'),
(4, 'Rem', 'Maid en Rezero', 800, 15, 'Pop up parade', 'Japon', 'rem.jpg'),
(5, 'Goku SS3', 'Goku supersayayin 3', 600, 100, 'Bandai', 'Japon', 'goku.jpg'),
(6, '2B', 'Protegonista de Nier Automata', 3200, 24, 'Square Enix', 'japon', '2b.jpg'),
(7, 'Batman', 'Batman', 10000, 80, 'Sideshow', 'USA', 'batman.jpg'),
(8, 'Makima', 'Cazadora de Demonios de Seguridad Pública de alto rango', 2100, 41, 'Eastream', 'Japon', 'makima.jpg'),
(9, 'Hayasaka', 'Maid de Kaguya', 1800, 99, 'amakuni', 'Japon', 'hayasaka.jpg'),
(10, 'Homura', 'Verdadera protegonista del anime Madoka Magica', 800, 56, 'Pop up parade', 'Japon', 'homura.jpg'),
(11, 'Megumin', 'Ws una de las protagonistas de Kono Subarashii Sekai ni Shukufuku wo!. Es una archimaga del Clan Mágico De Los Demonios Carmesí y la primera persona en unirse al equipo de Kazuma.', 1000, 50, 'Pop up parade ', 'japon', 'megumin.jpg'),
(12, 'Saber', 'Servant en Fate Zero', 6000, 64, 'Goodsmile', 'Japon', 'saber.jpg'),
(13, 'Kurisu', 'Portegonista femenina de Steins Gate', 2400, 22, 'Goodsmile', 'Japon', 'kurisu.jpg'),
(14, 'Bocchi', 'Portegonista de la obra Bocchi the rock', 800, 42, 'Aniplex', 'Japon', 'bocchi.jpg'),
(15, 'Obi Wan', 'Mestro Jedi', 9000, 88, ' SIDESHOW COLLECTIBLES ', 'USA', 'obiwan.jpg'),
(16, 'Shinobu', 'Vampira en Monogatari Series', 1300, 5, 'Garte kit', 'japon', 'shinobu.jpg'),
(17, 'Mandaloriano', 'Protegonista del Mandaloriano', 2500, 9, 'Medicom Toy', 'USA', 'mandaloriano.jpg'),
(18, 'Asuka', 'Best Girl de Evangelion', 1000, 14, 'Bandai', 'Japon', 'asuka.jpg'),
(19, 'Boba Fett', 'Mercenario hijo de Jnago Fett', 1200, 32, 'Medicom Toy', 'USA', 'boba.jpg'),
(20, 'Rei Ayanami', 'Co-protagonista de evangelion', 900, 18, 'Max Factory', 'Japon', 'rei.jpg'),
(21, 'Mai Sakurajima', 'Protagonista de Rascal Bunny Girl Senpai', 2300, 5, 'Nendoroid', 'Japon', 'sakurajima.jpg'),
(22, 'Chocola', 'Maid en el Solei hermana de Vanilla', 1000, 22, 'Pop up parade', 'Japon', 'chocola.jpg'),
(23, 'Vanilla', 'Maid en el Solei hermana de Chocola', 1000, 22, 'Pop up parade', 'Japon', 'vanilla.jpg'),
(24, 'Aqua', 'Personaje de Konosuba', 1000, 33, 'Pop up parade', 'Japon', 'aqua.jpg'),
(25, 'Darkness', 'Paladina en Konosuba', 1500, 56, 'Max Faxtory', 'Japon', 'darkness.jpg'),
(26, 'Pekora', 'Vtuber japonesa mas popular', 1200, 88, 'Pop up parade', 'Japon', 'pekora.jpg'),
(27, 'Amelia Watson', 'Vtuber y detective', 1200, 50, 'Pop up parade', 'Japon', 'amelia.jpg'),
(28, 'Momoka Sakurai', 'Idol de Idolmaster', 6000, 69, 'Ph worm', 'Japon', 'momoka.jpg'),
(29, 'Monika', 'Presidenta del Club de literatura', 900, 33, 'Pop up parade', 'Japon', 'monika.jpg'),
(30, 'Madoka', 'Mahou Shoujo en Madoka Magica', 1000, 66, 'pop up parade', 'Japon', 'madoka.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `correo` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nacimiento` date NOT NULL,
  `tarjetabancaria` varchar(15) NOT NULL,
  `direccionpostal` text NOT NULL,
  `es_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `nacimiento`, `tarjetabancaria`, `direccionpostal`, `es_admin`) VALUES
(9, 'eduardo', 'eduardo@gmail.com', 'jojo', '2015-04-15', '999564234', 'casa 3', 0),
(10, 'Billy', 'billy@gmail.com', 'billy', '2025-04-02', '9912342443', 'casa de billy', 0),
(11, 'Elena', 'elena@gmail.com', 'elena', '2025-04-03', '123456789', 'Casa de Elena', 0),
(12, 'Manolo', 'manolo@gmail.com', 'manolo', '1999-06-04', '9995642234', 'Casa 3 chiluca', 1),
(13, 'Martina', 'martina@gmail.com', 'martina', '2000-01-01', '99123812345', 'casa de martina', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`idproductoencarrito`),
  ADD UNIQUE KEY `uk_carrito_usuario_producto` (`idusuario`,`idproducto`),
  ADD KEY `fk_carrito_producto` (`idproducto`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`idcompra`),
  ADD KEY `fkalumni` (`usuario`),
  ADD KEY `fkproducto` (`producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproducto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `idproductoencarrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `idcompra` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproducto` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_producto` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`idproducto`),
  ADD CONSTRAINT `fk_carrito_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_productos` FOREIGN KEY (`producto`) REFERENCES `productos` (`idproducto`),
  ADD CONSTRAINT `historial_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
