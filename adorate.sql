-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2025 a las 08:14:20
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
(9, 1, '2025-06-01 01:46:31', 300.00, 'Plazuela del Framboyán, Colonia Primero de Mayo, Villahermosa, Tabasco, 86100, México', 'Tarjeta'),
(10, 3, '2025-06-02 00:32:03', 500.00, 'Escuela Adorate', 'Tarjeta'),
(11, 3, '2025-06-02 00:32:13', 500.00, 'Escuela Adorate', 'Tarjeta'),
(12, 3, '2025-06-02 00:34:33', 500.00, 'Escuela Adorate', 'Tarjeta'),
(13, 3, '2025-06-02 00:36:49', 500.00, 'Escuela Adorate', 'Tarjeta'),
(14, 1, '2025-06-12 20:45:34', 500.00, 'Escuela Adorate', 'Tarjeta'),
(15, 1, '2025-06-12 20:48:07', 500.00, 'Escuela Adorate', 'Tarjeta'),
(16, 1, '2025-06-12 20:58:46', 500.00, 'Escuela Adorate', 'Tarjeta'),
(17, 1, '2025-10-27 23:26:31', 500.00, 'Escuela Adorate', 'Tarjeta'),
(18, 1, '2025-10-27 23:26:37', 500.00, 'Escuela Adorate', 'Tarjeta'),
(19, 1, '2025-10-27 23:26:42', 500.00, 'Escuela Adorate', 'Tarjeta'),
(20, 1, '2025-10-31 20:16:43', 200.00, 'Escuela Adorate', 'Tarjeta'),
(21, 3, '2025-11-17 21:20:48', 500.00, 'Escuela Adorate', 'Tarjeta'),
(22, 3, '2025-11-17 22:41:29', 200.00, 'Escuela Adorate', 'Tarjeta'),
(23, 3, '2025-11-17 22:41:32', 200.00, 'Escuela Adorate', 'Tarjeta'),
(24, 3, '2025-11-17 23:50:25', 500.00, 'Escuela Adorarte', 'Tarjeta'),
(25, 3, '2025-11-18 00:01:06', 100.00, 'Escuela Adorarte', 'Tarjeta'),
(26, 3, '2025-11-21 16:11:24', 400.00, 'Escuela Adorarte', 'Tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(50) NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `dia_hora` varchar(50) NOT NULL,
  `grupo` char(1) NOT NULL DEFAULT 'A',
  `foto` varchar(255) DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL,
  `estado` enum('activo','terminado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `precio`, `dia_hora`, `grupo`, `foto`, `id_profesor`, `estado`) VALUES
(1, 'Guitarra', 500.00, 'Sábados 9 AM a 1 PM', 'A', NULL, NULL, 'activo'),
(2, 'Canto', 500.00, 'Sábados 9 AM a 1 PM', 'A', NULL, 4, 'activo'),
(3, 'Piano', 500.00, 'Sábados 9 AM a 1 PM', 'A', NULL, 2, 'activo'),
(4, 'Batería', 500.00, 'Sábados 9 AM a 1 PM', 'A', NULL, 1, 'activo'),
(5, 'Bajo', 500.00, 'Sábados 9 AM a 1 PM', 'A', NULL, NULL, 'activo'),
(6, 'Flauta', 200.00, 'Sábado 5 AM', 'A', NULL, NULL, 'activo'),
(7, 'Rítmica', 100.00, 'Viernes 5-6 PM', 'A', 'curso_1762063251.jpeg', NULL, 'activo'),
(9, 'Guitarra', 500.00, 'Martes 4PM', 'B', 'WhatsApp Image 2025-06-01 at 10.22.00 PM.jpeg', NULL, 'activo'),
(10, 'Guitarra eléctrica', 400.00, 'Lunes 3:00 PM', 'B', 'jerry-kavan-9XuEyCxp8pk-unsplash.jpg', 5, 'activo'),
(14, 'Piano', 400.00, 'Martes 5', 'G', 'YE.jpg', NULL, 'terminado'),
(15, 'Ballet', 100.00, 'Viernes 5:00 PM', 'A', 'robert-stump-Vn29R6UZCjI-unsplash.jpg', NULL, 'activo');

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
  `foto_perfil` varchar(255) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `estado` enum('inscrito','no_inscrito') DEFAULT 'inscrito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hijos`
--

INSERT INTO `hijos` (`id_hijo`, `id_usuario`, `nombre_completo`, `edad`, `genero`, `foto_perfil`, `matricula`, `estado`) VALUES
(1, 1, 'Evan Caleb', 12, 'M', NULL, NULL, 'inscrito'),
(15, 1, 'Lorenzo', 12, 'M', NULL, NULL, 'inscrito'),
(19, 1, 'Albertos', 17, 'M', NULL, NULL, 'inscrito'),
(20, 3, 'Iker jr', 17, 'M', NULL, '2514G20', 'no_inscrito'),
(21, 3, 'Fidencio', 18, 'M', NULL, '257A21', 'inscrito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `id_historial` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_hijo` int(11) NOT NULL,
  `calificacion` varchar(10) DEFAULT NULL,
  `fecha_terminacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`id_historial`, `id_curso`, `id_hijo`, `calificacion`, `fecha_terminacion`) VALUES
(1, 14, 20, '10', '2025-11-21 19:57:24');

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
(16, 15, 1, '2025-06-13 02:58:46'),
(20, 19, 6, '2025-11-01 02:16:43'),
(21, 1, 6, '2025-11-01 02:32:57'),
(34, 21, 7, '2025-11-18 06:01:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `nombre`, `email`, `contrasena`, `foto`, `fecha_registro`) VALUES
(1, 'Juan Manuel Quiroga', 'juanq@gmail.com', '', 'foto_1763703785.png', '2025-11-21 02:05:33'),
(2, 'Ignacio', 'ign@gmail.com', '', '', '2025-11-21 02:29:37'),
(4, 'jesus', 'jes@gmail.com', '', '', '2025-11-21 02:36:53'),
(5, 'Jesús Rodríguez López', 'alivesubtitulos@gmail.com', '$2y$10$IH3Xb91AILGnbFanAWrBm.XlwZsgs6jvui0Qh7e8QMCTCLAfKbWXy', '1763786370_pstpfp.png', '2025-11-21 06:12:06'),
(9, 'Profenuevo', 'profe@gmail.com', '$2y$10$E9yF8RI2QYwA60Rym9HsuOhftXk3H1uUlSk94j0At7RCBvmnrQH7u', NULL, '2025-11-22 06:13:06');

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
(10, 9, 'AUDÍFONOS', 200.00, 1),
(11, 9, 'TAMBOR', 100.00, 1),
(12, 10, 'Curso de: Guitarra', 500.00, 1),
(13, 11, 'Curso de: Canto', 500.00, 1),
(14, 12, 'Curso de: Guitarra', 500.00, 1),
(15, 13, 'Curso de: Canto', 500.00, 1),
(16, 14, 'Curso de: Guitarra', 500.00, 1),
(17, 15, 'Curso de: Guitarra', 500.00, 1),
(18, 16, 'Curso de: Guitarra', 500.00, 1),
(19, 17, 'Curso de: Canto', 500.00, 1),
(20, 18, 'Curso de: Canto', 500.00, 1),
(21, 19, 'Curso de: Canto', 500.00, 1),
(22, 20, 'Curso de: Flauta', 200.00, 1),
(23, 21, 'Curso de: Bajo', 500.00, 1),
(24, 22, 'Curso de: Flauta', 200.00, 1),
(25, 23, 'Curso de: Flauta', 200.00, 1),
(26, 24, 'Reinscripción al curso: Batería', 500.00, 1),
(27, 25, 'Reinscripción al curso: Rítmica', 100.00, 1),
(28, 26, 'Reinscripción al curso: Piano', 400.00, 1);

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
  `foto_perfil` varchar(255) DEFAULT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_com`, `correo_elec`, `contrasena`, `fecha_registro`, `foto_perfil`, `rol`) VALUES
(1, 'Selene', 'Selene@gmail.com', '$2y$10$D04a0K/0cisqo7A50Ej9bu8794EKxn5yJna86Hnx3aLBtsFi.uY3u', '2025-05-28 05:12:24', '69052a73c86f7.jpeg', 'usuario'),
(3, 'Iker Ignacio Salazar Liévano', 'ikersalazarliev@gmail.com', '$2y$10$rtp6djk6.90YpJSjMww4dO1NtZqyHrwV3BQexsXI9R4cknkgCWo9m', '2025-06-02 06:31:13', '', 'usuario'),
(4, 'Administrador General', 'admin@adorarte.com', '$2y$10$2mPzDCvbk9HcL72QyX9eiePgG3lo4Ad5ltxtB0h6a9Y.3JOlKdFgu', '2025-11-01 02:50:27', NULL, 'admin');

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
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `fk_profesor` (`id_profesor`);

--
-- Indices de la tabla `hijos`
--
ALTER TABLE `hijos`
  ADD PRIMARY KEY (`id_hijo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`id_historial`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `id_hijo` (`id_hijo`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `hijos`
--
ALTER TABLE `hijos`
  MODIFY `id_hijo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`) ON DELETE SET NULL;

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
