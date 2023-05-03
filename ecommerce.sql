DROP DATABASE IF EXISTS ecommerce;

CREATE DATABASE IF NOT EXISTS ecommerce;

CREATE USER IF NOT EXISTS 'ecommerce'@'%' IDENTIFIED BY 'ecommerce';

GRANT ALL PRIVILEGES ON ecommerce.* TO 'ecommerce'@'%';

USE ecommerce;

CREATE TABLE `utente` (
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nome` VARCHAR(50) NOT NULL,
  `cognome` VARCHAR(50) NOT NULL,
  `genere` ENUM('Maschio', 'Femmina', 'Azienda', 'Altro') NOT NULL,
  `codice_fiscale` VARCHAR(16) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `telefono` DECIMAL(10,0) NOT NULL,
  `data_di_nascita` DATE NOT NULL,
  `attivo` BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE `carrello` (
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `utente` INT NOT NULL,
  `variante` INT NOT NULL,
  `quantita` INT NOT NULL
);

CREATE TABLE `ordine` (
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `utente` INT NOT NULL,
  `stato` ENUM('Inviato', 'Saldato', 'In lavorazione', 'Spedito', 'Consegnato') NOT NULL,
  `attivo` BOOL NOT NULL DEFAULT TRUE
);

CREATE TABLE `ordine_prodotto` (
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `ordine` INT NOT NULL,
  `variante` INT NOT NULL
);

CREATE TABLE `indirizzo` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `utente` INT NOT NULL,
  `via` VARCHAR(60) NOT NULL,
  `civico` VARCHAR(10) NOT NULL,
  `comune` VARCHAR(30) NOT NULL,
  `provincia` VARCHAR(30) NOT NULL,
  `cap` VARCHAR(5) NOT NULL
);

CREATE TABLE `prodotto` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `fornitore` INT NOT NULL,
  `nome` VARCHAR(30) NOT NULL,
  `descrizione` VARCHAR(200) NOT NULL,
  `prezzo` DECIMAL(6,2) NOT NULL,
  `attivo` BOOL NOT NULL DEFAULT TRUE
);

CREATE TABLE `variante` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `prodotto` INT NOT NULL,
  `nome` VARCHAR(30) NOT NULL,
  `descrizione` VARCHAR(200) NOT NULL,
  `prezzo` DECIMAL(6,2) NOT NULL
);

CREATE TABLE `fornitore` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(30) NOT NULL,
  `utente` INT NOT NULL,
  `luogo` VARCHAR(30) NOT NULL
);

CREATE TABLE `sessione` (
  `id` UUID PRIMARY KEY NOT NULL DEFAULT UUID(),
  `utente` INT NOT NULL,
  `scadenza` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP() + 60 * 60 * 24 * 30),
  `attivo` BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE `exception` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `code` INT NOT NULL,
  `message` VARCHAR(200) NOT NULL,
  `file` VARCHAR(80) NOT NULL,
  `line` INT NOT NULL
);

ALTER TABLE `indirizzo` ADD FOREIGN KEY (`utente`) REFERENCES `utente` (`id`);

ALTER TABLE `prodotto` ADD FOREIGN KEY (`fornitore`) REFERENCES `fornitore` (`id`);

ALTER TABLE `variante` ADD FOREIGN KEY (`prodotto`) REFERENCES `prodotto` (`id`);

ALTER TABLE `fornitore` ADD FOREIGN KEY (`utente`) REFERENCES `utente` (`id`);

ALTER TABLE `sessione` ADD FOREIGN KEY (`utente`) REFERENCES `utente` (`id`);

ALTER TABLE `carrello` ADD FOREIGN KEY (`utente`) REFERENCES `utente` (`id`);

ALTER TABLE `carrello` ADD FOREIGN KEY (`variante`) REFERENCES `variante` (`id`);

ALTER TABLE `ordine` ADD FOREIGN KEY (`utente`) REFERENCES `utente` (`id`);

ALTER TABLE `ordine_prodotto` ADD FOREIGN KEY (`ordine`) REFERENCES `ordine` (`id`);

ALTER TABLE `ordine_prodotto` ADD FOREIGN KEY (`variante`) REFERENCES `variante` (`id`);