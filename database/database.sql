CREATE DATABASE turkiye_drinks;
USE turkiye_drinks;

CREATE TABLE dranken (
id INT AUTO_INCREMENT PRIMARY KEY,
naam VARCHAR(100),
prijs DECIMAL(5,2)
);

CREATE TABLE bestellingen (
id INT AUTO_INCREMENT PRIMARY KEY,
naam VARCHAR(100),
email VARCHAR(100),
adres TEXT,
totaal DECIMAL(6,2)
);

CREATE TABLE bestelling_items (
id INT AUTO_INCREMENT PRIMARY KEY,
bestelling_id INT,
drank_id INT,
aantal INT
);

INSERT INTO dranken (naam,prijs) VALUES
('cay',2.50),
('ayran',1.50),
('salgam',2.00),
('limonata',3.00),
('raki',10.00),
('koffie',2.80);
