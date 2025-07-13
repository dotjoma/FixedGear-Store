-- Categories Table for Fixed Gear Culture Admin Dashboard
-- This table stores product categories for the e-commerce system

CREATE TABLE IF NOT EXISTS `categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `slug` varchar(100) UNIQUE,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories data
INSERT INTO `categories` (`name`, `description`, `slug`, `status`, `sort_order`) VALUES
('Fixed Gear', 'Complete fixed gear bicycles and frames', 'fixed-gear', 'active', 1),
('Frames', 'Bicycle frames and framesets', 'frames', 'active', 2),
('Components', 'Bicycle components and parts', 'components', 'active', 3),
('Accessories', 'Bicycle accessories and gear', 'accessories', 'active', 4),
('Apparel', 'Cycling clothing and apparel', 'apparel', 'inactive', 5);

-- Add indexes for better performance
CREATE INDEX `idx_categories_status` ON `categories` (`status`);
CREATE INDEX `idx_categories_sort_order` ON `categories` (`sort_order`);
CREATE INDEX `idx_categories_slug` ON `categories` (`slug`); 