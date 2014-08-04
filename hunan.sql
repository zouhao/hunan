-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2014 年 07 月 31 日 09:25
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hunan`
--

-- --------------------------------------------------------

--
-- 表的结构 `zs_ad`
--

CREATE TABLE IF NOT EXISTS `zs_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `is_hidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '降序排列',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `link` varchar(255) NOT NULL DEFAULT '#' COMMENT '链接地址',
  `en_title` varchar(50) NOT NULL COMMENT '英文标题',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `admin_id` int(11) NOT NULL COMMENT '发布者',
  `type` tinyint(4) NOT NULL COMMENT '0:在上 1:在下',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- 转存表中的数据 `zs_ad`
--

INSERT INTO `zs_ad` (`id`, `title`, `is_hidden`, `sort`, `img`, `link`, `en_title`, `create_time`, `admin_id`, `type`) VALUES
(9, 'FGL-C902', 0, 2, 'big_cbed80d5251ab12a9200531ba7df69a4.jpg', '', '', '2013-08-23 19:50:34', 17, 1),
(7, 'FGL-A105', 0, 1, 'big_05be28a69337241613fc21690cf599bf.jpg', '', '', '2013-08-23 19:38:18', 17, 1),
(14, '2', 0, 2, 'big_4b0279fa0ac5556fd398ee2b1b2e8c40.jpg', '', '', '2013-08-23 19:56:57', 17, 0),
(10, 'FGL-C801', 0, 6, 'big_deeac4c7d37ae268eda789503cbfcb31.jpg', '', '', '2013-08-23 19:51:18', 17, 1),
(11, 'FGL-A101', 0, 0, 'big_d229bc12896c4c7ee079c3754fbb102a.jpg', '', '', '2013-08-23 19:51:40', 17, 1),
(12, 'FGL-C502', 0, 0, 'big_fcfd8605efc8c78dbe44fcf3a31d3ef3.jpg', '', '', '2013-08-23 19:52:02', 17, 1);

-- --------------------------------------------------------

--
-- 表的结构 `zs_admin`
--

CREATE TABLE IF NOT EXISTS `zs_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `is_forbid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:禁止 0:正常',
  `role_id` int(11) NOT NULL,
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `last_login_ip` int(11) NOT NULL COMMENT '最后登录ip',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `zs_admin`
--

INSERT INTO `zs_admin` (`id`, `username`, `password`, `is_forbid`, `role_id`, `last_login_time`, `last_login_ip`, `create_time`) VALUES
(17, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, 29, '2014-07-30 10:08:12', 0, '2013-08-22 07:21:23');

-- --------------------------------------------------------

--
-- 表的结构 `zs_attention`
--

CREATE TABLE IF NOT EXISTS `zs_attention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('1','2') CHARACTER SET utf8 NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `other_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `zs_attention`
--

INSERT INTO `zs_attention` (`id`, `type`, `user_id`, `other_id`) VALUES
(4, '2', 1, 1),
(9, '1', 1, 43);

-- --------------------------------------------------------

--
-- 表的结构 `zs_news`
--

CREATE TABLE IF NOT EXISTS `zs_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '内容',
  `img` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '略缩图',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页显示(必须存在缩略图)(1:显示,0:不显示)',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏(1:隐藏,0:不隐藏)',
  `admin_id` int(11) NOT NULL COMMENT '发布者',
  `category_id` int(11) NOT NULL COMMENT '类别外键',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '游览量',
  `en_title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '英文标题',
  `en_content` text CHARACTER SET utf8 NOT NULL COMMENT '内容标题',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`admin_id`),
  KEY `class_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `zs_news`
--

