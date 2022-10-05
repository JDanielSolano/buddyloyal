-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2019 at 05:14 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyecto`
--
CREATE DATABASE proyecto;

USE proyecto;

-- --------------------------------------------------------

--
-- Table structure for table `AMISTAD`
--

CREATE TABLE `AMISTAD` (
  `USUARIO1_ID` int(11) NOT NULL,
  `USUARIO2_ID` int(11) NOT NULL,
  `ESTADO_AMISTAD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `AMISTAD`
--

INSERT INTO `AMISTAD` (`USUARIO1_ID`, `USUARIO2_ID`, `ESTADO_AMISTAD`) VALUES
(2, 1, 1),
(3, 1, 1),
(3, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `POSTS`
--

CREATE TABLE `POSTS` (
  `POST_ID` int(11) NOT NULL,
  `POST_CONTENIDO` text NOT NULL,
  `POST_FECHA` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `POST_PRIVACIDAD` char(1) NOT NULL,
  `POST_AUTOR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `POSTS`
--

INSERT INTO `POSTS` (`POST_ID`, `POST_CONTENIDO`, `POST_FECHA`, `POST_PRIVACIDAD`, `POST_AUTOR`) VALUES
(1, 'Â¡Hola a todos! :) ', '2019-12-10 04:01:51', 'Y', 2),
(2, 'Amo estas tardes navideÃ±as...', '2019-12-10 04:02:20', 'N', 1),
(3, 'Â¡El concierto de anoche estuvo increÃ­ble!', '2019-12-10 04:03:05', 'Y', 1),
(4, 'Un recuerdo del Yosemite ', '2019-12-10 04:03:35', 'N', 3);

-- --------------------------------------------------------

--
-- Table structure for table `USUARIOS`
--

CREATE TABLE `USUARIOS` (
  `USUARIO_ID` int(11) NOT NULL,
  `USUARIO_NOMBRE` varchar(20) NOT NULL,
  `USUARIO_APELLIDO` varchar(20) NOT NULL,
  `USUARIO_NICKNAME` varchar(20) DEFAULT NULL,
  `USUARIO_CONTRA` varchar(255) NOT NULL,
  `USUARIO_CORREO` varchar(255) NOT NULL,
  `USUARIO_GENERO` char(1) NOT NULL,
  `USUARIO_CUMPLE` date NOT NULL,
  `USUARIO_ESTADO` char(1) DEFAULT NULL,
  `USUARIO_INFO` text DEFAULT NULL,
  `USUARIO_CIUDAD` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `USUARIOS`
--

INSERT INTO `USUARIOS` (`USUARIO_ID`, `USUARIO_NOMBRE`, `USUARIO_APELLIDO`, `USUARIO_NICKNAME`, `USUARIO_CONTRA`, `USUARIO_CORREO`, `USUARIO_GENERO`, `USUARIO_CUMPLE`, `USUARIO_ESTADO`, `USUARIO_INFO`, `USUARIO_CIUDAD`) VALUES
(1, 'Daniel', 'Solano', 'danielsolano', '098f6bcd4621d373cade4e832627b4f6', 'daniel@test.com', 'M', '1997-08-27', 'S', 'Software Engineer en Akurey', 'San Jose'),
(2, 'Edwin', 'Fuentes', 'edwin', '098f6bcd4621d373cade4e832627b4f6', 'edwin@test.com', 'M', '1994-04-01', 'R', '', 'Desamparados'),
(3, 'Mynor', 'Gamboa', 'mynor', '098f6bcd4621d373cade4e832627b4f6', 'mynor@test.com', 'M', '1995-12-07', 'R', '', 'Desamparados');

-- --------------------------------------------------------

--
-- Table structure for table `USUARIO_CELULAR`
--

CREATE TABLE `USUARIO_CELULAR` (
  `USUARIO_ID` int(11) DEFAULT NULL,
  `USUARIO_CELULAR` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AMISTAD`
--
ALTER TABLE `AMISTAD`
  ADD KEY `USUARIO1_ID` (`USUARIO1_ID`),
  ADD KEY `USUARIO2_ID` (`USUARIO2_ID`);

--
-- Indexes for table `POSTS`
--
ALTER TABLE `POSTS`
  ADD PRIMARY KEY (`POST_ID`),
  ADD KEY `POST_AUTOR` (`POST_AUTOR`);

--
-- Indexes for table `USUARIOS`
--
ALTER TABLE `USUARIOS`
  ADD PRIMARY KEY (`USUARIO_ID`);

--
-- Indexes for table `USUARIO_CELULAR`
--
ALTER TABLE `USUARIO_CELULAR`
  ADD KEY `USUARIO_ID` (`USUARIO_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `POSTS`
--
ALTER TABLE `POSTS`
  MODIFY `POST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `USUARIOS`
--
ALTER TABLE `USUARIOS`
  MODIFY `USUARIO_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AMISTAD`
--
ALTER TABLE `AMISTAD`
  ADD CONSTRAINT `AMISTAD_ibfk_1` FOREIGN KEY (`USUARIO1_ID`) REFERENCES `USUARIOS` (`USUARIO_ID`),
  ADD CONSTRAINT `AMISTAD_ibfk_2` FOREIGN KEY (`USUARIO2_ID`) REFERENCES `USUARIOS` (`USUARIO_ID`);

--
-- Constraints for table `POSTS`
--
ALTER TABLE `POSTS`
  ADD CONSTRAINT `POSTS_ibfk_1` FOREIGN KEY (`POST_AUTOR`) REFERENCES `USUARIOS` (`USUARIO_ID`);

--
-- Constraints for table `USUARIO_CELULAR`
--
ALTER TABLE `USUARIO_CELULAR`
  ADD CONSTRAINT `USUARIO_CELULAR_ibfk_1` FOREIGN KEY (`USUARIO_ID`) REFERENCES `USUARIOS` (`USUARIO_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
