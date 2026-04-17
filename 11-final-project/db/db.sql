CREATE TABLE `solace`.`events` (
  `ev_id` INT NOT NULL AUTO_INCREMENT,
  `ev_title` VARCHAR(255) NULL,
  `ev_desc` MEDIUMTEXT NULL,
  `ev_date` DATE NOT NULL,
  `ev_time` VARCHAR(100) NULL,
  `ev_completed` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`ev_id`));
  
CREATE TABLE `solace`.`todos` (
  `todo_id` INT NOT NULL AUTO_INCREMENT,
  `todo_task` MEDIUMTEXT NOT NULL,
  `todo_date` DATE NOT NULL,
  PRIMARY KEY (`todo_id`));
