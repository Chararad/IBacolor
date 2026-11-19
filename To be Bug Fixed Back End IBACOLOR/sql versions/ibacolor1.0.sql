-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 13, 2026 at 11:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibacolor`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `roles` enum('resident','admin') NOT NULL,
  `status` enum('active','pending','suspended') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `password_hash`, `roles`, `status`, `created_at`) VALUES
(1, 'ralchad20067@gmail.com', '$2y$10$BM12aw52WNzqnaH6G7rDE.mTNjCXNdy.zkKvwnuq3xTQPPxMJA3Xi', 'admin', 'active', '2026-11-06 03:07:51'),
(2, '2024310485@pampangastateu.edu.ph', '$2y$10$tBpJBUS/GYksl3vYyiQp.u9m9PzyxhZb4bWvU1krb32Mbt9kIsva2', 'resident', 'active', '2026-11-13 09:26:50'),
(3, 'test@gmail.com', '$2y$10$UGF./2RZADJmeAmcyz/8zelaY7wlUL2tIiDxAWBIAJmoxq6VFH.Sy', 'resident', 'active', '2026-11-06 07:39:37'),
(4, 'asdasdas@gmail.com', '$2y$10$a6/522x5a8vGRcOtFnSdSuItV31Xf29HRvhuc4nBtNeb/td42TB6y', 'resident', 'active', '2026-11-06 13:25:07'),
(5, '2024304413@pampangastateu.edu.ph', '$2y$10$WyUZtCtwTn75zwbtVVF8v.Al.G4kM6c0SqcVVLC9izEg7X3y5xh3S', 'admin', 'active', '2026-11-13 08:32:53'),
(6, 'irina@gmail.com', '$2y$10$6i60o0kPFw2hl4DdN3cMxeGa4XbgkpK.BhgmtJwqzKSxuDEGWGegO', 'resident', 'active', '2026-11-13 02:41:58'),
(7, 'cheskasitchon@gmail.com', '$2y$10$k59Nn6wlKbzCcvW8qMdKdeJkiWvSMB.8uco4UMSdm8MOutu3pyeIW', 'admin', 'active', '2026-11-13 06:21:22'),
(8, 'pagal.jannanicole.d@gmail.com', '$2y$10$SJ2NpuXutiKWRw637dHg4Opd4WbMU1g3UyfShPuMjVucshFVQCSEu', 'resident', 'active', '2026-11-13 06:58:09'),
(9, 'aquinojerome120905@gmail.com', '$2y$10$zbPSG7w.utvQD2k6bH6tMeBV7IfBcNn05Ia9zVTwhcxXzY99mxERO', 'resident', 'active', '2026-11-13 08:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `name`) VALUES
(1, 'Balas'),
(2, 'Cabalantian'),
(3, 'Cabambangan (Pob.)'),
(4, 'Cabetican'),
(5, 'Calibutbut'),
(6, 'Concepcion'),
(7, 'Dolores'),
(8, 'Duat'),
(9, 'Macabacle'),
(10, 'Magliman'),
(11, 'Maliwalu'),
(12, 'Mesalipit'),
(13, 'Parulog'),
(14, 'Potrero'),
(15, 'San Antonio'),
(16, 'San Isidro'),
(17, 'San Vicente'),
(18, 'Santa Barbara'),
(19, 'Santa Ines'),
(20, 'Talba'),
(21, 'Tinajero');

-- --------------------------------------------------------

--
-- Table structure for table `community_reports`
--

CREATE TABLE `community_reports` (
  `report_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `location_details` text NOT NULL,
  `description` text NOT NULL,
  `observed_at` datetime DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_reports`
--

INSERT INTO `community_reports` (`report_id`, `account_id`, `category`, `barangay_id`, `location_details`, `description`, `observed_at`, `frequency`, `status`, `submitted_at`, `updated_at`, `reference`) VALUES
(1, 2, 'Roads and transportation', 20, 'fsdfsdfsd', 'sfsdfsdfsdf', '2026-11-10 14:14:00', 'During heavy rain', 'In Progress', '2026-11-06 06:15:06', '2026-11-12 19:36:39', 'CR-2026-F85BF58C'),
(2, 5, 'Garbage disposal', 16, '343r3w', 'dsgdsfsdfs', NULL, 'Daily', 'Submitted', '2026-11-12 19:45:43', '2026-11-12 19:45:43', 'CR-2026-C9DB9703'),
(3, 2, 'Roads and transportation', 10, 'sssss', 'nasira po yung road naging dagat', '2026-11-13 07:07:00', 'One-time', 'Submitted', '2026-11-12 23:08:05', '2026-11-12 23:08:05', 'CR-2026-0C17AAAF'),
(4, 2, 'Drainage and flooding', 5, 'vvfsds', 'Bumaha napo nang kagandahan', '2026-11-25 07:10:00', 'During heavy rain', 'Submitted', '2026-11-12 23:11:23', '2026-11-12 23:11:23', 'CR-2026-184326F6'),
(5, 8, 'Street lighting', 9, 'Zone 2', 'Here', '2026-11-13 07:22:00', 'Daily', 'Resolved', '2026-11-12 23:22:47', '2026-11-13 01:35:10', 'CR-2026-CE6D62A6');

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `article_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `category` varchar(100) NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `image_storage_key` char(48) DEFAULT NULL,
  `image_mime_type` varchar(127) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`article_id`, `title`, `content`, `category`, `published_at`, `status`, `image_storage_key`, `image_mime_type`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Scholarship Grants 2026', '🎓 SCHOLARSHIP APPLICATIONS ARE NOW OPEN! \r\n\r\nAre you a passionate and deserving student looking for financial assistance? This is your opportunity!\r\n\r\nWe invite eligible students to apply for our scholarship program and take one step closer to achieving their academic dreams.\r\n\r\nBenefits: Financial assistance for qualified scholars.\r\n\r\nWho can apply: Eligible students who meet the scholarship requirements.\r\n\r\nApplication Period: November 24, 2026\r\n\r\nDon\'t miss this opportunity! Apply now and invest in your future. 🌟\r\n\r\nFor more information and inquiries, please contact 09xxxxxx.', 'Scholarship', '2026-11-13 07:29:00', 'published', '7ed2a3483535ea778bb03a14f4009c7c264711eb29d041ab', 'image/jpeg', 1, '2026-11-13 07:30:35', '2026-11-13 07:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `account_id` int(11) NOT NULL,
  `request_updates` tinyint(1) NOT NULL DEFAULT 1,
  `announcement_updates` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_preferences`
--

INSERT INTO `notification_preferences` (`account_id`, `request_updates`, `announcement_updates`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 1),
(9, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `public_contacts`
--

CREATE TABLE `public_contacts` (
  `contact_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `social_link` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `fb_page_link` varchar(255) DEFAULT NULL,
  `website_link` varchar(255) DEFAULT NULL,
  `official_socmed` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `public_contacts`
