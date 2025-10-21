-- SQL dump for my_raw_php_task
CREATE DATABASE IF NOT EXISTS `my_raw_php_task` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `my_raw_php_task`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample data
INSERT INTO `users` (`name`, `email`) VALUES
('Asif Mostofa', 'asif@example.com'),
('Test User', 'test@example.com');
