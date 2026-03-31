CREATE DATABASE turkiye_drinks;
USE turkiye_drinks;

CREATE TABLE dranken(
id INT AUTO_INCREMENT PRIMARY KEY,
naam VARCHAR(100),
beschrijving TEXT,
regio VARCHAR(100),
prijs DECIMAL(6,2),
prik BOOLEAN,
alcohol BOOLEAN
);

CREATE TABLE bestellingen(
id INT AUTO_INCREMENT PRIMARY KEY,
naam VARCHAR(100),
email VARCHAR(100),
adres TEXT,
totaal DECIMAL(6,2)
);

CREATE TABLE bestelling_items(
id INT AUTO_INCREMENT PRIMARY KEY,
bestelling_id INT,
drank_id INT,
aantal INT
);

INSERT INTO dranken (naam,beschrijving,regio,prijs,prik,alcohol) VALUES
('cay','Turkse thee','Zwarte Zee',2.50,0,0),
('ayran','Yoghurt drank','Anatolie',1.50,0,0),
('salgam','Pittig drankje','Adana',2.00,0,0),
('limonata','Citroen fris','Istanbul',3.00,1,0),
('raki','Alcohol drank','Egeische',10.00,0,1),
('koffie','Turkse koffie','Turkije',2.80,0,0);
