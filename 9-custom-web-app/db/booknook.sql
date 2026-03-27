CREATE TABLE `booknook`.`book` (
  `book_id` INT NOT NULL AUTO_INCREMENT,
  `book_title` VARCHAR(100) NOT NULL,
  `book_author_first` VARCHAR(100) NULL,
  `book_author_last` VARCHAR(100) NULL,
  `book_rating` INT NULL,
  `book_review` MEDIUMTEXT NULL,
  `book_status` VARCHAR(45) NOT NULL,
  `book_genre` VARCHAR(45) NULL,
  `book_length` INT NULL,
  `book_date_started` DATETIME NULL,
  `book_date_finished` DATETIME NULL,
  `book_publication` INT NULL,
  PRIMARY KEY (`book_id`));