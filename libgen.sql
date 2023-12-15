-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2023 at 11:39 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libgen`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_uuid` varchar(100) NOT NULL DEFAULT uuid(),
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `cover` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `rent` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_uuid`, `title`, `author`, `description`, `cover`, `category_id`, `rent`, `creation_date`, `modification_date`) VALUES
(1, 'e8a3c70e-9b16-11ee-a2dd-87231b1e44fc', 'Three Men In A Boat', 'Jerome K. Jerome', 'Three Men In A Boat is a comic memoir of a boating holiday on the Thames between Kingston and Oxford by Jerome K. Jerome.\r\n\r\nSummary of the Book\r\n\r\nThree men decide that they have been overworked for quite some time and decide to take a uneventful boating holiday. George, Harris, Jerome and Montmorency, a fox terrier, set forth on a boating trip up the River Thames, from Kingston upon Thames to Oxford, during which they plan to camp, despite Jerome&#039;s admission about previous experiences wit', '657bf856eb6d6.webp', 1, 3, '2023-12-15 07:55:18', '2023-12-15 07:55:18'),
(2, 'a5b2c650-9b17-11ee-a2dd-87231b1e44fc', 'The Secret Garden', 'Frances Hodgson Burnett', 'The Secret Garden by Frances Hodgson Burnett is a timeless children’s classic that will transport readers to a magical world filled with mystery and wonder. Set in England during the early twentieth century, the story follows Mary Lennox, a young girl who is sent to live with her uncle in a gloomy and neglected mansion. With the help of a new friend and the discovery of a hidden garden, Mary learns the power of nature, friendship, and the importance of nurturing one’s own soul. The novel feature', '657bfb096490a.webp', 1, 3, '2023-12-15 08:00:36', '2023-12-15 08:06:49'),
(3, '6347310b-9b18-11ee-a2dd-87231b1e44fc', 'Srimad Bhagavad Gita As It Is', 'A. C. Bhaktivedanta Swami', 'The largest-selling latest edition of the Bhagavad-gita in Hindi, is knowledge of 5 basic truths and the relationship of each truth to the other: These five truths are Krishna, or God, the individual soul, the material world, action in this world, and time. In translating the Gita, A. C. Bhaktivedanta Swami Prabhupada has remained loyal to the intended meaning of Krishna&#039;s words, and has unlocked all the secrets of the ancient knowledge of the Gita and placed them before us as an exciting o', '657bfad22ff38.webp', 2, 1, '2023-12-15 08:05:54', '2023-12-15 08:05:54'),
(4, 'cc35526f-9b18-11ee-a2dd-87231b1e44fc', 'The Invisible Man', 'H.G. Wells', 'From the twentieth century&#039;s first great practitioner of the novel of ideas comes a consummate masterpiece of science fiction about a man trapped in the terror of his own creation. First published in 1897, The Invisible Man ranks as one of the most famous scientific fantasies ever written. Part of a series of pseudoscientific romances written by H. G. Wells (1866–1946) early in his career, the novel helped establish the British author as one of the first and best writers of science fiction.', '657bfb823c886.webp', 3, 3, '2023-12-15 08:08:50', '2023-12-15 08:08:50'),
(5, '6542b5de-9b19-11ee-a2dd-87231b1e44fc', 'The Mystery Box', 'Dessai Roma N', 'This is an extremely powerful and intriguing story of a young girl Sophia and her friends . Sophia and her friends visit her Farmhouse for a stay where they find a unique box. After they reach the farmhouse they learn many secrets which they were unaware of. Sophia’s parents embarks on a journey of mysteries behind the box which leads them to many surprises. An innocent mistake leads to behavioral changes in Sophia . Sophia’s parents and friends attempt to help her unfolds many untold secrets wh', '657bfc83099e9.webp', 3, 2, '2023-12-15 08:13:07', '2023-12-15 08:13:07'),
(6, '6f3ee016-9b1a-11ee-a2dd-87231b1e44fc', 'The Lost World', 'Doyle Arthur Conan', '“So tomorrow we disappear into the unknown . . . it may be our last word to those who are interested in our fate.” Dinosaurs, pterodactyls, ape-men, and other prehistoric creatures still roam among us. This groundbreaking discovery has been made by the notorious Professor George Edward Challenger, who is a brilliant scientist. But this revelation has been subjected to ridicule. In order to believe, people need proof. So, that’s what he will give them. Braving danger and risking his life, Challen', '657bfe41468e9.webp', 3, 3, '2023-12-15 08:20:33', '2023-12-15 08:20:33'),
(7, '19c5ca4b-9b1b-11ee-a2dd-87231b1e44fc', 'Journey To The Centre Of The Earth', 'Verne Jules', 'The father of science fiction, Jules Verne, invites you to join the intrepid and eccentric Professor Liedenbrock and his companions on a thrilling and dramatic expedition as they travel down a secret tunnel in a volcano in Iceland on a journey which will lead them to the centre of the earth. Along the way they encounter various hazards and witness many incredible sights such as the underground forest, illuminated by electricity, the Great Geyser, the battle between prehistoric monsters, the stra', '657bff5f5d98b.webp', 3, 3, '2023-12-15 08:25:19', '2023-12-15 08:25:19'),
(8, '9ae3c99c-9b25-11ee-b397-49ac8288210c', 'Relativity', 'Einstein Albert', 'Originally published in German in 1916, and later translated into English in 1920, ‘Relativity: The Special and the General Theory’ is a short paper and was eventually published as a book by Albert Einstein, German-born, best known for his work on the theory of Relativity, gaining him the title of ‘Father of Modern Physics’. He received the 1921 Nobel Prize in Physics, and his work is attributed as an inspiration for the quantum theory within the field of physics. His hundreds of papers and book', '657c10feec37a.webp', 2, 2, '2023-12-15 09:40:30', '2023-12-15 09:40:30'),
(9, '6ea5dc12-9b26-11ee-b397-49ac8288210c', 'Adventures At School', 'Ruskin Bond', '‘In that last year at Prep school in Shimla, there were four of us who were close friends […] We called ourselves the “Four Feathers”, the feathers signifying that we were companions in adventure, comrades-in-arms and knights of the round table.’ School days play a major role in shaping who we are; our friends and teachers at school are the family we personally handpick. Not only does school teach us order, discipline and the importance of academic learning, it also gives us the priceless gifts ', '657c12623bf16.webp', 1, 2, '2023-12-15 09:46:26', '2023-12-15 09:46:26'),
(10, 'a07cc05a-9b26-11ee-b397-49ac8288210c', 'Othello', 'Shakespeare William', 'Othello, by William Shakespeare, is regarded as one of the most famous works of tragedy in English literature. It brings you the story of Othello, the Moor of Venice, and Desdemona, his lover.\r\n\r\nSummary of the Book\r\n\r\nOthello intricately portrays the subtle shades of jealousy and deception among many other human emotions. Desdemona and Othello are secretly married to each other, leading to Roderigo’s rage. Iago, Othello’s ensign, despises Othello for choosing Michael Cassio and not him. He is d', '657c12b5d2c25.webp', 1, 3, '2023-12-15 09:47:49', '2023-12-15 09:47:49'),
(11, 'd02899a2-9b26-11ee-b397-49ac8288210c', 'The Girl On The Train', 'Ruskin Bond', '‘I thought of running away with Kamla. When I mentioned it to her, her eyes lit up. She thought it would be great fun. Women in love can be more reckless than men! But I had read too many stories about runaway marriages ending in disaster, and I lacked the courage to go through with such an adventure. I must have known instinctively that it would not work. Where would we go, and how would we live? There would be no home to crawl back to, for either of us.’ Seldom do we come across a person who l', '657c1305c8b98.webp', 1, 2, '2023-12-15 09:49:09', '2023-12-15 09:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `book_id` varchar(100) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `book_id`, `creation_date`, `modification_date`) VALUES
(9, 'uuid()', 'a5b2c650-9b17-11ee-a2dd-87231b1e44fc', '2023-12-15 10:37:39', '2023-12-15 10:37:39'),
(10, 'uuid()', '6f3ee016-9b1a-11ee-a2dd-87231b1e44fc', '2023-12-15 10:37:51', '2023-12-15 10:37:51'),
(11, 'uuid()', '19c5ca4b-9b1b-11ee-a2dd-87231b1e44fc', '2023-12-15 10:38:06', '2023-12-15 10:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `base` int(11) NOT NULL,
  `additional` int(11) NOT NULL,
  `fine` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `base`, `additional`, `fine`, `creation_date`, `modification_date`) VALUES
(1, 'Literature', 20, 5, 2, '2023-12-15 07:53:27', '2023-12-15 07:53:27'),
(2, 'Philosophy', 10, 3, 1, '2023-12-15 07:56:54', '2023-12-15 09:39:16'),
(3, 'Science Fiction', 15, 8, 2, '2023-12-15 07:57:24', '2023-12-15 07:57:24');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` varchar(100) NOT NULL DEFAULT 'uuid()',
  `user_id` varchar(100) NOT NULL,
  `card` text NOT NULL,
  `amount` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `card`, `amount`, `creation_date`, `modification_date`) VALUES
