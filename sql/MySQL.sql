-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-04-04 07:41:45
-- 服务器版本： 8.0.35
-- PHP 版本： 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `m2310_bauw`
--

-- --------------------------------------------------------

--
-- 表的结构 `itv_admin`
--

CREATE TABLE `itv_admin` (
  `id` int NOT NULL,
  `name` varchar(16) NOT NULL,
  `psw` varchar(32) NOT NULL,
  `showcounts` tinyint(1) NOT NULL DEFAULT '20',
  `author` tinyint(1) NOT NULL DEFAULT '0',
  `useradmin01` tinyint(1) NOT NULL DEFAULT '0',
  `useradmin` tinyint(1) NOT NULL DEFAULT '0',
  `ipcheck` tinyint(1) NOT NULL DEFAULT '0',
  `mealsadmin` tinyint(1) NOT NULL DEFAULT '0',
  `ordersadmin` tinyint(1) NOT NULL DEFAULT '0',
  `epgadmin` tinyint(1) NOT NULL DEFAULT '0',
  `movieadmin` tinyint(1) NOT NULL DEFAULT '0',
  `channeladmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_admin`
--

INSERT INTO `itv_admin` (`id`, `name`, `psw`, `showcounts`, `author`, `useradmin01`, `useradmin`, `ipcheck`, `mealsadmin`, `ordersadmin`, `epgadmin`, `movieadmin`, `channeladmin`) VALUES
(1, 'admin', '8114c88b2062d554b895f92bd3d7b9b8', 100, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `itv_adminrec`
--

CREATE TABLE `itv_adminrec` (
  `id` int NOT NULL,
  `name` varchar(16) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `loc` varchar(32) DEFAULT NULL,
  `time` varchar(64) NOT NULL,
  `func` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_adminrec`
--

INSERT INTO `itv_adminrec` (`id`, `name`, `ip`, `loc`, `time`, `func`) VALUES
(1, 'admin', '::1', '', '2023-04-04 12:17:43', '用户登入'),

-- --------------------------------------------------------

--
-- 表的结构 `itv_category`
--

CREATE TABLE `itv_category` (
  `id` int NOT NULL,
  `name` varchar(16) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `psw` varchar(16) DEFAULT NULL,
  `type` varchar(16) NOT NULL DEFAULT 'default',
  `url` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_category`
--

INSERT INTO `itv_category` (`id`, `name`, `enable`, `psw`, `type`, `url`) VALUES
(5, 'LOC1', 1, '1234', 'default', NULL),
(4, 'tewst', 1, '1234', 'default', NULL),
(3, '各省卫视', 1, '', 'default', NULL),
(2, '央视频道', 1, '', 'default', NULL),
(1, '试看频道', 1, '', 'default', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `itv_channels`
--

CREATE TABLE `itv_channels` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `category` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_channels`
--

INSERT INTO `itv_channels` (`id`, `name`, `url`, `category`) VALUES
(347, 'TEST', 'rhttp://hw-m-l.cztv.com/channels/lantian/fm88/128k.m3u8', '各省卫视'),
(376, 'CCTV1综合', 'rhttp://hw-m-l.cztv.com/channels/lantian/fm88/128k.m3u8', '试看频道'),
(377, '凤凰卫视', 'https://127.0.0.1/fhx.php?id=fhzw', '试看频道'),
(378, '湖南卫视', 'http://127.0.0.1/index.m3u8', '试看频道'),
(379, '湖南卫视R', 'rhttp://127.0.0.1/index.m3u8', '试看频道'),
(380, 'CCTV2', 'http://127.0.0.1/index.m3u8', '试看频道'),
(381, 'CCTV3', 'http://127.0.0.1/index.m3u8', '试看频道'),
(382, 'CETV-1教育台', 'http://127.0.0.1/y.php?id=33', '央视频道'),
(383, 'CETV-2教育台', 'http://127.0.0.1/y.php?id=36', '央视频道'),
(384, 'MCCTV-1hd', 'http://127.0.0.1/index.m3u8', 'tewst'),
(385, 'MCCTV-2hd', 'http://127.0.0.1/index.m3u8', 'tewst'),
(386, 'MCCTV-3hd', 'http://127.0.0.1/index.m3u8', 'tewst'),
(387, 'MCCTV-10hd', 'http://127.0.0.1/index.m3u8', 'LOC1');
-- --------------------------------------------------------

--
-- 表的结构 `itv_config`
--

CREATE TABLE `itv_config` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_config`
--

INSERT INTO `itv_config` (`id`, `name`, `value`) VALUES
(1, 'adinfo', ''),
(2, 'adtext', '本软件为仅作为测试使用，请勿传播，谢谢配合，联系QQ24348662   ----___---'),
(3, 'appurl', 'https://127.0.0.1/tv.apk'),
(4, 'appurl_sdk14', 'https://127.0.0.1/tv_sdk14.apk'),
(5, 'appver', ''),
(6, 'appver_sdk14', '1.0'),
(7, 'app_appname', '测试骡马'),
(8, 'app_packagename', 'com.ceshi.luoma'),
(9, 'app_sign', '12315'),
(10, 'autoupdate', '1'),
(11, 'buffTimeOut', '20'),
(12, 'dataver', '2'),
(13, 'decoder', '1'),
(14, 'epg_api_chk', '0'),
(15, 'ipcount', '2'),
(16, 'ipchk', '1'),
(17, 'max_sameip_user', '5'),
(18, 'needauthor', '1'),
(19, 'randkey', 'a60c8810e941400dea8540dc86e78c7a'),
(20, 'secret_key', NULL),
(21, 'setver', '3'),
(22, 'showtime', '20'),
(23, 'showinterval', ''),
(24, 'showwea', '1'),
(25, 'tipepgerror_1000', '1000_EPG接口验证失败!'),
(26, 'tipepgerror_1001', '1001_EPG接口验证失败系!'),
(27, 'tipepgerror_1002', '1002_EPG接口验证失败!'),
(28, 'tipepgerror_1003', '1003_EPG接口验证失败!'),
(29, 'tipepgerror_1004', '1004_EPG接口验证失败!'),
(30, 'tipepgerror_1005', '1005_EPG接口验证失败!'),
(31, 'tiploading', '正在连接，请稍后 ...'),
(32, 'tipuserexpired', '账号已到期，请点击上方自助授权或联系授权！'),
(33, 'tipuserforbidden', '因违规操作已被管理员禁用，请联系管理！'),
(34, 'tipusernoreg', '设备未授权，请点击上方自助授权或联系管理授权！'),
(35, 'trialdays', '0'),
(36, 'updateinterval', '10'),
(37, 'up_size', '0.0M'),
(38, 'up_sets', '0'),
(39, 'up_text', '1.公告测试'),
(40, 'weaapi_id', '00000'),
(41, 'weaapi_key', '00000'),
(42, 'alipay_appid', ''),
(43, 'alipay_privatekey', ''),
(44, 'alipay_publickey', ''),
(45, 'epgurl', 'http://diyp.112114.xyz/'),
(46, 'imgstart', 'https://img1.baidu.com/it/u=4260691989,4034520541&fm=253&fmt=auto&app=138&f=JPEG?w=667&h=500'),
(47, 'imgend', 'https://img2.baidu.com/it/u=907409269,4118221465&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500'),
(48, 'tmua', 'SYTV/1.6');

-- --------------------------------------------------------

--
-- 表的结构 `itv_meals`
--

CREATE TABLE `itv_meals` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `amount` int NOT NULL DEFAULT '0',
  `days` int NOT NULL DEFAULT '0',
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_meals`
--

INSERT INTO `itv_meals` (`id`, `name`, `amount`, `days`, `content`, `status`) VALUES
(1000, '试看', 0, 0, '', 1),
(1001, '一级', 0, 7, '央视频道', 1),
(1002, '二级', 0, 30, '央视频道_各省卫视', 1),
(1003, '三级', 0, 90, '央视频道_各省卫视_tewst', 1),
(1004, '四级', 0, 365, '试看频道_央视频道_各省卫视_tewst', 1);

-- --------------------------------------------------------

--
-- 表的结构 `itv_proxy`
--

CREATE TABLE `itv_proxy` (
  `id` int NOT NULL,
  `src` text NOT NULL,
  `proxy` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_proxy`
--

INSERT INTO `itv_proxy` (`id`, `src`, `proxy`) VALUES
(1, 'eJzLKCkpsNLXNzQ01DMystQzMjTVMzQx0s8sKdMHAGSMBvM=\r\n', 'eJwrzi+w0tcHAAfeAes=');

-- --------------------------------------------------------

--
-- 表的结构 `itv_serialnum`
--

CREATE TABLE `itv_serialnum` (
  `id` int NOT NULL,
  `sn` bigint NOT NULL,
  `regid` int NOT NULL DEFAULT '0',
  `regtime` int NOT NULL DEFAULT '0',
  `exp` int NOT NULL,
  `author` varchar(16) NOT NULL,
  `createtime` int NOT NULL,
  `enable` int NOT NULL DEFAULT '0',
  `marks` varchar(100) NOT NULL,
  `vip` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `itv_users`
--

CREATE TABLE `itv_users` (
  `id` int NOT NULL,
  `name` bigint NOT NULL,
  `mac` varchar(32) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(32) DEFAULT NULL,
  `exp` bigint NOT NULL,
  `vpn` tinyint NOT NULL DEFAULT '0',
  `idchange` tinyint NOT NULL DEFAULT '0',
  `author` varchar(16) DEFAULT NULL,
  `authortime` bigint NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '-1',
  `lasttime` bigint NOT NULL,
  `marks` varchar(16) DEFAULT NULL,
  `meal` int NOT NULL DEFAULT '1000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `itv_users`
--

--
-- 转储表的索引
--

--
-- 表的索引 `itv_admin`
--
ALTER TABLE `itv_admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `itv_adminrec`
--
ALTER TABLE `itv_adminrec`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `itv_category`
--
ALTER TABLE `itv_category`
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `itv_channels`
--
ALTER TABLE `itv_channels`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `itv_config`
--
ALTER TABLE `itv_config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `itv_meals`
--
ALTER TABLE `itv_meals`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `itv_proxy`
--
ALTER TABLE `itv_proxy`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `itv_serialnum`
--
ALTER TABLE `itv_serialnum`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `itv_users`
--
ALTER TABLE `itv_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mac` (`mac`,`deviceid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `itv_admin`
--
ALTER TABLE `itv_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `itv_adminrec`
--
ALTER TABLE `itv_adminrec`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `itv_channels`
--
ALTER TABLE `itv_channels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3906;

--
-- 使用表AUTO_INCREMENT `itv_config`
--
ALTER TABLE `itv_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- 使用表AUTO_INCREMENT `itv_meals`
--
ALTER TABLE `itv_meals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- 使用表AUTO_INCREMENT `itv_proxy`
--
ALTER TABLE `itv_proxy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `itv_serialnum`
--
ALTER TABLE `itv_serialnum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `itv_users`
--
ALTER TABLE `itv_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