INSERT INTO `zs_news` (`id`, `title`, `content`, `img`, `istop`, `is_hidden`, `admin_id`, `category_id`, `click`, `en_title`, `en_content`, `create_time`) VALUES
(1, '郭美美赌球被抓 曾欠下两亿六千万赌债', '<p style="color:#333333;font-family:Arial, Helvetica, sans-serif, Tahoma, Geneva, sans-serif;font-size:14px;background-color:#FFFFFF;">\r\n	郭美美（资料图）\r\n</p>\r\n<p style="color:#333333;font-family:Arial, Helvetica, sans-serif, Tahoma, Geneva, sans-serif;font-size:14px;background-color:#FFFFFF;">\r\n	法制晚报独家播报：据线报，郭美美因赌球被北京警方抓了!郭美美好赌，曾在网上显摆在澳门豪赌的经历。警方打击世界杯期间网络赌球，郭美美应声落网。\r\n</p>\r\n<p style="color:#333333;font-family:Arial, Helvetica, sans-serif, Tahoma, Geneva, sans-serif;font-size:14px;background-color:#FFFFFF;">\r\n	　　郭美美澳门豪赌欠债两亿六千万 找到靠山还清赌债\r\n</p>\r\n<p style="color:#333333;font-family:Arial, Helvetica, sans-serif, Tahoma, Geneva, sans-serif;font-size:14px;background-color:#FFFFFF;">\r\n	　　近日，微博注册名为“报道香港”在微博上爆料，欠下两亿六千万赌债的内地炫富女郭美美赴澳门还款，其资料随即从追债网上删除。据调查，郭美美是因为找到了新靠山，替她还清了近半数的欠款，所以才暂时得以脱身。\r\n</p>\r\n<p style="color:#333333;font-family:Arial, Helvetica, sans-serif, Tahoma, Geneva, sans-serif;font-size:14px;background-color:#FFFFFF;">\r\n	　　随后，郭美美本人也转发该微博，但仅留下很汗的表情，没做出其他回应。\r\n</p>', 'baba820aadb4ccdf5e3f12b110cb9915.jpg', 0, 0, 17, 20, 0, '', '', '2014-07-11 06:39:14'),
(2, '湖南幼儿园校车翻入水库致11死 现场嚎哭不断', '<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	中新网湘潭7月11日电 (记者 唐小晴 杨华峰 刘柱)7月10日下午5时许，湖南湘潭市雨湖区响塘乡金桥村乐乐旺幼儿园所属园车，在送幼儿回家途中，途径湘潭市交界的长沙市岳麓区干子村时，翻入水库。此事故共致11人遇难，包括8名幼儿和3位成人。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	事故发生地位于一山坳中，需经过一段长约2公里的水泥村路，再徒步行走一条小山路方可进入。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	11日凌晨1点30分许，中新网记者在通往事发地途中看到，道路两旁停满了警车和其他车辆，每隔几百米的距离都有一名交警。逾百名警察和特警手持警棍与盾牌，快速徒步前往校车落水所在地。不少村民亦骑摩托车前往。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	记者抵达事故地点时，水库周围挤满了围观的村民，一位潜水员正在水库中搜救，水库旁停着一辆起重机，现场不断传来嚎哭声。一位伤心欲绝的中年妇女欲进入内场，但被警察拦下。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	据当地村民介绍，校车落入的水库面积约10余亩，水深7-8米，平时用来养鱼和灌溉农田。10日傍晚时分，这辆平时在干子村接送村中幼儿上下学的校车，在送完一个村的孩童后折返到另一个村的途中突发事故。一位垂钓者发现水塘中若隐闪现灯光后报警。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	一位周姓村民告诉记者，校车内共有8个孩子、3个大人，包括2名幼教和1名50余岁的司机。凌晨4时许，起重机将落水校车打捞出水，11名遇难者遗体全部找到，其中幼儿8名，成人3名。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	记者看到，该水库一面环山，四周长满了杂草和小树，无任何护栏。旁边则是一条可允许两车通行的石子路。从远处观望，这台落水的黄色校车挡风玻璃已碎，可明显看见一条静止弯曲的大腿。在起重机即将离开现场时，记者多次听到鞭炮声不间断响起。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	参与救援的消防官兵表示，因通往事故现场的山路狭窄且崎岖，救援车难以进入。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	事故发生后，湖南省委、省政府高度重视，省、市有关领导立即赶赴现场指挥救援。目前，事故勘察、善后处置工作仍在进行。(完)\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	<strong>涉事幼儿园校车超载运行</strong>\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	新华网长沙7月11日电（记者阳建）据湖南省委宣传部通报，事故发生后，湖南省委省政府高度重视，省、市有关领导赶赴现场指挥救援。长沙市、湘潭市联合组织专业力量进行救援，当时初步统计有11人失踪。11日凌晨3时左右，事故校车被打捞上岸，当场找到9具尸体，其中司机1名，幼儿8名。凌晨4时许，另外两名随车教师的尸体也被找到。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	记者在现场看到，事故校车为核载7人的面包车，而事故发生时校车却搭载了11人。多位遇难者家属和村民反映，涉事幼儿园的校车平时就经常超载运行。目前，事故勘察、善后处置工作仍在进行。【<a target="_blank" href="http://news.qq.com/a/20140711/008512.htm">详细</a>】\r\n</p>', '9933a17e4fab9d037fbc235175eaa036.jpg', 0, 0, 17, 20, 0, '', '', '2014-07-11 06:40:21'),
(3, '浙江“亿万富姐”吴英死缓减刑为无期', '<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2014年7月11日，浙江省高级人民法院依法公开开庭审理罪犯吴英减刑一案，当庭作出裁定：将吴英的死刑，缓期二年执行，剥夺政治权利终身减为无期徒刑，剥夺政治权利终身。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2012年5月21日，浙江省高级人民法院以集资诈骗罪，判处吴英死刑，缓期二年执行，剥夺政治权利终身，并处没收个人全部财产。判决发生法律效力后交付浙江省女子监狱执行。死刑缓期执行期间自2012年5月21日起至2014年5月20日止。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	罪犯吴英死刑缓期执行期满后，浙江省女子监狱依法提出减刑建议书，建议将吴英的死缓刑减为无期徒刑。经浙江省监狱管理局审核，报送浙江省高级人民法院审理。2014年7月11日，浙江省高级人民法院在浙江省女子监狱依法进行了公开开庭审理。部分浙江省人大代表、政协委员及吴英的部分亲属参加了旁听。浙江省人民检察院指派检察员出庭履行职务，浙江省监狱管理局和浙江省女子监狱分别指派警官出庭执行职务，吴英到庭参加诉讼。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	庭审中，合议庭依法组织各方当事人进行了举证、质证，并听取了各方当事人发表的意见，查明：吴英在死刑缓期执行期间，没有故意犯罪，经教育能认罪悔罪，服从管教，无违规扣分；积极参加政治、文化和技能学习，自觉参加生产劳动，确有悔改表现。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	浙江省高级人民法院裁定认为，吴英在死刑缓期二年执行期间，没有故意犯罪，没有重大立功表现，且确有悔改表现，应予减刑。依照《中华人民共和国刑事诉讼法》第二百五十条第二款，《中华人民共和国刑法》第五十条、第五十一条、第五十七条第一款、第七十九条之规定，裁定：将吴英的死缓刑减为无期徒刑，剥夺政治权利终身。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	<strong>吴英案大事记</strong>\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2007年3月16日，吴英因涉嫌非法吸收公众存款罪被逮捕。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2009年12月18日，吴英案于获浙江金华中级人民法院一审判决。判决认定，吴英于2005年5月至2007年2月间，以非法占有为目的，采用虚构事实、隐瞒真相、以高额利息为诱饵等手段，向社会公众非法集资人民币7.7亿元。案发时尚有3.8亿元无法归还，并大量欠债，一审判处被告人吴英死刑，剥夺政治权利终身，并处没收其个人全部财产。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2010年1月，吴英不服一审判决，提起上诉。2012年1月18日，浙江高院驳回吴英上诉，维持一审死刑判决，并报最高院复核。4月20日，最高法裁定不核准吴英死刑，将案件发回浙江高院重审。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	5月21日，浙江省高院做出终审判决，以集资诈骗罪判处被告人吴英死刑，缓期二年执行，剥夺政治权利终身，并处没收其个人全部财产。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	终审判决书中认定，吴英被扣押的财产价值1.7亿元。但吴英一方认为该鉴定结论明显偏低，漏计很多资产。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2013年2月20日，吴英家人向最高院提交刑事申诉状，要求依法撤销浙江省高院做出的（2012）浙刑二重字第1号刑事判决书，改判无罪，并主张吴英的资产完全可以偿清债务。\r\n</p>', '78b7602f20ae6106a6241c20370447ab.jpg', 0, 0, 17, 23, 0, '', '', '2014-07-11 06:41:02'),
(4, '全国政协原经济委员会副主任杨刚被双开', '<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	中新网7月11日电 据中央纪委监察部网站消息，日前，中共中央纪委对政协第十二届全国委员会经济委员会原副主任杨刚严重违纪违法问题进行了立案审查。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	经查，杨刚利用职务上的便利为他人谋取利益，收受巨额贿赂；与他人通奸。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	杨刚的上述行为已构成严重违纪，其中受贿问题已涉嫌违法犯罪。依据《中国共产党纪律处分条例》等有关规定，经中央纪委审议并报中共中央批准，决定给予杨刚开除党籍、开除公职处分；将其涉嫌犯罪问题及线索移送司法机关依法处理。\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	<strong>杨刚简历</strong>\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	1984.04至1985.07 共青团新疆生产建设兵团委员会办公室主任\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	1991.12至1993.10 共青团新疆维吾尔自治区委员会副书记、党组书记\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	1994.06至1997.07 新疆维吾尔自治区吐鲁番地委书记\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2000.12至2001.03 新疆维吾尔自治区党委常委，乌鲁木齐市委书记\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2004.12至2006.10 新疆维吾尔自治区党委常委，乌昌党委书记，乌鲁木齐市委书记，新疆生产建设兵团农十二师党委第一书记、第一政委\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2007.05至2010.12 新疆维吾尔自治区党委副书记，自治区常务副主席，自治区委党校校长\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2010.12至2013.07 国家质量监督检验检疫总局党组副书记、副局长\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2013.03 政协第十二届全国委员会经济委员会副主任\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	中共十六大 、十七大代表 ，第十六、十七届候补中央委员，第十一届全国人大代表\r\n</p>\r\n<p style="font-size:16px;font-family:宋体, Arial, sans-serif;text-indent:2em;background-color:#FFFFFF;">\r\n	2013年12月27日电，据中央纪委监察部网站消息，政协第十二届全国委员会经济委员会副主任杨刚涉嫌严重违纪违法，目前正接受组织调查。\r\n</p>', 'b62be168ecb64b6758e401c2642138be.jpg', 0, 0, 17, 20, 0, '', '', '2014-07-11 06:51:24');

