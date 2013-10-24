/*
Navicat MySQL Data Transfer

Source Server         : RsPi
Source Server Version : 50528
Source Host           : 192.168.1.113:3306
Source Database       : demo

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2013-10-24 12:34:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for datas_t
-- ----------------------------
DROP TABLE IF EXISTS `datas_t`;
CREATE TABLE `datas_t` (
  `task_id` int(10) unsigned NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Поля резюме';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Страницы задачи для парсера';
