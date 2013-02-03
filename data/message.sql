-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 03 feb 2013 om 13:47
-- Serverversie: 5.5.25
-- PHP-versie: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `db_test`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `test_group`
--

CREATE TABLE `test_group` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `grp` int(7) NOT NULL,
  `user` int(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `test_message`
--

CREATE TABLE `test_message` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `grp` int(7) NOT NULL,
  `content` text NOT NULL,
  `created` int(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `test_messages_updated_group`
--

CREATE TABLE `test_messages_updated_group` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `user` int(7) NOT NULL,
  `grp` int(7) NOT NULL,
  `updated` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `test_messages_updated_user`
--

CREATE TABLE `test_messages_updated_user` (
  `user` int(7) NOT NULL,
  `updated` enum('0','1') NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `test_user`
--

CREATE TABLE `test_user` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `online` smallint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
