SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `restaurant_halls`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `restaurant_halls` ;

CREATE TABLE IF NOT EXISTS `restaurant_halls` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `desc` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orders_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_status` ;

CREATE TABLE IF NOT EXISTS `orders_status` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `alias` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders` ;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_order` DATETIME NOT NULL,
  `hall_id` INT UNSIGNED NOT NULL,
  `seats_quantity` INT NOT NULL,
  `contact_name` VARCHAR(255) NOT NULL,
  `contact_phone` VARCHAR(15) NOT NULL,
  `contact_email` VARCHAR(65) NOT NULL,
  `order_description` TEXT NULL,
  `orders_status` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_orders_restaurant_halls1_idx` (`hall_id` ASC),
  INDEX `fk_orders_orders_status1_idx` (`orders_status` ASC),
  CONSTRAINT `fk_orders_restaurant_halls1`
    FOREIGN KEY (`hall_id`)
    REFERENCES `restaurant_halls` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_orders_status1`
    FOREIGN KEY (`orders_status`)
    REFERENCES `orders_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `restaurant_halls`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `restaurant_halls` (`id`, `name`, `desc`) VALUES (NULL, 'ORDER_RESTAURANT_HALL', 'ORDER_RESTAURANT_HALL_DESC');
INSERT INTO `restaurant_halls` (`id`, `name`, `desc`) VALUES (NULL, 'ORDER_PIZZERIA_HALL', 'ORDER_PIZZERIA_HALL_DESC');
INSERT INTO `restaurant_halls` (`id`, `name`, `desc`) VALUES (NULL, 'ORDER_BANKET_HALL', 'ORDER_BANKET_HALL_DESC');

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders_status`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `orders_status` (`id`, `name`, `alias`) VALUES (NULL, 'ORDER_STATUS_NEW', 'ORDER_STATUS_NEW');
INSERT INTO `orders_status` (`id`, `name`, `alias`) VALUES (NULL, 'ORDER_STATUS_CONFIRMED', 'ORDER_STATUS_CONFIRMED');
INSERT INTO `orders_status` (`id`, `name`, `alias`) VALUES (NULL, 'ORDER_STATUS_REJECTED', 'ORDER_STATUS_REJECTED');
INSERT INTO `orders_status` (`id`, `name`, `alias`) VALUES (NULL, 'ORDER_STATUS_REMOVED', 'ORDER_STATUS_REMOVED');

COMMIT;

 
