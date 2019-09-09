/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : common_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-09-09 14:24:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lee_log
-- ----------------------------
DROP TABLE IF EXISTS `lee_log`;
CREATE TABLE `lee_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(50) DEFAULT NULL,
  `req_url` varchar(100) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `log_contents` varchar(100) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
  PRIMARY KEY (`logid`) USING BTREE,
  KEY `login_name` (`login_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lee_log
-- ----------------------------
INSERT INTO `lee_log` VALUES ('16', 'admin', 'login/login', '127.0.0.1', '登陆后台', '2019-09-09 14:21:57');
INSERT INTO `lee_log` VALUES ('15', 'admin', 'login/logout', '127.0.0.1', '退出登陆', '2019-09-09 14:21:50');

-- ----------------------------
-- Table structure for lee_menu
-- ----------------------------
DROP TABLE IF EXISTS `lee_menu`;
CREATE TABLE `lee_menu` (
  `menuid` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT NULL COMMENT '父级菜单id',
  `menu_name` varchar(100) DEFAULT NULL COMMENT '菜单名称',
  `menu_link` varchar(255) DEFAULT NULL COMMENT '菜单规则',
  `menu_sort` varchar(11) DEFAULT NULL COMMENT '菜单排序',
  `createt_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `menu_status` int(11) DEFAULT '1' COMMENT '启用状态：0:表示禁用；1:表示启用',
  `menu_icon` varchar(255) DEFAULT NULL COMMENT '菜单图标路径',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `menu_display` tinyint(1) DEFAULT '1' COMMENT '显示状态：0:表示隐藏；1:表示显示',
  PRIMARY KEY (`menuid`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lee_menu
-- ----------------------------
INSERT INTO `lee_menu` VALUES ('1', '0', '系统设置', '', '1', '1567490123', null, '1', null, '系统设置', '1');
INSERT INTO `lee_menu` VALUES ('2', '1', '管理员列表', 'user/index', '2', '1567490123', null, '1', '', '管理员列表', '1');
INSERT INTO `lee_menu` VALUES ('3', '1', '角色管理', 'usergroup/index', '3', '1567490123', null, '1', null, '角色管理', '1');
INSERT INTO `lee_menu` VALUES ('4', '1', '菜单管理', 'menu/index', '4', '1567490123', null, '1', null, '菜单管理', '1');
INSERT INTO `lee_menu` VALUES ('5', '0', '个人会员', null, '5', '1567562220', null, '1', null, '个人会员', '1');
INSERT INTO `lee_menu` VALUES ('6', '5', '个人会员列表', 'pmember/index', '6', '1567562220', null, '1', null, '个人会员列表', '1');
INSERT INTO `lee_menu` VALUES ('7', '5', '个人会员详情', 'pmember/details', '7', '1567562220', null, '1', null, null, '0');
INSERT INTO `lee_menu` VALUES ('8', '5', '删除个人会员', 'pmember/del', '8', '1567562220', null, '1', null, null, '0');
INSERT INTO `lee_menu` VALUES ('9', '0', '数据库查询工具', '', '99', '1567754521', '1567756946', '1', null, '开发人员查询数据库用', '1');
INSERT INTO `lee_menu` VALUES ('10', '9', '数据库查询', 'query/index', '99', '1567668747', null, '1', null, '数据库查询', '1');
INSERT INTO `lee_menu` VALUES ('11', '1', '添加操作菜单', 'menu/add', '99', '1567669177', null, '1', null, '', '1');
INSERT INTO `lee_menu` VALUES ('12', '1', '菜单状态', 'menu/menu_status_on', '99', '1567748249', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('13', '1', '菜单是否显示开关', 'menu/menu_display_on', '99', '1567748346', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('19', '9', '执行mysql语句', 'usemysql/index', '99', '1567758589', '1567759042', '1', null, 'mysql执行语句谨慎执行', '1');
INSERT INTO `lee_menu` VALUES ('17', '1', '编辑操作菜单', 'menu/edit', '99', '1567757793', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('18', '1', '删除操作菜单', 'menu/del', '99', '1567757827', null, '0', null, '', '0');
INSERT INTO `lee_menu` VALUES ('20', '0', '用户资料', '', '99', '1567765190', null, '1', null, '', '1');
INSERT INTO `lee_menu` VALUES ('23', '1', '添加管理员', 'user/add', '99', '1567932497', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('22', '20', '修改密码', 'user/edit_pass', '99', '1567765442', null, '1', null, '', '1');
INSERT INTO `lee_menu` VALUES ('24', '1', '更新管理员信息', 'user/update', '99', '1567932518', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('25', '1', '删除管理员', 'user/del', '99', '1567932578', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('26', '1', '编辑管理员', 'user/edit', '99', '1567932597', '1567997140', '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('27', '1', '修改管理员状态', 'user/off', '99', '1567932634', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('28', '1', '添加角色', 'usergroup/add', '99', '1567932665', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('29', '1', '更新角色信息', 'usergroup/update', '99', '1567932706', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('30', '1', '删除角色', 'usergroup/del', '99', '1567932738', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('31', '1', '编辑角色', 'usergroup/edit', '99', '1567932766', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('32', '1', '修改角色状态', 'usergroup/off', '99', '1567932788', null, '1', null, '', '0');
INSERT INTO `lee_menu` VALUES ('33', '20', '日志记录', 'log/index', '99', '1567996713', null, '1', null, '', '1');
INSERT INTO `lee_menu` VALUES ('34', '20', '记录日志', 'log/add', '99', '1567996739', null, '1', null, '', '0');

-- ----------------------------
-- Table structure for lee_user
-- ----------------------------
DROP TABLE IF EXISTS `lee_user`;
CREATE TABLE `lee_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(100) NOT NULL COMMENT '管理员账号',
  `password` varchar(64) NOT NULL COMMENT '管理员密码',
  `roleid` int(11) NOT NULL COMMENT '管理员角色',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '启用状态：0 : 表示禁用；1 : 表示启用',
  `last_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(255) DEFAULT NULL COMMENT '最后登录ip',
  `login_count` int(11) DEFAULT NULL COMMENT '登陆次数',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示：0 : 隐藏；1 : 显示',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lee_user
-- ----------------------------
INSERT INTO `lee_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1567485930', '1567925510', '1', '1568010117', '127.0.0.1', '16', '超级管理员拥有所有操作权限不可删除！', '1');
INSERT INTO `lee_user` VALUES ('2', 'pmember', 'e10adc3949ba59abbe56e057f20f883e', '2', '2', '1567486241', '1567925049', '1', '1567997732', '127.0.0.1', '0', '个人会员', '1');

-- ----------------------------
-- Table structure for lee_user_group
-- ----------------------------
DROP TABLE IF EXISTS `lee_user_group`;
CREATE TABLE `lee_user_group` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '角色名称',
  `group_menu` mediumtext COMMENT '权限菜单',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '启用状态：0:表示禁用；1:表示启用',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注信息',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lee_user_group
-- ----------------------------
INSERT INTO `lee_user_group` VALUES ('1', '超级管理员', '1,2,3,4,11,12,13,17,23,24,25,26,27,28,29,30,31,32,5,6,7,8,9,10,19,20,22,33,34', '1', '1567490123', '1567997117', '1', '');
INSERT INTO `lee_user_group` VALUES ('2', '个人会员管理员', '5,6,7,8', '2', '1567490123', '1567925260', '1', '个人会员管理员');
