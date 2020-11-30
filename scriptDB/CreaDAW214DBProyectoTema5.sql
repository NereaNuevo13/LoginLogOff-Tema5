-- CREACION BASE DE DATOS
-- Creacion de la base de datos DAW214DBProyectoTema5
CREATE DATABASE IF NOT EXISTS DAW214DBProyectoTema5;

USE DAW214DBProyectoTema5;

-- Creacion de tablas de la base de datos
CREATE TABLE if NOT EXISTS T02_Departamento (
    T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaBajaDepartamento DATE NULL,
    T02_FechaCreacionDepartamento INT NULL,
    T02_VolumenNegocio FLOAT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS T01_Usuario(
        T01_CodUsuario VARCHAR(15) PRIMARY KEY,
        T01_DescUsuario VARCHAR(25) NOT NULL,
        T01_Password VARCHAR(64) NOT NULL,
        T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario', -- Valor por defecto usuario
        T01_FechaHoraUltimaConexion INT,
        T01_NumConexiones INT DEFAULT 0,
        T01_ImagenUsuario MEDIUMBLOB
)ENGINE=INNODB;

-- CREACION USUARIO ADMINISTRADOR
-- Creacion de usuario administrador de la base de datos: usuarioDAW214DBProyectoTema5 / paso
CREATE USER IF NOT EXISTS 'usuarioDAW214DBProyectoTema5'@'%' IDENTIFIED BY 'paso';

-- Permisos para la base de datos
GRANT ALL PRIVILEGES ON DAW214DBProyectoTema5.* TO 'usuarioDAW214DBProyectoTema5'@'%';