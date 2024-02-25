DROP TABLE IF EXISTS `fraiskm`;
CREATE TABLE IF NOT EXISTS `fraiskm` (
  `id` char(4) NOT NULL,
  `libelle` varchar(40) NOT NULL,
  `prix` decimal(3,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Déchargement des données de la table `fraiskm`
--
INSERT INTO `fraiskm` (`id`, `libelle`, `prix`) VALUES
('4D', 'véhicule 4CV diesel', '0.52'),
('4E', 'véhicule 4CV essence', '0.62'),
('5/6D', 'véhicule 5/6CV diesel ', '0.58'),
('5/6E', 'véhicule 5/6CV essence', '0.67'),
('Z', 'aucun véhicule', '0');

-- j'ai rajouté un id pour aucun véhicule pour les comptable
-- afin de pouvoir quand même poser une contrainte d'intégrité référentielle
-- si je mettais NULL possible pour fraiskm dans utilisateur mais NOT NULL pour id dans fraiskm, la constrainte fk n'était pas possible

ALTER TABLE utilisateur ADD COLUMN `idVehicule` char(4) NOT NULL DEFAULT '4D' ;
update utilisateur set idVehicule='Z' where role=2;
ALTER TABLE utilisateur ADD CONSTRAINT `FK_utilisateur_fraisKm` FOREIGN KEY (`idVehicule`) REFERENCES `fraiskm` (`id`);

-- si utilisateur n'est pas en primary key et fichefrais reference toujours visiteur :

-- alter table fichefrais DROP CONSTRAINT `fichefrais_ibfk_2` ;
-- drop table visiteur;
-- alter table fichefrais ADD CONSTRAINT `fichefrais_ibfk_2` FOREIGN KEY (`idvisiteur`) REFERENCES `utilisateur` (`id`) ;
-- alter table utilisateur add constraint pk_utilisateur_id PRIMARY KEY (id);