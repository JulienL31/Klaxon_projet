USE klaxon;

-- Hash bcrypt standard de "password" (valeur par défaut Laravel)
-- = $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- 👉 Identifiants:
--    admin@example.com / password
--    user@example.com  / password

INSERT INTO users (first_name,last_name,email,phone,role,password)
VALUES
('Alice','Admin','admin@example.com','0102030405','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Bob','User','user@example.com','0601020304','user','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO agencies (name) VALUES
('Paris'),('Lyon'),('Toulouse'),('Lille'),('Nantes');

-- Quelques trajets à venir (places_free > 0)
INSERT INTO trips
(agency_from_id, agency_to_id, departure_dt, arrival_dt, seats_total, seats_free, contact_name, contact_email, contact_phone, author_id)
VALUES
(1,3, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY + INTERVAL 7 HOUR), 4, 2, 'Bob User','user@example.com','0601020304', 2),
(3,2, DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY + INTERVAL 6 HOUR), 3, 1, 'Bob User','user@example.com','0601020304', 2),
(2,1, DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 5 DAY + INTERVAL 5 HOUR), 2, 1, 'Alice Admin','admin@example.com','0102030405', 1),
(4,5, DATE_ADD(NOW(), INTERVAL 6 DAY), DATE_ADD(NOW(), INTERVAL 6 DAY + INTERVAL 4 HOUR), 4, 3, 'Alice Admin','admin@example.com','0102030405', 1),
(5,1, DATE_ADD(NOW(), INTERVAL 8 DAY), DATE_ADD(NOW(), INTERVAL 8 DAY + INTERVAL 5 HOUR), 2, 1, 'Bob User','user@example.com','0601020304', 2);
