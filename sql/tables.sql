CREATE DATABASE url_shortner;
CREATE TABLE utilisateur (
             id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
             nom VARCHAR(50) NOT NULL,
             prenom VARCHAR(50) NOT NULL,
             email VARCHAR(100) NOT NULL UNIQUE,
             role VARCHAR(20) NULL
);
ALTER TABLE utilisateur ADD COLUMN token VARCHAR(32) NULL;
ALTER TABLE utilisateur ADD COLUMN etat_connexion BOOLEAN DEFAULT FALSE;
CREATE TABLE compte_utilisateur (id_compte int PRIMARY KEY AUTO_INCREMENT,
             email varchar(80),
             mot_de_passe varchar(90));
ALTER TABLE compte_utilisateur ADD COLUMN role VARCHAR(20);
ALTER TABLE compte_utilisateur ADD COLUMN etat_connexion TINYINT(1) DEFAULT 0;
ALTER TABLE compte_utilisateur ADD COLUMN reset_hash VARCHAR(64),
ADD COLUMN token_expiration DATETIME after reset_hash;

CREATE table lien (id_lien int PRIMARY KEY AUTO_INCREMENT,
                   id_utilisateur int,
                   identifiant varchar(20) NOT NULL UNIQUE,
                   long_lien varchar(300),
                   lien_court varchar(200),
                   FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur));
ALTER TABLE `lien` ADD `Date_creation` DATE NOT NULL after lien_court;
ALTER TABLE `lien` ADD `nb_clic` INT NOT NULL DEFAULT '0' AFTER `date_creation`;
