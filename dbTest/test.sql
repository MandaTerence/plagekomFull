SELECT
    sum(dv.Quantite * pr.Prix_detail) as prix
FROM 
    facture f
    JOIN    detailvente dv on dv.Facture = f.id
    JOIN    prix pr on dv.ID_prix = pr.Id 
where
    Matricule_personnel like 'VP00080'
    
    and Date BETWEEN '10/10/2020' and NOW()

CREATE TABLE classement(

);

CREATE TABLE classement(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    place SMALLINT NOT NULL,
    Id_de_la_mission VARCHAR(60) NOT NULL,
    Commercial VARCHAR(60) NOT NULL
);