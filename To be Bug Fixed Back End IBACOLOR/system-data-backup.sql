-- System/public-content data-only backup extracted from ibacolor (1).sql.
-- Contains barangays, services, service requirements, public contacts,
-- news articles, and transparency-document metadata only.
-- It intentionally excludes accounts, submissions, resident profiles, and logs.
-- Restore only into empty corresponding tables after importing the main schema.
-- Uploaded files themselves are not included; back up storage/private separately.
SET NAMES utf8mb4;
START TRANSACTION;

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

INSERT INTO `services` (`service_id`, `code`, `name`, `description`, `category`, `office`, `status`, `fee_information`, `processing_info`, `instructions`) VALUES
(1, 'business-permit', 'Business Permit', 'Apply for a new or renewed permit for operating a business or enterprise in Bacolor.', 'Business', 'Municipal Treasurer\'s Office', 'Unavailable', 'Fee varies depending on business type and category', 'Application review and payment processing are handled through the municipal office.', 'Submit the completed application and required supporting documents at the office counter or designated municipal intake desk.'),
(4, 'building-permit', 'Building Permit', 'Secure approval for construction, renovation, or structural works that require municipal engineering review.', 'Engineering', 'Municipal Engineering Office', 'Available', 'Fee varies based on project scope and project cost estimate', 'Project documents are assessed by the engineering office before approval is issued.', 'Submit the plans and requirements to the engineering office for evaluation and follow-up review.'),
(5, 'community-tax-certificate', 'Community Tax Certificate', 'Request a cedula or community tax certificate for personal or local compliance purposes.', 'Finance', 'Municipal Treasurer\'s Office', 'Available', 'Community tax fees apply according to municipal schedule', 'Payment and verification are done at the treasurer\'s office.', 'Present the required documents to the municipal treasurer and complete the payment process.'),
(6, 'health-certificate', 'Health Certificate', 'Request a health certificate or related public health service required for employment, transactions, or medical clearance.', 'Health', 'Rural Health Unit', 'Available', '₱100–₱200 for health certificates; ₱150–₱500 for laboratory tests depending on service', 'Assessment and medical service processing are handled by the local health unit.', 'Submit the doctor\'s request or medical document and any supporting papers to the health office.'),
(7, 'social-assistance', 'Social Assistance', 'Request assistance for medical, food, emergency, disaster, and family support programs administered by the municipality.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'No direct fee for government-funded support programs; requirements vary by program', 'Applications are assessed through social work intervention and referral services.', 'Visit the MSWDO or barangay assistance desk and provide the required documents for evaluation.'),
(8, 'senior-pwd-assistance', 'Senior / PWD / Women and Children Assistance', 'Apply for support programs, registration, and inclusion-based assistance for eligible residents.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'Free for government-supported programs where applicable', 'Program eligibility is determined by the office after document review and assessment.', 'Submit the application and required supporting documents for review and case assessment.'),
(9, 'real-property-tax', 'Real Property Tax Payment and Tax Declaration', 'Ask about real property tax assessment, payment, tax declarations, and related property records.', 'Finance', 'Municipal Assessor\'s Office / Municipal Treasurer\'s Office', 'Available', 'Assessment and fees must be confirmed with the responsible office.', 'Property records and payment requirements are subject to office verification.', 'Contact the Assessor\'s Office for records and assessment, and the Treasurer\'s Office for payment.'),
(10, 'occupancy-permit', 'Occupancy Permit', 'Request an occupancy permit after construction has been completed and required inspections are satisfied.', 'Engineering', 'Municipal Engineering Office', 'Available', 'Confirm applicable fees with the Municipal Engineering Office.', 'Requirements and inspection steps depend on the project.', 'Contact the Municipal Engineering Office before submitting permit documents.'),
(11, 'zoning-clearance', 'Locational / Zoning Clearance', 'Ask for land-use and zoning review for a proposed business, building, or development.', 'Planning', 'Municipal Planning and Development Office', 'Available', 'Confirm applicable fees and documentary requirements with the office.', 'Review depends on the proposed use and location.', 'Contact the Municipal Planning and Development Office for current requirements.'),
(12, 'burial-assistance', 'Burial Assistance', 'Ask about municipal assistance available to eligible families for burial-related expenses.', 'Social Welfare', 'Municipal Social Welfare and Development Office', 'Available', 'Eligibility and assistance are subject to assessment.', 'Applications are evaluated by the responsible social welfare office.', 'Contact the MSWDO or barangay office to confirm eligibility and current requirements.');

INSERT INTO `service_requirements` (`requirement_id`, `service_id`, `requirement`, `display_order`) VALUES
(1, 1, 'Valid government ID', 1),
(2, 1, 'Barangay clearance', 2),
(3, 1, 'Proof of business address', 3),
(4, 1, 'Business registration details', 4),
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
(15, 'Municipal Disaster Risk Reduction Management Office (MDRRMO - Bacolor)', 'emergency', 'Smart: 0929-446-1940 | Globe: 0966-136-5586', 'mdrrmcbacolor@gmail.com', 'XMW2+RM3, Barangay Cabambangan (Pob.), Bacolor, Pampanga', 0, NULL, 'Mr. Joel D. Samia', 'Disaster preparedness; Emergency rescue; Risk assessment; Disaster mitigation', 'Emergency', 'https://www.facebook.com/BacolorRescue.DIMANteam', NULL, NULL);

INSERT INTO `news_articles` (`article_id`, `title`, `content`, `category`, `published_at`, `status`, `image_storage_key`, `image_mime_type`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Scholarship Grants 2026', '🎓 SCHOLARSHIP APPLICATIONS ARE NOW OPEN! \r\n\r\nAre you a passionate and deserving student looking for financial assistance? This is your opportunity!\r\n\r\nWe invite eligible students to apply for our scholarship program and take one step closer to achieving their academic dreams.\r\n\r\nBenefits: Financial assistance for qualified scholars.\r\n\r\nWho can apply: Eligible students who meet the scholarship requirements.\r\n\r\nApplication Period: November 24, 2026\r\n\r\nDon\'t miss this opportunity! Apply now and invest in your future. 🌟\r\n\r\nFor more information and inquiries, please contact 09xxxxxx.', 'Scholarship', '2026-11-13 07:29:00', 'published', '7ed2a3483535ea778bb03a14f4009c7c264711eb29d041ab', 'image/jpeg', 1, '2026-11-13 07:30:35', '2026-11-13 07:31:43');

INSERT INTO `transparency_documents` (`document_id`, `title`, `description`, `category`, `document_year`, `file_name`, `storage_key`, `mime_type`, `file_size`, `version_number`, `previous_document_id`, `status`, `uploaded_by`, `uploaded_at`, `published_at`, `updated_at`, `sha256_hash`, `ots_proof_storage_key`, `ots_status`, `ots_submitted_at`, `ots_confirmed_at`, `ots_bitcoin_block_height`, `ots_last_checked_at`) VALUES
(1, '[2026] Annual Budget', 'Annual budget of 2026 by Bacolor', 'Budget allocations', '2026', '2026-Bacolor-Budget.pdf', '47613ed47ccbd78ec33ceb7adb072b2c0b142cda5ae3eca4.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 7, '2026-11-13 06:28:01', '2026-11-13 06:28:32', '2026-11-13 09:41:31', NULL, NULL, 'not_submitted', NULL, NULL, NULL, NULL),
(2, '[2026] Projects Updates', 'Projects of bacolor from 2024-2025', 'Projects and updates', '2025', 'IBACOLOR-TEST.pdf', '0d26dd1808e8e172e05fecab11785e5256df655399459e11.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 5, '2026-11-13 08:35:51', '2026-11-13 09:40:56', '2026-11-13 09:41:27', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', NULL, 'failed', NULL, NULL, NULL, NULL),
(3, 'IBACOLOR-TEST', 'ADWDAWD', 'Budget allocations', '2026', 'IBACOLOR-TEST.pdf', 'd8df936cc84af544a2389e7949431a0d05777ae70b53eef1.pdf', 'application/pdf', 210195, 1, NULL, 'Archived', 5, '2026-11-13 08:38:42', NULL, '2026-11-13 09:41:21', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', NULL, 'failed', NULL, NULL, NULL, NULL),
(4, 'IBACOLOR-TEST2', 'ACSASAS', 'Procurement records', '2026', 'IBACOLOR-TEST.pdf', '8e6fa16e0ea7eb7ebdca509ad5a0cb80c8cdeb598af3f015.pdf', 'application/pdf', 210195, 1, NULL, 'Published', 5, '2026-11-13 08:48:18', '2026-11-13 09:41:37', '2026-11-13 10:10:29', '57ad26bbff17bc96bcd1145debb8cf295d12b4e27d091eb08239133bc01aaad4', 'dc873d6dd5952541a67f7ef5cd4dfc631d4f9a36bacf7922.ots', 'pending', '2026-11-13 08:48:18', NULL, NULL, '2026-11-13 10:10:29');

COMMIT;
