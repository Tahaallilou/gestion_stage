-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 03 nov. 2025 à 13:32
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `we_ride`
--

-- --------------------------------------------------------

--
-- Structure de la table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_model` varchar(100) DEFAULT NULL,
  `license_plate` varchar(20) DEFAULT NULL,
  `car_color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `car_model`, `license_plate`, `car_color`) VALUES
(1, 1, 'Dacia Logan', '12345-A-56', 'Blanc'),
(2, 3, 'Renault Clio', '67890-B-12', 'Gris'),
(3, 7, 'Ford Focus', 'T 10000', 'bleu'),
(4, 4, 'Volkswagen Golf', '45678-D-4', 'Noir'),
(5, 5, 'Hyundai i20', '56789-E-5', 'Rouge'),
(6, 6, 'Toyota Yaris', '67890-F-6', 'Vert');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `trajet_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `seats_booked` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'confirmed',
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `trajet_id`, `passenger_id`, `seats_booked`, `status`, `booked_at`) VALUES
(5, 217, 7, 1, 'cancelled', '2025-11-03 00:50:50'),
(6, 196, 7, 1, 'cancelled', '2025-11-03 01:42:36'),
(7, 212, 7, 1, 'cancelled', '2025-11-03 01:44:14'),
(8, 244, 7, 1, 'cancelled', '2025-11-03 02:21:47'),
(9, 196, 7, 1, 'cancelled', '2025-11-03 11:39:14'),
(10, 243, 7, 4, 'confirmed', '2025-11-03 12:16:18');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `trajet_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewed_user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `trajet_id`, `reviewer_id`, `reviewed_user_id`, `rating`, `comment`, `created_at`) VALUES
(2, 217, 7, 5, 5, 'EXCELLENT', '2025-11-03 00:51:09'),
(3, 196, 7, 3, 5, 'test 1', '2025-11-03 01:47:08');

-- --------------------------------------------------------

--
-- Structure de la table `trajets`
--

