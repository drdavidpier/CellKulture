-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2015 at 09:05 PM
-- Server version: 5.6.13
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cell_kulture`
--
CREATE DATABASE IF NOT EXISTS `cell_kulture` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cell_kulture`;

-- --------------------------------------------------------

--
-- Table structure for table `box`
--

CREATE TABLE IF NOT EXISTS `box` (
  `box_id` int(10) NOT NULL AUTO_INCREMENT,
  `freezerid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vial_locations` varchar(10000) NOT NULL,
  PRIMARY KEY (`box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `freezer`
--

CREATE TABLE IF NOT EXISTS `freezer` (
  `freezer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`freezer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) DEFAULT NULL,
  `setting_value` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
(1, 'database_version', '1.7'),
(2, 'stb_install_date', '2013-12-16 12:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE IF NOT EXISTS `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `section` varchar(32) NOT NULL,
  `action` varchar(32) NOT NULL,
  `when` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `code` int(10) unsigned NOT NULL,
  `status` tinyint(4) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `priority` tinyint(4) unsigned NOT NULL,
  `description` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `flask_type` tinyint(4) unsigned NOT NULL,
  `flask_number` int(10) unsigned DEFAULT NULL,
  `confluence` int(5) unsigned DEFAULT NULL,
  `cell_quality` varchar(50) NOT NULL,
  `action` tinyint(4) unsigned NOT NULL,
  `infection` tinyint(4) unsigned NOT NULL,
  `flask_number_new` int(10) unsigned DEFAULT NULL,
  `split_ratio` varchar(50) DEFAULT NULL,
  `passage_new` int(50) unsigned DEFAULT NULL,
  `flasks_removed` int(10) unsigned NOT NULL,
  `vial_number` int(10) unsigned NOT NULL,
  `f_location` varchar(50) DEFAULT NULL,
  `removal_reason` tinyint(4) unsigned NOT NULL,
  `flasks_removed_two` int(10) unsigned NOT NULL,
  `action_two` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  KEY `status` (`project_id`,`status`),
  KEY `parent` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE IF NOT EXISTS `task_comments` (
  `task_comments_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `comment` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_comments_id`),
  KEY `task` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_history`
--

CREATE TABLE IF NOT EXISTS `task_history` (
  `task_history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `date_finished` datetime DEFAULT NULL,
  `duration` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`task_history_id`),
  KEY `task` (`task_id`,`status`),
  KEY `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `links` text,
  `photo` varchar(255) DEFAULT NULL,
  `github_username` varchar(100) DEFAULT NULL,
  `github_token` varchar(40) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lab` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `links`, `photo`, `github_username`, `github_token`, `email`, `password`, `level`, `date_created`, `lab`) VALUES
(1, 'Admin', 'null', '', NULL, NULL, 'admin@gmail.com', 'b5e76e0b976ff5945e711f65bdc0ed3b0c4ce2fe', 1, '2013-12-16 12:20:49', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_fbox`
--

CREATE TABLE IF NOT EXISTS `user_fbox` (
  `user` int(10) unsigned NOT NULL,
  `fbox` int(10) unsigned NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_freezer`
--

CREATE TABLE IF NOT EXISTS `user_freezer` (
  `user` int(10) unsigned NOT NULL,
  `freezer` int(10) unsigned NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_project`
--

CREATE TABLE IF NOT EXISTS `user_project` (
  `user` int(10) unsigned NOT NULL,
  `project` int(10) unsigned NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`,`project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_vial`
--

CREATE TABLE IF NOT EXISTS `user_vial` (
  `user` int(10) NOT NULL,
  `vial` int(10) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vial`
--

CREATE TABLE IF NOT EXISTS `vial` (
  `vial_id` int(10) NOT NULL AUTO_INCREMENT,
  `boxid` int(10) NOT NULL,
  `taskid` int(10) NOT NULL,
  `projectid` int(10) NOT NULL,
  `arrayid` int(10) NOT NULL,
  PRIMARY KEY (`vial_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_stbfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `task_stbfk_2` FOREIGN KEY (`parent_id`) REFERENCES `task` (`task_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_stbfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `task_history`
--
ALTER TABLE `task_history`
  ADD CONSTRAINT `task_history_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `task_history_stbfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
