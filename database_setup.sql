-- SQL Script for PRG120V Database Setup
-- Database: stpet1155
-- Author: stpet1155

-- =====================================================
-- CREATE TABLES
-- =====================================================

-- Create klasse table
CREATE TABLE IF NOT EXISTS klasse (
  klassekode VARCHAR(10) NOT NULL,
  klassenavn VARCHAR(50) NOT NULL,
  studiumkode VARCHAR(50) NOT NULL,
  PRIMARY KEY (klassekode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create student table
CREATE TABLE IF NOT EXISTS student (
  brukernavn CHAR(7) NOT NULL,
  fornavn VARCHAR(50) NOT NULL,
  etternavn VARCHAR(50) NOT NULL,
  klassekode VARCHAR(10) NOT NULL,
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
('IT1', 'IT og ledelse 1. år', 'ITLED'),
('IT2', 'IT og ledelse 2. år', 'ITLED'),
('IT3', 'IT og ledelse 3. år', 'ITLED'),
('PRG1', 'Programmering 1. semester', 'ITE')
ON DUPLICATE KEY UPDATE klassekode=klassekode;

-- Sample students
INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES
('stpet11', 'Petter', 'Petterson', 'IT1'),
('stola12', 'Ola', 'Olsen', 'IT1'),
('stkari3', 'Kari', 'Karlsen', 'IT2'),
('stanne4', 'Anne', 'Andersen', 'PRG1')
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
