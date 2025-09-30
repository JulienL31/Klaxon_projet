-- klaxon_seed_data.sql
-- Remplit la base "klaxon" pour le schéma de Dump20250930 (MySQL 8).
-- - agencies (villes FR)
-- - users (mdp = "password" bcrypt, role admin/user)
-- - trips (utilise agency_from_id / agency_to_id / author_id / departure_at / arrival_at / seats_total / seats_free)

SET NAMES utf8mb4;
SET time_zone = '+00:00';

USE `klaxon`;

START TRANSACTION;

-- Hash bcrypt standard de "password" (comme dans la doc Laravel)
SET @pwd := '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
SET @now := NOW();

/* ---------------------------
   AGENCIES (idempotent)
----------------------------*/
INSERT INTO agencies (name, created_at, updated_at) VALUES
('Toulouse', @now, @now), ('Paris', @now, @now), ('Lyon', @now, @now), ('Marseille', @now, @now),
('Bordeaux', @now, @now), ('Lille', @now, @now), ('Nantes', @now, @now), ('Rennes', @now, @now),
('Strasbourg', @now, @now), ('Nice', @now, @now), ('Montpellier', @now, @now), ('Grenoble', @now, @now),
('Clermont-Ferrand', @now, @now), ('Dijon', @now, @now), ('Nancy', @now, @now), ('Reims', @now, @now),
('Tours', @now, @now), ('Brest', @now, @now), ('Bayonne', @now, @now), ('Perpignan', @now, @now)
ON DUPLICATE KEY UPDATE updated_at=VALUES(updated_at);

/* ---------------------------
   USERS (idempotent)
----------------------------*/
-- Comptes obligatoires
INSERT INTO users (name,email,email_verified_at,phone,role,password,remember_token,created_at,updated_at) VALUES
('Admin','admin@example.com',NULL,NULL,'admin',@pwd,NULL,@now,@now)
ON DUPLICATE KEY UPDATE name=VALUES(name), role=VALUES(role), password=@pwd, updated_at=@now;

INSERT INTO users (name,email,email_verified_at,phone,role,password,remember_token,created_at,updated_at) VALUES
('User','user@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now)
ON DUPLICATE KEY UPDATE name=VALUES(name), role=VALUES(role), password=@pwd, updated_at=@now;

-- Série d’utilisateurs de test
INSERT INTO users (name,email,email_verified_at,phone,role,password,remember_token,created_at,updated_at) VALUES
('Alice Martin','alice.martin@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Bruno Dupont','bruno.dupont@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Camille Leroy','camille.leroy@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('David Bernard','david.bernard@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Emma Laurent','emma.laurent@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Farid Morel','farid.morel@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Gaëlle Robert','gaelle.robert@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Hugo Petit','hugo.petit@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Inès Fontaine','ines.fontaine@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Jules Lopez','jules.lopez@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Kenza Marchand','kenza.marchand@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Léo Garcia','leo.garcia@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Manon Chevalier','manon.chevalier@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Nassim Adam','nassim.adam@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Océane Roussel','oceane.roussel@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Paul Charles','paul.charles@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Quitterie Robin','quitterie.robin@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Rayan Gauthier','rayan.gauthier@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Salomé Aubert','salome.aubert@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Thomas Caron','thomas.caron@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Ulysse Fabre','ulysse.fabre@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Violette Pruvost','violette.pruvost@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Wassim Leblanc','wassim.leblanc@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Xavier Diallo','xavier.diallo@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Yasmine Colin','yasmine.colin@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now),
('Zoé Perrot','zoe.perrot@example.com',NULL,NULL,'user',@pwd,NULL,@now,@now)
ON DUPLICATE KEY UPDATE name=VALUES(name), password=@pwd, updated_at=@now;

/* ---------------------------
   IDS utiles
----------------------------*/
SET @admin_id := (SELECT id FROM users WHERE email='admin@example.com' LIMIT 1);
SET @user_id  := (SELECT id FROM users WHERE email='user@example.com'  LIMIT 1);

SET @TLSE := (SELECT id FROM agencies WHERE name='Toulouse'   LIMIT 1);
SET @PAR  := (SELECT id FROM agencies WHERE name='Paris'      LIMIT 1);
SET @LYO  := (SELECT id FROM agencies WHERE name='Lyon'       LIMIT 1);
SET @MRS  := (SELECT id FROM agencies WHERE name='Marseille'  LIMIT 1);
SET @BDX  := (SELECT id FROM agencies WHERE name='Bordeaux'   LIMIT 1);
SET @LIL  := (SELECT id FROM agencies WHERE name='Lille'      LIMIT 1);
SET @NTE  := (SELECT id FROM agencies WHERE name='Nantes'     LIMIT 1);
SET @RNS  := (SELECT id FROM agencies WHERE name='Rennes'     LIMIT 1);
SET @STR  := (SELECT id FROM agencies WHERE name='Strasbourg' LIMIT 1);
SET @NIC  := (SELECT id FROM agencies WHERE name='Nice'       LIMIT 1);
SET @MPL  := (SELECT id FROM agencies WHERE name='Montpellier' LIMIT 1);

/* ---------------------------
   TRIPS (idempotent)
----------------------------*/
-- Helper: insère un trajet si la combinaison (from,to,author,departure_at) n’existe pas encore
-- (on calcule aussi arrival_at = departure_at + durée fictive)
-- Tous les trajets ont des places libres (seats_free <= seats_total)

-- Toulouse -> Paris par Admin (demain 08:00, durée 7h)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @TLSE, @PAR, @admin_id,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '08:00:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '15:00:00' HOUR_SECOND,
       4, 3, 'Admin', 'admin@example.com', '0600000001', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips
  WHERE agency_from_id=@TLSE AND agency_to_id=@PAR AND author_id=@admin_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '08:00:00' HOUR_SECOND
);