-- --------------------------------------------------------

--
-- 表的结构 `zs_news_category`
--

CREATE TABLE IF NOT EXISTS `zs_news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '新闻类别名',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序(降序排列)',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:隐藏 0:显示',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id(无限递归,0代表没有父类)',
  `en_title` varchar(50) NOT NULL COMMENT '英文标题',
  `level` tinyint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- 转存表中的数据 `zs_news_category`
--

INSERT INTO `zs_news_category` (`id`, `title`, `sort`, `remark`, `is_hidden`, `parent_id`, `en_title`, `level`) VALUES
(20, '心情故事', 2, '', 0, 0, '', 0),
(21, '随心所欲', 1, '', 0, 0, '', 0),
(23, '文艺青年', 0, '', 0, 0, '', 0),
(24, '八卦趣事', 0, '', 0, 0, '', 0),
(25, '有口难开', 0, '', 0, 0, '', 0),
(26, '风轻云淡', 0, '', 0, 0, '', 0),
(27, '搞笑文章', 0, '', 0, 0, '', 0),
(28, '最喜名句', 0, '', 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `zs_question`
--

CREATE TABLE IF NOT EXISTS `zs_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `zs_question`
--

INSERT INTO `zs_question` (`id`, `title`, `content`, `user_id`, `create_time`) VALUES
(1, '突然肚子痛怎么办呢', '突然肚子痛怎么办呢', 1, '2014-07-11 15:18:25'),
(2, '验证问答', '测试', 2, '2014-07-15 12:05:53');

