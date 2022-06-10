-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2022 a las 00:02:33
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8*/;

--
-- Base de datos: `tms_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_list`
--

CREATE TABLE `proyecto_list` (
  `id` int(30) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `fecha_de_inicio` date NOT NULL,
  `fin_fecha` date NOT NULL,
  `manager_id` int(30) NOT NULL,
  `usuario_ids` text NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proyecto_list`
--

INSERT INTO `proyecto_list` (`id`, `nombre`, `descripcion`, `status`, `fecha_de_inicio`, `fin_fecha`, `manager_id`, `usuario_ids`, `fecha_de_creacion`) VALUES
(1, 'Sample Project', '								&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elementum, metus vitae malesuada mollis, urna nisi luctus ligula, vitae volutpat massa eros eu ligula. Nunc dui metus, iaculis id dolor non, luctus tristique libero. Aenean et sagittis sem. Nulla facilisi. Mauris at placerat augue. Nullam porttitor felis turpis, ac varius eros placerat et. Nunc ut enim scelerisque, porta lacus vitae, viverra justo. Nam mollis turpis nec dolor feugiat, sed bibendum velit placerat. Etiam in hendrerit leo. Nullam mollis lorem massa, sit amet tincidunt dolor lacinia at.&lt;/span&gt;							', 0, '2020-11-03', '2021-01-20', 2, '3,4,5', '2020-12-03 09:56:56'),
(2, 'Sample Project 102', 'Sample Only', 0, '2020-12-02', '2020-12-31', 2, '3', '2020-12-03 13:51:54'),
(3, 'arnolfo rebollosa', 'fghj', 0, '2021-03-23', '2021-03-17', 2, '4', '2021-03-23 14:34:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes_del_sistema`
--

CREATE TABLE `ajustes_del_sistema` (
  `id` int(30) NOT NULL,
  `nombre` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contacto` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `cubrir_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ajustes_del_sistema`
--

INSERT INTO `ajustes_del_sistema` (`id`, `nombre`, `email`, `contacto`, `direccion`, `cubrir_img`) VALUES
(1, 'Sistema Gestión de Proyectos', 'sistema@gmail.comm', '+545454', 'Formosa, 3600', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_list`
--

CREATE TABLE `tarea_list` (
  `id` int(30) NOT NULL,
  `proyecto_id` int(30) NOT NULL,
  `tarea` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tarea_list`
--

INSERT INTO `tarea_list` (`id`, `proyecto_id`, `tarea`, `descripcion`, `status`, `fecha_de_creacion`) VALUES
(1, 1, 'Sample tarea 1', '								&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Fusce ullamcorper mattis semper. Nunc vel risus ipsum. Sed maximus dapibus nisl non laoreet. Pellentesque quis mauris odio. Donec fermentum facilisis odio, sit amet aliquet purus scelerisque eget.&amp;nbsp;&lt;/span&gt;													', 3, '2020-12-03 11:08:58'),
(2, 1, 'Sample tarea 2', 'Sample tarea 2							', 1, '2020-12-03 13:50:15'),
(3, 2, 'tarea Test', 'Sample', 1, '2020-12-03 13:52:25'),
(4, 2, 'test 23', 'Sample test 23', 1, '2020-12-03 13:52:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(30) NOT NULL,
  `primer_nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `fecha_de_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `primer_nombre`, `apellido`, `email`, `password`, `type`, `avatar`, `fecha_de_creacion`) VALUES
(1, 'Administrador nombre', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, 'no-image-available.png', '2020-11-26 10:57:04'),
(2, 'Angel Jude', 'Suarez', 'jude@yahoo.com', '202cb962ac59075b964b07152d234b70', 2, '1606978560_avatar.jpg', '2020-12-03 09:26:03'),
(3, 'Adrian', 'Mercurio', 'adrian@gmail.com', '202cb962ac59075b964b07152d234b70', 3, '1606958760_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 09:26:42'),
(4, 'Niko', 'Curaza', 'niko@gmail.com', '202cb962ac59075b964b07152d234b70', 3, '1606963560_avatar.jpg', '2020-12-03 10:46:41'),
(5, 'Adones', 'Evangelista', 'adones@gmail.com', '202cb962ac59075b964b07152d234b70', 3, '1606963620_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 10:47:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_type`
--

CREATE TABLE `usuarios_type` (
  `id_type` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios_type`
--

INSERT INTO `usuarios_type` (`id_type`, `nombre_tipo`) VALUES
(1, 'Administrador'),
(2, 'Manager de Proyecto'),
(3, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productividad_usuario`
--

CREATE TABLE `productividad_usuario` (
  `id` int(30) NOT NULL,
  `proyecto_id` int(30) NOT NULL,
  `tarea_id` int(30) NOT NULL,
  `comentario` text NOT NULL,
  `tema` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `hora_de_inicio` time NOT NULL,
  `fin_tiempo` time NOT NULL,
  `usuario_id` int(30) NOT NULL,
  `tiempo_prestado` float NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productividad_usuario`
--

INSERT INTO `productividad_usuario` (`id`, `proyecto_id`, `tarea_id`, `comentario`, `tema`, `fecha`, `hora_de_inicio`, `fin_tiempo`, `usuario_id`, `tiempo_prestado`, `fecha_de_creacion`) VALUES
(1, 1, 1, '							&lt;p&gt;Sample Progress&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Test 1&lt;/li&gt;&lt;li&gt;Test 2&lt;/li&gt;&lt;li&gt;Test 3&lt;/li&gt;&lt;/ul&gt;																			', 'Sample Progress', '2020-12-03', '08:00:00', '10:00:00', 1, 2, '2020-12-03 12:13:28'),
(2, 1, 1, '							Sample Progress						', 'Sample Progress 2', '2020-12-03', '13:00:00', '14:00:00', 1, 1, '2020-12-03 13:48:28'),
(3, 1, 2, '							Sample						', 'Test', '2020-12-03', '08:00:00', '09:00:00', 5, 1, '2020-12-03 13:57:22'),
(4, 1, 2, 'asdasdasd', 'Sample Progress', '2020-12-02', '08:00:00', '10:00:00', 2, 2, '2020-12-03 14:36:30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `proyecto_list`
--
ALTER TABLE `proyecto_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ajustes_del_sistema`
--
ALTER TABLE `ajustes_del_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarea_list`
--
ALTER TABLE `tarea_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_type`
--
ALTER TABLE `usuarios_type`
  ADD PRIMARY KEY (`id_type`);

--
-- Indices de la tabla `productividad_usuario`
--
ALTER TABLE `productividad_usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `proyecto_list`
--
ALTER TABLE `proyecto_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ajustes_del_sistema`
--
ALTER TABLE `ajustes_del_sistema`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tarea_list`
--
ALTER TABLE `tarea_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productividad_usuario`
--
ALTER TABLE `productividad_usuario`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
