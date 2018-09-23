-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema totem
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema totem
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `totem` DEFAULT CHARACTER SET utf8 ;
USE `totem` ;

-- -----------------------------------------------------
-- Table `totem`.`instituicao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totem`.`instituicao` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `endereco` VARCHAR(150) NOT NULL,
  `nome_faculdade` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `totem`.`cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totem`.`cursos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `area` VARCHAR(50) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `faculdade` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_cursos_instituicao` (`faculdade` ASC),
  CONSTRAINT `FK_cursos_instituicao`
    FOREIGN KEY (`faculdade`)
    REFERENCES `totem`.`instituicao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `totem`.`eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totem`.`eventos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `faculdade` INT(11) NOT NULL,
  `data_ini` DATETIME NOT NULL,
  `data_fim` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_eventos_instituicao` (`faculdade` ASC),
  CONSTRAINT `FK_eventos_instituicao`
    FOREIGN KEY (`faculdade`)
    REFERENCES `totem`.`instituicao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `totem`.`pessoas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totem`.`pessoas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(16) NOT NULL,
  `matricula` VARCHAR(40) NULL DEFAULT NULL,
  `curso` INT(11) NULL DEFAULT NULL,
  `faculdade` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC),
  INDEX `FK_alunos_cursos` (`curso` ASC),
  INDEX `FK_alunos_instituicao` (`faculdade` ASC),
  CONSTRAINT `FK_alunos_cursos`
    FOREIGN KEY (`curso`)
    REFERENCES `totem`.`cursos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_alunos_instituicao`
    FOREIGN KEY (`faculdade`)
    REFERENCES `totem`.`instituicao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `totem`.`eventos_pessoas_inscricoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totem`.`eventos_pessoas_inscricoes` (
  `id_inscricao` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` INT(11) NOT NULL,
  `id_evento` INT(11) NOT NULL,
  `data_inscricao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `data_checkin` DATETIME NULL DEFAULT NULL,
  `data_checkout` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_inscricao`),
  INDEX `id_evento` (`id_evento` ASC),
  INDEX `id_aluno` (`id_pessoa` ASC),
  CONSTRAINT `id_eventofk`
    FOREIGN KEY (`id_evento`)
    REFERENCES `totem`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_pessoafk`
    FOREIGN KEY (`id_pessoa`)
    REFERENCES `totem`.`pessoas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
