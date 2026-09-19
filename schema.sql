-- Cyber Legend — database schema
-- Import this via phpMyAdmin (InfinityFree control panel -> MySQL Databases -> phpMyAdmin)

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS profile (
  id INT PRIMARY KEY DEFAULT 1,
  name VARCHAR(120) NOT NULL DEFAULT 'Cyber Legend',
  tagline VARCHAR(255) DEFAULT '',
  about TEXT,
  dp_path VARCHAR(255) DEFAULT '',
  badge TINYINT(1) DEFAULT 1,
  stats_json TEXT,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS socials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(40) NOT NULL,
  url VARCHAR(500) NOT NULL,
  enabled TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS sections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  description TEXT,
  icon VARCHAR(20) DEFAULT '',
  image_path VARCHAR(255) DEFAULT '',
  btn_text VARCHAR(60) DEFAULT '',
  btn_url VARCHAR(500) DEFAULT '',
  visible TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS theme_settings (
  id INT PRIMARY KEY DEFAULT 1,
  primary_color VARCHAR(9) DEFAULT '#3b82f6',
  secondary_color VARCHAR(9) DEFAULT '#22d3ee',
  bg_color VARCHAR(9) DEFAULT '#050810',
  glow DECIMAL(3,2) DEFAULT 0.55,
  animations TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS loader_settings (
  id INT PRIMARY KEY DEFAULT 1,
  enabled TINYINT(1) DEFAULT 1,
  duration_ms INT DEFAULT 2000,
  loader_text VARCHAR(120) DEFAULT 'Initializing Cyber Legend…'
);

CREATE TABLE IF NOT EXISTS seo_settings (
  id INT PRIMARY KEY DEFAULT 1,
  title VARCHAR(160) DEFAULT 'Cyber Legend',
  description VARCHAR(300) DEFAULT 'Personal cyber profile of Cyber Legend.',
  keywords VARCHAR(300) DEFAULT '',
  og_title VARCHAR(160) DEFAULT '',
  og_description VARCHAR(300) DEFAULT '',
  og_image VARCHAR(255) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS analytics_events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_type VARCHAR(20) NOT NULL,
  device VARCHAR(20) DEFAULT '',
  browser VARCHAR(40) DEFAULT '',
  referrer VARCHAR(255) DEFAULT '',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- seed default rows (safe to run once)
INSERT IGNORE INTO profile (id, name, tagline, about, stats_json) VALUES
(1, 'Cyber Legend', 'Building at the edge of code and imagination.',
 'Digital creator, builder, and night-shift architect of ideas. This space is a live signal — always shipping, always online.',
 '[{"label":"Projects","value":"42"},{"label":"Followers","value":"12K"},{"label":"Uptime","value":"99.9%"}]');

INSERT IGNORE INTO theme_settings (id) VALUES (1);
INSERT IGNORE INTO loader_settings (id) VALUES (1);
INSERT IGNORE INTO seo_settings (id) VALUES (1);

INSERT IGNORE INTO socials (id, type, url, enabled, sort_order) VALUES
(1,'WhatsApp','https://wa.me/66653458115',1,1),
(2,'Instagram','https://instagram.com/',1,2),
(3,'Telegram','https://t.me/',1,3),
(4,'Facebook','https://facebook.com/',1,4),
(5,'YouTube','https://youtube.com/',1,5);

INSERT IGNORE INTO sections (id, title, description, icon, btn_text, btn_url, visible, sort_order) VALUES
(1,'Live Systems','Currently building tools that blend design and automation.','⚡','Explore','#',1,1),
(2,'Open Source','Contributing to the community, one commit at a time.','🛰️','View','#',1,2);

-- NOTE: admin_users is intentionally left empty here.
-- Run api/setup.php once after upload to create your first admin login.
