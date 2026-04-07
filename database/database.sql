USE turkiye_drinks;

-- Tabel voor alle dranken
CREATE TABLE dranken (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    naam        VARCHAR(100),
    beschrijving TEXT,
    regio       VARCHAR(100),
    prijs       DECIMAL(6,2)
);

-- Voorbeelddata
INSERT INTO dranken VALUES
(1, 'cay',      'Turkse thee uit Zwarte Zee, sinds 1800 populair.', 'Zwarte Zee',    2.50),
(2, 'ayran',    'Yoghurt drank uit Anatolie, eeuwen oud.',           'Anatolie',      1.50),
(3, 'salgam',   'Pittig drankje uit Adana met diepe traditie.',      'Adana',         2.00),
(4, 'limonata', 'Frisse citroen drank uit Istanbul.',                'Istanbul',      3.00),
(5, 'raki',     'Bekende Turkse alcohol drank.',                     'Egeische regio',10.00),
(6, 'koffie',   'Sterke Turkse koffie.',                             'Turkije',       2.80);

-- Tabel voor geplaatste bestellingen
CREATE TABLE bestellingen (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    naam   VARCHAR(100),
    email  VARCHAR(100),
    adres  TEXT,
    totaal DECIMAL(6,2)
);