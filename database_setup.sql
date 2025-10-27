-- SQL Script for PRG120V Database Setup
-- Database: stpet1155
-- Author: stpet1155

-- =====================================================
-- CREATE TABLES
-- =====================================================

-- Create klasse table
CREATE TABLE IF NOT EXISTS klasse (
  klassekode CHAR(5) NOT NULL,
  klassenavn VARCHAR(50) NOT NULL,
  studiumkode VARCHAR(50) NOT NULL,
  PRIMARY KEY (klassekode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create student table
CREATE TABLE IF NOT EXISTS student (
  brukernavn CHAR(7) NOT NULL,
  fornavn VARCHAR(50) NOT NULL,
  etternavn VARCHAR(50) NOT NULL,
  klassekode CHAR(5) NOT NULL,
  PRIMARY KEY (brukernavn),
  FOREIGN KEY (klassekode) REFERENCES klasse(klassekode)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERT SAMPLE DATA (Optional)
-- =====================================================

-- Sample classes
INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES
('IT101', 'Informasjonsteknologi 1. klasse', 'ITE'),
('IT201', 'Informasjonsteknologi 2. klasse', 'ITE'),
('PRG01', 'Programmering 1. semester', 'ITE'),
('WEB01', 'Webutvikling 1. semester', 'ITE')
ON DUPLICATE KEY UPDATE klassekode=klassekode;

-- Sample students
INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES
('stpet11', 'Petter', 'Petterson', 'IT101'),
('stola12', 'Ola', 'Olsen', 'IT101'),
('stkari3', 'Kari', 'Karlsen', 'IT201'),
('stanne4', 'Anne', 'Andersen', 'PRG01'),
('stjohn5', 'John', 'Jensen', 'WEB01')
ON DUPLICATE KEY UPDATE brukernavn=brukernavn;

-- =====================================================
-- VERIFY DATA
-- =====================================================

-- Show all classes
SELECT 'Classes:' as Info;
SELECT * FROM klasse ORDER BY klassekode;

-- Show all students with class information
SELECT 'Students:' as Info;
SELECT 
    s.brukernavn,
    s.fornavn,
    s.etternavn,
    s.klassekode,
    k.klassenavn
FROM student s
LEFT JOIN klasse k ON s.klassekode = k.klassekode
ORDER BY s.etternavn, s.fornavn;

-- Show class statistics
SELECT 'Class Statistics:' as Info;
SELECT 
    k.klassekode,
    k.klassenavn,
    COUNT(s.brukernavn) as antall_studenter
FROM klasse k
LEFT JOIN student s ON k.klassekode = s.klassekode
GROUP BY k.klassekode, k.klassenavn
ORDER BY k.klassekode;