('pay-657c175e9aed4', '3b7fc4dd-9b29-11ee-b397-49ac8288210c', '2569', 33, '2023-12-15 10:07:42', '2023-12-15 10:07:42'),
('pay-657c17fa09797', '3b7fc4dd-9b29-11ee-b397-49ac8288210c', '1578', 29, '2023-12-15 10:10:18', '2023-12-15 10:10:18'),
('pay-657c18c7e07ea', '2908de4c-9b2a-11ee-b397-49ac8288210c', '2531', 15, '2023-12-15 10:13:43', '2023-12-15 10:13:43'),
('pay-657c194f987a5', '6e36f3c2-9b2a-11ee-b397-49ac8288210c', '2569', 37, '2023-12-15 10:15:59', '2023-12-15 10:15:59'),
('pay-657c1af4adb74', 'fc043743-9b29-11ee-b397-49ac8288210c', '7842', 53, '2023-12-15 10:23:00', '2023-12-15 10:23:00'),
('pay-657c1ba362a0b', 'a7f47494-9b2b-11ee-b397-49ac8288210c', '7845', 40, '2023-12-15 10:25:55', '2023-12-15 10:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `quantity`
--

CREATE TABLE `quantity` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `copies` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quantity`
--

INSERT INTO `quantity` (`id`, `book_id`, `copies`, `available`, `creation_date`, `modification_date`) VALUES
(1, 1, 50, 49, '2023-12-15 07:55:18', '2023-12-15 10:36:28'),
(2, 2, 30, 30, '2023-12-15 08:00:36', '2023-12-15 08:06:49'),
(3, 3, 40, 38, '2023-12-15 08:05:54', '2023-12-15 10:25:55'),
(4, 4, 30, 29, '2023-12-15 08:08:50', '2023-12-15 10:10:18'),
(5, 5, 15, 14, '2023-12-15 08:13:07', '2023-12-15 10:25:55'),
(6, 6, 10, 9, '2023-12-15 08:20:33', '2023-12-15 10:23:00'),
(7, 7, 15, 15, '2023-12-15 08:25:19', '2023-12-15 08:25:19'),
(8, 8, 30, 28, '2023-12-15 09:40:30', '2023-12-15 11:36:17'),
(9, 9, 25, 25, '2023-12-15 09:46:26', '2023-12-15 09:46:26'),
(10, 10, 15, 15, '2023-12-15 09:47:49', '2023-12-15 09:47:49'),
(11, 11, 25, 24, '2023-12-15 09:49:09', '2023-12-15 10:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `rented_books`
--

CREATE TABLE `rented_books` (
  `id` int(11) NOT NULL,
  `book_id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `due_date` date NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rented_books`
--

INSERT INTO `rented_books` (`id`, `book_id`, `user_id`, `date`, `due_date`, `creation_date`, `modification_date`) VALUES
(2, '6347310b-9b18-11ee-a2dd-87231b1e44fc', '3b7fc4dd-9b29-11ee-b397-49ac8288210c', '2023-12-15', '2024-03-31', '2023-12-15 10:10:18', '2023-12-15 10:10:18'),
(3, 'cc35526f-9b18-11ee-a2dd-87231b1e44fc', '3b7fc4dd-9b29-11ee-b397-49ac8288210c', '2023-12-15', '2024-02-15', '2023-12-15 10:10:18', '2023-12-15 10:10:18'),
(4, '9ae3c99c-9b25-11ee-b397-49ac8288210c', '2908de4c-9b2a-11ee-b397-49ac8288210c', '2023-12-15', '2024-01-30', '2023-12-15 10:13:43', '2023-12-15 10:13:43'),
(5, 'd02899a2-9b26-11ee-b397-49ac8288210c', '6e36f3c2-9b2a-11ee-b397-49ac8288210c', '2023-12-15', '2024-02-28', '2023-12-15 10:15:59', '2023-12-15 10:15:59'),
(6, 'e8a3c70e-9b16-11ee-a2dd-87231b1e44fc', 'fc043743-9b29-11ee-b397-49ac8288210c', '2023-12-15', '2024-01-30', '2023-12-15 10:23:00', '2023-12-15 10:23:00'),
(8, '6f3ee016-9b1a-11ee-a2dd-87231b1e44fc', 'fc043743-9b29-11ee-b397-49ac8288210c', '2023-12-15', '2024-03-26', '2023-12-15 10:23:00', '2023-12-15 10:23:00'),
(9, '6347310b-9b18-11ee-a2dd-87231b1e44fc', 'a7f47494-9b2b-11ee-b397-49ac8288210c', '2023-12-15', '2024-01-30', '2023-12-15 10:25:55', '2023-12-15 10:25:55'),
(10, '9ae3c99c-9b25-11ee-b397-49ac8288210c', 'a7f47494-9b2b-11ee-b397-49ac8288210c', '2023-12-15', '2024-02-28', '2023-12-15 10:25:55', '2023-12-15 10:25:55'),
(11, '6542b5de-9b19-11ee-a2dd-87231b1e44fc', 'a7f47494-9b2b-11ee-b397-49ac8288210c', '2023-12-15', '2024-03-28', '2023-12-15 10:25:55', '2023-12-15 10:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL DEFAULT uuid(),
  `name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `isSuper` tinyint(1) NOT NULL DEFAULT 0,
  `uniqueID` varchar(50) DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `address`, `password`, `image`, `active`, `role`, `isSuper`, `uniqueID`, `creation_date`, `modification_date`) VALUES
