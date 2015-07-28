-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2015 at 05:37 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gamescaffold`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `variant_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `variant_name`, `points`, `created_at`, `updated_at`) VALUES
(1, 'users', 'Group for default access.', 0, '2015-05-05 15:40:21', '2015-05-08 09:38:05'),
(2, 'admin', 'Group of all administrators.', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:43'),
(3, 'stats', 'Group for statistics access.', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(4, 'time_accounting', 'Group for all time accounting user.', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:45'),
(5, 'itsm-service', 'Group for ITSM Service mask access in the agent interface.', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(6, 'itsm-configitem', 'Group for ITSM ConfigItem mask access in the agent interface.', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(7, 'itsm-change', 'Group for ITSM Change mask access in the agent interface.', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:19'),
(8, 'itsm-change-builder', 'Group for ITSM Change Builders.', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(9, 'itsm-change-manager', 'Group for ITSM Change Managers.', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(10, 'IpTrunking', 'Vodafone IpTrunking', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:44'),
(11, 'ONENET', 'Vodafone OneNet', 240, '2015-05-05 15:40:20', '2015-06-05 14:32:25'),
(12, 'NGPP', 'Vodafone NGPP', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:34'),
(13, 'PPV', 'PPV', 38, '2015-05-05 15:40:20', '2015-06-05 11:15:10'),
(14, 'Test Dummy - Serviço X', 'Dummy', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(15, 'Test Dummy - Serviço Y', 'Dummy', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(16, 'Test Dummy - Serviço Z', 'Dummy', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:19'),
(17, 'VodafoneUK', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:44'),
(18, 'VodafonePT', '', 0, '2015-05-05 15:40:21', '2015-06-04 17:19:01'),
(19, 'otrs_support', 'OTRS Support Team', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:45'),
(20, 'eVPN NGPP', 'eVPN NGPP', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:44'),
(21, 'faq', 'faq database users', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:45'),
(22, 'faq_admin', 'faq admin users', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:45'),
(23, 'faq_approval', 'faq approval users', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(24, 'Shared Services', 'Shared Services ', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:44'),
(25, 'NOS', 'NOS QUEUE', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(26, 'IBM-ADM-AO', 'IBM', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:19'),
(27, 'OFFSITE', 'Vodafone PT Services', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(28, 'UNITEL', 'UNITEL', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(29, 'Junk', '', 0, '2015-05-05 15:40:20', '2015-05-14 12:54:44'),
(30, 'VFNL', 'Vodafone Holanda', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:19'),
(31, 'Vodafone NL', '', 0, '2015-05-05 15:40:21', '2015-05-05 15:40:21'),
(32, 'AO Tasks', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:45'),
(33, 'Reporting', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:45'),
(34, 'Vodafone Global', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:34'),
(35, 'VFGB SOBE', 'Vodafone Global', 0, '2015-05-05 15:40:21', '2015-06-03 15:42:20'),
(36, 'News Managment', '', 0, '2015-05-05 15:40:21', '2015-05-08 09:38:07'),
(37, 'MS - SI Support', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:13'),
(38, 'ONENET OLD', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(39, 'IPTrunking OLD', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(40, 'IBM-ADM-AM', 'IBM', 0, '2015-05-05 15:40:20', '2015-05-14 12:53:20'),
(41, 'VFE', 'VFE | 360 | SMART', 8704, '2015-05-05 15:40:22', '2015-06-05 14:32:28'),
(42, 'VFGH', '', 0, '2015-05-05 15:40:20', '2015-06-04 17:19:04'),
(43, 'VFTR [AM] OneFocus', 'Vodafone TR OneFocus', 864356, '2015-05-05 15:40:21', '2015-06-05 14:32:27'),
(44, 'Billing Post Paid', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(45, 'Billing Pre Paid', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:20'),
(46, 'eGain Support', '', 916, '2015-05-05 15:40:20', '2015-06-05 14:32:28'),
(47, 'VFQT', '', 3014, '2015-05-05 15:40:21', '2015-06-05 14:32:27'),
(48, 'VFCZ', 'Vodafone Republica checa', 0, '2015-05-05 15:40:21', '2015-05-08 09:38:07'),
(49, 'VFGC', 'Vodafone Greece', 0, '2015-05-05 15:40:21', '2015-05-08 09:38:07'),
(50, 'MSEM Support', 'Support JIRA | CONFLUENCE | SVN | GIT', 26552, '2015-05-05 15:40:21', '2015-06-05 14:32:28'),
(51, 'VFGB GDSP', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:54:49'),
(52, 'Business Support', '', 0, '2015-05-05 15:40:21', '2015-06-03 15:42:14'),
(53, 'VGE Commissions', '', 0, '2015-05-05 15:40:21', '2015-05-08 09:38:06'),
(54, 'VFGB M2M', '', 0, '2015-05-05 15:40:21', '2015-06-03 15:42:22'),
(55, 'VFGB WCS', '', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:21'),
(56, 'VFPT [AM] IBM3W', 'Unificação de Serviços (ONLINE | Billing | CRM | EAI)', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:21'),
(57, 'VFPT [AO] IBM3W', 'Unificação de Serviços (ONLINE | Billing | CRM | EAI)', 0, '2015-05-05 15:40:21', '2015-05-14 12:53:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
