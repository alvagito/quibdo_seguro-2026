-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-09-2025 a las 04:45:27
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
-- Base de datos: `quibdo_seguro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canjes_puntos`
--

CREATE TABLE `canjes_puntos` (
  `id_canje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_recompensa` int(11) NOT NULL,
  `puntos_canjeados` int(11) NOT NULL,
  `fecha_canje` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comercios_aliados`
--

CREATE TABLE `comercios_aliados` (
  `id_comercio` int(11) NOT NULL,
  `id_usuario_administrador` int(11) NOT NULL,
  `nombre_comercio` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reporte`
--

CREATE TABLE `estados_reporte` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estados_reporte`
--

INSERT INTO `estados_reporte` (`id_estado`, `nombre_estado`, `descripcion`) VALUES
(1, 'Pendiente', 'El reporte ha sido recibido y está en espera de revisión.'),
(2, 'En Revisión', 'El reporte está siendo evaluado por un administrador o autoridad.'),
(3, 'Confirmado', 'El incidente ha sido verificado y se considera válido.'),
(4, 'En Proceso', 'Las autoridades están actuando sobre el incidente.'),
(5, 'Resuelto', 'El incidente ha sido solucionado o cerrado.'),
(6, 'Falso', 'El reporte ha sido verificado y determinado como falso.'),
(7, 'Archivado', 'El reporte ha sido cerrado y archivado.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insidentes`
--

CREATE TABLE `insidentes` (
  `id_incidente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tipo_incidente` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT 1,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `fecha_hora_incidente` datetime DEFAULT NULL,
  `fecha_hora_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `evidencia_foto_url` varchar(255) DEFAULT NULL,
  `direccion_aproximada` varchar(255) DEFAULT NULL,
  `comentarios_autoridad` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_chat`
--

CREATE TABLE `mensajes_chat` (
  `id_mensaje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_zona` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_hora_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas`
--

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL,
  `id_comercio` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `puntos` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensas`
--

CREATE TABLE `recompensas` (
  `id` int(11) NOT NULL,
  `id_comercio` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo_puntos` int(11) NOT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `puntos` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `id_testimonio` int(11) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `testimonios`
--

INSERT INTO `testimonios` (`id_testimonio`, `autor`, `contenido`, `barrio`, `fecha_creacion`, `activo`) VALUES
(1, 'María López', 'Gracias a Quibdó Seguro, logramos identificar un punto crítico de robos en nuestro barrio y las autoridades actuaron rápidamente.', 'Barrio El Carmen', '2025-09-15 18:27:31', 1),
(2, 'Carlos Rodríguez', 'La plataforma nos ha permitido organizarnos como comunidad y prevenir incidentes antes de que ocurran.', 'Barrio El Progreso', '2025-09-15 18:27:31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_incidentes`
--

CREATE TABLE `tipos_incidentes` (
  `id_tipo_incidente` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_incidentes`
--

INSERT INTO `tipos_incidentes` (`id_tipo_incidente`, `nombre_tipo`, `descripcion`) VALUES
(1, 'Robo', 'Acto de apoderarse de bienes ajenos con violencia o intimidación.'),
(2, 'Hurto', 'Sustracción de bienes ajenos sin violencia ni intimidación.'),
(3, 'Microtráfico', 'Actividades relacionadas con la venta y distribución de drogas a pequeña escala.'),
(4, 'Vandalismo', 'Destrucción o daño intencional de propiedad pública o privada.'),
(5, 'Violencia Doméstica', 'Violencia que ocurre en el ámbito familiar o doméstico.'),
(6, 'Actividad Sospechosa', 'Comportamiento o presencia inusual que podría indicar una amenaza.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('ciudadano','administrador','autoridad','comercio_aliado') NOT NULL DEFAULT 'ciudadano',
  `puntos` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `puntos`, `created_at`, `updated_at`) VALUES
(2, 'alvaro', 'alvarochaverra0510@gmail.com', '$2y$12$NT6QPA/d0DM9iG9trRVIL.q4N/q2CEiUMpELchu/iF4a15R.xzoKu', 'ciudadano', 0, '2025-09-20 07:43:55', '2025-09-20 07:43:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas_comunitarias`
--

CREATE TABLE `zonas_comunitarias` (
  `id_zona` int(11) NOT NULL,
  `nombre_zona` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `zonas_comunitarias`
--

INSERT INTO `zonas_comunitarias` (`id_zona`, `nombre_zona`, `descripcion`) VALUES
(1, 'Centro', 'Zona centro de Quibdó'),
(2, 'Norte', 'Zona norte de Quibdó'),
(3, 'Sur', 'Zona sur de Quibdó'),
(4, 'Este', 'Zona este de Quibdó'),
(5, 'Oeste', 'Zona oeste de Quibdó');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canjes_puntos`
--
ALTER TABLE `canjes_puntos`
  ADD PRIMARY KEY (`id_canje`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_recompensa` (`id_recompensa`);

--
-- Indices de la tabla `comercios_aliados`
--
ALTER TABLE `comercios_aliados`
  ADD PRIMARY KEY (`id_comercio`),
  ADD KEY `id_usuario_administrador` (`id_usuario_administrador`);

--
-- Indices de la tabla `estados_reporte`
--
ALTER TABLE `estados_reporte`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `insidentes`
--
ALTER TABLE `insidentes`
  ADD PRIMARY KEY (`id_incidente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_zona` (`id_zona`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comercio` (`id_comercio`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comercio` (`id_comercio`);

--
-- Indices de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`id_testimonio`);

--
-- Indices de la tabla `tipos_incidentes`
--
ALTER TABLE `tipos_incidentes`
  ADD PRIMARY KEY (`id_tipo_incidente`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `zonas_comunitarias`
--
ALTER TABLE `zonas_comunitarias`
  ADD PRIMARY KEY (`id_zona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canjes_puntos`
--
ALTER TABLE `canjes_puntos`
  MODIFY `id_canje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comercios_aliados`
--
ALTER TABLE `comercios_aliados`
  MODIFY `id_comercio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados_reporte`
--
ALTER TABLE `estados_reporte`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insidentes`
--
ALTER TABLE `insidentes`
  MODIFY `id_incidente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  MODIFY `id_testimonio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos_incidentes`
--
ALTER TABLE `tipos_incidentes`
  MODIFY `id_tipo_incidente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `zonas_comunitarias`
--
ALTER TABLE `zonas_comunitarias`
  MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `canjes_puntos`
--
ALTER TABLE `canjes_puntos`
  ADD CONSTRAINT `canjes_puntos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `canjes_puntos_ibfk_2` FOREIGN KEY (`id_recompensa`) REFERENCES `recompensas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comercios_aliados`
--
ALTER TABLE `comercios_aliados`
  ADD CONSTRAINT `comercios_aliados_ibfk_1` FOREIGN KEY (`id_usuario_administrador`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `insidentes`
--
ALTER TABLE `insidentes`
  ADD CONSTRAINT `insidentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD CONSTRAINT `mensajes_chat_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensajes_chat_ibfk_2` FOREIGN KEY (`id_zona`) REFERENCES `zonas_comunitarias` (`id_zona`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `ofertas_ibfk_1` FOREIGN KEY (`id_comercio`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recompensas`
--
ALTER TABLE `recompensas`
  ADD CONSTRAINT `recompensas_ibfk_1` FOREIGN KEY (`id_comercio`) REFERENCES `comercios_aliados` (`id_comercio`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
