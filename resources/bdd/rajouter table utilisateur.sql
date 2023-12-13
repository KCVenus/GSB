use gsb_frais;
DROP TABLE IF EXISTS utilisateur ;
set sql_safe_updates=0;
create table utilisateur as select * from visiteur;
select * from utilisateur;
alter table utilisateur add column role int default 1;

update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='al.audibert' and prenom='Alain';
update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='ay.audibert' and prenom='Aymon';

update utilisateur set email = CONCAT(login,"@swiss-galaxy.com") where login='a.garceau' and prenom='Archaimbau';
update utilisateur set login = CONCAT(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where login='a.garceau' and prenom='Arridano';
      
update utilisateur set login = concat(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where id in ('a286e', 'g696e' , 'h414e', 'l945e','m127e', 'r115z' ) ;
update utilisateur set login = CONCAT(login,'2'), email = CONCAT(login,"@swiss-galaxy.com") where login='b.gagnon' and prenom='Bernadette';
  
              

-- requête pour sélectionner les utilisateur avec un login identique
SELECT DISTINCT *
FROM utilisateur u1
WHERE EXISTS (
              SELECT *
              FROM utilisateur u2
              WHERE u1.id <> u2.id
              AND   u1.login = u2.login 
     )

;
              
alter table utilisateur add constraint unique_login UNIQUE KEY (login);

-- ajouter un comptable 
insert into utilisateur(id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche, statut)
values('666','Dupont','Martin','m.dupont','1','52 rue de Penthièvre','95000','PONTOISE','1987-06-14', 2);
select count(distinct login), count(login) from utilisateur;

create table if not exists roles(
id int not null primary key,
nom char(50) not null);
insert into roles(id,nom) values(1,"visiteur"), (2, "comptable");
select * from roles;

alter table utilisateur add constraint fk_utilisateur_roles FOREIGN KEY (role) references roles(id);