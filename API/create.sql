-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 24 nov. 2022 à 18:18
-- Version du serveur : 5.7.36
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `messenger`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `messages_ibfk_1` (`sender`),
  KEY `messages_ibfk_2` (`receiver`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `content`, `type`, `is_read`, `created_at`) VALUES
(1, 6, 1, 'salut cv ?', 'text', 0, '2022-11-21 17:13:41'),
(3, 6, 1, 'salut cv ?', 'text', 0, '2022-11-21 17:17:33'),
(4, 6, 4, 'salut cv ?', 'text', 0, '2022-11-21 18:10:24'),
(5, 6, 4, 'salut cv ?', 'text', 0, '2022-11-21 18:25:09'),
(6, 1, 6, 'voila voila', 'text', 1, '2022-11-21 19:47:03'),
(7, 6, 4, 'salut cv ?', 'text', 0, '2022-11-21 21:55:22'),
(9, 6, 1, 'salut cvv ?', 'text', 0, '2022-11-21 22:13:17'),
(11, 5, 1, 'test', 'text', 0, '2022-11-21 22:30:37'),
(15, 6, 1, 'images/conversations/roman_903091.png', 'image', 0, '2022-11-21 23:39:28'),
(16, 6, 5, 'salut', 'text', 0, '2022-11-21 23:42:04');

-- --------------------------------------------------------

--
-- Structure de la table `resetpasswords`
--

DROP TABLE IF EXISTS `resetpasswords`;
CREATE TABLE IF NOT EXISTS `resetpasswords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `resetpasswords_ibfk_1` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `resetpasswords`
--

INSERT INTO `resetpasswords` (`id`, `token`, `user`, `is_used`, `created_at`) VALUES
(40, 'cfa98c05a289735a556373fe74d4bfc47346351ffecf24eae9c8dfdd4a950216cb91f8', 6, 1, '2022-11-24 14:18:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(200) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'images/profile/default_pp.png',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail_user` (`mail`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `mail`, `pseudo`, `password`, `image`, `created_at`) VALUES
(1, 'test@test.test', 'test', '$2y$10$wy7QvRe0iJFgYT.nP7DHcebDa98S4nGttx6g9JFBgP51JCXkU2vMG', 'images/profile/default_pp.png', '2022-11-21 11:17:01'),
(4, 'test2@test.test', 'test2', '$2y$10$xdhidUEMKtVR49jYf8w5ZeTiVzJiopZuh3mlfVYLZWYuOJiDnap96', 'images/profile/default_pp.png', '2022-11-21 11:19:37'),
(5, 'test22@test.test', 'ok', '$2y$10$7Jv5BH3fXBYLw0vkttRuxO.LxxEg5EfO4ApHJsviBfCfwxz3xxN7m', 'images/profile/default_pp.png', '2022-11-21 12:14:05'),
(6, 'kastler.roman.dev@gmail.com', 'roman', '$2y$10$UIJzw./6OzZF.Yng0ZVYJ.loUYVDrKab/Hb14JTDIsrPzRZvhUwLm', 'images/profile/roman_560385.png', '2022-11-21 12:28:49'),
(7, 'testahah@test.test', 'okok', '$2y$10$3kyjEsk6EdGpdcquh0TTYupThXXa.l92eozYzHE6r8Cv/nWq4ucw6', 'images/profile/default_pp.png', '2022-11-21 13:05:05'),
(8, 'teserztartth@test.test', 'okrzrottk', '$2y$10$YoCPNfNBmIeHBcVoTxNbHex2jNYiZ7LiRShGpIiJjSosgI72pg8uC', 'images/profile/default_pp.png', '2022-11-21 14:44:26'),
(23, 'testerztruyaryrrtth@test.test', 'okrztyrroryttk', '$2y$10$O5Ev9aumLNJXE4HYPLr.VuqIJ6b..3y9Pz0GT.jNfhnoL4OJyAvom', 'images/profile/default_pp.png', '2022-11-22 17:17:42'),
(24, 'testerztruryaryrrtth@test.test', 'okrzrtyrroryttk', '$2y$10$88XDNxs/vc0Rt05NmyHCBO21kluPEDiu7paFwfFW7S387nhBt/RkC', 'images/profile/default_pp.png', '2022-11-22 17:18:21'),
(25, 'testzrfe@zeze.fr', 'fzezfg', '$2y$10$ZQ5qRsWu5RB3eyw1UOIbSOv8j9/x5m8YbElWyLwPNxEwPx8zbgmwy', 'images/profile/default_pp.png', '2022-11-22 18:20:39'),
(26, 'rzrzrr@zrrzzr.fr', 'zrzr', '$2y$10$UyhE2cMekknUBQqmxM3qP.SL1eHrlubzdfdpm6dHLDE21tuOOyib.', 'images/profile/default_pp.png', '2022-11-22 18:25:22'),
(27, 'zzgegz@egzzgz.frz', 'eazeazfge', '$2y$10$r76cQCiAwNhMad5MNrv2huJs8YjPuX9tnCbqFoWYrdl1E21e0mwQW', 'images/profile/default_pp.png', '2022-11-22 18:25:53'),
(28, 'fzezeg@egzg.frz', 'testfzegzeg', '$2y$10$hfnkeiQ0k3KOP5UO6xzXseHdw6qRv4TSfqdPkFoqh7lA17um3KDu6', 'images/profile/default_pp.png', '2022-11-22 18:44:10'),
(29, 'ezzezeh@ezhzeh.fr', 'rezaegfze', '$2y$10$XPtKLdKnnF6rHjiEsgJ8C.OYcB3UeOolZOBahaU/VpIedRv.vSUo2', 'images/profile/default_pp.png', '2022-11-22 18:55:02'),
(30, 'zrr@zrrz.fr', 'zr', '$2y$10$fYoYKvWYYfwbF0Cw5r.zqOtuRAmlxNFIBUv2z34V7o1gRvuxPrNTi', 'images/profile/default_pp.png', '2022-11-23 16:34:51');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `resetpasswords`
--
ALTER TABLE `resetpasswords`
  ADD CONSTRAINT `resetpasswords_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
