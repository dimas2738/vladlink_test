-- CREATE DB
DROP DATABASE IF EXISTS `catalogDB`;
CREATE DATABASE `catalogDB` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

-- CREATE TABLE
DROP TABLE IF EXISTS `catalogDB`.`catalog`;
CREATE TABLE `catalogDB`.`catalog` (
                                       `id` INT(10) unsigned NOT NULL,
                                       `name` VARCHAR(255) NOT NULL,
                                       `alias` VARCHAR(255) NOT NULL,
                                       `url` VARCHAR(255) NOT NULL,
                                       `parent_id` INT(10) unsigned NOT NULL,
                                       `l_key` INT(10) unsigned NOT NULL DEFAULT 0,
                                       `r_key` INT(10) unsigned NOT NULL DEFAULT 0,
                                       `depth` INT(10) unsigned NOT NULL DEFAULT 0,
                                       PRIMARY KEY (`id`),
                                       KEY `search` (`l_key`, `r_key`, `depth`)
);