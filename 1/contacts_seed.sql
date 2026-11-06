-- Public contact seed data based on js/mock-data.js.
-- These records are UNVERIFIED examples. Confirm them with the municipality
-- and set is_verified = 1 only after confirmation.
-- Safe to run again: existing rows with the same name and type are skipped.

INSERT INTO public_contacts (
    name,
    `type`,
    telephone,
    email,
    address,
    is_verified,
    social_link,
    contact_name,
    purpose,
    category,
    fb_page_link,
    website_link,
    official_socmed
)
SELECT
    seed.name,
    seed.contact_type,
    seed.telephone,
    seed.email,
    seed.address,
    0,
    seed.social_link,
    seed.contact_name,
    seed.purpose,
    seed.category,
    seed.fb_page_link,
    seed.website_link,
    seed.official_socmed
FROM (
    SELECT 'Office of the Mayor' AS name, 'office' AS contact_type, '(045) 900-1000' AS telephone, 'municipalityofbacolor@gmail.com' AS email, 'Municipal Hall, Bacolor, Pampanga' AS address, NULL AS social_link, 'Mayor Diman Datu' AS contact_name, 'Executive oversight; Administrative coordination; Policy direction' AS purpose, 'Executive' AS category, NULL AS fb_page_link, 'https://www.bacolor.gov.ph' AS website_link, NULL AS official_socmed
    UNION ALL SELECT 'Municipal Treasurer''s Office', 'office', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Bacolor, Pampanga', NULL, 'Treasurer / staff', 'Tax payment; Community tax certificate; Business-related payments', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipal Civil Registry Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, 'Civil Registrar / staff', 'Birth certificates; Marriage certificates; Death certificates; Civil registry corrections', 'Civil Registry', NULL, NULL, NULL
    UNION ALL SELECT 'Municipal Engineer''s Office', 'office', '0917-833-7397', NULL, 'Municipal Hall Building, Bacolor, Pampanga', NULL, 'Engr. Marietta G. Rodriguez', 'Infrastructure planning; Building permit evaluation; Public works monitoring', 'Engineering', NULL, NULL, NULL
    UNION ALL SELECT 'Municipal Social Welfare and Development Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, 'MSWDO staff', 'Financial assistance; Medical assistance; Emergency aid; Social case assessment', 'Social Welfare', NULL, NULL, NULL
    UNION ALL SELECT 'Rural Health Unit', 'office', NULL, NULL, 'Municipal health facility, Bacolor, Pampanga', NULL, 'RHU staff', 'Health certificates; Medical services; Public health programs', 'Health', NULL, NULL, NULL
    UNION ALL SELECT 'Municipal Planning and Development Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, 'Planning and Development staff', 'Development planning; Programs and projects; Local development review', 'Planning', NULL, NULL, NULL
    UNION ALL SELECT 'Municipal Agriculture Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, 'Agriculture staff', 'Agriculture support; Community programs; Farm-related assistance', 'Agriculture', NULL, NULL, NULL
    UNION ALL SELECT 'Municipal Assessor''s Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, NULL, 'Real property assessment; Tax declarations; Property records', 'Assessment', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipal Budget Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, NULL, 'Municipal budget preparation; Budget records and coordination', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipal Accounting Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, NULL, 'Financial accounting; Disbursement and accounting records', 'Finance', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipal Environment and Natural Resources Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, NULL, 'Environmental programs; Solid waste coordination; Environmental concerns', 'Environment', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipal General Services Office', 'office', NULL, NULL, 'Municipal Hall, Bacolor, Pampanga', NULL, NULL, 'Municipal property and facilities; General support services', 'General Services', NULL, 'https://www.bacolor.gov.ph', NULL
    UNION ALL SELECT 'Municipality of Bacolor', 'hotline', '(045) 900-1000', 'municipalityofbacolor@gmail.com', 'Municipal Hall, Cabambangan (Pob.), Bacolor, Pampanga', NULL, NULL, 'General municipal inquiries and office directory', 'General inquiries', NULL, 'https://www.bacolor.gov.ph', 'https://www.facebook.com/BacolorPampanga'
    UNION ALL SELECT 'Municipal Disaster Risk Reduction Management Office (MDRRMO - Bacolor)', 'emergency', 'Smart: 0929-446-1940 | Globe: 0966-136-5586', 'mdrrmcbacolor@gmail.com', 'XMW2+RM3, Barangay Cabambangan (Pob.), Bacolor, Pampanga', NULL, 'Mr. Joel D. Samia', 'Disaster preparedness; Emergency rescue; Risk assessment; Disaster mitigation', 'Emergency', 'https://www.facebook.com/BacolorRescue.DIMANteam', NULL, NULL
    UNION ALL SELECT 'Bacolor Police Station (PNP)', 'emergency', '(045) 901-0833', NULL, 'XMW2+GM2, National Road, Bacolor, 2001 Pampanga', NULL, 'PMA Jessie James M. Domingo', 'Law enforcement; Public safety; Traffic management; Community policing', 'Emergency', 'https://www.facebook.com/PulisBaculud/', NULL, NULL
    UNION ALL SELECT 'Bacolor Fire Station (BFP)', 'emergency', '(045) 436-1828 | 0923-103-8363', NULL, 'XMW2+VJJ, Bacolor, Pampanga', NULL, 'FSINSP Jovel E. Hernandez', 'Fire prevention; Fire suppression; Emergency rescue; Fire safety education', 'Emergency', 'https://www.facebook.com/p/Bacolor-Fire-Station-BFP-R3-61569884632047/', NULL, NULL
) AS seed
WHERE NOT EXISTS (
    SELECT 1
    FROM public_contacts existing
    WHERE existing.name = seed.name
      AND existing.`type` = seed.contact_type
);
