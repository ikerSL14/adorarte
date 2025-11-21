-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2025 a las 04:05:37
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
-- Base de datos: `adorate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `direccion_envio` text DEFAULT NULL,
  `metodo_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_usuario`, `fecha`, `total`, `direccion_envio`, `metodo_pago`) VALUES
(1, 1, '2025-05-30 23:04:00', 100.00, 'Plazuela del Framboyán, Colonia Primero de Mayo, Villahermosa, Tabasco, 86100, México', 'Tarjeta'),
(2, 1, '2025-05-31 00:07:07', 200.00, 'Plazuela del Framboyán, Colonia Primero de Mayo, Villahermosa, Tabasco, 86100, México', 'Tarjeta'),
(3, 1, '2025-05-31 01:50:10', 500.00, 'Escuela Adorate', 'Tarjeta'),
(4, 1, '2025-05-31 15:51:38', 500.00, 'Escuela Adorate', 'Tarjeta'),
(5, 2, '2025-06-01 00:57:47', 500.00, 'Escuela Adorate', 'Tarjeta'),
(6, 2, '2025-06-01 01:15:32', 500.00, 'Escuela Adorate', 'Tarjeta'),
(7, 2, '2025-06-01 01:21:15', 500.00, 'Escuela Adorate', 'Tarjeta'),
(8, 2, '2025-06-01 01:21:34', 500.00, 'Escuela Adorate', 'Tarjeta'),
(9, 1, '2025-06-01 01:46:31', 300.00, 'Plazuela del Framboyán, Colonia Primero de Mayo, Villahermosa, Tabasco, 86100, México', 'Tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(50) NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `dia_hora` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `precio`, `dia_hora`) VALUES
(1, 'Guitarra', 500.00, 'Sábados 9 AM a 1 PM'),
(2, 'Canto', 500.00, 'Sábados 9 AM a 1 PM'),
(3, 'Piano', 500.00, 'Sábados 9 AM a 1 PM'),
(4, 'Batería', 500.00, 'Sábados 9 AM a 1 PM'),
(5, 'Bajo', 500.00, 'Sábados 9 AM a 1 PM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hijos`
--

CREATE TABLE `hijos` (
  `id_hijo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `edad` int(11) NOT NULL,
  `genero` enum('M','F','Otro') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hijos`
--

INSERT INTO `hijos` (`id_hijo`, `id_usuario`, `nombre_completo`, `edad`, `genero`, `foto_perfil`) VALUES
(1, 1, 'Evan Caleb', 12, 'M', NULL),
(3, 1, 'Juán Arreola', 16, 'M', '683b7a9624c3f.jpeg'),
(4, 1, 'Hijo nuevo', 18, 'M', '683b79eaf0d6e.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL,
  `id_hijo` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id_inscripcion`, `id_hijo`, `id_curso`, `fecha_inscripcion`) VALUES
(3, 1, 5, '2025-05-31 07:17:40'),
(4, 3, 1, '2025-05-31 07:50:10'),
(5, 4, 1, '2025-05-31 21:51:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id_registro` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id_registro`, `id_compra`, `titulo`, `precio`, `cantidad`) VALUES
(1, 1, 'PRODUCTO NUEVO', 100.00, 1),
(2, 2, 'FLAUTA', 100.00, 1),
(3, 2, 'TAMBOR', 100.00, 1),
(4, 3, 'Curso de: Guitarra', 500.00, 1),
(5, 4, 'Curso de: Guitarra', 500.00, 1),
(6, 5, 'Curso de: Canto', 500.00, 1),
(7, 6, 'Curso de: Piano', 500.00, 1),
(8, 7, 'Curso de: Guitarra', 500.00, 1),
(9, 8, 'Curso de: Piano', 500.00, 1),
(10, 9, 'AUDÍFONOS', 200.00, 1),
(11, 9, 'TAMBOR', 100.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_com` varchar(100) NOT NULL,
  `correo_elec` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_com`, `correo_elec`, `contrasena`, `fecha_registro`, `foto_perfil`) VALUES
(1, 'Selene', 'Selene@gmail.com', '$2y$10$D04a0K/0cisqo7A50Ej9bu8794EKxn5yJna86Hnx3aLBtsFi.uY3u', '2025-05-28 05:12:24', '683b76dc6ef9c.jpeg'),
(2, 'Alberto', 'alberto@gmail.com', '$2y$10$odrgXOJyMfgVT/1.38iyc.uCAed6.Tu5Oe3j.w4Q8YmeflToSPmdi', '2025-06-01 06:53:15', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `hijos`
--
ALTER TABLE `hijos`
  ADD PRIMARY KEY (`id_hijo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `id_hijo` (`id_hijo`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_elec` (`correo_elec`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `hijos`
--
ALTER TABLE `hijos`
  MODIFY `id_hijo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `hijos`
--
ALTER TABLE `hijos`
  ADD CONSTRAINT `hijos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_hijo`) REFERENCES `hijos` (`id_hijo`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
