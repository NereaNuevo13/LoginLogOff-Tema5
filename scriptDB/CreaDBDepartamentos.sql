/* Creación de la Base de Datos */
    CREATE DATABASE if NOT EXISTS dbs939516;
    
    
/* Usar la base de datos creada */
    USE dbs939516;

/* Creación de la table departamento */
CREATE TABLE IF NOT EXISTS Departamento (
    CodDepartamento CHAR(3) PRIMARY KEY,
    DescDepartamento VARCHAR(255) NOT NULL,
    FechaBaja DATE NULL,
    VolumenNegocio float NULL
)  ENGINE=INNODB;

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

