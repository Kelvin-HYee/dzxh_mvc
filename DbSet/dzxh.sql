;
--
-- ��Ľṹ `admin`
--


CREATE TABLE IF NOT EXISTS `xh_admin`(
`id` tinyint unsigned auto_increment key,
`username` varchar(20) not null unique key,
`password` varchar(32) not null,
`sex` enum('1','2','3') default '3',
`authority` enum('1','2','3')DEFAULT '3'
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;


/*
��Ľṹ`monitor` pid(�û�id) last_time(����ʱ��)
handle(����:1����¼ 2���·��� 3�����޸� 4����ɾ�� 5ҳ����� 6�����û���Ϣ 7ɾ���û� 8����û� 9���ݻ�ԭ�뱸�ݼ�ɾ�� 10�޸Ļ���ӷ���)
handle_o(��������)
*/
CREATE TABLE IF NOT EXISTS `xh_monitor`(
`id` tinyint unsigned auto_increment key,
`pid` tinyint unsigned,
`username` varchar(20) not null,
`ip` VARCHAR(32) not null,
`last_time` datetime,
`handle` enum('1','2','3','4','5','6','7','8','9','10') not null,
`handle_o` VARCHAR(30)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- ��Ľṹ `admin_mes`
--
CREATE TABLE IF NOT EXISTS `xh_admin_mes`(
`id` tinyint unsigned auto_increment key,
`title` VARCHAR(30),
`link` VARCHAR(60) not null,
`cate` VARCHAR(20) DEFAULT NULL,
`icp` VARCHAR(50)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
--
-- ��Ľṹ `mail`
--
CREATE TABLE IF NOT EXISTS `xh_mail`(
`id` tinyint unsigned auto_increment key,
`mail` VARCHAR(30) NOT NULL
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
--
-- ��Ľṹ `qq`
--
CREATE TABLE IF NOT EXISTS `xh_qq`(
`id` int default '1' unique key,
`qq1` VARCHAR(11),
`name1` VARCHAR(30),
`qq2` VARCHAR(11),
`name2` VARCHAR(30),
`qq3` VARCHAR(11),
`name3` VARCHAR(30),
`qq4` VARCHAR(11),
`name4` VARCHAR(30),
`qq5` VARCHAR(11),
`name5` VARCHAR(30)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
--
-- ��Ľṹ `article`
--
CREATE TABLE IF NOT EXISTS `xh_article`(
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`author` VARCHAR(100) NOT NULL,
`title` VARCHAR(100) NOT NULL,
`content` TEXT NOT NULL,
`dateline` datetime,
`photo` VARCHAR(150),
`cId` smallint(5) unsigned NOT NULL,
`zan` int(11) DEFAULT NULL DEFAULT 0,
`info` varchar(255) DEFAULT NULL,
`status` tinyint(4) DEFAULT NULL,
`link` VARCHAR(150) DEFAULT NULL,
`clicktimes` int(10) default 0
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- ��Ľṹ `dzhb`
--
CREATE TABLE IF NOT EXISTS `xh_dzhb`(
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`path_photo1` VARCHAR(150) NOT NULL,
`path_photo2` VARCHAR(150) NOT NULL,
`path_photo3` VARCHAR(150) NOT NULL,
`path_photo4` VARCHAR(150) NOT NULL
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- ��Ľṹ `movie`
--
CREATE TABLE IF NOT EXISTS `xh_movie`(
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`m_name` varchar(10) not null,
`p_name` VARCHAR (10) not null,
`reason` varchar(50) not null,
`time` datetime
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- ��Ľṹ `movie`
--
CREATE  TABLE IF NOT EXISTS `xh_join`(
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`name` varchar(20) not null,
`xy` VARCHAR (10),
`class` VARCHAR (10),
`sex` enum('��','Ů','����') default '����',
`qq` bigint UNSIGNED not null,
`phone` bigint UNSIGNED not null,
`time` datetime
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;



--
-- ��Ľṹ `cate`
--
CREATE TABLE IF NOT EXISTS `xh_cate`(
`pid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(50) DEFAULT NULL,
PRIMARY KEY (`pid`),
UNIQUE KEY name (`name`)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
--
-- ��Ľṹ `member`
--
CREATE TABLE IF NOT EXISTS `xh_member`(
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`name1` varchar(50) DEFAULT NULL,
`name2` varchar(50) DEFAULT NULL,
`name3` varchar(50) DEFAULT NULL,
`name4` varchar(50) DEFAULT NULL,
`name5` varchar(50) DEFAULT NULL,
`name6` varchar(50) DEFAULT NULL,
`f_name1` varchar(50) DEFAULT NULL,
`f_name2` varchar(50) DEFAULT NULL,
`f_name3` varchar(50) DEFAULT NULL,
`f_name4` varchar(50) DEFAULT NULL,
`f_name5` varchar(50) DEFAULT NULL,
`f_name6` varchar(50) DEFAULT NULL,
`path_photo1` VARCHAR(150),
`path_photo2` VARCHAR(150),
`path_photo3` VARCHAR(150),
`path_photo4` VARCHAR(150),
`path_photo5` VARCHAR(150),
`path_photo6` VARCHAR(150)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- ת����е�����
--
insert into `xh_admin`(`username`,`password`,`sex`,`authority`) values('xh_admin',md5('xh_admin'),'1','1');
insert into `xh_admin`(`username`,`password`,`sex`,`authority`) values('Admin',md5('Admin'),'1','1');
insert into `xh_admin_mes`(`title`,`link`) values('NULL','http://jishuz.cn');
insert into `xh_qq`(`qq1`) values('1375643047');
insert into `xh_dzhb`(`path_photo1`,`path_photo2`,`path_photo3`) values('NULL','NULL','NULL');
insert into `xh_cate`(`pid`,`name`) values(1,'Э��֪ͨ');
insert into `xh_cate`(`pid`,`name`) values(2,'��Ӱ��');
insert into `xh_cate`(`pid`,`name`) values(3,'Э��֮��');
insert into `xh_cate`(`pid`,`name`) values(4,'Э��');
insert into `xh_cate`(`pid`,`name`) values(5,'��������');
insert into `xh_member`(`path_photo1`,`path_photo2`,`path_photo3`) values('NULL','NULL','NULL');