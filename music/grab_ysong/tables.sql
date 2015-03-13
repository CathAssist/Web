-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 09 月 26 日 09:49
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `joomla`
--

-- --------------------------------------------------------

--
-- 表的结构 `alume`
--

CREATE TABLE IF NOT EXISTS `alume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `pic` text NOT NULL,
  `src` varchar(512) NOT NULL,
  `singer` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `src` (`src`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `singer`
--

CREATE TABLE IF NOT EXISTS `singer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `src` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `src` (`src`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `song`
--

CREATE TABLE IF NOT EXISTS `song` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `src` varchar(512) NOT NULL,
  `alume` int(11) NOT NULL,
  `singer` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `src` (`src`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;