CREATE TABLE `trajets` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `departure_address` varchar(255) NOT NULL,
  `arrival_address` varchar(255) NOT NULL,
  `departure_datetime` datetime NOT NULL,
  `price_per_seat` decimal(6,2) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `meeting_point` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('planned','completed','cancelled') DEFAULT 'planned',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajets`
--

INSERT INTO `trajets` (`id`, `driver_id`, `departure_address`, `arrival_address`, `departure_datetime`, `price_per_seat`, `available_seats`, `meeting_point`, `notes`, `status`, `created_at`) VALUES
(184, 1, 'Salé', 'Rabat', '2025-11-04 01:07:31', 22.00, 3, 'Mosquée Tabriquet', 'Trajet direct', '', '2025-11-03 00:07:31'),
(185, 2, 'Salé', 'Rabat', '2025-11-04 01:07:31', 22.00, 4, 'Boulangerie Hay Karima', 'Climatisé', '', '2025-11-03 00:07:31'),
(186, 3, 'Kénitra', 'Rabat', '2025-11-05 01:07:31', 35.00, 3, 'Place administrative', 'Trajet quotidien', '', '2025-11-03 00:07:31'),
(187, 4, 'Kénitra', 'Rabat', '2025-11-05 01:07:31', 35.00, 2, 'Faculté Kénitra', 'Étudiants', '', '2025-11-03 00:07:31'),
(188, 5, 'Skhirat', 'Rabat', '2025-11-06 01:07:31', 10.00, 4, 'Marché Skhirat', 'Trajet court', '', '2025-11-03 00:07:31'),
(189, 6, 'Témara', 'Rabat', '2025-11-06 01:07:31', 10.00, 3, 'Place Témara', 'Matinée', '', '2025-11-03 00:07:31'),
(190, 1, 'Salé', 'Rabat', '2025-11-07 01:07:31', 22.00, 2, 'Café Bettana', 'Pause déjeuner', '', '2025-11-03 00:07:31'),
(191, 2, 'Salé', 'Rabat', '2025-11-08 01:07:31', 22.00, 3, 'Arrêt de bus Salé', 'Après-midi', '', '2025-11-03 00:07:31'),
(192, 1, 'Salé', 'CMC Rabat', '2025-11-04 07:30:00', 22.00, 3, 'Mosquée Tabriquet', 'Trajet direct matinal', '', '2025-11-03 00:24:15'),
(193, 2, 'Salé', 'CMC Rabat', '2025-11-04 08:15:00', 22.00, 4, 'Boulangerie Hay Karima', 'Climatisé', '', '2025-11-03 00:24:15'),
(194, 1, 'Salé', 'CMC Rabat', '2025-11-05 07:30:00', 22.00, 2, 'Café Bettana', 'Pause café possible', '', '2025-11-03 00:24:15'),
(195, 2, 'Salé', 'CMC Rabat', '2025-11-05 10:00:00', 22.00, 3, 'Arrêt de bus Salé', 'Flexible', '', '2025-11-03 00:24:15'),
(196, 3, 'Kénitra', 'CMC Rabat', '2025-11-04 06:45:00', 35.00, 3, 'Place administrative', 'Départ tôt', '', '2025-11-03 00:24:15'),
(197, 4, 'Kénitra', 'CMC Rabat', '2025-11-04 07:30:00', 35.00, 2, 'Faculté de Kénitra', 'Étudiants bienvenus', '', '2025-11-03 00:24:15'),
(198, 3, 'Kénitra', 'CMC Rabat', '2025-11-05 08:00:00', 35.00, 4, 'Mosquée Saknia', 'Confort garanti', '', '2025-11-03 00:24:15'),
(199, 4, 'Kénitra', 'CMC Rabat', '2025-11-06 07:15:00', 35.00, 3, 'Plage Mehdia', 'Départ plage', '', '2025-11-03 00:24:15'),
(200, 5, 'Témara', 'CMC Rabat', '2025-11-04 08:30:00', 10.00, 3, 'Place Témara', 'Trajet express', '', '2025-11-03 00:24:15'),
(201, 6, 'Témara', 'CMC Rabat', '2025-11-05 09:15:00', 10.00, 4, 'Mosquée Hay Nahda', 'Propre et confortable', '', '2025-11-03 00:24:15'),
(202, 5, 'Témara', 'CMC Rabat', '2025-11-06 08:00:00', 10.00, 2, 'Centre Témara', 'Direct', '', '2025-11-03 00:24:15'),
(203, 6, 'Skhirat', 'CMC Rabat', '2025-11-04 07:45:00', 10.00, 2, 'Marché Skhirat', 'Départ centre ville', '', '2025-11-03 00:24:15'),
(204, 5, 'Skhirat', 'CMC Rabat', '2025-11-05 08:30:00', 10.00, 3, 'Station balnéaire', 'Vue mer', '', '2025-11-03 00:24:15'),
(205, 6, 'Skhirat', 'CMC Rabat', '2025-11-07 07:30:00', 10.00, 4, 'Plage Skhirat', 'Air marin', '', '2025-11-03 00:24:15'),
(206, 1, 'Rabat', 'CMC Rabat', '2025-11-04 08:00:00', 18.00, 3, 'Place Mohammed V', 'Centre ville', '', '2025-11-03 00:24:15'),
(207, 2, 'Rabat', 'CMC Rabat', '2025-11-05 08:30:00', 18.00, 4, 'Faculté des Sciences', 'Quartier Agdal', '', '2025-11-03 00:24:15'),
(208, 3, 'Rabat', 'CMC Rabat', '2025-11-06 09:00:00', 18.00, 2, 'Centre commercial Hay Riad', 'Zone résidentielle', '', '2025-11-03 00:24:15'),
(209, 1, 'CMC Rabat', 'Salé', '2025-11-04 17:30:00', 22.00, 3, 'Entrée principale CMC', 'Retour soirée', '', '2025-11-03 00:24:15'),
(210, 2, 'CMC Rabat', 'Salé', '2025-11-04 18:15:00', 22.00, 4, 'Parking CMC', 'Fin de journée', '', '2025-11-03 00:24:15'),
(211, 1, 'CMC Rabat', 'Salé', '2025-11-05 17:30:00', 22.00, 2, 'Sortie CMC', 'Direct', '', '2025-11-03 00:24:15'),
(212, 3, 'CMC Rabat', 'Kénitra', '2025-11-04 16:45:00', 35.00, 3, 'Entrée CMC', 'Retour Kénitra', '', '2025-11-03 00:24:15'),
(213, 4, 'CMC Rabat', 'Kénitra', '2025-11-05 17:30:00', 35.00, 2, 'Parking CMC', 'Étudiants', '', '2025-11-03 00:24:15'),
(214, 3, 'CMC Rabat', 'Kénitra', '2025-11-06 18:00:00', 35.00, 4, 'Sortie CMC', 'Confortable', '', '2025-11-03 00:24:15'),
(215, 5, 'CMC Rabat', 'Témara', '2025-11-04 18:30:00', 10.00, 3, 'Entrée principale CMC', 'Retour Témara', '', '2025-11-03 00:24:15'),
(216, 6, 'CMC Rabat', 'Témara', '2025-11-05 19:15:00', 10.00, 4, 'Parking CMC', 'Soirée', '', '2025-11-03 00:24:15'),
(217, 5, 'CMC Rabat', 'Témara', '2025-11-07 18:00:00', 10.00, 2, 'Sortie CMC', 'Direct', '', '2025-11-03 00:24:15'),
(218, 6, 'CMC Rabat', 'Skhirat', '2025-11-04 17:45:00', 10.00, 2, 'Entrée CMC', 'Retour Skhirat', '', '2025-11-03 00:24:15'),
(219, 5, 'CMC Rabat', 'Skhirat', '2025-11-05 18:30:00', 10.00, 3, 'Parking CMC', 'Soirée plage', '', '2025-11-03 00:24:15'),
(220, 6, 'CMC Rabat', 'Skhirat', '2025-11-06 17:30:00', 10.00, 4, 'Sortie CMC', 'Détente', '', '2025-11-03 00:24:15'),
(221, 1, 'CMC Rabat', 'Rabat', '2025-11-04 18:00:00', 18.00, 3, 'Entrée principale CMC', 'Centre ville', '', '2025-11-03 00:24:15'),
(222, 2, 'CMC Rabat', 'Rabat', '2025-11-05 18:30:00', 18.00, 4, 'Parking CMC', 'Quartier Agdal', '', '2025-11-03 00:24:15'),
(223, 3, 'CMC Rabat', 'Rabat', '2025-11-06 19:00:00', 18.00, 2, 'Sortie CMC', 'Zone résidentielle', '', '2025-11-03 00:24:15'),
(224, 1, 'Salé', 'CMC Rabat', '2025-11-10 07:30:00', 22.00, 3, 'Mosquée Tabriquet', 'Lundi matin', '', '2025-11-03 00:24:15'),
(225, 3, 'Kénitra', 'CMC Rabat', '2025-11-11 07:30:00', 35.00, 2, 'Place administrative', 'Mardi travail', '', '2025-11-03 00:24:15'),
(226, 5, 'Témara', 'CMC Rabat', '2025-11-12 08:00:00', 10.00, 4, 'Place Témara', 'Mercredi', '', '2025-11-03 00:24:15'),
(227, 6, 'Skhirat', 'CMC Rabat', '2025-11-13 07:45:00', 10.00, 3, 'Station balnéaire', 'Jeudi', '', '2025-11-03 00:24:15'),
(228, 2, 'Salé', 'CMC Rabat', '2025-11-17 08:15:00', 22.00, 4, 'Boulangerie Hay Karima', 'Nouvelle semaine', '', '2025-11-03 00:24:15'),
(229, 4, 'Kénitra', 'CMC Rabat', '2025-11-18 07:30:00', 35.00, 3, 'Faculté Kénitra', 'Cours', '', '2025-11-03 00:24:15'),
(230, 5, 'Témara', 'CMC Rabat', '2025-11-19 08:30:00', 10.00, 2, 'Centre Témara', 'Direct', '', '2025-11-03 00:24:15'),
(231, 6, 'Skhirat', 'CMC Rabat', '2025-11-20 07:30:00', 10.00, 4, 'Marché Skhirat', 'Vendredi', '', '2025-11-03 00:24:15'),
(232, 1, 'Salé', 'CMC Rabat', '2025-11-24 07:30:00', 22.00, 3, 'Café Bettana', 'Début semaine', '', '2025-11-03 00:24:15'),
(233, 3, 'Kénitra', 'CMC Rabat', '2025-11-25 08:00:00', 35.00, 4, 'Mosquée Saknia', 'Mardi', '', '2025-11-03 00:24:15'),
(234, 5, 'Témara', 'CMC Rabat', '2025-11-26 09:15:00', 10.00, 3, 'Mosquée Hay Nahda', 'Mercredi', '', '2025-11-03 00:24:15'),
(235, 6, 'Skhirat', 'CMC Rabat', '2025-11-27 07:45:00', 10.00, 2, 'Plage Skhirat', 'Jeudi', '', '2025-11-03 00:24:15'),
(236, 2, 'CMC Rabat', 'Salé', '2025-11-24 17:30:00', 22.00, 4, 'Parking CMC', 'Retour lundi', '', '2025-11-03 00:24:15'),
(237, 4, 'CMC Rabat', 'Kénitra', '2025-11-25 17:15:00', 35.00, 3, 'Sortie CMC', 'Retour mardi', '', '2025-11-03 00:24:15'),
(238, 5, 'CMC Rabat', 'Témara', '2025-11-26 18:30:00', 10.00, 4, 'Entrée CMC', 'Retour mercredi', '', '2025-11-03 00:24:15'),
(239, 6, 'CMC Rabat', 'Skhirat', '2025-11-27 18:30:00', 10.00, 3, 'Parking CMC', 'Retour jeudi', '', '2025-11-03 00:24:15'),
(240, 1, 'Salé', 'CMC Rabat', '2025-11-28 07:30:00', 22.00, 3, 'Mosquée Tabriquet', 'Avant weekend', '', '2025-11-03 00:24:15'),
(241, 3, 'Kénitra', 'CMC Rabat', '2025-11-29 08:00:00', 35.00, 2, 'Place administrative', 'Samedi travail', '', '2025-11-03 00:24:15'),
(242, 2, 'CMC Rabat', 'Salé', '2025-11-30 17:30:00', 22.00, 4, 'Sortie CMC', 'Dimanche soir', '', '2025-11-03 00:24:15'),
(243, 7, 'CMC Rabat', 'Salé', '2025-11-04 07:30:00', 22.00, 0, 'gare train', '', '', '2025-11-03 01:18:15'),
(244, 7, 'Témara', 'CMC Rabat', '2025-11-11 11:11:00', 10.00, 2, 'je sais pas ', '', '', '2025-11-03 01:29:13');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_type` enum('passenger','driver') NOT NULL,
  `rating_avg` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `user_type`, `rating_avg`, `created_at`) VALUES
