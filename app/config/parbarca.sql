-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-04-2026 a las 23:10:05
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parbarca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `empresa_nombre` varchar(200) NOT NULL DEFAULT 'PARBARCA',
  `empresa_rif` varchar(20) DEFAULT NULL,
  `empresa_direccion` varchar(255) DEFAULT NULL,
  `empresa_telefono` varchar(20) DEFAULT NULL,
  `empresa_email` varchar(100) DEFAULT NULL,
  `factura_prefijo` varchar(10) NOT NULL DEFAULT 'FAC-',
  `factura_numero_inicial` int(11) NOT NULL DEFAULT '1',
  `factura_ultimo_numero` int(11) NOT NULL DEFAULT '0',
  `logo` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagada','anulada') NOT NULL DEFAULT 'pendiente',
  `descripcion` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_requerimientos`
--

CREATE TABLE `factura_requerimientos` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `requerimiento_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_requerimientos`
--

CREATE TABLE `historial_requerimientos` (
  `id` int(11) NOT NULL,
  `requerimiento_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','en_proceso','finalizado','rechazado') DEFAULT NULL,
  `estado_nuevo` enum('pendiente','en_proceso','finalizado','rechazado') NOT NULL,
  `comentario` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requerimientos`
--

CREATE TABLE `requerimientos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `estado` enum('pendiente','en_proceso','finalizado','rechazado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','empleado','cliente') NOT NULL DEFAULT 'cliente',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_factura_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_factura_detalle` (
`id` int(11)
,`numero_factura` varchar(50)
,`fecha_emision` date
,`monto_total` decimal(10,2)
,`factura_estado` enum('pendiente','pagada','anulada')
,`factura_descripcion` varchar(500)
,`cliente_id` int(11)
,`cliente_nombre` varchar(100)
,`cliente_apellido` varchar(100)
,`cliente_email` varchar(100)
,`cliente_telefono` varchar(20)
,`cliente_cedula` varchar(20)
,`cliente_direccion` varchar(255)
,`empleado_nombre` varchar(100)
,`empleado_apellido` varchar(100)
,`empresa_nombre` varchar(200)
,`empresa_rif` varchar(20)
,`empresa_direccion` varchar(255)
,`empresa_telefono` varchar(20)
,`empresa_email` varchar(100)
,`logo` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reporte_facturacion_periodo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reporte_facturacion_periodo` (
`fecha` date
,`total_facturas` bigint(21)
,`pagadas` decimal(23,0)
,`pendientes` decimal(23,0)
,`anuladas` decimal(23,0)
,`total_facturado` decimal(32,2)
,`total_pendiente` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reporte_facturas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reporte_facturas` (
`id` int(11)
,`numero_factura` varchar(50)
,`fecha_emision` date
,`monto_total` decimal(10,2)
,`estado` enum('pendiente','pagada','anulada')
,`cliente_id` int(11)
,`cliente_nombre` varchar(100)
,`cliente_apellido` varchar(100)
,`cliente_email` varchar(100)
,`empleado_id` int(11)
,`empleado_nombre` varchar(100)
,`empleado_apellido` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reporte_requerimientos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reporte_requerimientos` (
`id` int(11)
,`titulo` varchar(200)
,`descripcion` varchar(500)
,`estado` enum('pendiente','en_proceso','finalizado','rechazado')
,`fecha_creacion` timestamp
,`cliente_id` int(11)
,`cliente_nombre` varchar(100)
,`cliente_apellido` varchar(100)
,`cliente_email` varchar(100)
,`cliente_telefono` varchar(20)
,`cliente_cedula` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_resumen_dashboard`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_resumen_dashboard` (
`total_clientes_activos` bigint(21)
,`total_clientes_total` bigint(21)
,`total_empleados_activos` bigint(21)
,`total_empleados_total` bigint(21)
,`total_admin_activos` bigint(21)
,`total_requerimientos` bigint(21)
,`requerimientos_pendientes` bigint(21)
,`requerimientos_en_proceso` bigint(21)
,`requerimientos_finalizados` bigint(21)
,`requerimientos_rechazados` bigint(21)
,`facturacion_mes_actual` decimal(32,2)
,`facturacion_mes_anterior` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_factura_detalle`
--
DROP TABLE IF EXISTS `vista_factura_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_factura_detalle`  AS SELECT `f`.`id` AS `id`, `f`.`numero_factura` AS `numero_factura`, `f`.`fecha_emision` AS `fecha_emision`, `f`.`monto_total` AS `monto_total`, `f`.`estado` AS `factura_estado`, `f`.`descripcion` AS `factura_descripcion`, `c`.`id` AS `cliente_id`, `c`.`nombre` AS `cliente_nombre`, `c`.`apellido` AS `cliente_apellido`, `c`.`email` AS `cliente_email`, `c`.`telefono` AS `cliente_telefono`, `c`.`cedula` AS `cliente_cedula`, `c`.`direccion` AS `cliente_direccion`, `e`.`nombre` AS `empleado_nombre`, `e`.`apellido` AS `empleado_apellido`, `conf`.`empresa_nombre` AS `empresa_nombre`, `conf`.`empresa_rif` AS `empresa_rif`, `conf`.`empresa_direccion` AS `empresa_direccion`, `conf`.`empresa_telefono` AS `empresa_telefono`, `conf`.`empresa_email` AS `empresa_email`, `conf`.`logo` AS `logo` FROM (((`facturas` `f` join `usuarios` `c` on((`f`.`cliente_id` = `c`.`id`))) join `usuarios` `e` on((`f`.`empleado_id` = `e`.`id`))) join `configuracion` `conf`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reporte_facturacion_periodo`
--
DROP TABLE IF EXISTS `vista_reporte_facturacion_periodo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reporte_facturacion_periodo`  AS SELECT cast(`f`.`fecha_emision` as date) AS `fecha`, count(`f`.`id`) AS `total_facturas`, sum((case when (`f`.`estado` = 'pagada') then 1 else 0 end)) AS `pagadas`, sum((case when (`f`.`estado` = 'pendiente') then 1 else 0 end)) AS `pendientes`, sum((case when (`f`.`estado` = 'anulada') then 1 else 0 end)) AS `anuladas`, sum((case when (`f`.`estado` = 'pagada') then `f`.`monto_total` else 0 end)) AS `total_facturado`, sum((case when (`f`.`estado` = 'pendiente') then `f`.`monto_total` else 0 end)) AS `total_pendiente` FROM `facturas` AS `f` GROUP BY cast(`f`.`fecha_emision` as date) ORDER BY cast(`f`.`fecha_emision` as date) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reporte_facturas`
--
DROP TABLE IF EXISTS `vista_reporte_facturas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reporte_facturas`  AS SELECT `f`.`id` AS `id`, `f`.`numero_factura` AS `numero_factura`, `f`.`fecha_emision` AS `fecha_emision`, `f`.`monto_total` AS `monto_total`, `f`.`estado` AS `estado`, `c`.`id` AS `cliente_id`, `c`.`nombre` AS `cliente_nombre`, `c`.`apellido` AS `cliente_apellido`, `c`.`email` AS `cliente_email`, `e`.`id` AS `empleado_id`, `e`.`nombre` AS `empleado_nombre`, `e`.`apellido` AS `empleado_apellido` FROM ((`facturas` `f` join `usuarios` `c` on((`f`.`cliente_id` = `c`.`id`))) join `usuarios` `e` on((`f`.`empleado_id` = `e`.`id`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reporte_requerimientos`
--
DROP TABLE IF EXISTS `vista_reporte_requerimientos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reporte_requerimientos`  AS SELECT `r`.`id` AS `id`, `r`.`titulo` AS `titulo`, `r`.`descripcion` AS `descripcion`, `r`.`estado` AS `estado`, `r`.`created_at` AS `fecha_creacion`, `c`.`id` AS `cliente_id`, `c`.`nombre` AS `cliente_nombre`, `c`.`apellido` AS `cliente_apellido`, `c`.`email` AS `cliente_email`, `c`.`telefono` AS `cliente_telefono`, `c`.`cedula` AS `cliente_cedula` FROM (`requerimientos` `r` join `usuarios` `c` on((`r`.`cliente_id` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resumen_dashboard`
--
DROP TABLE IF EXISTS `vista_resumen_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resumen_dashboard`  AS SELECT (select count(0) from `usuarios` where ((`usuarios`.`rol` = 'cliente') and (`usuarios`.`estado` = 'activo'))) AS `total_clientes_activos`, (select count(0) from `usuarios` where (`usuarios`.`rol` = 'cliente')) AS `total_clientes_total`, (select count(0) from `usuarios` where ((`usuarios`.`rol` = 'empleado') and (`usuarios`.`estado` = 'activo'))) AS `total_empleados_activos`, (select count(0) from `usuarios` where (`usuarios`.`rol` = 'empleado')) AS `total_empleados_total`, (select count(0) from `usuarios` where ((`usuarios`.`rol` = 'admin') and (`usuarios`.`estado` = 'activo'))) AS `total_admin_activos`, (select count(0) from `requerimientos`) AS `total_requerimientos`, (select count(0) from `requerimientos` where (`requerimientos`.`estado` = 'pendiente')) AS `requerimientos_pendientes`, (select count(0) from `requerimientos` where (`requerimientos`.`estado` = 'en_proceso')) AS `requerimientos_en_proceso`, (select count(0) from `requerimientos` where (`requerimientos`.`estado` = 'finalizado')) AS `requerimientos_finalizados`, (select count(0) from `requerimientos` where (`requerimientos`.`estado` = 'rechazado')) AS `requerimientos_rechazados`, (select coalesce(sum(`facturas`.`monto_total`),0) from `facturas` where ((`facturas`.`estado` = 'pagada') and (month(`facturas`.`fecha_emision`) = month(curdate())) and (year(`facturas`.`fecha_emision`) = year(curdate())))) AS `facturacion_mes_actual`, (select coalesce(sum(`facturas`.`monto_total`),0) from `facturas` where ((`facturas`.`estado` = 'pagada') and (month(`facturas`.`fecha_emision`) = month((curdate() - interval 1 month))) and (year(`facturas`.`fecha_emision`) = year((curdate() - interval 1 month))))) AS `facturacion_mes_anterior` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `idx_facturas_numero` (`numero_factura`),
  ADD KEY `idx_facturas_cliente_id` (`cliente_id`),
  ADD KEY `idx_facturas_empleado_id` (`empleado_id`),
  ADD KEY `idx_facturas_estado` (`estado`),
  ADD KEY `idx_facturas_fecha_emision` (`fecha_emision`),
  ADD KEY `idx_facturas_fecha_estado` (`fecha_emision`,`estado`);

--
-- Indices de la tabla `factura_requerimientos`
--
ALTER TABLE `factura_requerimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_factura_req_factura_id` (`factura_id`),
  ADD KEY `idx_factura_req_requerimiento_id` (`requerimiento_id`);

--
-- Indices de la tabla `historial_requerimientos`
--
ALTER TABLE `historial_requerimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_historial_requerimiento_id` (`requerimiento_id`),
  ADD KEY `idx_historial_usuario_id` (`usuario_id`);

--
-- Indices de la tabla `requerimientos`
--
ALTER TABLE `requerimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_requerimientos_cliente_id` (`cliente_id`),
  ADD KEY `idx_requerimientos_estado` (`estado`),
  ADD KEY `idx_requerimientos_created_at` (`created_at`),
  ADD KEY `idx_requerimientos_cliente_estado` (`cliente_id`,`estado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `idx_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_cedula` (`cedula`),
  ADD KEY `idx_usuarios_rol` (`rol`),
  ADD KEY `idx_usuarios_estado` (`estado`),
  ADD KEY `idx_usuarios_rol_estado` (`rol`,`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_requerimientos`
--
ALTER TABLE `factura_requerimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_requerimientos`
--
ALTER TABLE `historial_requerimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `requerimientos`
--
ALTER TABLE `requerimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `factura_requerimientos`
--
ALTER TABLE `factura_requerimientos`
  ADD CONSTRAINT `factura_requerimientos_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `factura_requerimientos_ibfk_2` FOREIGN KEY (`requerimiento_id`) REFERENCES `requerimientos` (`id`);

--
-- Filtros para la tabla `historial_requerimientos`
--
ALTER TABLE `historial_requerimientos`
  ADD CONSTRAINT `historial_requerimientos_ibfk_1` FOREIGN KEY (`requerimiento_id`) REFERENCES `requerimientos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_requerimientos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `requerimientos`
--
ALTER TABLE `requerimientos`
  ADD CONSTRAINT `requerimientos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
