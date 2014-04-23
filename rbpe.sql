-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 17, 2014 at 03:02 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


USE `a1464840_db`;

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(6) NOT NULL,
  `enigmaID` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `ref`, `enigmaID`, `text`) VALUES
(1, 'HYTTI7', 1, 'reponse 1'),
(2, 'OIUR45', 2, 'reponse 2'),
(3, 'PRBD23', 3, 'reponse 3'),
(4, 'PMRS02', 4, 'reponse 4'),
(11, 'TYPE45', 5, 'ROUE');

-- --------------------------------------------------------

--
-- Table structure for table `clue`
--

CREATE TABLE IF NOT EXISTS `clue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `enigmaID` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codeID` (`enigmaID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `clue`
--

INSERT INTO `clue` (`id`, `text`, `enigmaID`, `order`) VALUES
(1, 'Clue 1', 1, 1),
(2, 'le sol n''est pas forcément plat.', 5, 1),
(3, 'Qui file toujours droit?', 5, 0),
(4, 'Qui file toujours droit?', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE IF NOT EXISTS `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `enigmaID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `status` enum('New','Assigned','Used') NOT NULL,
  `usedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enigmaID` (`enigmaID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `code`
--

INSERT INTO `code` (`id`, `text`, `enigmaID`, `userID`, `status`, `usedDate`) VALUES
(2, 'Code 1', 1, 2, 'Assigned', '2014-04-15'),
(3, 'Code 2', 2, 1, 'Assigned', NULL),
(4, 'Code 3', NULL, NULL, 'New', NULL),
(5, 'Code 4', 4, 1, 'Used', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enigma`
--

CREATE TABLE IF NOT EXISTS `enigma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `nbClues` int(11) DEFAULT NULL,
  `publiDate` date DEFAULT NULL,
  `ref` varchar(6) NOT NULL,
  `picture` text NOT NULL,
  `expected_answer` text NOT NULL,
  `buy_clue` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `enigma`
--

INSERT INTO `enigma` (`id`, `title`, `text`, `nbClues`, `publiDate`, `ref`, `picture`, `expected_answer`, `buy_clue`) VALUES
(1, 'test', 'test', 4, '2014-04-24', 'RYBG54', '', '', 0),
(2, 'Title 2', 'test 2', 2, '2014-04-30', 'NJDU06', '', '', 0),
(3, 'Title 3', 'test 3', 42, '2014-04-09', 'BNMT54', '', '', 0),
(4, 'test 4', 'test 4', 42, '2014-04-14', 'CFRT36', '', '', 1),
(5, 'Trois fourmis', 'Trois fourmis marchent en file indienne. \r\nLa première dit : "Une fourmis me suit"\r\nLa seconde dit : "Une fourmis me suit"\r\nLa troisième dit : "Une fourmis me suit" \r\nles fourmis disent la vérité, pourquoi?', 1, '2014-04-01', 'MKOT87', 'image1.jpg', 'ROUE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `submitted_answers`
--

CREATE TABLE IF NOT EXISTS `submitted_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enigma_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `password` text NOT NULL,
  `safeID` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `firstname`, `lastname`, `password`, `safeID`) VALUES
(1, 'matthieu.dartois@thomsonreuters.com', 'Matthieu', 'Dartois', 'f71dbe52628a3f83a77ab494817525c6', '8005262'),
(2, 'matthieu.dartois1@thomsonreuters.com', 'Matthieu', 'Dartois', 'f71dbe52628a3f83a77ab494817525c6', '8005262'),
(3, 'rbpe', 'Raphaël', 'Bernard-Perrottey', 'bad55fd1cad7dd4f51ee28d1fb8da20a', '8013199'),
(4, 'rb', 'Raph Test', 'Sans', '1a20d9b850ff9e50e535726bd8e68ea2', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE IF NOT EXISTS `user_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `enigmaID` int(11) NOT NULL,
  `clueRank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `enigmaID` (`enigmaID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`id`, `userID`, `enigmaID`, `clueRank`) VALUES
(6, 1, 1, 8),
(7, 2, 1, 1),
(11, 2, 1, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clue`
--
ALTER TABLE `clue`
  ADD CONSTRAINT `clue_ibfk_1` FOREIGN KEY (`enigmaID`) REFERENCES `enigma` (`id`);

--
-- Constraints for table `code`
--
ALTER TABLE `code`
  ADD CONSTRAINT `code_ibfk_1` FOREIGN KEY (`enigmaID`) REFERENCES `enigma` (`id`),
  ADD CONSTRAINT `code_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`enigmaID`) REFERENCES `enigma` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
