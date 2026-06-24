-- ============================================================
-- Database: tapak_lampung
-- Platform: Tapak Lampung — Pariwisata Lampung
-- Koneksi: MySQL (XAMPP) via unix_socket
-- Update: 2026-05-20
-- ============================================================

CREATE DATABASE IF NOT EXISTS tapak_lampung CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tapak_lampung;

-- ============================================================
-- DROP TABLES (urutan dependensi)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `trip_tags`;
DROP TABLE IF EXISTS `restaurants`;
DROP TABLE IF EXISTS `trips`;
DROP TABLE IF EXISTS `culinaries`;
DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. users
-- ============================================================
CREATE TABLE `users` (
    `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`              VARCHAR(255) NOT NULL,
    `email`             VARCHAR(255) UNIQUE NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password`          VARCHAR(255) NOT NULL,
    `role`              VARCHAR(50) DEFAULT 'user' COMMENT 'admin, organizer, user',
    `is_admin`          TINYINT(1) DEFAULT 0,
    `remember_token`    VARCHAR(100) NULL DEFAULT NULL,
    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. destinations (Hidden Gems)
-- ============================================================
CREATE TABLE `destinations` (
    `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`         VARCHAR(255) NOT NULL,
    `location`     VARCHAR(255) NOT NULL,
    `description`  TEXT NOT NULL,
    `image_path`   VARCHAR(255) NOT NULL,
    `label`        VARCHAR(50) DEFAULT 'Hidden Gem' COMMENT 'Hidden Gem, Populer, Surfing',
    `rating`       DECIMAL(3,2) DEFAULT 0.00,
    `likes_count`  INT UNSIGNED DEFAULT 0,
    `category`     VARCHAR(50) NOT NULL COMMENT 'Pantai, Teluk, Air Terjun, Danau, Pulau',
    `distance_km`  VARCHAR(100) DEFAULT NULL,
    `travel_time`  VARCHAR(100) DEFAULT NULL,
    `entrance_fee` VARCHAR(100) DEFAULT NULL,
    `best_time`    VARCHAR(100) DEFAULT NULL,
    `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_destination_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. trips (Open Trip)
-- ============================================================
CREATE TABLE `trips` (
    `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`            VARCHAR(255) NOT NULL,
    `description`     TEXT NOT NULL,
    `image_path`      VARCHAR(255) NOT NULL,
    `organizer_name`  VARCHAR(255) NOT NULL,
    `organizer_avatar`VARCHAR(10) NOT NULL COMMENT 'Initials e.g. DL, BW',
    `schedule_date`   DATE NOT NULL,
    `duration`        VARCHAR(100) NOT NULL,
    `current_quota`   INT UNSIGNED DEFAULT 0,
    `max_quota`       INT UNSIGNED NOT NULL,
    `rating`          DECIMAL(3,2) DEFAULT 0.00,
    `reviews_count`   INT UNSIGNED DEFAULT 0,
    `price`           DECIMAL(12,2) NOT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_trip_schedule` (`schedule_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. trip_tags
-- ============================================================
CREATE TABLE `trip_tags` (
    `id`      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `trip_id` BIGINT UNSIGNED NOT NULL,
    `tag`     VARCHAR(100) NOT NULL,
    FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. culinaries (Kuliner Khas)
-- ============================================================
CREATE TABLE `culinaries` (
    `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `category`    VARCHAR(50) NOT NULL COMMENT 'Makanan, Minuman, Camilan',
    `image_path`  VARCHAR(255) NOT NULL,
    `spice_level` TINYINT UNSIGNED DEFAULT 0,
    `outlet_count`INT UNSIGNED DEFAULT 0,
    `outlet_type` VARCHAR(50) DEFAULT 'warung',
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_culinary_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. bookings
-- ============================================================
CREATE TABLE `bookings` (
    `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`           BIGINT UNSIGNED NULL,
    `trip_id`           BIGINT UNSIGNED NOT NULL,
    `booking_date`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `participants_count`INT UNSIGNED NOT NULL DEFAULT 1,
    `total_price`       DECIMAL(12,2) NOT NULL,
    `status`            VARCHAR(50) DEFAULT 'pending' COMMENT 'pending, paid, cancelled',
    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SEED DATA
-- ============================================================

-- users (password = 'password' bcrypt)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_admin`) VALUES
(1, 'Admin Tapak Lampung',   'admin@tapaklampung.com',      '$2y$12$R6aYw.b7rPszh314aN4jEObUo3w7VeeMepPecp1gG3V.zP2R48hKK', 'admin',     1),
(2, 'Dewi Trip Organizer',   'dewitrip@tapaklampung.com',   '$2y$12$R6aYw.b7rPszh314aN4jEObUo3w7VeeMepPecp1gG3V.zP2R48hKK', 'organizer', 1),
(3, 'Bumi Wisata Organizer', 'bumiwisata@tapaklampung.com', '$2y$12$R6aYw.b7rPszh314aN4jEObUo3w7VeeMepPecp1gG3V.zP2R48hKK', 'organizer', 0),
(4, 'Krui Surf Organizer',   'kruisurf@tapaklampung.com',   '$2y$12$R6aYw.b7rPszh314aN4jEObUo3w7VeeMepPecp1gG3V.zP2R48hKK', 'organizer', 0);

-- destinations (Hidden Gems)
INSERT INTO `destinations` (`id`, `name`, `location`, `description`, `image_path`, `label`, `rating`, `likes_count`, `category`, `distance_km`, `travel_time`, `entrance_fee`, `best_time`) VALUES
(1, 'Pulau Pahawang Kecil',  'Pesawaran, Lampung',      'Pulau privat dengan pasir putih halus dan spot snorkeling terbaik. Terumbu karang masih sangat terjaga dengan visibility hingga 15 meter.',   'images/wisata/pulau_pahawang_kecil.jpg',   'Hidden Gem', 4.90, 847, 'Pantai',    '25 km (darat) + 30 mnt perahu', '1.5 - 2 Jam', 'Gratis (Sewa kapal Rp 500k-800k)', 'April - September'),
(2, 'Teluk Kiluan',          'Tanggamus, Lampung',      'Spot terbaik melihat lumba-lumba di habitat asli. Perahu tradisional membawa Anda ke tengah teluk saat matahari terbit.',                       'images/wisata/teluk_kiluan.jpeg',          'Populer',    4.80, 623, 'Teluk',     '80 km',                         '3 - 4 Jam',   'Rp 10.000 (Sewa Jukung Rp 350k)',  'Mei - Oktober'),
(3, 'Pantai Mandiri Krui',   'Pesisir Barat, Lampung',  'Ombak konsisten sepanjang tahun, surga bagi surfer. Pantai landai dengan pemandangan sunset spektakuler.',                                      'images/wisata/pantai_mandiri_krui.jpg',    'Surfing',    4.70, 512, 'Pantai',    '250 km',                        '5 - 6 Jam',   'Rp 5.000',                         'Juni - September'),
(4, 'Air Terjun Way Lalaan', 'Lampung Barat, Lampung',  'Air terjun bertingkat di tengah hutan tropis. Air jernih kehijauan dengan kolam alami di setiap tingkatnya.',                                  'images/wisata/air_terjun_waylalaan.jpg',   'Hidden Gem', 4.60, 389, 'Air Terjun','85 km',                         '2 Jam',       'Rp 10.000',                        'November - Februari'),
(5, 'Danau Ranau',           'Lampung Selatan, Lampung', 'Danau vulkanik terbesar kedua di Sumatera. Dikelilingi pegunungan dengan air terjun dan pemandian air panas alami.',                           'images/wisata/danau_ranau.jpeg',           'Populer',    4.80, 756, 'Danau',     '280 km',                        '6 - 7 Jam',   'Rp 10.000',                        'Juni - September'),
(6, 'Pulau Kelapa',          'Pesawaran, Lampung',      'Pulau karang kecil nan eksotis yang dikenal dengan nama Pulau Kelapa. Destinasi menawan dengan pemandangan karang menjulang tinggi.',           'images/wisata/pulau_kelapa.webp',          'Hidden Gem', 4.80, 420, 'Pulau',     '30 km (darat) + 45 mnt perahu', '2.5 Jam',     'Rp 15.000',                        'Maret - Agustus');

-- trips (Open Trip)
INSERT INTO `trips` (`id`, `name`, `description`, `image_path`, `organizer_name`, `organizer_avatar`, `schedule_date`, `duration`, `current_quota`, `max_quota`, `rating`, `reviews_count`, `price`) VALUES
(1, 'Pahawang & Kelagian 3D2N',    'Jelajahi 5 pulau cantik di gugusan Pahawang. Snorkeling, camping di pantai, dan BBQ seafood.',          'images/wisata/open_trip_pahawang.jpg', 'Dewi Trip',  'DL', '2026-07-03', '3 Hari 2 Malam', 8, 20, 4.90, 127, 485000.00),
(2, 'Kiluan Dolphin Watching 2D1N','Melihat ratusan lumba-lumba di Teluk Kiluan. Termasuk island hopping dan snorkeling.',                  'images/wisata/open_trip_kiluan.jpg',  'Bumi Wisata','BW', '2026-07-10', '2 Hari 1 Malam', 5, 15, 4.80,  89, 350000.00),
(3, 'Krui Surf & Explore 4D3N',    'Belajar surfing di ombak Krui, explore pantai tersembunyi, dan nikmati kopi Lampung asli.',             'images/wisata/open_trip_krui.jpg',    'Krui Surf',  'KS', '2026-07-17', '4 Hari 3 Malam', 3, 12, 4.70,  56, 750000.00);

-- trip_tags
INSERT INTO `trip_tags` (`trip_id`, `tag`) VALUES
(1, 'Snorkeling'), (1, '3D2N'),
(2, 'Dolphin'),    (2, '2D1N'),
(3, 'Surfing'),    (3, '4D3N');

-- culinaries
INSERT INTO `culinaries` (`id`, `name`, `description`, `category`, `image_path`, `spice_level`, `outlet_count`, `outlet_type`) VALUES
(1, 'Seruit',        'Ikan bakar dengan sambal tempoyak fermentasi durian. Hidangan ikonik Lampung.',                    'Makanan',  'images/kuliner/seruit.jpeg',       4, 12, 'warung'),
(2, 'Pindang Lampung','Sup ikan asam pedas khas Lampung dengan irisan nanas dan cabai rawit segar.',                    'Makanan',  'images/kuliner/pindang_lampung.jpeg',      5, 18, 'warung'),
(3, 'Kopi Lampung',  'Robusta dengan body penuh dan aftertaste cokelat. Nikmati langsung di perkebunan.',               'Minuman',  'images/kuliner/kopi_lampung.jpeg', 1, 25, 'kafe'),
(4, 'Gulai Taboh',   'Gulai ikan kuah santan kuning dengan rempah khas. Hidangan tradisional adat Lampung.',            'Makanan',  'images/kuliner/gulai_taboh.jpeg',  3,  8, 'warung');

-- bookings (contoh data)
INSERT INTO `bookings` (`id`, `user_id`, `trip_id`, `participants_count`, `total_price`, `status`) VALUES
(1, 1, 1, 2, 970000.00,  'paid'),
(2, 1, 3, 1, 750000.00,  'paid');

-- ============================================================
-- 7. restaurants
-- ============================================================
DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE `restaurants` (
    `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `culinary_id`    BIGINT UNSIGNED NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `address`        VARCHAR(500) NOT NULL,
    `district`       VARCHAR(100) NOT NULL,
    `phone`          VARCHAR(30) DEFAULT NULL,
    `open_time`      VARCHAR(10) DEFAULT '08:00',
    `close_time`     VARCHAR(10) DEFAULT '21:00',
    `open_days`      VARCHAR(100) DEFAULT 'Senin - Minggu',
    `price_range`    VARCHAR(50) DEFAULT 'Rp 15.000 - Rp 40.000',
    `rating`         DECIMAL(3,2) DEFAULT 4.50,
    `reviews_count`  INT UNSIGNED DEFAULT 0,
    `description`    TEXT DEFAULT NULL,
    `image_path`     VARCHAR(255) DEFAULT NULL,
    `maps_url`       VARCHAR(500) DEFAULT NULL,
    `is_open`        TINYINT(1) DEFAULT 1,
    `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`culinary_id`) REFERENCES `culinaries`(`id`) ON DELETE CASCADE,
    INDEX `idx_resto_culinary` (`culinary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `restaurants` (`culinary_id`,`name`,`address`,`district`,`phone`,`open_time`,`close_time`,`open_days`,`price_range`,`rating`,`reviews_count`,`description`,`image_path`,`maps_url`) VALUES
(1,'Warung Bu Atun','Jl. Raden Intan No. 45, Tanjung Karang','Bandar Lampung','0812-7890-1234','09:00','21:00','Senin - Sabtu','Rp 20.000 - Rp 45.000',4.80,234,'Warung legendaris yang sudah berdiri sejak 1985. Seruit Bu Atun terkenal dengan sambal tempoyak yang kaya rasa dan ikan bakar yang selalu segar.','images/kuliner/seruit.jpeg','https://maps.google.com'),
(1,'Rumah Makan Muli Lampung','Jl. Kartini No. 12, Enggal','Bandar Lampung','0721-252-888','10:00','22:00','Senin - Minggu','Rp 25.000 - Rp 60.000',4.70,189,'Restoran modern dengan nuansa adat Lampung. Menyajikan Seruit dengan berbagai pilihan ikan.','images/kuliner/seruit.jpeg','https://maps.google.com'),
(1,'Kedai Seruit Pak Hamid','Jl. Soekarno-Hatta KM 8, Way Halim','Bandar Lampung','0856-6789-0123','11:00','20:00','Selasa - Minggu','Rp 18.000 - Rp 35.000',4.60,97,'Kedai sederhana dengan cita rasa otentik. Tempoyak buatan sendiri setiap hari.','images/kuliner/seruit.jpeg','https://maps.google.com'),
(2,'Pindang 88 Teluk Betung','Jl. Laksamana Malahayati No. 3, Teluk Betung','Bandar Lampung','0721-482-555','08:00','17:00','Senin - Sabtu','Rp 15.000 - Rp 30.000',4.90,412,'Warung pindang paling ikonik. Kaldu ikannya dimasak sejak subuh dengan rempah segar.','images/kuliner/pindang_lampung.jpeg','https://maps.google.com'),
(2,'RM Pindang Mba Yuni','Jl. Pulau Sebesi No. 14, Sukarame','Bandar Lampung','0812-3456-7890','09:00','16:00','Senin - Minggu','Rp 12.000 - Rp 25.000',4.70,278,'Pindang dengan kuah asam yang segar dan nanas lokal.','images/kuliner/pindang_lampung.jpeg','https://maps.google.com'),
(2,'Depot Pindang Pesisir','Jl. Yos Sudarso No. 67, Panjang','Bandar Lampung','0721-895-321','07:00','15:00','Senin - Sabtu','Rp 10.000 - Rp 20.000',4.50,156,'Dekat pelabuhan dengan ikan super segar. Harga paling terjangkau.','images/kuliner/pindang_lampung.jpeg','https://maps.google.com'),
(3,'Kopi Joss Lampung','Jl. Teuku Umar No. 101, Kedaton','Bandar Lampung','0812-9087-6543','06:00','23:00','Senin - Minggu','Rp 8.000 - Rp 25.000',4.80,523,'Kafe modern dengan biji kopi Robusta pilihan langsung dari petani Lampung Barat.','images/kuliner/kopi_lampung.jpeg','https://maps.google.com'),
(3,'Kedai Kopi Pak Mulyadi','Jl. Gajah Mada No. 22, Tanjung Gading','Bandar Lampung','0721-334-212','05:30','12:00','Senin - Minggu','Rp 5.000 - Rp 15.000',4.90,871,'Warung kopi tertua di Bandar Lampung, berdiri sejak 1972.','images/kuliner/kopi_lampung.jpeg','https://maps.google.com'),
(3,'Lampung Coffee Roastery','Jl. Wolter Monginsidi No. 55, Teluk Betung Selatan','Bandar Lampung','0812-1122-3344','07:00','22:00','Senin - Minggu','Rp 20.000 - Rp 45.000',4.70,334,'Roastery premium dengan fasilitas cicip kopi gratis.','images/kuliner/kopi_lampung.jpeg','https://maps.google.com'),
(4,'Warung Taboh Ibu Rohimah','Jl. P. Diponegoro No. 9, Rajabasa','Bandar Lampung','0856-1234-5678','10:00','20:00','Selasa - Minggu','Rp 20.000 - Rp 40.000',4.80,143,'Gulai Taboh yang dimasak dengan resep turun-temurun dari Way Kanan.','images/kuliner/gulai_taboh.jpeg','https://maps.google.com'),
(4,'RM Adat Lampung Jaya','Jl. Ahmad Yani No. 30, Tanjung Karang Pusat','Bandar Lampung','0721-261-900','09:00','21:00','Senin - Minggu','Rp 25.000 - Rp 55.000',4.70,201,'Restoran bernuansa adat Lampung. Gulai Taboh menjadi menu andalan.','images/kuliner/gulai_taboh.jpeg','https://maps.google.com'),
(4,'Dapur Nenek Lampung','Jl. Pangeran Antasari No. 77, Labuhan Ratu','Bandar Lampung','0812-5544-6677','11:00','20:00','Senin - Sabtu','Rp 18.000 - Rp 35.000',4.60,88,'Suasana seperti makan di rumah nenek. Semua masakan dimasak dengan kayu bakar.','images/kuliner/gulai_taboh.jpeg','https://maps.google.com');
