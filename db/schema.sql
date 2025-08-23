-- schema.sql — Touche pas au Klaxon
-- MySQL 8+ / MariaDB 10.4+
CREATE DATABASE IF NOT EXISTS tpak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tpak;

-- Users (employees)
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Agencies (cities)
CREATE TABLE IF NOT EXISTS agencies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Trips
CREATE TABLE IF NOT EXISTS trips (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  author_id INT UNSIGNED NOT NULL,
  agency_from_id INT UNSIGNED NOT NULL,
  agency_to_id INT UNSIGNED NOT NULL,
  depart_at DATETIME NOT NULL,
  arrive_at DATETIME NOT NULL,
  seats_total TINYINT UNSIGNED NOT NULL,
  seats_available TINYINT UNSIGNED NOT NULL,
  contact_name VARCHAR(200) NOT NULL,
  contact_phone VARCHAR(30) NOT NULL,
  contact_email VARCHAR(190) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_trips_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_trips_from FOREIGN KEY (agency_from_id) REFERENCES agencies(id) ON DELETE RESTRICT,
  CONSTRAINT fk_trips_to FOREIGN KEY (agency_to_id) REFERENCES agencies(id) ON DELETE RESTRICT,
  CONSTRAINT chk_seats CHECK (seats_total >= seats_available AND seats_total BETWEEN 1 AND 9),
  CONSTRAINT chk_dates CHECK (arrive_at > depart_at),
  INDEX idx_depart_at (depart_at),
  INDEX idx_agencies (agency_from_id, agency_to_id)
) ENGINE=InnoDB;