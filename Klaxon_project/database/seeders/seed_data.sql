SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- Agencies (exemple minimal, adapte si besoin)
INSERT INTO `agencies` (`id`,`name`,`created_at`,`updated_at`) VALUES
(1,'Paris',NOW(),NOW()),
(2,'Lyon',NOW(),NOW()),
(3,'Marseille',NOW(),NOW());

-- Users (admin + user)
-- mot de passe = "password" (hash Laravel par défaut)
INSERT INTO `users` (`id`,`name`,`email`,`email_verified_at`,`phone`,`role`,`password`,`remember_token`,`created_at`,`updated_at`) VALUES
(1,'Admin Demo','admin@example.com',NULL,'0600000000','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NOW(),NOW()),
(2,'User Demo','user@example.com',NULL,'0611111111','user','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NOW(),NOW());

-- Trips (dates futures)
INSERT INTO `trips`
(`id`,`agency_from_id`,`agency_to_id`,`author_id`,`departure_at`,`arrival_at`,`seats_total`,`seats_free`,`contact_name`,`contact_email`,`contact_phone`,`created_at`,`updated_at`)
VALUES
(1,1,2,2, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 3 HOUR, 4, 2, 'User Demo','user@example.com','0611111111',NOW(),NOW()),
(2,2,3,1, DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY) + INTERVAL 2 HOUR, 3, 1, 'Admin Demo','admin@example.com','0600000000',NOW(),NOW());

SET FOREIGN_KEY_CHECKS=1;
