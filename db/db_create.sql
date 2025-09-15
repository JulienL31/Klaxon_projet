-- === Création base + utilisateur SQL (optionnel mais recommandé) ===
-- 👉 adapte les mots de passe si besoin
CREATE DATABASE IF NOT EXISTS klaxon
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'klaxon_user'@'localhost' IDENTIFIED BY 'KlaxonPwd!2025';
GRANT ALL PRIVILEGES ON klaxon.* TO 'klaxon_user'@'localhost';
FLUSH PRIVILEGES;

USE klaxon;

-- Sécurité pour rejouer le script proprement
SET FOREIGN_KEY_CHECKS = 0;

-- === Table users (extraite du SI RH : pas de CRUD côté appli) ===
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name     VARCHAR(100) NOT NULL,
  last_name      VARCHAR(100) NOT NULL,
  email          VARCHAR(190) NOT NULL UNIQUE,
  phone          VARCHAR(30)  NULL,
  role           ENUM('user','admin') NOT NULL DEFAULT 'user',
  password       VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at     TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- === Table agencies (CRUD admin only) ===
DROP TABLE IF EXISTS agencies;
CREATE TABLE agencies (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- === Table trips (listée en accueil) ===
DROP TABLE IF EXISTS trips;
CREATE TABLE trips (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  agency_from_id  INT UNSIGNED NOT NULL,
  agency_to_id    INT UNSIGNED NOT NULL,
  departure_dt    DATETIME NOT NULL,
  arrival_dt      DATETIME NOT NULL,
  seats_total     INT UNSIGNED NOT NULL,
  seats_free      INT UNSIGNED NOT NULL,
  contact_name    VARCHAR(150) NOT NULL,
  contact_email   VARCHAR(190) NOT NULL,
  contact_phone   VARCHAR(30)  NULL,
  author_id       INT UNSIGNED NOT NULL, -- utilisateur auteur du trajet
  created_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_trips_from   FOREIGN KEY (agency_from_id) REFERENCES agencies(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_trips_to     FOREIGN KEY (agency_to_id)   REFERENCES agencies(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_trips_author FOREIGN KEY (author_id)      REFERENCES users(id)    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT chk_agency_diff CHECK (agency_from_id <> agency_to_id),
  CONSTRAINT chk_seats_valid CHECK (seats_total >= 1 AND seats_free >= 0 AND seats_free <= seats_total)
) ENGINE=InnoDB;

-- Index utiles (accueil : tri par départ + filtre places)
CREATE INDEX idx_trips_departure ON trips(departure_dt);
CREATE INDEX idx_trips_seatsfree ON trips(seats_free);

SET FOREIGN_KEY_CHECKS = 1;
