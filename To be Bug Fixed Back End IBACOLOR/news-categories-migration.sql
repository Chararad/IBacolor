-- Optional: move existing articles onto the four new announcement types.
-- First check what you have:  SELECT DISTINCT category FROM news_articles;
UPDATE news_articles SET category = CASE category
  WHEN 'Announcement' THEN 'Government and Administrative Announcements'
  WHEN 'Advisory'     THEN 'Public Safety and Emergency Advisories'
  WHEN 'Health'       THEN 'Public Safety and Emergency Advisories'
  WHEN 'Community'    THEN 'Community and Cultural Events'
  ELSE category
END;
