/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : success

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-09-16 18:09:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for coin
-- ----------------------------
DROP TABLE IF EXISTS `coin`;
CREATE TABLE `coin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of coin
-- ----------------------------

-- ----------------------------
-- Table structure for jack_admin
-- ----------------------------
DROP TABLE IF EXISTS `jack_admin`;
CREATE TABLE `jack_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `coin` int(10) DEFAULT '0' COMMENT '激活币',
  `register` int(10) DEFAULT '0' COMMENT ' 激活会员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jack_admin
-- ----------------------------

-- ----------------------------
-- Table structure for jack_agent
-- ----------------------------
DROP TABLE IF EXISTS `jack_agent`;
CREATE TABLE `jack_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `phone` varchar(20) DEFAULT NULL,
  `sex` tinyint(2) DEFAULT '1' COMMENT '性别（1，男；2，女）',
  `identity` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `location` varchar(255) DEFAULT NULL COMMENT '所在地',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `coin` int(10) DEFAULT '0' COMMENT ' 激活币总数',
  `coin1` int(10) DEFAULT '0' COMMENT '激活币库存（总数与转出之和）',
  `coin2` int(10) DEFAULT '0' COMMENT '激活币转出数',
  `agent` int(11) DEFAULT '0' COMMENT '直接代理商总数',
  `agentnum` int(10) DEFAULT '0' COMMENT '代理商总数（不算自己）',
  `register` int(11) DEFAULT '0' COMMENT '直接激活会员总数',
  `registernum` int(10) DEFAULT '0' COMMENT '激活会员数',
  `parentuser` varchar(255) DEFAULT NULL COMMENT '上级代理商',
  `parentid` int(10) DEFAULT '0' COMMENT '上级代理商ID',
  `ldstr` varchar(255) DEFAULT NULL COMMENT '组织关系',
  `addtime` int(10) DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态(0，异常;1，正常)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jack_agent
-- ----------------------------
INSERT INTO `jack_agent` VALUES ('1', 'jack', '13007120000', '1', '123456789', '湖北武汉', '21218cca77804d2ba1922c33e0151105', '13', '6', '5', '2', '4', '0', '0', null, '0', null, '0', '1');
INSERT INTO `jack_agent` VALUES ('4', '发给', '13007120001', '1', '1300', 'aa', '21218cca77804d2ba1922c33e0151105', '2', '0', '2', '2', '2', '0', '0', 'jack', '1', '1', '1505019149', '1');
INSERT INTO `jack_agent` VALUES ('5', '我去', '13007120002', '1', '123', '13007120002', '21218cca77804d2ba1922c33e0151105', '2', '1', '1', '0', '0', '0', '0', 'jack', '1', '1', '1505019222', '1');
INSERT INTO `jack_agent` VALUES ('6', '啊啊', '13007121111', '1', '13007121111', '13007121111', '21218cca77804d2ba1922c33e0151105', '2', '1', '1', '0', '0', '0', '0', '发给', '4', '1,4', '1505019322', '1');
INSERT INTO `jack_agent` VALUES ('7', '去去', '13007121112', '1', '13007121112', '13007121112', '21218cca77804d2ba1922c33e0151105', '2', '1', '1', '0', '0', '0', '0', '发给', '4', '1,4', '1505019359', '1');

-- ----------------------------
-- Table structure for jack_user
-- ----------------------------
DROP TABLE IF EXISTS `jack_user`;
CREATE TABLE `jack_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `coin` int(10) DEFAULT NULL COMMENT '激活币',
  `agentid` int(10) DEFAULT NULL COMMENT '代理商ID',
  `parentid` int(10) DEFAULT '0' COMMENT '上级会员(0,自由注册;>0，会员)',
  `status` tinyint(2) DEFAULT '0' COMMENT '会员状态（0，未激活；1,激活）',
  `hobby` varchar(255) DEFAULT NULL COMMENT '兴趣爱好',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jack_user
-- ----------------------------
