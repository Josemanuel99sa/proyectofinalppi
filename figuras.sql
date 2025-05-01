-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2025 a las 04:12:55
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
  `idproductoencarrito` int(20) NOT NULL,
  `idusuario` int(20) NOT NULL,
  `idproducto` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `idcompra` int(20) NOT NULL,
  `usuario` int(20) NOT NULL,
  `producto` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproducto` int(20) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` text NOT NULL,
  `fotos` blob NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `cantridad_almacen` int(100) NOT NULL,
  `fabricante` varchar(100) NOT NULL,
  `origen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `direccionpostal` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `nacimiento`, `tarjetabancaria`, `direccionpostal`) VALUES
(9, 'eduardo', 'eduardo@gmail.com', 'jojo', '2015-04-15', '999564234', 'casa 3'),
(10, 'Billy', 'billy@gmail.com', 'billy', '2025-04-02', '9912342443', 'casa de billy'),
(11, 'Elena', 'elena@gmail.com', 'elena', '2025-04-03', '123456789', 'Casa de Elena');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`idproductoencarrito`),
  ADD KEY `fkcarritoidusuario` (`idusuario`),
  ADD KEY `fkcarritoidproducto` (`idproducto`);

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
  MODIFY `idproductoencarrito` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `idcompra` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproducto` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_productoid` FOREIGN KEY (`idproductoencarrito`) REFERENCES `productos` (`idproducto`),
  ADD CONSTRAINT `usarios_idusuariocarrito` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`);

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
