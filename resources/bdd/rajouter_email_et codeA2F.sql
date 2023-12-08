use gsb_frais;
alter table utilisateur modify mdp char(100);
set sql_safe_updates=0;

ALTER TABLE utilisateur ADD email TEXT NULL;
UPDATE utilisateur SET email = CONCAT(login,"@swiss-galaxy.com");
ALTER TABLE utilisateur ADD codea2f CHAR(4);