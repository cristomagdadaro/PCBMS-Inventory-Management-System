-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pcbms_db
DROP DATABASE IF EXISTS `pcbms_db`;
CREATE DATABASE IF NOT EXISTS `pcbms_db` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `pcbms_db`;

-- Dumping structure for table pcbms_db.consigned_details
DROP TABLE IF EXISTS `consigned_details`;
CREATE TABLE IF NOT EXISTS `consigned_details` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_id` smallint(6) DEFAULT '0',
  `prod_id` int(11) DEFAULT NULL,
  `particulars` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `interest` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `FK_consigned_details_consigned_product` (`cp_id`),
  KEY `FK_consigned_details_product` (`prod_id`),
  CONSTRAINT `FK_consigned_details_consigned_product` FOREIGN KEY (`cp_id`) REFERENCES `consigned_product` (`cp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_consigned_details_product` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.consigned_product
DROP TABLE IF EXISTS `consigned_product`;
CREATE TABLE IF NOT EXISTS `consigned_product` (
  `cp_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `supp_id` smallint(6) DEFAULT NULL,
  `user_id` smallint(6) DEFAULT NULL,
  `date_delivered` date NOT NULL,
  PRIMARY KEY (`cp_id`),
  KEY `FK_consigned_product_personnel` (`user_id`),
  KEY `FK_consigned_product_supplier` (`supp_id`),
  CONSTRAINT `FK_consigned_product_personnel` FOREIGN KEY (`user_id`) REFERENCES `personnel` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_consigned_product_supplier` FOREIGN KEY (`supp_id`) REFERENCES `supplier` (`supp_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.customer
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT 'unknown',
  `address` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for view pcbms_db.get_delivery_detail
