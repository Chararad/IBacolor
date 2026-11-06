-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 02:44 PM
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
(2, '2024310485@pampangastateu.edu.ph', '$2y$10$tBpJBUS/GYksl3vYyiQp.u9m9PzyxhZb4bWvU1krb32Mbt9kIsva2', 'resident', 'active', '2026-11-06 03:57:38'),
(3, 'test@gmail.com', '$2y$10$UGF./2RZADJmeAmcyz/8zelaY7wlUL2tIiDxAWBIAJmoxq6VFH.Sy', 'resident', 'active', '2026-11-06 07:39:37'),
(4, 'asdasdas@gmail.com', '$2y$10$a6/522x5a8vGRcOtFnSdSuItV31Xf29HRvhuc4nBtNeb/td42TB6y', 'resident', 'active', '2026-11-06 13:25:07');

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
(4, 1, 1);

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
(11, 'Municipal Accounting Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Financial accounting; Disbursement and accounting records', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL),
(12, 'Municipal Environment and Natural Resources Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Environmental programs; Solid waste coordination; Environmental concerns', 'Environment', NULL, 'https://www.bacolor.gov.ph', NULL),
(13, 'Municipal General Services Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', 0, NULL, NULL, 'Municipal property and facilities; General support services', 'General Services', NULL, 'https://www.bacolor.gov.ph', NULL),
(14, 'Municipality of Bacolor', 'office', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Cabambangan (Pob.), Bacolor, Pampanga', 0, NULL, NULL, 'General municipal inquiries and office directory', 'General inquiries', NULL, 'https://www.bacolor.gov.ph', 'https://www.facebook.com/BacolorPampanga'),
(15, 'Municipal Disaster Risk Reduction Management Office (MDRRMO - Bacolor)', 'emergency', 'Smart: 0929-446-1940 | Globe: 0966-136-5586', 'mdrrmcbacolor@gmail.com', 'XMW2+RM3, Barangay Cabambangan (Pob.), Bacolor, Pampanga', 0, NULL, 'Mr. Joel D. Samia', 'Disaster preparedness; Emergency rescue; Risk assessment; Disaster mitigation', 'Emergency', 'https://www.facebook.com/BacolorRescue.DIMANteam', NULL, NULL),
(16, 'Bacolor Police Station (PNP)', 'emergency', '(045) 901-0833', NULL, 'XMW2+GM2, National Road, Bacolor, 2001 Pampanga', 0, NULL, 'PMA Jessie James M. Domingo', 'Law enforcement; Public safety; Traffic management; Community policing', 'Emergency', 'https://www.facebook.com/PulisBaculud/', NULL, NULL),
(17, 'Bacolor Fire Station (BFP)', 'emergency', '(045) 436-1828 | 0923-103-8363', NULL, 'XMW2+VJJ, Bacolor, Pampanga', 0, NULL, 'FSINSP Jovel E. Hernandez', 'Fire prevention; Fire suppression; Emergency rescue; Fire safety education', 'Emergency', 'https://www.facebook.com/p/Bacolor-Fire-Station-BFP-R3-61569884632047/', NULL, NULL);

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
(1, 1, 4, 'Submitted', 'Request received.', '2026-11-06 05:26:06');

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
(4, 'mmmm', 'a', 'dasdasd', '09176491308', 18, 'dasdasd', NULL, 'pending', 'v1', '2026-11-06 13:25:07');

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
(1, 'business-permit', 'Business Permit', 'Apply for a new or renewed permit for operating a business or enterprise in Bacolor.', 'Business', 'Municipal Treasurer\'s Office', 'Available', 'Fee varies depending on business type and category', 'Application review and payment processing are handled through the municipal office.', 'Submit the completed application and required supporting documents at the office counter or designated municipal intake desk.'),
(2, 'civil-registry', 'Civil Registry Certificate', 'Request certified copies of birth, marriage, or death certificates and related civil registry records.', 'Civil Registry', 'Municipal Civil Registry Office', 'Available', 'Processing fee depends on the type of certificate and request', 'Requests are processed after verification of records and required documentation.', 'Provide the requested record details and submit the application with any required supporting papers.'),
(3, 'barangay-clearance', 'Barangay Clearance', 'Secure a barangay clearance for employment, business, or local transactions requiring local certification.', 'Community', 'Barangay Office', 'Available', 'Fee may vary depending on the purpose of the clearance', 'Barangay review and approval are required before the clearance is released.', 'Coordinate with the barangay where you are residing and submit the necessary documents to the local clerk or office.'),
(4, 'building-permit', 'Building Permit', 'Secure approval for construction, renovation, or structural works that require municipal engineering review.', 'Engineering', 'Municipal Engineering Office', 'In review', 'Fee varies based on project scope and project cost estimate', 'Project documents are assessed by the engineering office before approval is issued.', 'Submit the plans and requirements to the engineering office for evaluation and follow-up review.'),
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
(1, 'IB-2026-8CC997EA', 4, 3, 'nothing', 'Submitted', '2026-11-06 05:26:06', '2026-11-06 05:26:06');

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
(9, 3, 'Valid government ID', 1),
(10, 3, 'Proof of residency', 2),
(11, 3, 'Barangay certification', 3),
(12, 3, 'Application form', 4),
(13, 4, 'Building plans', 1),
(14, 4, 'Lot title or authorization', 2),
(15, 4, 'Valid government ID', 3),
(16, 4, 'Scope of work details', 4),
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
(41, 12, 'Valid IDs', 1),
(42, 12, 'Death certificate or certification', 2),
(43, 12, 'Proof of residence and financial need', 3);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `community_reports`
--
ALTER TABLE `community_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public_contacts`
--
ALTER TABLE `public_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `report_attachments`
--
ALTER TABLE `report_attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_status_history`
--
ALTER TABLE `report_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_documents`
--
ALTER TABLE `request_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_requirements`
--
ALTER TABLE `service_requirements`
  MODIFY `requirement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
