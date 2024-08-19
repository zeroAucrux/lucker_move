-- SQL Dump by Erik Edgren
-- version 1.0
--
-- SQL Dump created: May 29th, 2024 @ 7:06 am

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";



-- --------------------------------------------------------



--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_ad`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) CHARACTER SET utf8 DEFAULT '' COMMENT '类型',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '标题',
  `content` varchar(1500) DEFAULT '' COMMENT '内容',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '描述',
  `image` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '图片',
  `url` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '跳转链接',
  `sort` int(11) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 ',
  `target` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '跳转',
  `create_time` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `lang` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '语言',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COMMENT='广告表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_ad` (`id`, `type`, `title`, `content`, `description`, `image`, `url`, `sort`, `status`, `target`, `create_time`, `update_time`, `lang`) VALUES
(96, 'banner', '首页轮播图1', ' <div class=\"style-banner \">\r\n        <h5>运输行业解决方案</h5>\r\n        <h4 class=\"mb-lg-3 mb-2\">Transportation industry solutions</h4>\r\n  </div>\r\n<div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n          <a href=\"{$item.url}\" class=\"btn scroll\">立即查看</a>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner1.jpeg', '#', 1, 1, '_self', 1661085982, 1689385082, 'zh-cn'),
(97, 'news_banner', '新闻页banner', '', '', '/assets/addons/ldcms/default/images/banner2.jpeg', '#', 9, 1, '_self', 1661091370, 1689385132, 'zh-cn'),
(98, 'banner', '首页轮播图2', ' <div class=\"style-banner \">\r\n       <h5>建筑行业解决方案</h5>\r\n       <h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n  </div>\r\n<div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n          <a href=\"{$item.url}\" class=\"btn scroll\">立即查看</a>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner2.jpeg', '#', 2, 1, '_self', 1661092145, 1689385092, 'zh-cn'),
(99, 'banner', '首页轮播3', ' <div class=\"style-banner \">\r\n      <h5>钢铁行业解决方案</h5>\r\n      <h4 class=\"mb-lg-3 mb-2\">Solutions for steel industry</h4>\r\n  </div>\r\n<div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n          <a href=\"{$item.url}\" class=\"btn scroll\">立即查看</a>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner3.jpeg', '#', 3, 1, '_self', 1661777214, 1689385100, 'zh-cn'),
(100, 'product_banner', '产品页banner', '', '', '/assets/addons/ldcms/default/images/banner1.jpeg', '#', 9, 1, '_self', 1662126162, 1689385125, 'zh-cn'),
(101, 'team_banner', '服务团队页banner', NULL, '', '/assets/addons/ldcms/default/images/casebanner.jpeg', '#', 9, 1, '_self', 1662212366, 1673535239, 'zh-cn'),
(102, 'api_about', 'api关于我们背景', '', '', '/assets/addons/ldcms/default/images/ebg.jpg', '#', 9, 1, '_self', 1671413071, 1689385114, 'zh-cn'),
(108, 'news_banner', '新闻页banner', '', '', '/assets/addons/ldcms/default/images/banner2.jpeg', '#', 9, 1, '_self', 1661091370, 1689387826, 'en'),
(106, 'case_banner', '案例页banner', '', '', '/assets/addons/ldcms/default/images/banner3.jpeg', '#', 9, 1, '_self', 1673535228, 1689385107, 'zh-cn'),
(107, 'banner', '首页轮播图1', '<div class=\"container slide-content text-center\">\r\n        <div class=\"style-banner \">\r\n              <h4 class=\"mb-lg-3 mb-2\">Transportation industry solutions</h4>\r\n               <h5>Transportation industry solutions</h5>\r\n        </div>\r\n\r\n        <div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n               <a href=\"{$item.url}\" class=\"btn scroll\">More</a>\r\n         </div>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner1.jpeg', '#', 1, 1, '_self', 1661085982, 1689387774, 'en'),
(109, 'banner', '首页轮播图2', '<div class=\"container slide-content text-center\">\r\n        <div class=\"style-banner \">\r\n              <h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n              <h5>Construction industry solutions</h5>\r\n        </div>\r\n\r\n        <div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n               <a href=\"{$item.url}\" class=\"btn scroll\">More</a>\r\n         </div>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner2.jpeg', '#', 2, 1, '_self', 1661092145, 1689387789, 'en'),
(110, 'banner', '首页轮播3', '<div class=\"container slide-content text-center\">\r\n        <div class=\"style-banner \">\r\n              <h4 class=\"mb-lg-3 mb-2\">Solutions for steel industry</h4>\r\n              <h5>Solutions for steel industry</h5>\r\n        </div>\r\n\r\n        <div class=\"view-buttn mt-md-4 mt-sm-4 mt-3\">\r\n               <a href=\"{$item.url}\" class=\"btn scroll\">More</a>\r\n         </div>\r\n</div>', '', '/assets/addons/ldcms/default/images/banner3.jpeg', '#', 3, 1, '_self', 1661777214, 1689387796, 'en'),
(111, 'product_banner', '产品页banner', '', '', '/assets/addons/ldcms/default/images/banner1.jpeg', '#', 9, 1, '_self', 1662126162, 1689387820, 'en'),
(112, 'team_banner', '服务团队页banner', NULL, '', '/assets/addons/ldcms/default/images/casebanner.jpeg', '#', 9, 1, '_self', 1662212366, 1673535239, 'en'),
(113, 'api_about', 'api关于我们背景', '', '', '/assets/addons/ldcms/default/images/ebg.jpg', '#', 9, 1, '_self', 1671413071, 1689387811, 'en'),
(114, 'case_banner', '案例页banner', '', '', '/assets/addons/ldcms/default/images/banner3.jpeg', '#', 9, 1, '_self', 1673535228, 1689387803, 'en'),
(115, 'about', '首页关于我们背景', '', '', '/assets/addons/ldcms/default/images/ebg.jpg', '#', 9, 1, '_self', 1689385792, 1689385807, 'zh-cn'),
(116, 'about', '首页关于我们背景', '', '', '/assets/addons/ldcms/default/images/ebg.jpg', '#', 9, 1, '_self', 1689387864, 1689387883, 'en');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_category`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `ename` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '英文名称',
  `pid` int(11) NOT NULL COMMENT '父ID',
  `mid` int(11) NOT NULL COMMENT '模型',
  `urlname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'url名称',
  `template_list` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '列表页模版',
  `template_detail` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '详情页模版',
  `outlink` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '跳转链接',
  `image` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '栏目缩略图',
  `big_image` varchar(255) DEFAULT '' COMMENT '栏目大图',
  `seo_title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT 'SEO描述',
  `sort` int(10) DEFAULT '99' COMMENT '排序',
  `lang` varchar(255) NOT NULL DEFAULT 'zh-cn' COMMENT '语言',
  `is_target` tinyint(1) DEFAULT '0' COMMENT '是否target',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0 模型 1链接',
  `gid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '浏览权限  0 公开  1 1级会员 2 2级会员 ...',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `create_time` bigint(20) DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(20) DEFAULT NULL COMMENT '更新时间',
  `is_nav` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否导航显示',
  `model_table_name` varchar(255) NOT NULL DEFAULT '' COMMENT '模型名的表名称',
  `subname` varchar(255) DEFAULT NULL COMMENT '子名称',
  `is_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页显示模块',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `urlname` (`urlname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb4 COMMENT='栏目表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_category` (`id`, `name`, `ename`, `pid`, `mid`, `urlname`, `template_list`, `template_detail`, `outlink`, `image`, `big_image`, `seo_title`, `seo_keywords`, `seo_description`, `sort`, `lang`, `is_target`, `type`, `gid`, `status`, `create_time`, `update_time`, `is_nav`, `model_table_name`, `subname`, `is_home`) VALUES
