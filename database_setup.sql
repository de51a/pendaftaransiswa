-- File Skrip Database untuk Pendaftaran Siswa
-- Database: db_siswa

CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan user admin default (Username: admin, Password: admin123)
INSERT INTO `tb_users` (`username`, `password`, `nama_lengkap`) 
SELECT 'admin', '$2y$10$qC5Yd/02Hl2h3W8m1O.a7.hR6J/Y8D3Z8/9.wX8m1O.a7.hR6J/Y8', 'Administrator'
WHERE NOT EXISTS (SELECT * FROM `tb_users` WHERE `username` = 'admin');
