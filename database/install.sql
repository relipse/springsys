CREATE TABLE `companies`
(
    `id` INT NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(255) NOT NULL DEFAULT '' ,
    `street1` VARCHAR(100) NOT NULL DEFAULT '' ,
    `street2` VARCHAR(100) NOT NULL DEFAULT '' ,
    `city` VARCHAR(100) NOT NULL DEFAULT '' ,
     `state_province` VARCHAR(100) NOT NULL DEFAULT '' ,
     `zip` VARCHAR(100) NOT NULL DEFAULT '' ,
     PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `employees`
(
    `id` INT NOT NULL AUTO_INCREMENT ,
    `company_id` INT NOT NULL ,
    `firstname` VARCHAR(255) NOT NULL ,
    `middlename` VARCHAR(100) NOT NULL ,
    `lastname` VARCHAR(255) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