(125, '关于我们', 'about', 0, 1, 'guanyuwomen', '', 'detail_page.html', '', '', '', '', '', '', 1, 'zh-cn', 0, 0, '', 1, 1660810013, 1716934789, 1, 'page', '专注做擅长的事情', 1),
(127, '行业资讯', '', 128, 2, 'hangyezixun', 'list_news.html', 'detail_news.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1660899388, 1661605908, 1, '', NULL, 0),
(128, '新闻中心', '', 0, 2, 'xinwenzhongxin', 'list_news.html', 'detail_news.html', '', '', '', '测试新闻', '', '', 5, 'zh-cn', 0, 0, '', 1, 1660899406, 1716934833, 1, 'news', '提供实时的资讯信息', 1),
(141, '联系我们', '', 0, 1, 'lianxiwomen', '', 'detail_page.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1661005544, 1662162333, 1, '', NULL, 0),
(143, '公司新闻', '', 128, 2, 'gongsixinwen', 'list_news.html', 'detail_news.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1661009757, 1673232822, 1, '', NULL, 0),
(144, '产品中心', '', 0, 6, 'chanpinzhongxin', 'list_product.html', 'detail_product.html', '', '', '', '', '', '产品的描述', 3, 'zh-cn', 0, 0, '', 1, 1661071377, 1716934824, 1, 'product', '凡是别人做不了的我们都擅长', 1),
(145, '案例展示', '', 0, 7, 'anlizhanshi', 'list_case.html', 'detail_case.html', '', '', '', '', '', '', 4, 'zh-cn', 0, 0, '', 1, 1662162227, 1716934801, 1, 'case', '精益求精，精雕细琢', 1),
(146, '品牌设计', '', 145, 7, 'pinpaisheji', 'list_case.html', 'detail_case.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1662162275, 1662162275, 1, '', NULL, 0),
(147, '画册设计', '', 145, 7, 'huacesheji', 'list_case.html', 'detail_case.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1662162301, 1662162301, 1, '', NULL, 0),
(148, '服务团队', '', 0, 8, 'fuwutuandui', 'list_team.html', 'detail_team.html', '', '', '', '', '', '', 6, 'zh-cn', 0, 0, '', 1, 1662162493, 1716934812, 1, 'team', '打造专业的技术团队', 1),
(149, '在线留言', '', 0, 1, 'zaixianliuyan', '', 'detail_message.html', '', '', '', '', '', '', 99, 'zh-cn', 0, 0, '', 1, 1664787637, 1664800087, 1, '', NULL, 0),
(151, '产品系列1', '', 144, 6, 'chanpinxilie1', 'list_product.html', 'detail_product.html', '', '', '', '', '', '', 1, 'zh-cn', 0, 0, '', 1, 1665539057, 1673232855, 1, '', NULL, 0),
(154, 'product', 'product', 0, 6, 'product', 'list_product.html', 'detail_product.html', '', '', '', '', '', '', 5, 'en', 0, 0, '', 1, 1671204463, 1716934927, 1, 'product', 'We are good at what others can\'t do', 1),
(155, 'case', 'case', 0, 7, 'case', 'list_case.html', 'detail_case.html', '', '', '', '', '', '', 15, 'en', 0, 0, '', 1, 1671204647, 1716934881, 1, 'case', 'Strive for perfection', 1),
(156, 'news', 'news', 0, 2, 'news', 'list_news.html', 'detail_news.html', '', '', '', '', '', '', 10, 'en', 0, 0, '', 1, 1671204664, 1716934913, 1, 'news', 'We are good at what others can\'t do', 1),
(157, 'team', 'team', 0, 8, 'team', 'list_team.html', 'detail_team.html', '', '', '', '', '', '', 20, 'en', 0, 0, '', 1, 1671204678, 1716934897, 1, 'team', 'Build a professional technical team', 1),
(158, 'about', 'about', 0, 1, 'about', '', 'detail_page.html', '', '', '', '', '', '', 1, 'en', 0, 0, '', 1, 1671204759, 1716934870, 1, 'page', 'Focus on what you are good at', 1),
(159, 'contact', 'contact', 0, 1, 'contact', '', 'detail_page.html', '', '', '', '', '', '', 99, 'en', 0, 0, '', 1, 1671204817, 1671204817, 1, '', NULL, 0),
(160, 'message', '', 0, 1, 'message', '', 'detail_message.html', '', '', '', '', '', '', 99, 'en', 0, 0, '', 1, 1672922337, 1672922337, 1, '', NULL, 0),
(161, '产品系列2', '', 144, 6, 'chanpinxilie2', 'list_product.html', 'detail_product.html', '', '', '', '', '', '', 2, 'zh-cn', 0, 0, '', 1, 1673232846, 1673232857, 1, '', NULL, 0);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_category_fields`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_category_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `field` char(30) DEFAULT NULL COMMENT '名称',
  `type` varchar(30) DEFAULT NULL COMMENT '类型',
  `title` varchar(30) DEFAULT NULL COMMENT '标题',
  `filterlist` text COMMENT '筛选列表',
  `default` varchar(100) DEFAULT NULL COMMENT '默认值',
  `rule` varchar(100) DEFAULT NULL COMMENT '验证规则',
  `tip` varchar(100) DEFAULT NULL COMMENT '提示消息',
  `decimals` tinyint(4) DEFAULT NULL COMMENT '小数点',
  `length` mediumint(9) DEFAULT NULL COMMENT '长度',
  `minimum` smallint(6) DEFAULT NULL COMMENT '最小数量',
  `maximum` smallint(6) DEFAULT '0' COMMENT '最大数量',
  `extend_html` varchar(255) DEFAULT NULL COMMENT '扩展信息',
  `setting` varchar(1500) DEFAULT NULL COMMENT '配置信息',
  `sort` int(11) DEFAULT '9' COMMENT '排序',
  `create_time` bigint(20) DEFAULT NULL COMMENT '添加时间',
  `update_time` bigint(20) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='栏目自定义字段表';


--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_content_url`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_content_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接',
  `lang` varchar(100) NOT NULL DEFAULT '',
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='文章内容内链';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_content_url` (`id`, `name`, `url`, `lang`, `create_time`, `update_time`) VALUES
(1, '测试', 'http://www.example.com', 'zh-cn', 1665709509, 1673232306),
(3, '测试1', 'www.example.com', 'zh-cn', 1673488459, 1673488532);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_diyform`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_diyform` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) DEFAULT '' COMMENT '表单名称',
  `title` varchar(100) DEFAULT '' COMMENT '标题',
  `table` varchar(50) DEFAULT '' COMMENT '表名',
  `needlogin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录发布',
  `iscaptcha` tinyint(1) unsigned DEFAULT '0' COMMENT '是否启用验证码',
  `create_time` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `setting` varchar(1500) DEFAULT '' COMMENT '表单配置',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='自定义表单表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_diyform` (`id`, `name`, `title`, `table`, `needlogin`, `iscaptcha`, `create_time`, `update_time`, `setting`, `status`) VALUES
(16, 'message', '在线留言', 'ldcms_message', 0, 1, 1672923154, 1673275104, '', 1);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_diyform_fields`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_diyform_fields` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `diyform_id` int(10) NOT NULL DEFAULT '0' COMMENT '表单ID',
  `field` char(30) DEFAULT '' COMMENT '名称',
  `type` varchar(30) DEFAULT '' COMMENT '类型',
  `title` varchar(30) DEFAULT '' COMMENT '标题',
  `filterlist` text COMMENT '筛选列表',
  `default` varchar(100) DEFAULT '' COMMENT '默认值',
  `rule` varchar(100) DEFAULT '' COMMENT '验证规则',
  `tip` varchar(100) DEFAULT '' COMMENT '提示消息',
  `decimals` tinyint(1) DEFAULT NULL COMMENT '小数点',
  `length` mediumint(8) DEFAULT NULL COMMENT '长度',
  `minimum` smallint(6) DEFAULT NULL COMMENT '最小数量',
  `maximum` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '最大数量',
  `extend_html` varchar(255) DEFAULT '' COMMENT '扩展信息',
  `setting` varchar(1500) DEFAULT '' COMMENT '配置信息',
  `sort` int(10) NOT NULL DEFAULT '9' COMMENT '排序',
  `create_time` bigint(16) DEFAULT NULL COMMENT '添加时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `issort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可排序',
  `isfilter` tinyint(1) NOT NULL DEFAULT '0' COMMENT '筛选',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `id` (`diyform_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='自定义字段表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_diyform_fields` (`id`, `diyform_id`, `field`, `type`, `title`, `filterlist`, `default`, `rule`, `tip`, `decimals`, `length`, `minimum`, `maximum`, `extend_html`, `setting`, `sort`, `create_time`, `update_time`, `issort`, `isfilter`, `status`) VALUES
(11, 16, 'uname', 'string', '姓名', NULL, '', 'require', '', NULL, 100, NULL, 0, '', '', 9, 1672923431, 1672923598, 0, 0, 1),
(12, 16, 'mobile', 'string', '手机号', NULL, '', 'require', '', NULL, 100, NULL, 0, '', '', 9, 1672923652, 1672923652, 0, 0, 1),
(13, 16, 'remark', 'string', '内容', NULL, '', 'require', '', NULL, 100, NULL, 0, '', '', 9, 1672923681, 1672923681, 0, 0, 1);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '模型',
  `cid` int(11) DEFAULT NULL COMMENT '栏目',
  `sub_cid` varchar(255) DEFAULT '' COMMENT '副栏目',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '标题',
  `image` text CHARACTER SET utf8 COMMENT '缩略图',
  `pics` text CHARACTER SET utf8 COMMENT '多图',
  `show_time` bigint(16) DEFAULT NULL COMMENT '显示时间',
  `seo_keywords` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT 'seo关键词',
  `seo_description` varchar(500) CHARACTER SET utf8 DEFAULT NULL COMMENT 'seo描述',
  `visits` int(255) DEFAULT '0' COMMENT '浏览次数',
  `likes` int(255) DEFAULT '0' COMMENT '点赞次数',
  `admin_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `author` varchar(100) DEFAULT NULL COMMENT '作者',
  `sort` int(11) NOT NULL DEFAULT '99' COMMENT '排序',
  `lang` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'zh-cn' COMMENT '语言',
  `flag` varchar(255) DEFAULT '' COMMENT '标识',
  `tag` varchar(255) DEFAULT '' COMMENT '标签',
  `outlink` varchar(255) DEFAULT NULL COMMENT '跳转链接',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `gid` varchar(255) DEFAULT '' COMMENT '浏览权限',
  `custom_tpl` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '详情页模版',
  `custom_urlname` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '自定义url',
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `delete_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`,`mid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=utf8mb4 COMMENT='文章基础表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document` (`id`, `mid`, `cid`, `sub_cid`, `title`, `image`, `pics`, `show_time`, `seo_keywords`, `seo_description`, `visits`, `likes`, `admin_id`, `author`, `sort`, `lang`, `flag`, `tag`, `outlink`, `status`, `gid`, `custom_tpl`, `custom_urlname`, `create_time`, `update_time`, `delete_time`) VALUES
(17, 1, 141, '', '联系我们', '/assets/addons/ldcms/default/images/contact.png', '', 1661009533, '', '企业品牌信息技术（北京）有限公司&nbsp;集产品销售、技术服务、软件开发于一身的高科技企业。服务范围涵盖了软件销售、私有云、IT基础架构系统规划、IT咨询、系统实施、信息安全构建、业务系统开发等全方位业务。地址：北京市朝阳区北苑路40号院彩一办公楼一层业务部：010 - 88888888 12345678890技术部：010 - 88888888邮箱：example@example.com网址：www....', 35, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1661009533, 1673536834, NULL),
(53, 1, 125, '', '关于我们', '/assets/addons/ldcms/default/images/about.jpeg', '', 1661740673, '', '企业品牌信息技术（北京）有限公司是一家集产品销售、技术服务、软件开发于一身的高科技企业。服务范围涵盖了软件销售、私有云、IT基础架构系统规划、IT咨询、系统实施、信息安全构建、业务系统开发等全方位业务。网易通正在向着一流的信息化解决方案提供商而努力着。', 1816, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1661740673, 1673531300, NULL),
(178, 7, 146, '', '桌面设计', '/assets/addons/ldcms/default/images/b4.jpeg', '', 1662164485, '', 'logo设计', 21, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1662166443, 1673529455, NULL),
(179, 7, 147, '', 'logo设计_copy', '/assets/addons/ldcms/default/images/b3.jpeg', '', 1662164485, '', 'logo设计', 21, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1662166451, 1673529436, NULL),
(188, 1, 149, '', '在线留言', '/assets/addons/ldcms/default/images/about.jpeg', NULL, 1664787637, '', NULL, 109, 0, NULL, NULL, 99, 'zh-cn', '', '', NULL, 1, '', '', '', 1664787637, 1664787637, NULL),
(191, 1, 158, '', 'about', '/assets/addons/ldcms/default/images/about.jpeg', '', 1671204759, '', 'this is about content', 131, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671204759, 1673566729, NULL),
(192, 1, 159, '', 'contact', '/assets/addons/ldcms/default/images/contact.png', '', 1671204817, '', 'Enterprise Brand Information Technology (Beijing) Co., Ltd&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A high-tech enterprise integrating product sales, technical services and software development. The service scope...', 14, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671204817, 1673573319, NULL),
(193, 8, 157, '', 'Test team content', '/assets/addons/ldcms/default/images/teams1.jpeg', '', 1671204916, '', 'test team content', 0, 0, 0, 'Admin', 99, 'en', 'top', '', '', 1, '', '', '', 1671204978, 1673573607, NULL),
(194, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205276, 1673571511, 1673571511),
(195, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205281, 1673571511, 1673571511),
(196, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205286, 1673571511, 1673571511),
(197, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205286, 1673571511, 1673571511),
(198, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205290, 1673571511, 1673571511),
(199, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205290, 1673571511, 1673571511),
(200, 2, 156, '', '热烈祝贺xx公司，官方网站正式上线_copy_copy', '', '', 1671205200, '', '热烈祝贺xx公司，官方网站正式上线', 4, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205290, 1673571511, 1673571511),
(201, 2, 156, '', 'Group leaders visited the factory and the factory technicians explained', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1671205200, '', 'Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained', 4, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205290, 1673571454, NULL),
(202, 6, 154, '', '新产品上线', '', '', 1671205315, '', '', 1, 0, NULL, NULL, 99, 'en', '', '', '', 1, '', '', '', 1671205364, 1673566416, 1673566416),
(203, 6, 154, '', 'Construction industry solutions', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205368, 1673566408, NULL),
(204, 6, 154, '', 'Solutions for steel industry', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205371, 1673566313, NULL),
(205, 6, 154, '', 'Transportation industry solutions', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1671205371, 1673566150, NULL),
(206, 1, 160, '', 'message', '/assets/addons/ldcms/default/images/about.jpeg', NULL, 1672922337, '', NULL, 28, 0, NULL, NULL, 99, 'en', '', '', NULL, 1, '', '', '', 1672922337, 1672922337, NULL),
(211, 7, 146, '', '桌面设计_copy', '/assets/addons/ldcms/default/images/b2.jpeg', '', 1662164485, '', 'logo设计', 21, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673247618, 1673529412, NULL),
(212, 7, 147, '', 'logo设计_copy_copy', '/assets/addons/ldcms/default/images/b1.jpeg', '', 1662164485, '', 'logo设计', 21, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673247618, 1673529345, NULL),
(213, 8, 148, '', '业务经理', '/assets/addons/ldcms/default/images/teams1.jpeg', '', 1673247755, '', '业务经理的测试内容业务经理的测试内容', 13, 0, 0, 'Admin', 99, 'zh-cn', 'top', '', '', 1, '', '', '', 1673247820, 1673530598, NULL),
(214, 8, 148, '', '技术总监', '/assets/addons/ldcms/default/images/teams2.jpeg', '/assets/addons/ldcms/default/images/teams2.jpeg', 1673247925, '', '模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容', 15, 0, 0, 'Admin', 99, 'zh-cn', 'top', '', '', 1, '', '', '', 1673247984, 1673530694, NULL),
(216, 2, 143, '', '展会策划之超维震撼亮相深圳礼品展_copy', '', '', 1662090058, '', 'te', 55, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673249003, 1673532119, 1673532119),
(217, 2, 143, '', '展会策划之超维震撼亮相深圳礼品展_copy', '', '', 1662090058, '', 'te', 56, 0, NULL, 'Admin', 99, 'zh-cn', NULL, '', '', 1, '', '', '', 1673249006, 1673532119, 1673532119),
(218, 2, 143, '', '展会策划之超维震撼亮相深圳礼品展_copy_copy', '', '', 1662090058, '', 'te', 55, 0, NULL, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673249006, 1673532119, 1673532119),
(219, 2, 127, '', '集团领导到工厂视察，工厂技术人员讲解', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1662090058, '', 'te', 60, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673249013, 1673532160, NULL),
(220, 2, 127, '', '从长岛一直延伸到Plane Land的新隧道', '/assets/addons/ldcms/default/images/b7.jpeg', '', 1662090058, '', '从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道', 63, 0, 0, 'abc', 2, 'zh-cn', '', '', '', 1, '', '', '', 1673249013, 1673532432, NULL),
(221, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 57, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673249013, 1673532162, NULL),
(222, 2, 143, '', '我们为陆上和海上平台提供钢材', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1662090058, '', '他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿', 57, 0, 0, 'abc', 4999, 'zh-cn', '', '', '', 1, '', '', '', 1673249013, 1673532343, NULL),
(253, 8, 148, '', '项目经理', '/assets/addons/ldcms/default/images/teams3.jpeg', '/assets/addons/ldcms/default/images/teams3.jpeg', 1673247925, '', '模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容', 15, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673316748, 1673530679, NULL),
(262, 6, 151, '', '运输行业解决方案', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525154, 1673534528, NULL),
(263, 6, 151, '', '钢铁行业解决方案', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525292, 1673525292, NULL),
(264, 6, 161, '', '建筑行业解决方案', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 3, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525309, 1673525395, NULL),
(265, 6, 151, '', '运输行业解决方案_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 3, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525421, 1673534521, NULL),
(266, 6, 151, '', '钢铁行业解决方案_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525421, 1673525421, NULL),
(267, 6, 161, '', '建筑行业解决方案_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 3, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673525421, 1673525421, NULL),
(268, 8, 148, '', '技术顾问', '/assets/addons/ldcms/default/images/teams4.jpeg', '/assets/addons/ldcms/default/images/teams4.jpeg', 1673247925, '', '模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容模特的描述内容', 14, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673530848, 1673530877, NULL),
(269, 2, 127, '', '集团领导到工厂视察，工厂技术人员讲解_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1662090058, '', '集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解\r\n集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解\r\n集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解', 61, 0, 0, 'Admin', 99999, 'zh-cn', '', '', '', 1, '', '', '', 1673532123, 1673532321, NULL),
(270, 2, 127, '', '从长岛一直延伸到Plane Land的新隧道_copy', '/assets/addons/ldcms/default/images/b7.jpeg', '', 1662090058, '', '从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道', 63, 0, 0, 'abc', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673532123, 1673532451, NULL),
(271, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 57, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673532123, 1673532123, NULL),
(272, 2, 143, '', '我们为陆上和海上平台提供钢材_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1662090058, '', 'te', 56, 0, 0, 'abc', 0, 'zh-cn', '', '', '', 1, '', '', '', 1673532123, 1673532144, NULL),
(273, 6, 151, '', '运输行业解决方案_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534485, NULL),
(274, 6, 151, '', '钢铁行业解决方案_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 0, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534399, NULL),
(275, 6, 161, '', '建筑行业解决方案_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534399, NULL),
(276, 6, 151, '', '运输行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534478, NULL),
(277, 6, 151, '', '钢铁行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534399, NULL),
(278, 6, 161, '', '建筑行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534399, 1673534399, NULL),
(279, 6, 151, '', '运输行业解决方案_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534472, NULL),
(280, 6, 151, '', '钢铁行业解决方案_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 0, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(281, 6, 161, '', '建筑行业解决方案_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(282, 6, 151, '', '运输行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534468, NULL),
(283, 6, 151, '', '钢铁行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(284, 6, 161, '', '建筑行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 2, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(285, 6, 151, '', '运输行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534463, NULL),
(286, 6, 151, '', '钢铁行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 1, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(287, 6, 161, '', '建筑行业解决方案_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 3, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(288, 6, 151, '', '运输行业解决方案_copy_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1673524815, '', '运输行业解决方案,为运输行业提供了非常完善的解决方案这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案', 10, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534459, NULL),
(289, 6, 151, '', '钢铁行业解决方案_copy_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 9, 0, 1, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673534403, 1673534403, NULL),
(290, 6, 161, '', '建筑行业解决方案_copy_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1673525193, '', '钢铁行业解决方案让我们花费很很大的心血才研制出来这些内容专为测试时使用', 3, 0, 1, 'Admin', 99, 'zh-cn', '', 'tag1', '', 1, '', '', '', 1673534403, 1673574195, NULL),
(291, 2, 127, '', '集团领导到工厂视察，工厂技术人员讲解_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1662090058, '', 'te', 60, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673536947, 1673536947, NULL),
(292, 2, 127, '', '从长岛一直延伸到Plane Land的新隧道_copy', '/assets/addons/ldcms/default/images/b7.jpeg', '', 1662090058, '', '从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道', 63, 0, 0, 'abc', 2, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(293, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 57, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(294, 2, 143, '', '我们为陆上和海上平台提供钢材_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1662090058, '', '他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿', 57, 0, 0, 'abc', 4999, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(295, 2, 127, '', '集团领导到工厂视察，工厂技术人员讲解_copy_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1662090058, '', '集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解\r\n集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解\r\n集团领导到工厂视察，工厂技术人员讲解\r\n\r\n集团领导到工厂视察，工厂技术人员讲解', 61, 0, 0, 'Admin', 99999, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(296, 2, 127, '', '从长岛一直延伸到Plane Land的新隧道_copy_copy', '/assets/addons/ldcms/default/images/b7.jpeg', '', 1662090058, '', '从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道从长岛一直延伸到Plane Land的新隧道', 63, 0, 0, 'abc', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(297, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。_copy_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 57, 0, 0, 'Admin', 99, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(298, 2, 143, '', '我们为陆上和海上平台提供钢材_copy_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1662090058, '', 'te', 56, 0, 0, 'abc', 0, 'zh-cn', '', '', '', 1, '', '', '', 1673536948, 1673536948, NULL),
(299, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 58, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673537002, 1673537002, NULL),
(300, 2, 143, '', '我们为陆上和海上平台提供钢材_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1662090058, '', '他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿', 57, 0, 0, 'abc', 4999, 'zh-cn', '', '', '', 1, '', '', '', 1673537002, 1673537002, NULL),
(301, 2, 127, '', '集团领导到工厂视察，工厂技术人员讲解_copy_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1662090058, '', 'te', 60, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673537002, 1673537002, NULL),
(302, 2, 143, '', '在钢铁生产过程中需要注意的10件小事。_copy_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1662090058, '', 'te', 58, 0, 0, 'Admin', 999, 'zh-cn', '', '', '', 1, '', '', '', 1673537002, 1673537002, NULL),
(303, 6, 154, '', 'Construction industry solutions_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566420, 1673566420, NULL),
(304, 6, 154, '', 'Solutions for steel industry_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566420, 1673566420, NULL),
(305, 6, 154, '', 'Transportation industry solutions_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566420, 1673566420, NULL),
(306, 6, 154, '', 'Construction industry solutions_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(307, 6, 154, '', 'Solutions for steel industry_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(308, 6, 154, '', 'Transportation industry solutions_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(309, 6, 154, '', 'Construction industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(310, 6, 154, '', 'Solutions for steel industry_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(311, 6, 154, '', 'Transportation industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566424, 1673566424, NULL),
(312, 6, 154, '', 'Construction industry solutions_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(313, 6, 154, '', 'Solutions for steel industry_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(314, 6, 154, '', 'Transportation industry solutions_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(315, 6, 154, '', 'Construction industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(316, 6, 154, '', 'Solutions for steel industry_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(317, 6, 154, '', 'Transportation industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 0, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(318, 6, 154, '', 'Construction industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(319, 6, 154, '', 'Solutions for steel industry_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(320, 6, 154, '', 'Transportation industry solutions_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 1, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(321, 6, 154, '', 'Construction industry solutions_copy_copy_copy', '/assets/addons/ldcms/default/images/pro3.jpeg', '', 1671205315, '', 'Construction industry solutionssed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod t...', 4, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(322, 6, 154, '', 'Solutions for steel industry_copy_copy_copy', '/assets/addons/ldcms/default/images/pro2.jpeg', '', 1671205315, '', 'Solutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industry', 5, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(323, 6, 154, '', 'Transportation industry solutions_copy_copy_copy', '/assets/addons/ldcms/default/images/pro1.jpeg', '', 1671205315, '', 'Transportation industry solutionsWe are good at what others can\'t doWe are good at what others can\'t doWe are good at what others can\'t do', 3, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673566437, 1673566437, NULL),
(324, 2, 156, '', 'We provide steel for onshore and offshore platforms', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1673571514, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, ...', 1, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571585, 1673571585, NULL),
(325, 2, 156, '', '10 small things to pay attention to in the process of steel production', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1673571613, '', '10 small things to pay attention to in the process of steel production', 2, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571641, 1673571641, NULL),
(326, 2, 156, '', 'Group leaders visited the factory and the factory technicians explained_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1671205200, '', 'Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained', 7, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571645, 1673571645, NULL),
(327, 2, 156, '', 'We provide steel for onshore and offshore platforms_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1673571514, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, ...', 2, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571645, 1673571645, NULL),
(328, 2, 156, '', '10 small things to pay attention to in the process of steel production_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1673571613, '', '10 small things to pay attention to in the process of steel production', 1, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571645, 1673571645, NULL),
(329, 2, 156, '', 'Group leaders visited the factory and the factory technicians explained_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1671205200, '', 'Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained', 4, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(330, 2, 156, '', 'We provide steel for onshore and offshore platforms_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1673571514, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, ...', 0, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(331, 2, 156, '', '10 small things to pay attention to in the process of steel production_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1673571613, '', '10 small things to pay attention to in the process of steel production', 0, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(332, 2, 156, '', 'Group leaders visited the factory and the factory technicians explained_copy_copy', '/assets/addons/ldcms/default/images/b8.jpeg', '', 1671205200, '', 'Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained', 5, 0, 0, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(333, 2, 156, '', 'We provide steel for onshore and offshore platforms_copy_copy', '/assets/addons/ldcms/default/images/b5.jpeg', '', 1673571514, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, ...', 1, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(334, 2, 156, '', '10 small things to pay attention to in the process of steel production_copy_copy', '/assets/addons/ldcms/default/images/b6.jpeg', '', 1673571613, '', '10 small things to pay attention to in the process of steel production', 1, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571648, 1673571648, NULL),
(335, 7, 155, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr', '/assets/addons/ldcms/default/images/b1.jpeg', '', 1673571700, '', '&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consetetur sadipscing elitrLorem ipsum dolor sit amet, consetetur sadipscing elitr', 2, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673571765, 1673571765, NULL),
(336, 7, 155, '', 'Desktop design', '/assets/addons/ldcms/default/images/b2.jpeg', '', 1673572389, '', 'sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.&nbsp;sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptu...', 1, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673572456, 1673572456, NULL),
(337, 7, 155, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr_copy', '/assets/addons/ldcms/default/images/b3.jpeg', '', 1673571700, '', '&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consetetur sadipscing elitrLorem ipsum dolor sit amet, consetetur sadipscing elitr', 2, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673572486, 1673572520, NULL),
(338, 7, 155, '', 'Desktop design_copy', '/assets/addons/ldcms/default/images/b4.jpeg', '', 1673572389, '', 'sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.&nbsp;sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptu...', 0, 0, 1, 'Admin', 99, 'en', '', '', '', 1, '', '', '', 1673572486, 1673572503, NULL),
(339, 8, 157, '', 'Technical consultant', '/assets/addons/ldcms/default/images/teams4.jpeg', '', 1673572615, '', '', 1, 0, 1, 'Admin', 99, 'en', 'new', '', '', 1, '', '', '', 1673572681, 1673573601, NULL),
(340, 8, 157, '', 'Technical Director', '/assets/addons/ldcms/default/images/teams2.jpeg', '', 1673572696, '', '', 3, 0, 1, 'Admin', 99, 'en', 'top', '', '', 1, '', '', '', 1673572733, 1673573422, NULL),
(341, 8, 157, '', 'Project manager', '/assets/addons/ldcms/default/images/teams3.jpeg', '', 1673572739, '', '', 3, 0, 1, 'Admin', 99, 'en', 'top', 'tag1', '', 1, '', '', '', 1673572780, 1673574454, NULL);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_case`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_case` (
  `document_id` int(10) NOT NULL,
  `xmbj` text COMMENT '项目背景',
  `test1` varchar(255) DEFAULT '' COMMENT '测试关联表',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='案例';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_case` (`document_id`, `xmbj`, `test1`) VALUES
(175, '', ''),
(176, '', ''),
(177, '', ''),
(178, '简约风格', ''),
(179, '测试项目背景', ''),
(180, '项目背景logo设计', ''),
(181, '项目背景logo设计', '143'),
(211, '简约风格', ''),
(212, '测试项目背景', ''),
(335, '', ''),
(336, '', ''),
(337, '', ''),
(338, '', '');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_content`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_content` (
  `document_id` int(11) NOT NULL,
  `content` longtext CHARACTER SET utf8 COMMENT '正文内容',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章内容表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_content` (`document_id`, `content`) VALUES
(17, '<p><strong>企业品牌信息技术（北京）有限公司</strong></p>\r\n<p>&nbsp;</p>\r\n<p>集产品销售、技术服务、软件开发于一身的高科技企业。服务范围涵盖了软件销售、私有云、IT基础架构系统规划、IT咨询、系统实施、信息安全构建、业务系统开发等全方位业务。<br />地址：北京市朝阳区北苑路40号院彩一办公楼一层<br />业务部：010 - 88888888 12345678890<br />技术部：010 - 88888888<br />邮箱：example@example.com</p>\r\n<p>网址：www.example.com</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/contact.png\" alt=\"\" width=\"100%\" /></p>'),
(53, '<p>xx企业品牌信息技术（北京）有限公司是一家集产品销售、技术服务、软件开发于一身的高科技企业。服务范围涵盖了软件销售、私有云、IT基础架构系统规划、IT咨询、系统实施、信息安全构建、业务系统开发等全方位业务。我们正在向着一流的信息化解决方案提供商而努力着。</p>\r\n<p><img class=\"img-responsive\" src=\"/assets/addons/ldcms/default/images/about.jpeg\" alt=\" \" width=\"100%\" /></p>\r\n<p>xx企业品牌信息技术（北京）有限公司是一家集产品销售、技术服务、软件开发于一身的高科技企业。服务范围涵盖了软件销售、私有云、IT基础架构系统规划、IT咨询、系统实施、信息安全构建、业务系统开发等全方位业务</p>'),
(178, '<p><img src=\"/assets/addons/ldcms/default/images/b4.jpeg\" alt=\"\" width=\"340\" height=\"280\" /></p>'),
(179, '<p><img src=\"/assets/addons/ldcms/default/images/b3.jpeg\" alt=\"\" width=\"340\" height=\"280\" /></p>\r\n<p>图片展示</p>'),
(188, ''),
(191, '<p>Xx Enterprise Brand Information Technology (Beijing) Co., Ltd. is a high-tech enterprise integrating product sales, technical services and software development. The service scope covers software sales, private cloud, IT infrastructure system planning, IT consulting, system implementation, information security construction, business system development and other comprehensive businesses. We are working towards a first-class information solution provider.</p>\r\n<p><img class=\"img-responsive\" src=\"/assets/addons/ldcms/default/images/about.jpeg\" alt=\" \" width=\"100%\" /></p>\r\n<p>Xx Enterprise Brand Information Technology (Beijing) Co., Ltd. is a high-tech enterprise integrating product sales, technical services and software development. The service scope covers software sales, private cloud, IT infrastructure system planning, IT consulting, system implementation, information security construction, business system development and other comprehensive businesses. We are working towards a first-class information solution provider.</p>'),
(192, '<p>Enterprise Brand Information Technology (Beijing) Co., Ltd</p>\r\n<p>A high-tech enterprise integrating product sales, technical services and software development. The service scope covers software sales, private cloud, IT infrastructure system planning, IT consulting, system implementation, information security construction, business system development and other comprehensive businesses.</p>\r\n<p>Address: Floor 1, Caiyi Office Building, No. 40, Beiyuan Road, Chaoyang District, Beijing</p>\r\n<p>Business Department: 010 - 8888888 12345678890</p>\r\n<p>Technical Department: 010 - 88888888</p>\r\n<p>Email: example@example.com</p>\r\n<p>Enterprise Brand Information Technology (Beijing) Co., Ltd</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/contact.png\" alt=\"\" width=\"100%\" /></p>'),
(193, '<p>test team content</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/teams1.jpeg\" alt=\"\" width=\"1024\" height=\"1009\" /></p>'),
(194, '热烈祝贺xx公司，官方网站正式上线'),
(195, '热烈祝贺xx公司，官方网站正式上线'),
(196, '热烈祝贺xx公司，官方网站正式上线'),
(197, '热烈祝贺xx公司，官方网站正式上线'),
(198, '热烈祝贺xx公司，官方网站正式上线'),
(199, '热烈祝贺xx公司，官方网站正式上线'),
(200, '热烈祝贺xx公司，官方网站正式上线'),
(201, '<p>Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained<img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" /></p>'),
(202, ''),
(203, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(204, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(205, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(206, ''),
(211, '<p><img src=\"/assets/addons/ldcms/default/images/b2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(212, '<p><img src=\"/assets/addons/ldcms/default/images/b1.jpeg\" alt=\"\" /></p>\r\n<p>图片展示</p>'),
(213, '<p>业务经理的测试内容</p>\r\n<p>业务经理的测试内容</p>\r\n<p><img src=\"teams1.jpeg\" alt=\"\" /></p>'),
(214, '<p><img src=\"/assets/addons/ldcms/default/images/teams2.jpeg\" alt=\"\" width=\"1024\" height=\"1009\" /></p>'),
(216, '<p>\r\n	展会策划之超维震撼亮相深圳礼品展\r\n</p>\r\n<p>\r\n	<span style=\"color:#202124;font-family:menlo, monospace;font-size:11px;background-color:#FFFFFF;\">超维携带着新品“宇宙骑士”震撼亮相深圳展会中心举行的大型礼品展</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	\r\n</p>'),
(217, '<p>\r\n	展会策划之超维震撼亮相深圳礼品展\r\n</p>\r\n<p>\r\n	<span style=\"color:#202124;font-family:menlo, monospace;font-size:11px;background-color:#FFFFFF;\">超维携带着新品“宇宙骑士”震撼亮相深圳展会中心举行的大型礼品展</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	\r\n</p>'),
(218, '<p>\r\n	展会策划之超维震撼亮相深圳礼品展\r\n</p>\r\n<p>\r\n	<span style=\"color:#202124;font-family:menlo, monospace;font-size:11px;background-color:#FFFFFF;\">超维携带着新品“宇宙骑士”震撼亮相深圳展会中心举行的大型礼品展</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	\r\n</p>'),
(219, '<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(220, '<p>从长岛一直延伸到Plane Land的新隧道</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b7.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(221, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(222, '<p>我们为陆上和海上平台提供钢材</p>\r\n<p>他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿，你应该承受更多的痛苦，逃离世界末日。某物</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(253, '<p><img src=\"/assets/addons/ldcms/default/images/teams3.jpeg\" alt=\"\" width=\"1024\" height=\"1009\" /></p>'),
(262, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(263, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(264, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(265, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(266, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(267, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(268, '<p><img src=\"/assets/addons/ldcms/default/images/teams4.jpeg\" alt=\"\" width=\"1024\" height=\"1009\" /></p>'),
(269, '<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(270, '<p>从长岛一直延伸到Plane Land的新隧道</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b7.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(271, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(272, '<p>我们为陆上和海上平台提供钢材</p>\r\n<p>他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿，你应该承受更多的痛苦，逃离世界末日。某物</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(273, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(274, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(275, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(276, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(277, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(278, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(279, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(280, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(281, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(282, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(283, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(284, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(285, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(286, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(287, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(288, '<h5>运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5>这些内容是测试的数据，运输行业解决方案,为运输行业提供了非常完善的解决方案</h5>\r\n<h5><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" width=\"100%\" /></h5>'),
(289, '<p>钢铁行业解决方案</p>\r\n<p>让我们花费很很大的心血才研制出来</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(290, '<p>建筑行业解决方案</p>\r\n<p>相对而言要简单些</p>\r\n<p>因为这个行业比较的成熟</p>\r\n<p>这些内容专为测试时使用</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(291, '<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(292, '<p>从长岛一直延伸到Plane Land的新隧道</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b7.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(293, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(294, '<p>我们为陆上和海上平台提供钢材</p>\r\n<p>他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿，你应该承受更多的痛苦，逃离世界末日。某物</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(295, '<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(296, '<p>从长岛一直延伸到Plane Land的新隧道</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b7.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(297, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(298, '<p>我们为陆上和海上平台提供钢材</p>\r\n<p>他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿，你应该承受更多的痛苦，逃离世界末日。某物</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(299, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(300, '<p>我们为陆上和海上平台提供钢材</p>\r\n<p>他意识到自己喜欢它。他们说他们被开除了。我们应该尽最大努力，但没有人会高兴，因为这个意愿，你应该承受更多的痛苦，逃离世界末日。某物</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(301, '<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>集团领导到工厂视察，工厂技术人员讲解</p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(302, '<p>展会策划之超维震撼亮相深圳礼品展</p>\r\n<p><span style=\"color: #202124; font-family: menlo, monospace; font-size: 11px; background-color: #ffffff;\">超维携带着新品&ldquo;宇宙骑士&rdquo;震撼亮相深圳展会中心举行的大型礼品展</span></p>\r\n<p>&nbsp;</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(303, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(304, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(305, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(306, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(307, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(308, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(309, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(310, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(311, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(312, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(313, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(314, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(315, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(316, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(317, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(318, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(319, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(320, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(321, '<h4 class=\"mb-lg-3 mb-2\">Construction industry solutions</h4>\r\n<p>sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing sed do eiusmod tempor incididunt ut Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, eiusmod tempor incididunt ut labore et consectetur adipiscing</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro3.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(322, '<p>Solutions for steel industry</p>\r\n<p>Lorem ipsum dolor sit amet Lorem ipsum dolor sit ametSolutions for steel industryLorem ipsum dolor sit amet Lorem ipsum dolor sit amet</p>\r\n<p>Solutions for steel industry</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro2.jpeg\" alt=\"\" width=\"1024\" height=\"683\" /></p>'),
(323, '<p>Transportation industry solutions</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p>We are good at what others can\'t do</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/pro1.jpeg\" alt=\"\" /></p>'),
(324, '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, pellentesque ullamcorper lectus ut labore et. Suspendisse aliquam, libero non posuere hendrerit, libero ligula interdum tortor</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" /></p>'),
(325, '<p>10 small things to pay attention to in the process of steel production</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(326, '<p>Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained<img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" /></p>'),
(327, '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, pellentesque ullamcorper lectus ut labore et. Suspendisse aliquam, libero non posuere hendrerit, libero ligula interdum tortor</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" /></p>'),
(328, '<p>10 small things to pay attention to in the process of steel production</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(329, '<p>Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained<img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" /></p>'),
(330, '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, pellentesque ullamcorper lectus ut labore et. Suspendisse aliquam, libero non posuere hendrerit, libero ligula interdum tortor</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" /></p>'),
(331, '<p>10 small things to pay attention to in the process of steel production</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(332, '<p>Group leaders visited the factory and the factory technicians explainedGroup leaders visited the factory and the factory technicians explained<img src=\"/assets/addons/ldcms/default/images/b8.jpeg\" alt=\"\" /></p>'),
(333, '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliqu yam erat, sed diam voluptua. Fusce sapien velit, pretium at sapien sed, pellentesque ullamcorper lectus ut labore et. Suspendisse aliquam, libero non posuere hendrerit, libero ligula interdum tortor</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b5.jpeg\" alt=\"\" /></p>'),
(334, '<p>10 small things to pay attention to in the process of steel production</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b6.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(335, '<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>\r\n<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b1.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(336, '<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p>&nbsp;</p>\r\n<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b2.jpeg\" alt=\"\" /></p>\r\n<p>&nbsp;</p>'),
(337, '<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>\r\n<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b3.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(338, '<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p>&nbsp;</p>\r\n<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p>sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>\r\n<p><img src=\"/assets/addons/ldcms/default/images/b4.jpeg\" alt=\"\" width=\"340\" height=\"280\" /></p>\r\n<p>&nbsp;</p>'),
(339, '<p><img src=\"/assets/addons/ldcms/default/images/teams4.jpeg\" alt=\"\" /></p>'),
(340, '<p><img src=\"/assets/addons/ldcms/default/images/teams2.jpeg\" alt=\"\" width=\"100%\" /></p>'),
(341, '<p><img src=\"/assets/addons/ldcms/default/images/teams3.jpeg\" alt=\"\" width=\"100%\" /></p>');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_download`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_download` (
  `document_id` int(10) NOT NULL,
  `downlinks` varchar(2555) DEFAULT '' COMMENT '下载文件',
  `progl` varchar(255) DEFAULT '' COMMENT '管理产品',
  PRIMARY KEY (`document_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='下载';


--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_news`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_news` (
  `document_id` int(10) NOT NULL,
  `test` varchar(255) DEFAULT '' COMMENT '测试',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_news` (`document_id`, `test`) VALUES
(11, '测试字段'),
(42, '测试字段'),
(44, '测试字段'),
(46, '测试字段'),
(48, '测试字段'),
(50, '测试字段'),
(51, '测试字段'),
(108, '测试字段'),
(110, ''),
(111, ''),
(194, ''),
(195, ''),
(196, ''),
(197, ''),
(198, ''),
(199, ''),
(200, ''),
(201, ''),
(215, '测试字段'),
(216, ''),
(217, ''),
(218, ''),
(219, ''),
(220, ''),
(221, ''),
(222, ''),
(223, ''),
(224, ''),
(269, ''),
(270, ''),
(271, ''),
(272, ''),
(291, ''),
(292, ''),
(293, ''),
(294, ''),
(295, ''),
(296, ''),
(297, ''),
(298, ''),
(299, ''),
(300, ''),
(301, ''),
(302, ''),
(324, ''),
(325, ''),
(326, ''),
(327, ''),
(328, ''),
(329, ''),
(330, ''),
(331, ''),
(332, ''),
(333, ''),
(334, '');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_page`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_page` (
  `document_id` int(10) NOT NULL,
  `test` varchar(255) DEFAULT '' COMMENT 'test',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='单页';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_page` (`document_id`, `test`) VALUES
(13, ''),
(16, ''),
(17, ''),
(53, ''),
(188, ''),
(191, ''),
(192, ''),
(206, '');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_product`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_product` (
  `document_id` int(10) NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `zhekou` varchar(255) DEFAULT '' COMMENT '折扣',
  `types` varchar(255) DEFAULT '' COMMENT '类型',
  `test` varchar(255) DEFAULT '' COMMENT '测试',
  `color` varchar(50) DEFAULT '' COMMENT '颜色',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_product` (`document_id`, `price`, `zhekou`, `types`, `test`, `color`) VALUES
(52, 1999.00, NULL, '', '', ''),
(54, 1999.00, NULL, '', '', ''),
(55, 1999.00, NULL, '', '', ''),
(56, 1999.00, NULL, '', '', ''),
(57, 1999.00, NULL, '', '', ''),
(58, 1999.00, NULL, '', '', ''),
(59, 1999.00, NULL, '', '', ''),
(60, 1999.00, NULL, '', '', ''),
(61, 1999.00, NULL, '', '', ''),
(62, 1999.00, NULL, '', '', ''),
(63, 1999.00, NULL, '', '', ''),
(64, 1999.00, NULL, '', '', ''),
(65, 1999.00, NULL, '', '', ''),
(66, 1999.00, NULL, '', '', ''),
(67, 1999.00, NULL, '', '', ''),
(68, 1999.00, NULL, '', '', ''),
(69, 1999.00, NULL, '', '', ''),
(70, 1999.00, NULL, '', '', ''),
(71, 1999.00, NULL, '', '', ''),
(72, 1999.00, NULL, '', '', ''),
(73, 1999.00, NULL, '', '', ''),
(74, 1999.00, NULL, '', '', ''),
(75, 1999.00, NULL, '', '', ''),
(76, 1999.00, NULL, '', '', ''),
(77, 1999.00, NULL, '', '', ''),
(78, 1999.00, NULL, '', '', ''),
(79, 0.00, NULL, '', '', ''),
(80, 0.00, NULL, '', '', ''),
(81, 0.00, NULL, '', '', ''),
(82, 0.00, NULL, '', '', ''),
(83, 0.00, NULL, '', '', ''),
(84, 0.00, NULL, '', '', ''),
(85, 0.00, NULL, '', '', ''),
(86, 0.00, NULL, '', '', ''),
(87, 0.00, NULL, '', '', ''),
(88, 0.00, NULL, '', '', ''),
(89, 0.00, NULL, '', '', ''),
(90, 0.00, NULL, '', '', ''),
(91, 0.00, NULL, '', '', ''),
(92, 0.00, NULL, '', '', ''),
(93, 0.00, NULL, '', '', ''),
(94, 0.00, NULL, '', '', ''),
(95, 0.00, NULL, '', '', ''),
(96, 0.00, NULL, '', '', ''),
(97, 0.00, NULL, '', '', ''),
(98, 0.00, NULL, '', '', ''),
(112, 1999.00, NULL, '', '', ''),
(113, 1999.00, NULL, '', '', ''),
(114, 1999.00, NULL, '', '', ''),
(115, 1999.00, NULL, '', '', ''),
(116, 1999.00, NULL, '', '', ''),
(117, 1999.00, NULL, '', '', ''),
(118, 1999.00, NULL, '', '', ''),
(119, 1999.00, NULL, '', '', ''),
(120, 1999.00, NULL, '', '', ''),
(121, 1999.00, NULL, '', '', ''),
(122, 1999.00, NULL, '', '', ''),
(123, 1999.00, NULL, '', '', ''),
(124, 1999.00, NULL, '', '', ''),
(125, 1999.00, NULL, '', '', ''),
(126, 1999.00, NULL, '', '', ''),
(127, 1999.00, NULL, '', '', ''),
(128, 1999.00, NULL, '', '', ''),
(129, 1999.00, NULL, '', '', ''),
(130, 1999.00, NULL, '', '', ''),
(131, 1999.00, NULL, '', '', ''),
(132, 1999.00, NULL, '', '', ''),
(133, 1999.00, NULL, '', '', ''),
(134, 1999.00, NULL, '', '', ''),
(135, 1999.00, NULL, '', '', ''),
(136, 1999.00, NULL, '', '', ''),
(137, 1999.00, NULL, '', '', ''),
(138, 1999.00, NULL, '', '', ''),
(139, 1999.00, NULL, '', '', ''),
(140, 1999.00, NULL, '', '', ''),
(141, 1999.00, NULL, '', '', ''),
(142, 1999.00, NULL, '', '', ''),
(143, 1999.00, NULL, '', '', ''),
(144, 1999.00, NULL, '', '', ''),
(145, 1999.00, NULL, '', '', ''),
(146, 1999.00, NULL, '', '', ''),
(147, 1999.00, NULL, '', '', ''),
(148, 1999.00, NULL, '', '', ''),
(149, 1999.00, NULL, '', '', ''),
(150, 1999.00, NULL, '', '', ''),
(151, 1999.00, NULL, '', '', ''),
(152, 1999.00, NULL, '', '', ''),
(153, 1999.00, NULL, '', '', ''),
(154, 1999.00, NULL, '', '', ''),
(155, 1999.00, NULL, '', '', ''),
(156, 1999.00, NULL, '', '', ''),
(157, 1999.00, NULL, '', '', ''),
(158, 1999.00, NULL, '', '', ''),
(159, 1999.00, NULL, '', '', ''),
(160, 1999.00, NULL, '', '', ''),
(161, 1999.00, NULL, '', '', ''),
(162, 1999.00, NULL, '', '', ''),
(163, 1999.00, NULL, '', '', ''),
(164, 1999.00, NULL, '', '', ''),
(165, 1999.00, NULL, '', '', ''),
(166, 1999.00, NULL, '', '', ''),
(167, 1999.00, NULL, '', '', ''),
(168, 1999.00, NULL, '', '', ''),
(169, 1999.00, NULL, '', '', ''),
(170, 1999.00, NULL, '', '', ''),
(171, 1999.00, '', '0', '', ''),
(172, 1999.00, NULL, '', '', ''),
(173, 1999.00, '', '0', '', '1'),
(174, 1999.00, '', '0,1', '', '3'),
(202, 0.00, '', '0', '', ''),
(203, 0.00, '', '0,1,2', '', '3'),
(204, 0.00, '', '0,2', '', '2'),
(205, 0.00, '', '0,1', '', '2'),
(209, 1999.00, '', '0', '', '1'),
(210, 1999.00, '', '0,1', '', '3'),
(225, 1999.00, '', '0', '', '1'),
(226, 1999.00, '', '0', '', '1'),
(227, 1999.00, '', '0', '', '1'),
(228, 1999.00, '', '0,1', '', '3'),
(229, 1999.00, '', '0', '', '1'),
(230, 1999.00, '', '0,1', '', '3'),
(231, 1999.00, '', '0', '', '1'),
(232, 1999.00, '', '0,1', '', '3'),
(233, 1999.00, '', '0', '', '1'),
(234, 1999.00, '', '0', '', '1'),
(235, 1999.00, '', '0', '', '1'),
(236, 1999.00, '', '0,1', '', '3'),
(237, 1999.00, '', '0', '', '1'),
(238, 1999.00, '', '0,1', '', '3'),
(239, 1999.00, '', '0', '', '1'),
(240, 1999.00, '', '0,1', '', '3'),
(241, 1999.00, '', '0', '', '1'),
(242, 1999.00, '', '0', '', '1'),
(243, 1999.00, '', '0', '', '1'),
(244, 1999.00, '', '0,1', '', '3'),
(245, 1999.00, '', '0', '', '1'),
(246, 1999.00, '', '0,1', '', '3'),
(247, 1999.00, '', '0', '', '1'),
(248, 1999.00, '', '0,1', '', '3'),
(249, 1999.00, '', '0', '', '1'),
(250, 1999.00, '', '0', '', '1'),
(251, 1999.00, '', '0', '', '1'),
(252, 1999.00, '', '0,1', '', '3'),
(255, 1999.00, '', '0,1', '', '3'),
(256, 1999.00, '', '0', '', '1'),
(257, 1999.00, '', '0', '', '1'),
(262, 0.00, '', '0,1,2', '', '2'),
(263, 0.00, '', '0', '', '1'),
(264, 0.00, '', '0', '', '1'),
(265, 0.00, '', '0,2', '', '2'),
(266, 0.00, '', '0', '', '1'),
(267, 0.00, '', '0', '', '1'),
(273, 0.00, '', '0,1,2', '', '3'),
(274, 0.00, '', '0', '', '1'),
(275, 0.00, '', '0', '', '1'),
(276, 0.00, '', '0,1', '', '2'),
(277, 0.00, '', '0', '', '1'),
(278, 0.00, '', '0', '', '1'),
(279, 0.00, '', '0', '', '2'),
(280, 0.00, '', '0', '', '1'),
(281, 0.00, '', '0', '', '1'),
(282, 0.00, '', '0', '', '4'),
(283, 0.00, '', '0', '', '1'),
(284, 0.00, '', '0', '', '1'),
(285, 0.00, '', '0', '', '3'),
(286, 0.00, '', '0', '', '1'),
(287, 0.00, '', '0', '', '1'),
(288, 0.00, '', '0', '', '2'),
(289, 0.00, '', '0', '', '1'),
(290, 0.00, '', '0', '', '1'),
(303, 0.00, '', '0,1,2', '', '3'),
(304, 0.00, '', '0,2', '', '2'),
(305, 0.00, '', '0,1', '', '2'),
(306, 0.00, '', '0,1,2', '', '3'),
(307, 0.00, '', '0,2', '', '2'),
(308, 0.00, '', '0,1', '', '2'),
(309, 0.00, '', '0,1,2', '', '3'),
(310, 0.00, '', '0,2', '', '2'),
(311, 0.00, '', '0,1', '', '2'),
(312, 0.00, '', '0,1,2', '', '3'),
(313, 0.00, '', '0,2', '', '2'),
(314, 0.00, '', '0,1', '', '2'),
(315, 0.00, '', '0,1,2', '', '3'),
(316, 0.00, '', '0,2', '', '2'),
(317, 0.00, '', '0,1', '', '2'),
(318, 0.00, '', '0,1,2', '', '3'),
(319, 0.00, '', '0,2', '', '2'),
(320, 0.00, '', '0,1', '', '2'),
(321, 0.00, '', '0,1,2', '', '3'),
(322, 0.00, '', '0,2', '', '2'),
(323, 0.00, '', '0,1', '', '2');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_team`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_team` (
  `document_id` int(10) NOT NULL,
  `zhiwei` varchar(255) DEFAULT '' COMMENT '职位',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='团队';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_document_team` (`document_id`, `zhiwei`) VALUES
(193, ''),
(213, '业务经理'),
(214, ''),
(253, '交互设计师'),
(268, '交互设计师'),
(339, ''),
(340, ''),
(341, '');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_document_wordlist`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_document_wordlist` (
  `document_id` int(10) NOT NULL,
  `subtitle` varchar(255) DEFAULT '' COMMENT '副标题',
  PRIMARY KEY (`document_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文档';


--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_fields`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_fields` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL DEFAULT '0' COMMENT '模型',
  `field` char(30) DEFAULT '' COMMENT '名称',
  `type` varchar(30) DEFAULT '' COMMENT '类型',
  `title` varchar(30) DEFAULT '' COMMENT '标题',
  `filterlist` text COMMENT '筛选列表',
  `default` varchar(100) DEFAULT '' COMMENT '默认值',
  `rule` varchar(100) DEFAULT '' COMMENT '验证规则',
  `tip` varchar(100) DEFAULT '' COMMENT '提示消息',
  `decimals` tinyint(1) DEFAULT NULL COMMENT '小数点',
  `length` mediumint(8) DEFAULT NULL COMMENT '长度',
  `minimum` smallint(6) DEFAULT NULL COMMENT '最小数量',
  `maximum` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '最大数量',
  `extend_html` varchar(255) DEFAULT '' COMMENT '扩展信息',
  `setting` varchar(1500) DEFAULT '' COMMENT '配置信息',
  `sort` int(10) NOT NULL DEFAULT '9' COMMENT '排序',
  `create_time` bigint(16) DEFAULT NULL COMMENT '添加时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `issort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可排序',
  `isfilter` tinyint(1) NOT NULL DEFAULT '0' COMMENT '筛选',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `content_list` text COMMENT '数据列表',
  `visible` varchar(255) DEFAULT '' COMMENT '动态显示',
  `islist` tinyint(1) DEFAULT '1' COMMENT '数据列表显示',
  PRIMARY KEY (`id`),
  KEY `id` (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='自定义字段表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_fields` (`id`, `mid`, `field`, `type`, `title`, `filterlist`, `default`, `rule`, `tip`, `decimals`, `length`, `minimum`, `maximum`, `extend_html`, `setting`, `sort`, `create_time`, `update_time`, `issort`, `isfilter`, `status`, `content_list`, `visible`, `islist`) VALUES
(6, 2, 'test', 'string', '测试', NULL, '', '', '', 0, 255, NULL, 0, '', '', 9, 1660653275, 1662467472, 0, 0, 1, NULL, '', 1),
(7, 1, 'test', 'string', 'test', NULL, '', '', '', 0, 255, NULL, 0, '', '', 9, 1660977429, 1662467477, 0, 0, 1, NULL, '', 1),
(8, 6, 'price', 'number', '价格', NULL, '0', '', '', 2, 10, NULL, 0, '', '', 9, 1661073285, 1665134619, 0, 1, 1, NULL, '', 1),
(9, 7, 'xmbj', 'text', '项目背景', NULL, '', '', '', 0, 0, NULL, 0, '', '', 9, 1662162624, 1662467464, 0, 0, 1, NULL, '', 1),
(10, 8, 'zhiwei', 'string', '职位', NULL, '', '', '', 0, 255, NULL, 0, '', '', 9, 1662167625, 1662467454, 0, 0, 1, NULL, '', 1),
(11, 6, 'zhekou', 'string', '折扣', NULL, '', '', '', 0, 255, NULL, 0, '', '', 9, 1662467517, 1665064065, 0, 0, 1, NULL, '', 1),
(12, 6, 'types', 'checkbox', '类型', NULL, '', '', '', 0, 255, 1, 0, '', '', 9, 1665145048, 1665151504, 0, 1, 1, '0:基础\r\n1:专业\r\n2:旗舰\r\n', '', 1),
(14, 6, 'test', 'selectpage', '测试', NULL, '', '', '', 0, 255, 0, 0, '', '{\"table\":\"fa_ldcms_document\",\"primarykey\":\"id\",\"field\":\"title\",\"key\":\"\",\"value\":\"\",\"conditions\":\"{}\"}', 9, 1665458553, 1673140330, 0, 0, 1, '', '', 1),
(15, 7, 'test1', 'selectpage', '测试关联表', NULL, '', '', '', 0, 255, 0, 0, '', '{\"table\":\"fa_ldcms_category\",\"primarykey\":\"id\",\"field\":\"name\",\"conditions\":\"{}\"}', 9, 1665469184, 1665475043, 0, 0, 1, '', '', 1),
(18, 6, 'color', 'radio', '颜色', NULL, '', '', '', 0, 50, 0, 0, '', '{\"table\":\"\",\"primarykey\":\"\",\"field\":\"\",\"conditions\":\"null \",\"key\":\"\",\"value\":\"\"}', 9, 1673234711, 1673234716, 0, 1, 1, '1:红色\r\n2:绿色\r\n3:黄色\r\n4:黑色', '', 1);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_langs`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_langs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '名称',
  `sub_title` varchar(255) DEFAULT '' COMMENT '简称',
  `code` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '编码',
  `domain` varchar(100) DEFAULT '' COMMENT '域名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '默认语言',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='多语言表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_langs` (`id`, `title`, `sub_title`, `code`, `domain`, `status`, `is_default`, `sort`, `create_time`, `update_time`) VALUES
(1, '简体中文', '', 'zh-cn', '', 1, 1, 0, 1675334627, 1675334724),
(2, 'English', '', 'en', '', 1, 0, 0, 1675334645, 1675334645);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_links`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) CHARACTER SET utf8 DEFAULT '' COMMENT '类型',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '标题',
  `image` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT 'logo',
  `url` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '跳转链接',
  `sort` int(11) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 ',
  `target` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '跳转',
  `create_time` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `lang` varchar(255) DEFAULT '' COMMENT '语言',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='友情链接';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_links` (`id`, `type`, `title`, `image`, `url`, `sort`, `status`, `target`, `create_time`, `update_time`, `lang`) VALUES
(1, 'link', '测试2', '', 'http://www.example.com', 10, 1, '_blank', 1662207821, 1673232336, 'zh-cn'),
(2, 'link', '测试1', '', 'http://www.example.com', 9, 1, '_blank', 1662208033, 1673326576, 'zh-cn'),
(4, 'link', 'en google', '', 'http://www.example.com', 9, 1, '_blank', 1672919587, 1672919614, 'en');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_message`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `diyform_id` int(10) NOT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `user_ip` varchar(255) DEFAULT NULL,
  `user_os` varchar(255) DEFAULT NULL,
  `user_bs` varchar(255) DEFAULT NULL,
  `create_time` bigint(20) DEFAULT NULL,
  `update_time` bigint(20) DEFAULT NULL,
  `uname` varchar(100) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(100) DEFAULT '' COMMENT '手机号',
  `remark` varchar(100) DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='在线留言';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_message` (`id`, `diyform_id`, `lang`, `user_ip`, `user_os`, `user_bs`, `create_time`, `update_time`, `uname`, `mobile`, `remark`) VALUES
(1, 16, 'zh-cn', '192.168.32.1', 'Mac', 'Chrome', 1672923706, 1672923706, 'root', '13112341234', 'sasdf'),
(2, 16, 'en', '192.168.32.1', 'Mac', 'Chrome', 1672923742, 1672923742, 'test', '13112341231', 'testtesttesttest'),
(6, 16, 'en', '192.168.32.1', 'Mac', 'Chrome', 1673573182, 1673573182, 'root', '13112341234', 'sdfsfsdf');
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_models`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '模型名',
  `table_name` varchar(128) CHARACTER SET utf8 DEFAULT NULL COMMENT '对应表',
  `template_list` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT '列表模板',
  `template_detail` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT '内容模板',
  `ismenu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台菜单',
  `default_fields` text COMMENT '默认字段',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 0 = 关闭  || 1= 正常',
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='cms 模型表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_models` (`id`, `name`, `table_name`, `template_list`, `template_detail`, `ismenu`, `default_fields`, `sort`, `status`, `create_time`, `update_time`) VALUES
(2, '新闻', 'news', 'list_news.html', 'detail_news.html', 1, NULL, NULL, 1, 1660652121, 1673490730),
(1, '单页', 'page', '', 'detail_page.html', 1, NULL, NULL, 1, 1660729799, 1660953440),
(6, '产品', 'product', 'list_product.html', 'detail_product.html', 1, NULL, NULL, 1, 1661071323, 1673491797),
(7, '案例', 'case', 'list_case.html', 'detail_case.html', 1, NULL, NULL, 1, 1662162090, 1673490728),
(8, '团队', 'team', 'list_team.html', 'detail_team.html', 1, NULL, NULL, 1, 1662162420, 1673491744);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_siteinfo`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_siteinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(50) NOT NULL COMMENT '语言',
  `config` text COMMENT '配置',
  `create_time` bigint(20) DEFAULT NULL,
  `update_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='站点信息表';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_siteinfo` (`id`, `lang`, `config`, `create_time`, `update_time`) VALUES
(1, 'zh-cn', '[{\"name\":\"template\",\"title\":\"前台模板\",\"type\":\"select\",\"content\":\"[\\\"default\\\"]\",\"value\":\"default\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"请确保addons\\/ldcms\\/view有相应的目录\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"sitetitle\",\"title\":\"网站标题\",\"type\":\"string\",\"content\":[],\"value\":\"苏州xx教育公司中文\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"sitesubtitle\",\"title\":\"网站副标题\",\"type\":\"string\",\"content\":[],\"value\":\"苏州xx教育公司中文\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"seo_keywords\",\"title\":\"网站关键词\",\"type\":\"text\",\"content\":[],\"value\":\"苏州xx教育公司\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"seo_description\",\"title\":\"网站描述\",\"type\":\"text\",\"content\":[],\"value\":\"苏州xx教育公司\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"logo1\",\"title\":\"网站logo\",\"type\":\"image\",\"content\":[],\"maximum\":1,\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/logo1.png\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"logo2\",\"title\":\"网站logo\",\"type\":\"image\",\"content\":[],\"maximum\":1,\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/logo2.png\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"compony\",\"title\":\"公司名称\",\"type\":\"string\",\"content\":[],\"value\":\"苏州xx教育公司\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"tel\",\"title\":\"联系电话\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"400-000-0000\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"phone\",\"title\":\"手机号\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"400-000-0000\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"text\",\"name\":\"address\",\"title\":\"地址\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"江苏省苏州市xxx街xx号\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"email\",\"title\":\"邮箱\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"123123@163.com\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"contacts\",\"title\":\"联系人\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"李经理\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"qq\",\"title\":\"在线咨询链接\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"http:\\/\\/wpa.qq.com\\/msgrd?v=3&uin=&site=qq&menu=yes\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"image\",\"name\":\"wechat\",\"title\":\"微信二维码\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/qrcode.png\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"image\",\"name\":\"wechat2\",\"title\":\"微信二维码2\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/qrcode.png\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"icp\",\"title\":\"ICP备案号\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"苏ICP备88888888号\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"copyright\",\"title\":\"底部版权\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"苏州xx教育公司\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"text\",\"name\":\"script\",\"title\":\"第三方代码\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\",\"content\":\"\",\"tip\":\"\",\"visible\":\"\",\"rule\":\"\",\"extend\":\"\"}]', 1680791788, 1689303453),
(2, 'en', '[{\"name\":\"template\",\"title\":\"前台模板\",\"type\":\"select\",\"content\":\"[\\\"default\\\"]\",\"value\":\"en\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"请确保addons\\/ldcms\\/view有相应的目录\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"sitetitle\",\"title\":\"网站标题\",\"type\":\"string\",\"content\":[],\"value\":\"New Point Education啊1111\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"sitesubtitle\",\"title\":\"网站副标题\",\"type\":\"string\",\"content\":[],\"value\":\"苏州xx教育公司中文\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"seo_keywords\",\"title\":\"网站关键词\",\"type\":\"text\",\"content\":[],\"value\":\"New Point Education\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"seo_description\",\"title\":\"网站描述\",\"type\":\"text\",\"content\":[],\"value\":\"New Point Education\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"logo1\",\"title\":\"网站logo\",\"type\":\"images\",\"content\":[],\"maximum\":1,\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/logo1.png\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"logo2\",\"title\":\"网站logo\",\"type\":\"image\",\"content\":[],\"maximum\":1,\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/logo2.png\",\"rule\":\"\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"name\":\"compony\",\"title\":\"公司名称\",\"type\":\"string\",\"content\":[],\"value\":\"New Point Education\",\"rule\":\"required\",\"msg\":\"\",\"tip\":\"\",\"ok\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"tel\",\"title\":\"联系电话\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"400-000-0000\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"phone\",\"title\":\"手机号\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"400-000-0000\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"text\",\"name\":\"address\",\"title\":\"地址\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"Room 1301, Suzhou News Building, No. 118, Bada Street, Suzhou Industrial Park, Jiangsu Province\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"email\",\"title\":\"邮箱\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"hr-zhaopin@163.com\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"contacts\",\"title\":\"联系人\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"Manager Li\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"qq\",\"title\":\"在线咨询链接\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"http:\\/\\/wpa.qq.com\\/msgrd?v=3&uin=&site=qq&menu=yes\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"image\",\"name\":\"wechat\",\"title\":\"微信二维码\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/qrcode.png\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"image\",\"name\":\"wechat2\",\"title\":\"微信二维码2\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\\/assets\\/addons\\/ldcms\\/default\\/images\\/qrcode.png\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"icp\",\"title\":\"ICP备案号\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"苏ICP备88888888号\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"string\",\"name\":\"copyright\",\"title\":\"底部版权\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"xxxTechnology Co., Ltd\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"},{\"type\":\"text\",\"name\":\"script\",\"title\":\"第三方代码\",\"setting\":{\"table\":\"\",\"conditions\":\"\",\"key\":\"\",\"value\":\"\"},\"value\":\"\",\"content\":\"\",\"tip\":\"\",\"rule\":\"\",\"visible\":\"\",\"extend\":\"\"}]', 1680791969, 1689303456);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_tagaction`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_tagaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) DEFAULT '' COMMENT '方法名称',
  `type` enum('sql','func') DEFAULT 'sql' COMMENT '方法类型：sql=sql语句,func=函数名',
  `setting` text COMMENT '方法主体',
  `create_time` bigint(20) DEFAULT NULL,
  `update_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='自定义标签sql';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_tagaction` (`id`, `action`, `type`, `setting`, `create_time`, `update_time`) VALUES
(1, 'product', 'sql', '{\"model\":\"\\\\addons\\\\ldcms\\\\model\\\\Document\",\"name\":\"ldcms_document\",\"db_type\":\"model\",\"alias\":\"document\",\"field\":\"document.*,extend.*,category.name cname,category.urlname curlname\",\"join\":[[\"ldcms_document_content content\",\"content.document_id=document.id\",\"LEFT\"],[\"ldcms_document_product extend\",\"extend.document_id=document.id\",\"LEFT\"],[\"ldcms_category category\",\"category.id=document.cid\",\"LEFT\"]],\"class\":\"\\\\addons\\\\ldcms\\\\model\\\\Document\",\"func\":\"getHomeList\",\"params\":[[\"mid\",\"=\",\"6\",\"AND\"],[\"lang\",\"=\",\":ld_get_home_lang\",\"AND\"],[\"status\",\"=\",\"1\",\"AND\"],{\"0\":\"filter_where\",\"1\":\"CUSTOM\",\"3\":\"AND\"},[\"show_time\",\"<=\",\":time\",\"AND\"],{\"0\":\"cid\",\"1\":\"IN\",\"3\":\"AND\"},{\"0\":\"sub_cid\",\"1\":\"FIND_IN_SET_AND\",\"3\":\"OR\"}]}', 1716512401, 1716730332),
(2, 'home_block', 'sql', '{\"model\":\"\\\\addons\\\\ldcms\\\\model\\\\Category\",\"name\":\"\",\"db_type\":\"model\",\"alias\":\"category\",\"field\":\"id,is_home,name,urlname,model_table_name,subname,pid\",\"class\":\"\",\"func\":\"\",\"params\":[[\"lang\",\"=\",\":ld_get_home_lang\",\"AND\"],[\"status\",\"=\",\"1\",\"AND\"],[\"pid\",\"=\",\"0\",\"AND\"],[\"type\",\"=\",\"0\",\"AND\"],{\"0\":\"model_table_name\",\"1\":\"=\",\"3\":\"AND\"},[\"is_home\",\"=\",\"1\",\"AND\"]],\"join\":null}', 1716732521, 1716733942);
--
-- Remove the table if it exists
--

DROP TABLE IF EXISTS `__PREFIX__ldcms_tags`;


--
-- Create the table if it not exists
--

CREATE TABLE IF NOT EXISTS `__PREFIX__ldcms_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `use_num` int(11) DEFAULT '0' COMMENT '使用次数',
  `lang` varchar(100) NOT NULL DEFAULT '',
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='文章tags\n';


--
-- List the data for the table
--

INSERT INTO `__PREFIX__ldcms_tags` (`id`, `name`, `title`, `use_num`, `lang`, `create_time`, `update_time`) VALUES
(5, 'tag1', 'tag1', 2, 'zh-cn', 1673574196, 1673574196);