(1, 'uuid()', 'Sajal Khawas', 'sajal@gmail.com', '#10, Street 33, Jujhar Nagar, SAS Nagar, Punjab - 140301, India', '$2y$10$b8wjPEON6iY9SZzc3Bh0cuaitimHT8gJgqLGCyllOy7XrieMWJK0O', '657bf74ac7021.jpg', 1, 1, 1, NULL, '2023-12-15 12:15:10', '2023-12-15 11:24:45'),
(2, 'f47d58de-9b26-11ee-b397-49ac8288210c', 'Karan Sharma', 'karan@gmail.com', 'Mohali, Punjab', '$2y$10$NePNblyzZ1s7Jdl6mDS9yO1tJkk3.U5r6oRj3iVLY61n/2Mzv0gUm', NULL, 1, 1, 1, NULL, '2023-12-15 09:50:10', '2023-12-15 09:50:24'),
(3, '31f166a8-9b27-11ee-b397-49ac8288210c', 'Pradeep Singh', 'pradeep@yahoo.com', '#5467, Sector-35 D, Chandigarh', '$2y$10$BAx62JnJYiQnu2QBD.dK0uZ6lIPOUNgUoaCwCr76YRO6JpWc.o6Xa', NULL, 1, 1, 0, NULL, '2023-12-15 09:51:53', '2023-12-15 09:51:53'),
(4, '3b7fc4dd-9b29-11ee-b397-49ac8288210c', 'Gourav Khawas', 'gouravkhawas@gmail.com', 'S.A.S. Nagar (Mohali), Punjab, India', '$2y$10$QRwnSksYcSUtqaqp3qPst.zrlAZiHcdJVkw0omPTKMXJ7anEqNAmq', NULL, 1, 0, 0, NULL, '2023-12-15 10:06:28', '2023-12-15 10:26:56'),
(5, 'fc043743-9b29-11ee-b397-49ac8288210c', 'Neeraj', 'neeraj@gmail.com', 'Zirakpur, Punjab, India', '$2y$10$L.iGHy9e9sCxkExr7vRZI.hW37mA3coS6/S2TddVow3UyD85fwsjO', NULL, 1, 0, 0, NULL, '2023-12-15 10:11:51', '2023-12-15 10:12:04'),
(6, '2908de4c-9b2a-11ee-b397-49ac8288210c', 'Aman', 'aman@yahoo.com', 'Chandigarh, India', '$2y$10$dAX1t2XfaNDGMxPjcOGes.bzVwvoO.EMrGtILc1Vbt2EcufASJh1C', NULL, 1, 0, 0, NULL, '2023-12-15 10:13:07', '2023-12-15 10:13:07'),
(7, '6e36f3c2-9b2a-11ee-b397-49ac8288210c', 'Simran', 'simran@gmail.com', 'Kharar, Punjab, India', '$2y$10$Q0dd32eB5lPb61Iu7qx6OuNXPhMgEbt/Q98e1lhQ9cF8d6n5BkJt2', NULL, 1, 0, 0, NULL, '2023-12-15 10:15:03', '2023-12-15 10:15:03'),
(8, 'a7f47494-9b2b-11ee-b397-49ac8288210c', 'Sukhpal', 'sukh@gmail.com', 'Kharar, Mohali, Punjab, India', '$2y$10$lCXlA1GvHdGNQLHEAyDglezDvfxW0.TQYXcXcSYvBKC//ssmnCDGq', NULL, 1, 0, 0, NULL, '2023-12-15 10:23:49', '2023-12-15 10:23:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`title`),
  ADD UNIQUE KEY `book_uuid` (`book_uuid`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`name`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quantity`
--
ALTER TABLE `quantity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `rented_books`
--
ALTER TABLE `rented_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `unique_img` (`image`(20));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quantity`
--
ALTER TABLE `quantity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rented_books`
--
ALTER TABLE `rented_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quantity`
--
ALTER TABLE `quantity`
  ADD CONSTRAINT `quantity_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