-- Paris -> Lyon par User (J+2 09:15, durée 5h)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @PAR, @LYO, @user_id,
       DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '09:15:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '14:15:00' HOUR_SECOND,
       4, 2, 'User', 'user@example.com', '0600000002', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@PAR AND agency_to_id=@LYO AND author_id=@user_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '09:15:00' HOUR_SECOND
);

-- Lyon -> Marseille par Admin (J+1 14:30, 4h30)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @LYO, @MRS, @admin_id,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '14:30:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '19:00:00' HOUR_SECOND,
       5, 4, 'Admin', 'admin@example.com', '0600000001', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@LYO AND agency_to_id=@MRS AND author_id=@admin_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '14:30:00' HOUR_SECOND
);

-- Bordeaux -> Toulouse par User (J+3 17:45, 3h15)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @BDX, @TLSE, @user_id,
       DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '17:45:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '21:00:00' HOUR_SECOND,
       4, 3, 'User', 'user@example.com', '0600000002', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@BDX AND agency_to_id=@TLSE AND author_id=@user_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '17:45:00' HOUR_SECOND
);

-- Lille -> Paris (J+4 07:20, 3h40)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @LIL, @PAR, @admin_id,
       DATE_ADD(DATE(NOW()), INTERVAL 4 DAY) + INTERVAL '07:20:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 4 DAY) + INTERVAL '11:00:00' HOUR_SECOND,
       4, 3, 'Admin', 'admin@example.com', '0600000001', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@LIL AND agency_to_id=@PAR AND author_id=@admin_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 4 DAY) + INTERVAL '07:20:00' HOUR_SECOND
);

-- Nantes -> Rennes (J+1 10:05, 1h55)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @NTE, @RNS, @user_id,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '10:05:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '12:00:00' HOUR_SECOND,
       3, 2, 'User', 'user@example.com', '0600000002', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@NTE AND agency_to_id=@RNS AND author_id=@user_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 1 DAY) + INTERVAL '10:05:00' HOUR_SECOND
);

-- Strasbourg -> Paris (J+5 12:10, 5h50)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @STR, @PAR, @admin_id,
       DATE_ADD(DATE(NOW()), INTERVAL 5 DAY) + INTERVAL '12:10:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 5 DAY) + INTERVAL '18:00:00' HOUR_SECOND,
       4, 2, 'Admin', 'admin@example.com', '0600000001', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@STR AND agency_to_id=@PAR AND author_id=@admin_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 5 DAY) + INTERVAL '12:10:00' HOUR_SECOND
);

-- Nice -> Montpellier (J+2 16:00, 3h30)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @NIC, @MPL, @user_id,
       DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '16:00:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '19:30:00' HOUR_SECOND,
       4, 3, 'User', 'user@example.com', '0600000002', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@NIC AND agency_to_id=@MPL AND author_id=@user_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 2 DAY) + INTERVAL '16:00:00' HOUR_SECOND
);

-- Paris -> Toulouse (J+6 08:30, 7h30)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @PAR, @TLSE, @admin_id,
       DATE_ADD(DATE(NOW()), INTERVAL 6 DAY) + INTERVAL '08:30:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 6 DAY) + INTERVAL '16:00:00' HOUR_SECOND,
       5, 3, 'Admin', 'admin@example.com', '0600000001', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@PAR AND agency_to_id=@TLSE AND author_id=@admin_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 6 DAY) + INTERVAL '08:30:00' HOUR_SECOND
);

-- Marseille -> Lyon (J+3 11:45, 3h15)
INSERT INTO trips (agency_from_id, agency_to_id, author_id, departure_at, arrival_at, seats_total, seats_free, contact_name, contact_email, contact_phone, created_at, updated_at)
SELECT @MRS, @LYO, @user_id,
       DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '11:45:00' HOUR_SECOND,
       DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '15:00:00' HOUR_SECOND,
       4, 3, 'User', 'user@example.com', '0600000002', @now, @now
WHERE NOT EXISTS (
  SELECT 1 FROM trips WHERE agency_from_id=@MRS AND agency_to_id=@LYO AND author_id=@user_id
    AND departure_at = DATE_ADD(DATE(NOW()), INTERVAL 3 DAY) + INTERVAL '11:45:00' HOUR_SECOND
);

COMMIT;