(1, 'Ahmed', 'Benali', 'ahmed@test.com', 'password', '0612345678', 'driver', 0.00, '2025-11-02 17:18:48'),
(2, 'Fatima', 'Zahra', 'fatima@test.com', 'password', '0623456789', 'passenger', 0.00, '2025-11-02 17:18:48'),
(3, 'Mehdi', 'Alaoui', 'mehdi@test.com', 'password', '0634567890', 'driver', 5.00, '2025-11-02 17:18:48'),
(4, 'Khadija', 'Mansouri', 'khadija@test.com', 'password', '0645678901', 'passenger', 0.00, '2025-11-02 17:18:48'),
(5, 'Hassan', 'Mansouri', 'hassan@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0656789012', 'driver', 5.00, '2025-11-02 23:42:10'),
(6, 'passager', '', 'passager@gmail.com', 'password', '00 00 00 00 00', 'passenger', 0.00, '2025-11-02 17:44:20'),
(7, 'conducteur', 'test', 'conducteur@gmail.com', 'password', '0600000000', 'driver', 5.00, '2025-11-02 17:46:24');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trajet_id` (`trajet_id`),
  ADD KEY `passenger_id` (`passenger_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trajet_id` (`trajet_id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `reviewed_user_id` (`reviewed_user_id`);

--
-- Index pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `trajets`
--
ALTER TABLE `trajets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`trajet_id`) REFERENCES `trajets` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`trajet_id`) REFERENCES `trajets` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`reviewed_user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD CONSTRAINT `trajets_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
