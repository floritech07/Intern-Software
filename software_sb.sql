-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 30 oct. 2024 à 09:39
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `software.sb`
--

-- --------------------------------------------------------

--
-- Structure de la table `logiciels`
--

DROP TABLE IF EXISTS `logiciels`;
CREATE TABLE IF NOT EXISTS `logiciels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `lien` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `documentation` varchar(255) DEFAULT NULL,
  `version_actuelle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `logiciels`
--

INSERT INTO `logiciels` (`id`, `nom`, `image`, `description`, `lien`, `user_id`, `created_at`, `documentation`, `version_actuelle`) VALUES
(17, 'Visual Studio Code ', 'uploads/1728287929_communityIcon_2f7hhwfs5pk31.jpg', 'Visual Studio Code, also commonly referred to as VS Code, is an integrated development environment developed by Microsoft for Windows, Linux, macOS and web browsers.', 'https://code.visualstudio.com/', 1, '2024-10-07 07:58:49', NULL, NULL),
(18, 'Pycharm', 'uploads/1728288433_PyCharmCore256.jpg', 'PyCharm est un environnement de développement intégré utilisé pour programmer en Python. Il permet l&#039;analyse de code et contient un débogueur graphique.', 'https://www.jetbrains.com/pycharm/', 1, '2024-10-07 08:07:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `mises_a_jour`
--

DROP TABLE IF EXISTS `mises_a_jour`;
CREATE TABLE IF NOT EXISTS `mises_a_jour` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_logiciel` int NOT NULL,
  `version` varchar(255) NOT NULL,
  `fonctionnalites` text NOT NULL,
  `date_maj` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_logiciel` (`id_logiciel`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `mises_a_jour`
--

INSERT INTO `mises_a_jour` (`id`, `id_logiciel`, `version`, `fonctionnalites`, `date_maj`) VALUES
(1, 2, '1.0.0', 'Extra', '2024-10-08 09:38:09'),
(2, 18, '1.0.0', 'sd', '2024-10-08 09:44:33');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `photo_profil` varchar(255) DEFAULT 'default_profile.png',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `photo_profil`) VALUES
(1, 'floritech_dev_07', 'raoulchakoun@gmail.com', '$2y$10$SzkdWyvYev2Ut/HrV7LWJeHyW2poDlyGfl9qt/uXe7CMUH9tzITAq', '2024-10-03 06:57:47', 'uploads/1700067493661_1.jpg'),
(2, 'ferel10', 'marcflorianchakoun@gmail.com', '$2y$10$NejIX2rFHp4lcsZ0xTBiNu1XFJ2RQNAxMgN68De.F4jfPXDJFnqlq', '2024-10-03 06:59:36', 'default_profile.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
