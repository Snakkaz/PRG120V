-- Database schema for student and class management system

-- Create database
CREATE DATABASE IF NOT EXISTS skole_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE skole_db;

-- Drop tables if they exist (for clean reinstall)
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS klasse;

-- Create klasse (class) table
CREATE TABLE klasse (
    klassekode VARCHAR(10) PRIMARY KEY,
    klassenavn VARCHAR(100) NOT NULL,
    studiumkode VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create student table
CREATE TABLE student (
    brukernavn VARCHAR(50) PRIMARY KEY,
    fornavn VARCHAR(50) NOT NULL,
    etternavn VARCHAR(50) NOT NULL,
    klassekode VARCHAR(10),
    FOREIGN KEY (klassekode) REFERENCES klasse(klassekode) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some sample data for testing
INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES
('PRG120', 'Programmering 1', 'IT'),
('DAT101', 'Databehandling', 'IT'),
('MAT100', 'Matematikk 1', 'MAT');

INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES
('ola123', 'Ola', 'Nordmann', 'PRG120'),
('kari456', 'Kari', 'Hansen', 'PRG120'),
('per789', 'Per', 'Jensen', 'DAT101');
