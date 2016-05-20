--
-- 列表
--
DROP TABLE IF EXISTS `ts_list`;
CREATE TABLE `ts_list` (
	`list_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
	`title` VARCHAR(50) NOT NULL COMMENT '话题/项目标题',
	`intro` VARCHAR(255) NOT NULL COMMENT '话题/项目简介',
	`cover` INT(11) NOT NULL COMMENT '封面ID',
	`uid` INT(11) NOT NULL COMMENT '发布人UID',
	`type` TINYINT(1) NOT NULL COMMENT '数据类型 1-话题 2-项目',
	`data_id` INT(11) NOT NULL COMMENT '详细数据id',
	`is_hot` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否为热门',
	`is_recommend` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否为推荐',
	`ctime` INT(11) NOT NULL COMMENT '发布时间',
	PRIMARY KEY (`list_id`),
	INDEX `uid` (`uid`),
	INDEX `data_id` (`data_id`)
)
COMMENT='列表数据表'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


--
-- 话题
--
DROP TABLE IF EXISTS `ts_topic_data`;
CREATE TABLE `ts_topic_data` (
	`data_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '数据id',
	`content` TEXT NULL COMMENT '详细内容',
	PRIMARY KEY (`data_id`)
)
COMMENT='话题数据表'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


--
-- 话题标签
--
CREATE TABLE `ts_topic_tag` (
	`tag_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '标签id',
	`tag_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '标签名称',
	`style` VARCHAR(50) NULL DEFAULT NULL COMMENT '标签样式（暂不用）',
	PRIMARY KEY (`tag_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;


--
-- 话题标签关联
--
DROP TABLE IF EXISTS `ts_topic_tag_link`;
CREATE TABLE `ts_topic_tag_link` (
	`list_id` INT(11) NULL DEFAULT NULL COMMENT '列表数据id',
	`tag_id` INT(11) NULL DEFAULT NULL COMMENT 'tagid'
)
COMMENT='标签关联表'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


--
-- 项目
--
DROP TABLE IF EXISTS `ts_project_data`;
CREATE TABLE `ts_project_data` (
	`data_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '数据id',
	`category_id` INT(11) NOT NULL COMMENT '频道id',
	`amount` INT(11) NOT NULL COMMENT '筹款金额',
	`use` TEXT NOT NULL COMMENT '资金用途',
	`content` TEXT NOT NULL COMMENT '详细描述',
	`etime` INT(11) NOT NULL COMMENT '截止日期',
	`ctime` INT(11) NOT NULL COMMENT '创建日期',
	`image` VARCHAR(80) NOT NULL COMMENT '相册',
	PRIMARY KEY (`data_id`)
)
COMMENT='项目数据表'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;