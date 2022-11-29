DROP DATABASE IF EXISTS `messenger`;
CREATE DATABASE `messenger`;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `messages_ibfk_1` (`sender`),
  KEY `messages_ibfk_2` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `resetpasswords`;
CREATE TABLE IF NOT EXISTS `resetpasswords` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `token` varchar(255) UNIQUE NOT NULL,
  `user` int(11) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `resetpasswords_ibfk_1` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `mail` varchar(200) UNIQUE NOT NULL,
  `pseudo` varchar(50) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'images/profile/default_pp.png',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`);

ALTER TABLE `resetpasswords`
  ADD CONSTRAINT `resetpasswords_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