--

INSERT INTO `public_contacts` (`contact_id`, `name`, `type`, `telephone`, `email`, `address`, `is_verified`, `social_link`, `contact_name`, `purpose`, `category`, `fb_page_link`, `website_link`, `official_socmed`) VALUES
(1, 'Office of the Mayor', 'office', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'Mayor Diman Datu', 'Executive oversight; Administrative coordination; Policy direction', 'Executive', NULL, 'https://www.bacolor.gov.ph', NULL),
(2, 'Municipal Treasurer\'s Office', 'office', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'Treasurer / staff', 'Tax payment; Community tax certificate; Business-related payments', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL),
(3, 'Municipal Civil Registry Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'Civil Registrar / staff', 'Birth certificates; Marriage certificates; Death certificates; Civil registry corrections', 'Civil Registry', NULL, NULL, NULL),
(4, 'Municipal Engineer\'s Office', 'office', '0917-833-7397', NULL, 'Municipal Hall Building, Bacolor, Pampanga', 0, NULL, 'Engr. Marietta G. Rodriguez', 'Infrastructure planning; Building permit evaluation; Public works monitoring', 'Engineering', NULL, NULL, NULL),
(5, 'Municipal Social Welfare and Development Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'MSWDO staff', 'Financial assistance; Medical assistance; Emergency aid; Social case assessment', 'Social Welfare', NULL, NULL, NULL),
(6, 'Rural Health Unit', 'office', NULL, NULL, 'Municipal health facility, Bacolor, Pampanga', 0, NULL, 'RHU staff', 'Health certificates; Medical services; Public health programs', 'Health', NULL, NULL, NULL),
(7, 'Municipal Planning and Development Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'Planning and Development staff', 'Development planning; Programs and projects; Local development review', 'Planning', NULL, NULL, NULL),
(8, 'Municipal Agriculture Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, 'Agriculture staff', 'Agriculture support; Community programs; Farm-related assistance', 'Agriculture', NULL, NULL, NULL),
(9, 'Municipal Assessor\'s Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Real property assessment; Tax declarations; Property records', 'Assessment', NULL, 'https://www.bacolor.gov.ph', NULL),
(10, 'Municipal Budget Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Municipal budget preparation; Budget records and coordination', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL),
(12, 'Municipal Environment and Natural Resources Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Environmental programs; Solid waste coordination; Environmental concerns', 'Environment', NULL, 'https://www.bacolor.gov.ph', NULL),
(13, 'Municipal General Services Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Municipal property and facilities; General support services', 'General Services', NULL, 'https://www.bacolor.gov.ph', NULL),
(14, 'Municipality of Bacolor', 'office', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Cabambangan (Pob.), Bacolor, Pampanga', 0, NULL, NULL, 'General municipal inquiries and office directory', 'General inquiries', NULL, 'https://www.bacolor.gov.ph', 'https://www.facebook.com/BacolorPampanga'),
(15, 'Municipal Disaster Risk Reduction Management Office (MDRRMO - Bacolor)', 'emergency', 'Smart: 0929-446-1940 | Globe: 0966-136-5586', 'mdrrmcbacolor@gmail.com', 'XMW2+RM3, Barangay Cabambangan (Pob.), Bacolor, Pampanga', 0, NULL, 'Mr. Joel D. Samia', 'Disaster preparedness; Emergency rescue; Risk assessment; Disaster mitigation', 'Emergency', 'https://www.facebook.com/BacolorRescue.DIMANteam', NULL, NULL),
(18, 'PNP Bacolor', 'emergency', '(045) 901-0833', NULL, 'XMW2+GM2, National Road, Bacolor, 2001 Pampanga', 0, NULL, 'PMA J Jessie James M. Domingo', 'Law enforcement; Crime prevention; Public safety and security; Traffic management; Investigation services; Community policing', 'Emergency / Public Safety', 'https://www.facebook.com/PulisBaculud/', NULL, NULL),
(19, 'Fire Department Bacolor', 'emergency', '(045) 436-1828 | 0923 103 8363', NULL, 'XMW2+VJJ, Bacolor, Pampanga', 0, NULL, 'FSINSP Jovel E. Hernandez', 'Fire prevention and suppression; Emergency rescue services; Fire safety inspections; Fire safety education and awareness programs', 'Emergency / Public Safety', 'https://www.facebook.com/p/Bacolor-Fire-Station-BFP-R3-61569884632047/', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_attachments`
--

CREATE TABLE `report_attachments` (
  `attachment_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `storage_key` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_attachments`
--

INSERT INTO `report_attachments` (`attachment_id`, `report_id`, `uploaded_by`, `file_name`, `storage_key`, `mime_type`, `uploaded_at`) VALUES
(1, 2, 5, '752845640_2292209851525229_8427586804494950547_n.jpg', 'a653d1a3324403e387cc1c57d2c3abd4e75f06b9079295df', 'image/jpeg', '2026-11-12 19:45:43'),
(2, 3, 2, '436f56ab-168d-4660-920f-d28bfbc74db5.jpg', 'afaf16889be94b9251958cfd9fe27a40d6171478526195cd', 'image/jpeg', '2026-11-12 23:08:05'),
(3, 4, 2, '204df3b3-5fee-47a3-b12d-25bf954c4bed.jpg', '8afa9c035bb6946b723abd02d09d955a61044b1c0e4ef611', 'image/jpeg', '2026-11-12 23:11:23'),
(4, 5, 8, 'IMG_4523.png', '264a095ff4c75ff0cd22fb3289eacd55e79b9ab1029b5ea9', 'image/png', '2026-11-12 23:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `report_status_history`
--

CREATE TABLE `report_status_history` (
  `history_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_status_history`
--

INSERT INTO `report_status_history` (`history_id`, `report_id`, `changed_by`, `status`, `note`, `changed_at`) VALUES
(1, 1, 2, 'Submitted', 'Report received.', '2026-11-06 06:15:06'),
(2, 1, 1, 'Submitted', 'done', '2026-11-06 06:16:13'),
(3, 1, 1, 'Submitted', 'Wala man talaga sira', '2026-11-12 19:36:07'),
(4, 1, 1, 'Submitted', 'wala man sira po', '2026-11-12 19:36:26'),
(5, 1, 1, 'In Progress', NULL, '2026-11-12 19:36:39'),
(6, 2, 5, 'Submitted', 'Report received.', '2026-11-12 19:45:43'),
(7, 3, 2, 'Submitted', 'Report received.', '2026-11-12 23:08:05'),
(8, 4, 2, 'Submitted', 'Report received.', '2026-11-12 23:11:23'),
(9, 5, 8, 'Submitted', 'Report received.', '2026-11-12 23:22:47'),
(10, 5, 7, 'In Progress', 'progress', '2026-11-12 23:23:25'),
(11, 5, 7, 'Resolved', NULL, '2026-11-12 23:23:49'),
(12, 5, 1, 'Under Review', NULL, '2026-11-13 01:34:57'),
(13, 5, 1, 'In Progress', NULL, '2026-11-13 01:35:03'),
(14, 5, 1, 'Resolved', NULL, '2026-11-13 01:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `request_appointments`
--

CREATE TABLE `request_appointments` (
  `appointment_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `status` enum('scheduled','rescheduled','cancelled','completed') NOT NULL DEFAULT 'scheduled',
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `office` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_appointments`
--

INSERT INTO `request_appointments` (`appointment_id`, `request_id`, `changed_by`, `status`, `appointment_date`, `appointment_time`, `office`, `location`, `instructions`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'scheduled', '2026-11-19', '17:14:00', 'gvgvu', 'tufutffu', 'gcftydctyct', '2026-11-13 09:12:54', '2026-11-13 09:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `request_documents`
--

CREATE TABLE `request_documents` (
  `document_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `storage_key` varchar(255) NOT NULL,
  `mime_type` varchar(127) NOT NULL,
  `review_status` enum('submitted','accepted','needs correction') NOT NULL,
  `review_note` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_documents`
--

INSERT INTO `request_documents` (`document_id`, `request_id`, `uploaded_by`, `document_name`, `storage_key`, `mime_type`, `review_status`, `review_note`, `uploaded_at`) VALUES
(1, 3, 7, 'IMG_2777.jpeg', '24634552dbfec6b91d13571479679dbe3706c835ef1f1ee1', 'image/jpeg', 'accepted', NULL, '2026-11-12 18:50:22'),
(2, 3, 7, 'IMG_2773.jpeg', 'fe5576ed0c632f2c8cd8832bb8726218f315ae942d8e9907', 'image/jpeg', 'submitted', NULL, '2026-11-12 18:50:44'),
(3, 4, 5, 'Medical request form', '0c5b9b3686878fba7b4f1bedea3e6a749c5e6453a09dfc8b', 'image/png', 'submitted', 'submitted', '2026-11-12 19:48:15'),
(4, 4, 5, 'Medical exam results', '523b130621826e10d25d830af657d03dc17cb8fd06ec0ab1', 'image/png', 'needs correction', 'Invalid medical result', '2026-11-12 19:48:15'),
(5, 4, 5, 'Barangay clearance', '8742b050dff4bc052ac8a9712a80ba73fcd65af53e089fed', 'image/png', 'submitted', NULL, '2026-11-12 19:48:15'),
(6, 4, 5, 'Official receipt', '375011b5bbf07513cc93ce216e0a2b79432b4448599ba8b7', 'image/png', 'submitted', NULL, '2026-11-12 19:48:15'),
(7, 5, 2, 'Valid government ID', 'ed0ebab30b85a14d57773221a4d14fedc6744b23a758140e', 'image/jpeg', 'accepted', 'hahgsdsdx', '2026-11-12 23:39:01'),
(8, 5, 2, 'Proof of residency', '2202b7eeb7440b6311dad06f93fc374343f80a90e287f8fc', 'image/jpeg', 'submitted', NULL, '2026-11-12 23:39:01'),
(9, 5, 2, 'Barangay certification', '84f834c9b7ec833caad0c16c72d80360e897f74142209142', 'image/jpeg', 'submitted', NULL, '2026-11-12 23:39:01'),
(10, 5, 2, 'Application form', '2d6e0bcf24915672f2b10d266099a4dffc09c546eea6e138', 'image/jpeg', 'submitted', NULL, '2026-11-12 23:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `history_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`history_id`, `request_id`, `changed_by`, `status`, `note`, `changed_at`) VALUES
(1, 1, 4, 'Submitted', 'Request received.', '2026-11-06 05:26:06'),
(2, 1, 1, 'Submitted', NULL, '2026-11-06 06:15:46'),
(3, 1, 1, 'Submitted', 'fghgf', '2026-11-06 06:16:47'),
(4, 2, 5, 'Submitted', 'Request received.', '2026-11-12 17:45:11'),
(5, 2, 1, 'Submitted', NULL, '2026-11-12 18:12:43'),
(6, 2, 1, 'Rejected', NULL, '2026-11-12 18:12:55'),
(7, 2, 1, 'Rejected', NULL, '2026-11-12 18:13:03'),
(8, 2, 1, 'Rejected', NULL, '2026-11-12 18:13:07'),
(9, 2, 1, 'Rejected', 'upload more info', '2026-11-12 18:14:23'),
(10, 3, 7, 'Submitted', 'Request received.', '2026-11-12 18:49:14'),
(11, 4, 5, 'Submitted', 'Request received.', '2026-11-12 19:48:15'),
(12, 5, 2, 'Submitted', 'Request received.', '2026-11-12 23:39:01'),
(13, 5, 1, 'In Progress', NULL, '2026-11-13 01:08:22'),
(14, 5, 1, 'Completed', NULL, '2026-11-13 01:13:11'),
(15, 5, 1, 'For Revision', NULL, '2026-11-13 01:13:42'),
(16, 5, 1, 'For Revision', NULL, '2026-11-13 01:13:51'),
(17, 5, 1, 'For Revision', 'bvhvhvh', '2026-11-13 01:17:05'),
(18, 5, 1, 'Completed', 'bvhvhvh', '2026-11-13 01:18:13'),
(19, 4, 1, 'In Progress', NULL, '2026-11-13 01:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `resident_profiles`
--

CREATE TABLE `resident_profiles` (
  `account_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `profile_image_key` varchar(255) DEFAULT NULL,
  `verification` enum('pending','verified') NOT NULL,
  `privacy_policy_version` varchar(20) NOT NULL,
  `consented_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_profiles`
--

INSERT INTO `resident_profiles` (`account_id`, `first_name`, `middle_name`, `last_name`, `mobile_number`, `barangay_id`, `address`, `profile_image_key`, `verification`, `privacy_policy_version`, `consented_at`) VALUES
(1, 'Richard', 'Miranda', 'Lacanlale', '09942518011', 16, 'I dont Know', NULL, 'pending', 'v1', '2026-11-06 03:07:02'),
(2, 'Janine Mariz', NULL, 'Marcellones', '09693683493', 16, 'Non', NULL, 'verified', 'v1', '2026-11-06 03:57:38'),
(3, 'test', 'test', 'test', '09176491308', 3, 'eeeeeee', NULL, 'pending', 'v1', '2026-11-06 07:39:37'),
(4, 'mmmm', 'a', 'dasdasd', '09176491308', 18, 'dasdasd', NULL, 'pending', 'v1', '2026-11-06 13:25:07'),
(5, 'Jester', NULL, 'Dayao', '09850411895', 3, '32432 eme eme', NULL, 'verified', 'v1', '2026-11-13 01:20:34'),
(6, 'Irina', 'Donovan', 'Frennier', '09123456789', 19, 'Blk 22, Lot 16', NULL, 'pending', 'v1', '2026-11-13 02:41:58'),
(7, 'Cheska', NULL, 'Sitchon', '09455432378', 12, 'No. 123, 123 street', NULL, 'pending', 'v1', '2026-11-13 02:43:05'),
(8, 'Janna', 'Dejito', 'Pagal', '09184455768', 16, 'Zone 2, San Isidro, Bacolor', NULL, 'verified', 'v1', '2026-11-13 06:58:09'),
(9, 'Jerome', 'P.', 'Aquino', '09916268164', 12, 'bbhvbdbubud', NULL, 'pending', 'v1', '2026-11-13 08:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `resident_verification_documents`
--

CREATE TABLE `resident_verification_documents` (
  `document_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `storage_key` char(48) NOT NULL,
  `mime_type` varchar(127) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resident_verification_history`
--

CREATE TABLE `resident_verification_history` (
  `history_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL,
  `note` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_verification_history`
--

INSERT INTO `resident_verification_history` (`history_id`, `account_id`, `changed_by`, `status`, `note`, `changed_at`) VALUES
(1, 8, 7, 'verified', NULL, '2026-11-12 23:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `category` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `fee_information` varchar(255) DEFAULT NULL,
  `processing_info` varchar(255) DEFAULT NULL,
  `instructions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `code`, `name`, `description`, `category`, `office`, `status`, `fee_information`, `processing_info`, `instructions`) VALUES
(1, 'business-permit', 'Business Permit', 'Apply for a new or renewed permit for operating a business or enterprise in Bacolor.', 'Business', 'Municipal Treasurer\'s Office', 'Unavailable', 'Fee varies depending on business type and category', 'Application review and payment processing are handled through the municipal office.', 'Submit the completed application and required supporting documents at the office counter or designated municipal intake desk.'),
(2, 'civil-registry', 'Civil Registry Certificate', 'Request certified copies of birth, marriage, or death certificates and related civil registry records.', 'Civil Registry', 'Municipal Civil Registry Office', 'Unavailable', 'Processing fee depends on the type of certificate and request', 'Requests are processed after verification of records and required documentation.', 'Provide the requested record details and submit the application with any required supporting papers.'),
(3, 'barangay-clearance', 'Barangay Clearance', 'Secure a barangay clearance for employment, business, or local transactions requiring local certification.', 'Community', 'Barangay Office', 'Available', 'Fee may vary depending on the purpose of the clearance', 'Barangay review and approval are required before the clearance is released.', 'Coordinate with the barangay where you are residing and submit the necessary documents to the local clerk or office.'),
(4, 'building-permit', 'Building Permit', 'Secure approval for construction, renovation, or structural works that require municipal engineering review.', 'Engineering', 'Municipal Engineering Office', 'Available', 'Fee varies based on project scope and project cost estimate', 'Project documents are assessed by the engineering office before approval is issued.', 'Submit the plans and requirements to the engineering office for evaluation and follow-up review.'),
(5, 'community-tax-certificate', 'Community Tax Certificate', 'Request a cedula or community tax certificate for personal or local compliance purposes.', 'Finance', 'Municipal Treasurer\'s Office', 'Available', 'Community tax fees apply according to municipal schedule', 'Payment and verification are done at the treasurer\'s office.', 'Present the required documents to the municipal treasurer and complete the payment process.'),
(6, 'health-certificate', 'Health Certificate', 'Request a health certificate or related public health service required for employment, transactions, or medical clearance.', 'Health', 'Rural Health Unit', 'Available', '₱100–₱200 for health certificates; ₱150–₱500 for laboratory tests depending on service', 'Assessment and medical service processing are handled by the local health unit.', 'Submit the doctor\'s request or medical document and any supporting papers to the health office.'),
(7, 'social-assistance', 'Social Assistance', 'Request assistance for medical, food, emergency, disaster, and family support programs administered by the municipality.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'No direct fee for government-funded support programs; requirements vary by program', 'Applications are assessed through social work intervention and referral services.', 'Visit the MSWDO or barangay assistance desk and provide the required documents for evaluation.'),
(8, 'senior-pwd-assistance', 'Senior / PWD / Women and Children Assistance', 'Apply for support programs, registration, and inclusion-based assistance for eligible residents.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'Free for government-supported programs where applicable', 'Program eligibility is determined by the office after document review and assessment.', 'Submit the application and required supporting documents for review and case assessment.'),
(9, 'real-property-tax', 'Real Property Tax Payment and Tax Declaration', 'Ask about real property tax assessment, payment, tax declarations, and related property records.', 'Finance', 'Municipal Assessor\'s Office / Municipal Treasurer\'s Office', 'Available', 'Assessment and fees must be confirmed with the responsible office.', 'Property records and payment requirements are subject to office verification.', 'Contact the Assessor\'s Office for records and assessment, and the Treasurer\'s Office for payment.'),
(10, 'occupancy-permit', 'Occupancy Permit', 'Request an occupancy permit after construction has been completed and required inspections are satisfied.', 'Engineering', 'Municipal Engineering Office', 'Available', 'Confirm applicable fees with the Municipal Engineering Office.', 'Requirements and inspection steps depend on the project.', 'Contact the Municipal Engineering Office before submitting permit documents.'),
(11, 'zoning-clearance', 'Locational / Zoning Clearance', 'Ask for land-use and zoning review for a proposed business, building, or development.', 'Planning', 'Municipal Planning and Development Office', 'Available', 'Confirm applicable fees and documentary requirements with the office.', 'Review depends on the proposed use and location.', 'Contact the Municipal Planning and Development Office for current requirements.'),
(12, 'burial-assistance', 'Burial Assistance', 'Ask about municipal assistance available to eligible families for burial-related expenses.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'Eligibility and assistance are subject to assessment.', 'Applications are evaluated by the responsible social welfare office.', 'Contact the MSWDO or barangay office to confirm eligibility and current requirements.');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `request_id` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `account_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`request_id`, `reference`, `account_id`, `service_id`, `details`, `status`, `submitted_at`, `updated_at`) VALUES
(1, 'IB-2026-8CC997EA', 4, 3, 'nothing', 'Submitted', '2026-11-06 05:26:06', '2026-11-06 06:16:47'),
(2, 'IB-2026-16889A20', 5, 3, 'kikik', 'Rejected', '2026-11-12 17:45:11', '2026-11-12 18:14:23'),
(3, 'IB-2026-AA6747DA', 7, 1, 'Testing lng', 'Submitted', '2026-11-12 18:49:14', '2026-11-12 18:49:14'),
(4, 'IB-2026-5CD6AF04', 5, 6, 'dsgds', 'In Progress', '2026-11-12 19:48:15', '2026-11-13 01:22:43'),
(5, 'IB-2026-185264DC', 2, 3, 'cleaaarance', 'Completed', '2026-11-12 23:39:01', '2026-11-13 01:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `service_requirements`
--

CREATE TABLE `service_requirements` (
  `requirement_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `requirement` varchar(500) NOT NULL,
  `display_order` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requirements`
--

INSERT INTO `service_requirements` (`requirement_id`, `service_id`, `requirement`, `display_order`) VALUES
(1, 1, 'Valid government ID', 1),
(2, 1, 'Barangay clearance', 2),
(3, 1, 'Proof of business address', 3),
(4, 1, 'Business registration details', 4),
(5, 2, 'Valid government ID', 1),
(6, 2, 'Completed request form', 2),
(7, 2, 'Supporting record details', 3),
(8, 2, 'Barangay certification when required', 4),
(17, 5, 'Valid ID', 1),
(18, 5, 'Tax declaration or proof of residence', 2),
(19, 5, 'Application form', 3),
(20, 6, 'Medical request form', 1),
(21, 6, 'Medical exam results', 2),
(22, 6, 'Barangay clearance', 3),
(23, 6, 'Official receipt', 4),
(24, 7, 'Valid ID', 1),
(25, 7, 'Barangay certification', 2),
(26, 7, 'Medical abstract or prescription where applicable', 3),
(27, 7, 'Incident or damage report if needed', 4),
(28, 8, 'Registration form', 1),
(29, 8, 'Valid ID', 2),
(30, 8, 'Barangay certification', 3),
(31, 8, 'Supporting documents if applicable', 4),
(32, 9, 'Valid government ID', 1),
(33, 9, 'Latest tax declaration or property details', 2),
(34, 9, 'Authorization letter if acting for the owner', 3),
(35, 10, 'Approved building permit', 1),
(36, 10, 'As-built plans where required', 2),
(37, 10, 'Inspection and completion documents', 3),
(38, 11, 'Proof of property ownership or authorization', 1),
(39, 11, 'Lot plan or location sketch', 2),
(40, 11, 'Project description', 3),
(44, 3, 'Valid government ID', 0),
(45, 3, 'Proof of residency', 1),
(46, 3, 'Barangay certification', 2),
(47, 3, 'Application form', 3),
(48, 4, 'Building plans', 0),
(49, 4, 'Lot title or authorization', 1),
(50, 4, 'Valid government ID', 2),
(51, 4, 'Scope of work details', 3),
(52, 12, 'Valid IDs', 0),
(53, 12, 'Death certificate or certification', 1),
(54, 12, 'Proof of residence and financial need', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transparency_documents`
--

CREATE TABLE `transparency_documents` (
  `document_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `document_year` year(4) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `storage_key` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `version_number` int(11) NOT NULL DEFAULT 1,
  `previous_document_id` int(11) DEFAULT NULL,
  `status` enum('Draft','Published','Archived') NOT NULL DEFAULT 'Draft',
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `published_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sha256_hash` char(64) DEFAULT NULL,
  `ots_proof_storage_key` varchar(255) DEFAULT NULL,
  `ots_status` enum('not_submitted','pending','confirmed','failed') NOT NULL DEFAULT 'not_submitted',
  `ots_submitted_at` datetime DEFAULT NULL,
  `ots_confirmed_at` datetime DEFAULT NULL,
  `ots_bitcoin_block_height` bigint(20) UNSIGNED DEFAULT NULL,
  `ots_last_checked_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transparency_documents`
--

INSERT INTO `transparency_documents` (`document_id`, `title`, `description`, `category`, `document_year`, `file_name`, `storage_key`, `mime_type`, `file_size`, `version_number`, `previous_document_id`, `status`, `uploaded_by`, `uploaded_at`, `published_at`, `updated_at`, `sha256_hash`, `ots_proof_storage_key`, `ots_status`, `ots_submitted_at`, `ots_confirmed_at`, `ots_bitcoin_block_height`, `ots_last_checked_at`) VALUES
(1, '[2026] Annual Budget', 'Annual budget of 2026 by Bacolor', 'Budget allocations', '2026', '2026-Bacolor-Budget.pdf', '47613ed47ccbd78ec33ceb7adb072b2c0b142cda5ae3eca4.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 7, '2026-11-13 06:28:01', '2026-11-13 06:28:32', '2026-11-13 09:41:31', NULL, NULL, 'not_submitted', NULL, NULL, NULL, NULL),
(2, '[2026] Projects Updates', 'Projects of bacolor from 2024-2025', 'Projects and updates', '2025', 'IBACOLOR-TEST.pdf', '0d26dd1808e8e172e05fecab11785e5256df655399459e11.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 5, '2026-11-13 08:35:51', '2026-11-13 09:40:56', '2026-11-13 09:41:27', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', NULL, 'failed', NULL, NULL, NULL, NULL),
(3, 'IBACOLOR-TEST', 'ADWDAWD', 'Budget allocations', '2026', 'IBACOLOR-TEST.pdf', 'd8df936cc84af544a2389e7949431a0d05777ae70b53eef1.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 5, '2026-11-13 08:38:42', NULL, '2026-11-13 09:41:21', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', NULL, 'failed', NULL, NULL, NULL, NULL),
(4, 'IBACOLOR-TEST2', 'ACSASAS', 'Procurement records', '2026', 'IBACOLOR-TEST.pdf', '8e6fa16e0ea7eb7ebdca509ad5a0cb80c8cdeb598af3f015.pdf', 'application/pdf', 210195, 1, NULL, 'Published', 5, '2026-11-13 08:48:18', '2026-11-13 09:41:37', '2026-11-13 10:10:29', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', 'dc873d6dd5952541a67f7ef5cd4dfc631d4f9a36bacf7922.ots', 'pending', '2026-11-13 08:48:18', NULL, NULL, '2026-11-13 10:10:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `community_reports`
--
ALTER TABLE `community_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `status_published_at` (`status`,`published_at`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `public_contacts`
--
ALTER TABLE `public_contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `report_attachments`
--
ALTER TABLE `report_attachments`
  ADD PRIMARY KEY (`attachment_id`,`report_id`,`uploaded_by`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `report_status_history`
--
ALTER TABLE `report_status_history`
  ADD PRIMARY KEY (`history_id`,`report_id`,`changed_by`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `request_appointments`
--
ALTER TABLE `request_appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `request_documents`
--
ALTER TABLE `request_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD UNIQUE KEY `storage_key` (`storage_key`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `resident_profiles`
--
ALTER TABLE `resident_profiles`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `resident_verification_documents`
--
ALTER TABLE `resident_verification_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD UNIQUE KEY `storage_key` (`storage_key`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `resident_verification_history`
--
ALTER TABLE `resident_verification_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`request_id`,`account_id`,`service_id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service_requirements`
--
ALTER TABLE `service_requirements`
  ADD PRIMARY KEY (`requirement_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `transparency_documents`
--
ALTER TABLE `transparency_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD UNIQUE KEY `storage_key` (`storage_key`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `previous_document_id` (`previous_document_id`),
  ADD KEY `idx_ots_status` (`ots_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `community_reports`
--
ALTER TABLE `community_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `public_contacts`
--
ALTER TABLE `public_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `report_attachments`
--
ALTER TABLE `report_attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `report_status_history`
--
ALTER TABLE `report_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `request_appointments`
--
ALTER TABLE `request_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_documents`
--
ALTER TABLE `request_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `resident_verification_documents`
--
ALTER TABLE `resident_verification_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resident_verification_history`
--
ALTER TABLE `resident_verification_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_requirements`
--
ALTER TABLE `service_requirements`
  MODIFY `requirement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transparency_documents`
--
ALTER TABLE `transparency_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_reports`
--
ALTER TABLE `community_reports`
  ADD CONSTRAINT `community_reports_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `community_reports_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`);

--
-- Constraints for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `report_attachments`
--
ALTER TABLE `report_attachments`
  ADD CONSTRAINT `report_attachments_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `community_reports` (`report_id`),
  ADD CONSTRAINT `report_attachments_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `report_status_history`
--
ALTER TABLE `report_status_history`
  ADD CONSTRAINT `report_status_history_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `community_reports` (`report_id`),
  ADD CONSTRAINT `report_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `request_documents`
--
ALTER TABLE `request_documents`
  ADD CONSTRAINT `request_documents_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`),
  ADD CONSTRAINT `request_documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD CONSTRAINT `request_status_history_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`),
  ADD CONSTRAINT `request_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `resident_profiles`
--
ALTER TABLE `resident_profiles`
  ADD CONSTRAINT `resident_profiles_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resident_profiles_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON UPDATE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Constraints for table `service_requirements`
--
ALTER TABLE `service_requirements`
  ADD CONSTRAINT `service_requirements_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transparency_documents`
--
ALTER TABLE `transparency_documents`
  ADD CONSTRAINT `transparency_documents_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `transparency_documents_ibfk_2` FOREIGN KEY (`previous_document_id`) REFERENCES `transparency_documents` (`document_id`);

--
-- Table structure for table `audit_history`
--
CREATE TABLE IF NOT EXISTS `audit_history` (
  `history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_account_id` int(11) DEFAULT NULL,
  `actor_role` varchar(20) NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `entity_id` varchar(64) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (`details` IS NULL OR json_valid(`details`)),
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `actor_created_at` (`actor_account_id`,`created_at`),
  KEY `entity_created_at` (`entity_type`,`entity_id`,`created_at`),
  KEY `event_created_at` (`event_type`,`created_at`),
  CONSTRAINT `audit_history_actor_fk` FOREIGN KEY (`actor_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
