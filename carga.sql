CREATE DATABASE IF NOT EXISTS universidad;
USE universidad;

CREATE TABLE IF NOT EXISTS materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_materia VARCHAR(10) NOT NULL,
    nombre_materia VARCHAR(100) NOT NULL,
    creditos INT NOT NULL,
    descripcion TEXT,
    semestre INT NOT NULL,
    UNIQUE (codigo_materia)
);
-- Insertar materias de ejemplo
INSERT INTO materias (codigo_materia, nombre_materia, creditos, descripcion, semestre)
VALUES 
('MAT101', 'Matematicas Basicas', 3, 'Introduccion a las matemáticas para ciencias sociales.', 1),
('FIS201', 'Fisica General', 4, 'Conceptos básicos de física clásica.', 2),
('PROG102', 'Programacion 1', 4, 'Fundamentos de programacion en lenguajes estructurados.', 1),
('EST301', 'Estadistica Aplicada', 3, 'Aplicaciones de la estadística en la ingeniería.', 3),
('QUI202', 'Quimica Organica', 4, 'Estudio de compuestos orgánicos y sus reacciones.', 2),
('HIS101', 'Historia Universal', 2, 'Estudio cronológico de los principales eventos históricos.', 1);

-- Insertar más materias
INSERT INTO materias (codigo_materia, nombre_materia, creditos, descripcion, semestre)
VALUES 
('ING102', 'Ingles Tecnico', 2, 'Inglés especializado para la comprensión de textos técnicos.', 1),
('ADM301', 'Administracion de Empresas', 3, 'Introducción a los conceptos de administración empresarial.', 3),
('ECO201', 'Economia General', 3, 'Conceptos básicos de micro y macroeconomía.', 2),
('BIO103', 'Biologia Celular', 4, 'Estudio de la estructura y función de la célula.', 1);

CREATE TABLE IF NOT EXISTS materia_estudiante(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materia VARCHAR(20) NOT NULL,
    id_estudiante VARCHAR(20) NOT NULL);