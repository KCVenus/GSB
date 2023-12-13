use gsb_frais;
set sql_safe_updates=0;

DROP TABLE IF EXISTS utilisateur ;
create table utilisateur as select * from visiteur;
alter table utilisateur add column role int default 1;

alter table utilisateur modify mdp char(100);
ALTER TABLE utilisateur ADD email TEXT NULL;
UPDATE utilisateur SET email = CONCAT(login,"@swiss-galaxy.com");
ALTER TABLE utilisateur ADD codea2f CHAR(4);

update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='al.audibert' and prenom='Alain';
update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='ay.audibert' and prenom='Aymon';
update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='a.garceau' and prenom='Archaimbau';
update utilisateur set login = CONCAT(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where login='a.garceau' and prenom='Arridano';
update utilisateur set login = concat(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where id in ('a286e', 'g696e' , 'h414e', 'l945e','m127e', 'r115z' ) ;
update utilisateur set login = CONCAT(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where login='b.gagnon' and prenom='Bernadette';

-- ajouter un comptable dans la table utilisateur
insert into utilisateur(id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche, statut, email)
values('666','Dupont','Martin','m.dupont','1','52 rue de Penthièvre','95000','PONTOISE','1987-06-14', 2, 'm.dupont@swiss-galaxy.com');

-- ajouter contrainte pour que le login soit unique
alter table utilisateur add constraint unique_login UNIQUE KEY (login);

-- requête pour sélectionner les utilisateur avec un login identique
-- SELECT DISTINCT *
-- FROM utilisateur u1
-- WHERE EXISTS (
-- SELECT *
--  FROM utilisateur u2
--  WHERE u1.id <> u2.id
--  AND   u1.login = u2.login 
-- );

-- ajouter table roles
create table if not exists roles(
id int not null primary key,
nom char(50) not null);

insert into roles(id,nom) values(1,"visiteur"), (2, "comptable");
alter table utilisateur add constraint fk_utilisateur_roles FOREIGN KEY (role) references roles(id);



