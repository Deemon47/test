/*
Navicat MySQL Data Transfer

Source Server         : RsPi
Source Server Version : 50528
Source Host           : 192.168.1.113:3306
Source Database       : demo

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2013-10-24 12:09:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for datas_t
-- ----------------------------
DROP TABLE IF EXISTS `datas_t`;
CREATE TABLE `datas_t` (
  `task_id` int(10) unsigned NOT NULL,
  `1` text,
  `2` text,
  `3` text,
  `4` text,
  `5` text,
  `6` text,
  `7` text,
  `8` text,
  `9` text,
  `10` text,
  `11` text,
  `12` text,
  `13` text,
  `14` text,
  `15` text,
  `16` text,
  `17` text,
  `18` text,
  `19` text,
  `20` text,
  `21` text,
  `22` text,
  `23` text,
  `24` text,
  `25` text,
  `26` text,
  `27` text,
  `28` text,
  `29` text,
  `30` text,
  `31` text,
  `32` text,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Данные в табличном виде';

-- ----------------------------
-- Table structure for fields
-- ----------------------------
DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='Поля резюме';

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(2000) NOT NULL,
  `type` enum('list','page') NOT NULL,
  `status` enum('to_update','to_do','success','error') NOT NULL DEFAULT 'to_do',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`(333)) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Страницы задачи для парсера';
