CREATE DATABASE turkiye_drinks;
USE turkiye_drinks;

CREATE TABLE dranken (
 id INT AUTO_INCREMENT PRIMARY KEY,
 naam VARCHAR(100),
 beschrijving TEXT,
 regio VARCHAR(100),
 prijs DECIMAL(5,2),
 prik BOOLEAN,
 alcohol BOOLEAN
);

INSERT INTO dranken (naam, beschrijving, regio, prijs, prik, alcohol) VALUES
('cay','Turkse thee','Zwarte Zee',2.50,0,0),
('ayran','Yoghurt drank','Anatolie',1.50,0,0),
('salgam','Pittige drank','Adana',2.00,0,0),
('limonata','Citroen frisdrank','Istanbul',3.00,1,0),
('raki','Alcoholische drank','Egeïsche regio',10.00,0,1),
('koffie','Turkse koffie','Turkije',2.80,0,0),
('boza','Gefermenteerd','Istanbul',3.50,0,0),
('sherbet','Zoete drank','Ottomaans',2.20,0,0);
