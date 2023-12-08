use gsb_frais;
DROP TABLE IF EXISTS utilisateur ;

create table utilisateur as select * from visiteur;
select * from utilisateur;
alter table utilisateur add column statut int default 1;

insert into utilisateur(id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche, statut)
values('666','Dupont','Martin','m.dupont','1','52 rue de Penthièvre','95000','PONTOISE','1987-06-14', 2);


-- CREATE TABLE IF NOT EXISTS utilisateur (
 --  id char(5) NOT NULL,
--  nom char(30) DEFAULT NULL,
--  prenom char(30)  DEFAULT NULL, 
--  login char(20) DEFAULT NULL,
--  mdp char(20) DEFAULT NULL,
--  adresse char(30) DEFAULT NULL,
--  cp char(5) DEFAULT NULL,
--  ville char(30) DEFAULT NULL,
--  dateembauche date DEFAULT NULL,
--  statut int DEFAULT 1,
--  PRIMARY KEY (id)
-- ) ENGINE=InnoDB;