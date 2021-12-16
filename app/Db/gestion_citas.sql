--
-- Base de datos: `gestion_citas`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `numero_cita` int(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `estado` varchar(30) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `id_policlinica` int(11) NOT NULL,
  `cedula_paciente` varchar(11) NOT NULL,
  `id_medico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `numero_cita` int(100) NOT NULL,
  `cedula_paciente` varchar(11) NOT NULL,
  `telefono` int(11) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(30) NOT NULL,
  `id_medico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre_especialidad`, `id_medico`) VALUES
(1, 'Neurología', 1),
(2, 'Cardiología', 2),
(3, 'Gineco Obstetricia', 3),
(4, 'Oftalmología', 4),
(5, 'Geriatría', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico`
--

CREATE TABLE `medico` (
  `id_medico` int(11) NOT NULL,
  `cedula_medico` varchar(11) NOT NULL,
  `nombre_medico` varchar(30) NOT NULL,
  `apellido_medico` varchar(30) NOT NULL,
  `especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `medico`
--

INSERT INTO `medico` (`id_medico`, `cedula_medico`, `nombre_medico`, `apellido_medico`, `especialidad`) VALUES
(1, '8-000-0000', 'Lourdes ', 'Adrianza', 1),
(2, '8-000-0001', 'Joseline', 'Marval', 2),
(3, '8-000-0003', 'Humberto', 'Van Grieken', 3),
(4, '8-000-0004', 'Arianny', 'Arrieche', 4),
(5, '8-000-0005', 'Andres', 'Salamanca', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `cedula` varchar(11) NOT NULL,
  `fechanac` date NOT NULL,
  `tipo_sangre` varchar(2) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `policlinica`
--

CREATE TABLE `policlinica` (
  `id_policlinica` int(11) NOT NULL,
  `nombre_poli` varchar(50) NOT NULL,
  `direccion_poli` text NOT NULL,
  `telefono_poli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `policlinica`
--

INSERT INTO `policlinica` (`id_policlinica`, `nombre_poli`, `direccion_poli`, `telefono_poli`) VALUES
(1, 'Complejo Hospitalario Arnulfo Arias Madrid', 'Via Simon Bolivar, antes de la Universidad de Panamá.', 5036600),
(2, 'Policlínica Dr. Carlos N. Brin', 'Av. Belisario Porras, al lado de Parque Omar, Panamá', 5031100),
(3, 'Dr. Manuel Ferrer Valdés', 'Calle 25, Calidonia', 5031700),
(4, 'Policlínica Alejandro De La Guardia', 'Av. 21E Nte. Bethania, Panamá', 5031200),
(5, 'Policlínica Dr. Generoso Guardia', 'Santa Librada, 3FCV+VVQ, Panamá', 5038348);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_citas`
--

CREATE TABLE `registro_citas` (
  `id_medico` int(11) NOT NULL,
  `numero_cita` int(100) NOT NULL,
  `fecha_cita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula_medico` varchar(11) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cedula_medico`, `password`) VALUES
('8-000-0001', 'clave321');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`numero_cita`),
  ADD KEY `id_medico_fk_idx` (`id_medico`),
  ADD KEY `especialidad_id_fk_idx` (`id_especialidad`),
  ADD KEY `policlinica_id_fk_idx` (`id_policlinica`),
  ADD KEY `citas_cedula_paciente_fk_idx` (`cedula_paciente`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD UNIQUE KEY `contacto_numero_cita` (`numero_cita`),
  ADD KEY `contacto_cita_fk_idx` (`numero_cita`),
  ADD KEY `contacto_cedpaciente_fk_idx` (`cedula_paciente`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD KEY `especialidad_fk` (`id_especialidad`),
  ADD KEY `especialidad_IDmedico_fk_idx` (`id_medico`);

--
-- Indices de la tabla `medico`
--
ALTER TABLE `medico`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `id_medico` (`id_medico`,`especialidad`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `paciente_cedula_idx` (`cedula`);

--
-- Indices de la tabla `policlinica`
--
ALTER TABLE `policlinica`
  ADD PRIMARY KEY (`id_policlinica`);

--
-- Indices de la tabla `registro_citas`
--
ALTER TABLE `registro_citas`
  ADD UNIQUE KEY `registro_numero_cita` (`numero_cita`),
  ADD KEY `registro_IDMedico_fk_idx` (`id_medico`),
  ADD KEY `registro_fecha_fk_idx` (`fecha_cita`),
  ADD KEY `registro_numcita_indx` (`numero_cita`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `numero_cita` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `numero_cita` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `medico`
--
ALTER TABLE `medico`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `policlinica`
--
ALTER TABLE `policlinica`
  MODIFY `id_policlinica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registro_citas`
--
ALTER TABLE `registro_citas`
  MODIFY `numero_cita` int(100) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_IDespecialidad_fk` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_IDmedico_fk` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_IDpoliclinica_fk` FOREIGN KEY (`id_policlinica`) REFERENCES `policlinica` (`id_policlinica`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_cedulapaciente_fk` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `citas` (`cedula_paciente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contacto_numerocita_fk` FOREIGN KEY (`numero_cita`) REFERENCES `citas` (`numero_cita`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD CONSTRAINT `especialidad_IDmedico_fk` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_citas`
--
ALTER TABLE `registro_citas`
  ADD CONSTRAINT `registro_IDmedico_fk` FOREIGN KEY (`id_medico`) REFERENCES `citas` (`id_medico`) ON UPDATE CASCADE,
  ADD CONSTRAINT `registro_numcita_fk` FOREIGN KEY (`numero_cita`) REFERENCES `citas` (`numero_cita`) ON UPDATE CASCADE;
COMMIT;


INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Rafael', 'Alejandro', '8-000-0011', '2003-03-26', 'O+', 'Urb. Versalles, Casa 42D');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Ricardo Andrés', 'Marval Jurado', '8-000-0012', '2005-11-07', 'O+', 'Urb. Versalles, Casa 42F');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Bruno', 'Diaz Gonzales', '8-000-0013', '1960-03-05', 'O-', 'Santa María, Valencia');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Marvel Cecilia', 'Ramirez Contreras', '8-000-0014', '1941-12-29', 'O+', 'Via Israel, Terramar');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('José Alejandro', 'Totesautt Duarte', '8-000-0015', '1999-10-20', 'O+', 'Via España, Vista Sol');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Isaac Eliezer', 'Lamus Díaz', '8-000-0016', '1999-02-24', 'O-', 'Costa del Este, Vista Mar');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Humberto', 'Jurado Adrianza', '8-000-0017', '1977-05-03', 'A+', 'Via Isael, Terrasol');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Juan Manuel', 'Lopez Maduro', '8-000-0018', '1999-11-27', 'A-', 'Costa del Este, Vista Mar');
INSERT INTO `gestion_citas`.`paciente` (`nombre`, `apellido`, `cedula`, `fechanac`, `tipo_sangre`, `direccion`) VALUES ('Maria Fernanda', 'Jimenez López', '8-000-0019', '2000-05-23', 'O+', 'Via Isael, Terramar');

