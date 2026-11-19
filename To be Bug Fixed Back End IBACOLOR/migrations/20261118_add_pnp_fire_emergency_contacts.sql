INSERT INTO public_contacts (
    name,
    type,
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
    new_contact.name,
    new_contact.type,
    new_contact.telephone,
    new_contact.email,
    new_contact.address,
    new_contact.is_verified,
    new_contact.social_link,
    new_contact.contact_name,
    new_contact.purpose,
    new_contact.category,
    new_contact.fb_page_link,
    new_contact.website_link,
    new_contact.official_socmed
FROM (
    SELECT
        'PNP Bacolor' AS name,
        'emergency' AS type,
        '(045) 901-0833' AS telephone,
        NULL AS email,
        'XMW2+GM2, National Road, Bacolor, 2001 Pampanga' AS address,
        0 AS is_verified,
        NULL AS social_link,
        'PMA J Jessie James M. Domingo' AS contact_name,
        'Law enforcement; Crime prevention; Public safety and security; Traffic management; Investigation services; Community policing' AS purpose,
        'Emergency / Public Safety' AS category,
        'https://www.facebook.com/PulisBaculud/' AS fb_page_link,
        NULL AS website_link,
        NULL AS official_socmed
    UNION ALL
    SELECT
        'Fire Department Bacolor',
        'emergency',
        '(045) 436-1828 | 0923 103 8363',
        NULL,
        'XMW2+VJJ, Bacolor, Pampanga',
        0,
        NULL,
        'FSINSP Jovel E. Hernandez',
        'Fire prevention and suppression; Emergency rescue services; Fire safety inspections; Fire safety education and awareness programs',
        'Emergency / Public Safety',
        'https://www.facebook.com/p/Bacolor-Fire-Station-BFP-R3-61569884632047/',
        NULL,
        NULL
) AS new_contact
WHERE NOT EXISTS (
    SELECT 1
    FROM public_contacts existing_contact
    WHERE LOWER(TRIM(existing_contact.name)) = LOWER(TRIM(new_contact.name))
);
