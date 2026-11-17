-- Enquiry tracker: stores every enquiry-form submission (contact page and the
-- "Ready To...?" forms) so it can be followed up in admin -> Enquiries.
--
-- OPTIONAL: the site creates this table automatically the first time an
-- enquiry is submitted or the Enquiries admin page is opened. Run this only if
-- the admin page says the table couldn't be created (database user without
-- CREATE permission). Safe to re-run.
--
-- Run in Hostinger hPanel -> phpMyAdmin, SQL tab.

CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(60) NOT NULL DEFAULT '',
    service VARCHAR(150) NOT NULL DEFAULT '',
    plan VARCHAR(50) NOT NULL DEFAULT '',
    source VARCHAR(40) NOT NULL DEFAULT '',
    page VARCHAR(255) NOT NULL DEFAULT '',
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    notes TEXT NULL,
    deal_value DECIMAL(12,2) NULL,
    email_sent TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