-- --------------------------------------------------------

--
-- 表的结构 `zs_question_category`
--

CREATE TABLE IF NOT EXISTS `zs_question_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '新闻类别名',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序(降序排列)',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:隐藏 0:显示',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id(无限递归,0代表没有父类)',
  `en_title` varchar(50) NOT NULL COMMENT '英文标题',
  `level` tinyint(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `zs_question_category`
--

INSERT INTO `zs_question_category` (`id`, `title`, `sort`, `remark`, `is_hidden`, `parent_id`, `en_title`, `level`) VALUES
(1, '医学问答', 0, '', 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `zs_question_reply`
--

CREATE TABLE IF NOT EXISTS `zs_question_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `question_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `zs_question_reply`
--

INSERT INTO `zs_question_reply` (`id`, `user_id`, `content`, `create_time`, `question_id`) VALUES
(1, 1, '吃肚子痛的药就好了', '2014-07-11 15:18:38', 1);

-- --------------------------------------------------------

--
-- 表的结构 `zs_role`
--

CREATE TABLE IF NOT EXISTS `zs_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) NOT NULL DEFAULT '0',
  `is_forbid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`is_forbid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- 转存表中的数据 `zs_role`
--

INSERT INTO `zs_role` (`id`, `name`, `pid`, `is_forbid`, `remark`) VALUES
(29, '管理员', 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `zs_role_menu`
--

CREATE TABLE IF NOT EXISTS `zs_role_menu` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色名',
  `menu_id` smallint(6) unsigned NOT NULL COMMENT '菜单名',
  PRIMARY KEY (`role_id`,`menu_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `zs_role_menu`
--

INSERT INTO `zs_role_menu` (`role_id`, `menu_id`) VALUES
(29, 3),
(29, 4),
(29, 5),
(29, 6),
(29, 7),
(29, 8),
(29, 9),
(29, 10),
(29, 11),
(29, 12),
(29, 13),
(29, 14),
(29, 15),
(29, 16),
(29, 17),
(29, 18),
(29, 19),
(29, 20),
(29, 21),
(29, 22),
(29, 23),
(29, 24),
(29, 25),
(29, 26),
(29, 27),
(29, 28),
(29, 29),
(29, 30),
(29, 31),
(29, 32),
(29, 33),
(29, 34),
(29, 35),
(29, 36),
(29, 37),
(29, 38),
(29, 39),
(29, 40),
(29, 41),
(29, 42),
(29, 43),
(29, 44),
(29, 45),
(33, 3),
(33, 4),
(33, 5),
(33, 6),
(33, 7),
(33, 8),
(33, 9),
(33, 10),
(33, 11),
(33, 12),
(33, 13),
(33, 14),
(33, 15),
(33, 16),
(33, 17),
(33, 18),
(33, 19),
(33, 20),
(33, 21),
(33, 22),
(33, 23),
(33, 24),
(33, 25),
(33, 26),
(33, 27),
(33, 28),
(33, 29),
(33, 30),
(33, 31),
(33, 32),
(33, 33),
(33, 34),
(33, 35),
(33, 36),
(33, 37),
(33, 38),
(33, 39),
(33, 40),
(33, 41),
(33, 42),
(33, 43),
(33, 44),
(33, 45),
(34, 3),
(34, 4),
(34, 5),
(34, 6),
(34, 7),
(34, 8),
(34, 9),
(34, 10),
(34, 11),
(34, 12),
(34, 13),
(34, 14),
(34, 15),
(34, 16),
(34, 17),
(34, 18),
(34, 19),
(34, 20),
(34, 21),
(34, 22),
(34, 23),
(34, 24),
(34, 25),
(34, 26),
(34, 27),
(34, 28),
(34, 29),
(34, 30),
(34, 31),
(34, 32),
(34, 33),
(34, 34),
(34, 35),
(34, 36),
(34, 37),
(34, 38),
(34, 39),
(34, 40),
(34, 41),
(34, 42),
(34, 43),
(34, 44),
(34, 45),
(35, 3),
(35, 4),
(35, 5),
(35, 6),
(35, 7),
(35, 8),
(35, 9),
(35, 10),
(35, 11),
(35, 12),
(35, 13),
(35, 14),
(35, 15),
(35, 16),
(35, 17),
(35, 18),
(35, 19),
(35, 20),
(35, 21),
(35, 22),
(35, 23),
(35, 24),
(35, 25),
(35, 26),
(35, 27),
(35, 28),
(35, 29),
(35, 30),
(35, 31),
(35, 32),
(35, 33),
(35, 34),
(35, 35),
(35, 36),
(35, 37),
(35, 38),
(35, 39),
(35, 40),
(35, 41),
(35, 42),
(35, 43),
(35, 44),
(35, 45);

-- --------------------------------------------------------

--
-- 表的结构 `zs_slider`
--

CREATE TABLE IF NOT EXISTS `zs_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(200) NOT NULL COMMENT '图片路径',
  `describe` varchar(255) NOT NULL COMMENT '描述',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `href` varchar(255) NOT NULL COMMENT '超级连接地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='首页缩略图表' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `zs_slider`
--

INSERT INTO `zs_slider` (`id`, `image`, `describe`, `create_time`, `href`) VALUES
(6, 'bdf3bf1da3405725be763540d6601144.jpg', '222', '2014-07-11 05:56:37', '#');

-- --------------------------------------------------------

--
-- 表的结构 `zs_special`
--

CREATE TABLE IF NOT EXISTS `zs_special` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '标题',
  `en_title` text NOT NULL COMMENT '英文标题',
  `content` text NOT NULL COMMENT '内容',
  `en_content` text NOT NULL COMMENT '英文内容',
  `sort` int(11) NOT NULL COMMENT '排序',
  `name` varchar(20) NOT NULL,
  `small_img` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `big_img` varchar(255) NOT NULL COMMENT '大图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='特殊主题' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `zs_special`
--

INSERT INTO `zs_special` (`id`, `title`, `en_title`, `content`, `en_content`, `sort`, `name`, `small_img`, `category_id`, `big_img`) VALUES
(1, '企业文化', '', '<span></span><span style="font-size:14px;">【企业广告语】</span><br />\r\n<span style="font-size:14px;"> 用富贵龙门、享富贵人生</span><br />\r\n<span style="font-size:14px;"> 【企业理念】</span><br />\r\n<span style="font-size:14px;"> 勤、俭、精、诚、信（业精于勤、刻苦节俭、精益求精、以诚待人、取信天下）</span><span style="font-size:14px;"></span><span style="font-size:14px;"></span><br />\r\n<span style="font-size:14px;"> 【企业使命】</span><br />\r\n<span style="font-size:14px;"> 创新科技、共享生活</span><br />\r\n<span style="font-size:14px;"> 【企业宗旨】</span><br />\r\n<span style="font-size:14px;"> 为客户创造价值，为员工创造机会，为社会作出贡献</span><br />\r\n<span style="font-size:14px;"> 【产品定位】</span><br />\r\n<span style="font-size:14px;"> 门业产品中的精品，富有独特的品质魅力及较高的产品附加值</span><br />\r\n<span style="font-size:14px;"> 【质量方针】</span><br />\r\n<span style="font-size:14px;"> 无惧时间考验，品质依然保证</span><br />\r\n<span style="font-size:14px;"> 【核心价值观】</span><br />\r\n<span style="font-size:14px;"> 1、为客户服务，致力于客户的需求与满意度</span><br />\r\n<span style="font-size:14px;"> 2、追求速度和效率、专注于对客户和公司有影响的创新</span><br />\r\n<span style="font-size:14px;"> 3、基于事实的决策与业务管理</span><br />\r\n<span style="font-size:14px;"> 4、建立信任与负责的人际关系</span><br />\r\n<span style="font-size:14px;"> 5、与友商共同发展、即是竞争对手、也是合作伙伴、共同创造良好的生存空间、共同价值的利益！</span><span></span><br />', '<p>【企业广告语】</p><p>\r\n用富贵龙门、享富贵人生</p><p>\r\n【企业理念】</p><p>\r\n勤、俭、精、诚、信（业精于勤、刻苦节俭、精益求精、以诚待人、取信天下）</p><p>\r\n【企业目标】</p><p>\r\n创全球名牌，建国际企业</p><p>\r\n【企业使命】</p><p>\r\n创新科技、共享生活</p><p>\r\n【企业宗旨】</p><p>\r\n为客户创造价值，为员工创造机会，为社会作出贡献</p><p>\r\n【产品定位】</p><p>\r\n门业产品中的精品，富有独特的品质魅力及较高的产品附加值</p><p>\r\n【质量方针】</p><p>\r\n无惧时间考验，品质依然保证</p><p>\r\n【核心价值观】</p><p>\r\n1、为客户服务，致力于客户的需求与满意度</p><p>\r\n2、追求速度和效率、专注于对客户和公司有影响的创新</p><p>\r\n3、基于事实的决策与业务管理</p><p>\r\n4、建立信任与负责的人际关系</p><p>\r\n5、与友商共同发展、即是竞争对手、也是合作伙伴、共同创造良好的生存空间、共同价值的利益！</p>			', 1, 'cultural', '', 1, ''),
(2, '公司简介', '', '<p>\r\n	宁波市江北富贵龙电动门经营部是一家专业生产、销售、维修各种中高档电动伸缩门、电动卷帘门、防火卷帘门、抗风卷帘门、铝合金卷帘门、不锈钢网型门、遥控车库门、道闸、护栏等系列门类产品，经过多年来的不断努力，公司秉承“品质诚信”的营商之道，以高品质作为企业的核心生命力，使消费者利益获得结实的保障，并令富贵龙门业得以稳健发展，持续进步。\r\n</p>\r\n<p>\r\n	富贵龙门业以现代科学管理模式，集结先进专业设备与精湛工艺，用严紧苛刻的质检程序来完成每一款产品。产品以“质优价廉、经典耐用、时尚高贵、出众的品质、卓越的性能、安全性高”等特点，深受广大消费的赞誉，已成为党政机关、企事业单位、部队、学校的首选产品。\r\n</p>\r\n<p>\r\n	专注于创新与人本的进步哲学，全力务求与众多商家及消费者达致共赢。展望未来，富贵龙门业将一如既往和各界的朋友一起领导门业潮流，创造门业精品，与大家携手共同迈进富贵之门！\r\n</p>\r\n<br />', '', 3, 'profile', 'small_f23e01745da1bc710c045987e2511f30.jpg', 1, 'big_f23e01745da1bc710c045987e2511f30.jpg'),
(3, '公司荣誉', '', '公司荣誉333333333333', '', 2, 'honor', '', 1, ''),
(4, '联系我们', '', '<p>\r\n	宁波市江北富贵龙电动门经营部\r\n</p>\r\n<p>\r\n	电 &nbsp; 话：0574-87668356\r\n</p>\r\n<p>\r\n	传 &nbsp; 真：0574-27858735\r\n</p>\r\n<p>\r\n	手 &nbsp; 机：13777042298\r\n</p>\r\n<p>\r\n	联系人：王先生\r\n</p>\r\n<p>\r\n	邮 &nbsp; 箱：270113808@qq.com\r\n</p>\r\n<p>\r\n	网&nbsp;&nbsp; &nbsp;址：www.nbfgl.com\r\n</p>\r\n<p>\r\n	地&nbsp;&nbsp; &nbsp;址：宁波市江北区康桥南路天合家园小区门口201号1-2\r\n</p>', '', 0, 'contact', '', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `zs_tongji`
--

CREATE TABLE IF NOT EXISTS `zs_tongji` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `access_time` mediumint(8) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '离开时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `zs_tongji`
--

INSERT INTO `zs_tongji` (`id`, `user_id`, `access_time`, `url`, `time_stamp`) VALUES
(1, 1, 0, 'http://localhost/hunan/index.php/News/read/id/4.html', '0000-00-00 00:00:00'),
(2, 1, 5, 'http://localhost/hunan/index.php/News/read/id/4.html', '0000-00-00 00:00:00'),
(3, 1, 346, 'http://localhost/hunan/index.php/News/search.html?keyword=111111', '2014-07-30 09:05:49'),
(4, 1, 0, 'http://localhost/hunan/index.php/News/read/id/4.html', '2014-07-30 09:06:49');

-- --------------------------------------------------------

--
-- 表的结构 `zs_tongji_search`
--

CREATE TABLE IF NOT EXISTS `zs_tongji_search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `zs_tongji_search`
--

INSERT INTO `zs_tongji_search` (`id`, `user_id`, `keyword`, `time_stamp`) VALUES
(1, 0, '111', '2014-07-30 01:01:03'),
(2, 1, '111111', '2014-07-30 08:59:48');

-- --------------------------------------------------------

--
-- 表的结构 `zs_user`
--

CREATE TABLE IF NOT EXISTS `zs_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip` varchar(15) NOT NULL,
  `last_login_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `avater_pic` varchar(37) NOT NULL,
  `sign` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `weixin` varchar(50) NOT NULL,
  `gender` enum('0','1') NOT NULL,
  `birthday` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `information` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `zs_user`
--

INSERT INTO `zs_user` (`id`, `username`, `password`, `create_time`, `last_login_ip`, `last_login_time`, `avater_pic`, `sign`, `email`, `qq`, `weixin`, `gender`, `birthday`, `information`) VALUES
(1, 'zouhao', 'e10adc3949ba59abbe56e057f20f883e', '2014-07-30 00:59:21', '0.0.0.0', '2014-07-30 08:59:45', '', '', '', '', '', '0', '0000-00-00 00:00:00', ''),
(2, 'aaa', '670b14728ad9902aecba32e22fa4f6bd', '2014-07-15 12:03:58', '60.55.42.176', '2014-07-15 12:03:58', '', '', '', '', '', '0', '0000-00-00 00:00:00', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