DROP VIEW IF EXISTS `get_delivery_detail`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_delivery_detail` (
	`cp_id` SMALLINT(6) NOT NULL,
	`item_id` INT(11) NOT NULL,
	`prod_id` INT(11) NOT NULL,
	`prod_name` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`unit` ENUM('pcs','ml','l','kg','g','lb') NOT NULL COLLATE 'latin1_swedish_ci',
	`particulars` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`unit_price` DECIMAL(10,2) NULL,
	`interest` DECIMAL(10,2) NULL,
	`selling_price` DECIMAL(10,2) NULL,
	`quantity` INT(11) NULL,
	`amount` DECIMAL(10,2) NULL,
	`date_delivered` DATE NOT NULL,
	`expiry_date` DATE NULL,
	`user_id` SMALLINT(6) NULL,
	`personnel` VARCHAR(61) NOT NULL COLLATE 'latin1_swedish_ci',
	`supp_id` SMALLINT(6) NULL,
	`company` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`contact_person` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`phone` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_delivery_summary
DROP VIEW IF EXISTS `get_delivery_summary`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_delivery_summary` (
	`cp_id` SMALLINT(6) NOT NULL,
	`dist_item_count` BIGINT(21) NOT NULL,
	`avg_interest` DECIMAL(13,2) NULL,
	`total_sell_price` DECIMAL(32,2) NULL,
	`total_qty` DECIMAL(32,0) NULL,
	`total_amt` DECIMAL(32,2) NULL,
	`date_delivered` DATE NOT NULL,
	`user_id` SMALLINT(6) NULL,
	`personnel` VARCHAR(61) NOT NULL COLLATE 'latin1_swedish_ci',
	`supp_id` SMALLINT(6) NULL,
	`company` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_order_detail
DROP VIEW IF EXISTS `get_order_detail`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_order_detail` (
	`item_id` INT(11) NOT NULL,
	`order_id` INT(11) NULL,
	`supp_id` SMALLINT(6) NOT NULL,
	`company` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`prod_id` INT(11) NOT NULL,
	`prod_name` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`unit` ENUM('pcs','ml','l','kg','g','lb') NOT NULL COLLATE 'latin1_swedish_ci',
	`quantity` INT(11) NULL,
	`status` ENUM('Pending','Received','Cancelled') NOT NULL COLLATE 'latin1_swedish_ci',
	`contact_person` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`phone` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci',
	`user_id` SMALLINT(6) NULL,
	`personnel` VARCHAR(61) NOT NULL COLLATE 'latin1_swedish_ci',
	`order_date` DATE NULL
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_order_summary
DROP VIEW IF EXISTS `get_order_summary`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_order_summary` (
	`or_id` INT(11) NOT NULL,
	`company` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`order_date` DATE NULL,
	`item_count` BIGINT(21) NULL,
	`phone` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci',
	`status` ENUM('Pending','Received','Cancelled') NOT NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_product_sale_details
DROP VIEW IF EXISTS `get_product_sale_details`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_product_sale_details` (
	`prod_id` INT(11) NOT NULL,
	`cp_id` SMALLINT(6) NULL,
	`item_id` INT(11) NOT NULL,
	`prod_name` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`unit_price` DECIMAL(10,2) NULL,
	`initial_qty` INT(11) NULL,
	`total_qty_sold` DECIMAL(32,0) NULL
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_product_summary
DROP VIEW IF EXISTS `get_product_summary`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_product_summary` (
	`prod_id` INT(11) NOT NULL,
	`prod_name` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`initial_qty` DECIMAL(32,0) NULL,
	`total_qty_sold` DECIMAL(32,0) NULL
) ENGINE=MyISAM;

-- Dumping structure for view pcbms_db.get_sales_summary
DROP VIEW IF EXISTS `get_sales_summary`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `get_sales_summary` (
	`num_sales` DECIMAL(32,0) NULL,
	`total_revenue` DECIMAL(32,0) NULL
) ENGINE=MyISAM;

-- Dumping structure for table pcbms_db.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `or_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` smallint(6) DEFAULT '0',
  `user_id` smallint(6) DEFAULT '0',
  `order_date` date DEFAULT NULL,
  `status` enum('Pending','Received','Cancelled') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`or_id`),
  KEY `FK__supplier` (`comp_id`),
  KEY `FK_orders_personnel` (`user_id`),
  CONSTRAINT `FK__supplier` FOREIGN KEY (`comp_id`) REFERENCES `supplier` (`supp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_orders_personnel` FOREIGN KEY (`user_id`) REFERENCES `personnel` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.order_details
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `prod_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `FK__orders` (`order_id`),
  KEY `FK__product` (`prod_id`),
  CONSTRAINT `FK__orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`or_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__product` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.personnel
DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `user_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) NOT NULL,
  `mname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `designation` enum('Store Manager','Cashier') NOT NULL,
  `picture` longblob,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(50) NOT NULL DEFAULT 'unkown',
  `shelf_life` int(10) unsigned DEFAULT NULL,
  `unit` enum('pcs','ml','l','kg','g','lb') NOT NULL,
  PRIMARY KEY (`prod_id`),
  UNIQUE KEY `prod_name` (`prod_name`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.sales
DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `ORNum` varchar(10) DEFAULT NULL,
  `date_issued` datetime NOT NULL,
  `customer` int(11) DEFAULT NULL,
  `user_id` smallint(6) NOT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `FK1_saler` (`user_id`),
  KEY `FK2_customer` (`customer`),
  CONSTRAINT `FK1_saler` FOREIGN KEY (`user_id`) REFERENCES `personnel` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK2_customer` FOREIGN KEY (`customer`) REFERENCES `customer` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.sale_details
DROP TABLE IF EXISTS `sale_details`;
CREATE TABLE IF NOT EXISTS `sale_details` (
  `item_no` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) DEFAULT NULL,
  `prod_id` int(11) DEFAULT NULL,
  `qty_sold` int(11) NOT NULL,
  `amount_sold` int(11) NOT NULL,
  PRIMARY KEY (`item_no`),
  KEY `FK2_item` (`prod_id`) USING BTREE,
  KEY `FK1_sale` (`sale_id`),
  CONSTRAINT `FK1_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sale_details_consigned_details` FOREIGN KEY (`prod_id`) REFERENCES `consigned_details` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pcbms_db.supplier
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `supp_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `company` varchar(50) NOT NULL,
  `contact_person` varchar(50) DEFAULT NULL,
  `sex` enum('Male','Female','Non-binary') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`supp_id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for view pcbms_db.get_delivery_detail
DROP VIEW IF EXISTS `get_delivery_detail`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_delivery_detail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_delivery_detail` AS SELECT `consigned_product`.`cp_id`, `item_id`,`product`.`prod_id`,`prod_name`,`unit`,`particulars`, `unit_price`, `interest`, `selling_price`, `quantity`, `amount`, `date_delivered`,
`expiry_date`, `consigned_product`.`user_id`, CONCAT(`fname`," ",`lname`) AS `personnel`, `consigned_product`.`supp_id`, `company`,`contact_person`, `phone`
FROM `consigned_details` 
INNER JOIN `consigned_product` ON `consigned_product`.`cp_id` = `consigned_details`.`cp_id`
INNER JOIN `supplier` ON `consigned_product`.`supp_id` = `supplier`.`supp_id`
INNER JOIN `personnel` ON `personnel`.user_id = `consigned_product`.user_id 
INNER JOIN `product` ON `product`.`prod_id` = `consigned_details`.`prod_id` ;

-- Dumping structure for view pcbms_db.get_delivery_summary
DROP VIEW IF EXISTS `get_delivery_summary`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_delivery_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_delivery_summary` AS SELECT `consigned_product`.`cp_id`, COUNT(`item_id`) AS dist_item_count, (CAST(AVG(`interest`) AS DECIMAL(10,2)) * 100) AS avg_interest, 
SUM(`selling_price`) AS total_sell_price, SUM(`quantity`) AS total_qty, SUM(`amount`) AS total_amt, 
`date_delivered`,`consigned_product`.`user_id`, CONCAT(`fname`," ",`lname`) AS `personnel`, `consigned_product`.`supp_id`, `company`
FROM `consigned_details` 
INNER JOIN `consigned_product` ON `consigned_product`.`cp_id` = `consigned_details`.`cp_id`
INNER JOIN `supplier` ON `consigned_product`.`supp_id` = `supplier`.`supp_id`
INNER JOIN `personnel` ON `personnel`.user_id = `consigned_product`.user_id
GROUP BY `cp_id` ;

-- Dumping structure for view pcbms_db.get_order_detail
DROP VIEW IF EXISTS `get_order_detail`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_order_detail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_order_detail` AS SELECT item_id, order_id, supp_id, company, `product`.prod_id, prod_name, unit,quantity, `status`, contact_person, phone, `orders`.user_id, 
CONCAT(fname,' ',lname) AS `personnel`, order_date
FROM `order_details` 
INNER JOIN `product` ON `product`.prod_id = `order_details`.prod_id
INNER JOIN `orders` ON or_id = order_id
INNER JOIN `supplier` ON supp_id = comp_id
INNER JOIN `personnel` ON `personnel`.user_id = `orders`.user_id ;

-- Dumping structure for view pcbms_db.get_order_summary
DROP VIEW IF EXISTS `get_order_summary`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_order_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_order_summary` AS SELECT or_id, company, order_date, 
(SELECT COUNT( `order_details`.prod_id) FROM `order_details` WHERE order_id = or_id) AS item_count,
phone, `status` FROM `orders` 
INNER JOIN `supplier` ON supp_id = comp_id  
INNER JOIN `order_details` ON order_id = or_id
INNER JOIN `product` ON `product`.prod_id = `order_details`.prod_id
GROUP BY order_id ;

-- Dumping structure for view pcbms_db.get_product_sale_details
DROP VIEW IF EXISTS `get_product_sale_details`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_product_sale_details`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_product_sale_details` AS select `product`.prod_id, cp_id, item_id, prod_name, unit_price, quantity AS initial_qty, 
COALESCE((SELECT SUM(qty_sold) FROM `sale_details` WHERE `sale_details`.prod_id = `product`.prod_id), 0) AS total_qty_sold
from `product` 
INNER JOIN `consigned_details` ON `consigned_details`.prod_id = `product`.prod_id ;

-- Dumping structure for view pcbms_db.get_product_summary
DROP VIEW IF EXISTS `get_product_summary`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_product_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_product_summary` AS SELECT `product`.prod_id, prod_name, SUM(quantity) AS initial_qty,
COALESCE((SELECT SUM(qty_sold) FROM `sale_details` WHERE `sale_details`.prod_id = `product`.prod_id), 0) AS total_qty_sold
FROM `product`
INNER JOIN `consigned_details` ON `consigned_details`.prod_id = `product`.prod_id
GROUP BY prod_id ;

-- Dumping structure for view pcbms_db.get_sales_summary
DROP VIEW IF EXISTS `get_sales_summary`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `get_sales_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `get_sales_summary` AS SELECT SUM(qty_sold) AS `num_sales`, SUM(amount_sold) AS `total_revenue` FROM `sale_details` GROUP BY `prod_id` ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
