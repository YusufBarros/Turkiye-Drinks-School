CREATE DATABASE IF NOT EXISTS turkiye_drinks
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE turkiye_drinks;

-- Verwijder tabellen als ze al bestaan (goede volgorde vanwege foreign keys)
DROP TABLE IF EXISTS bestelling_items;
DROP TABLE IF EXISTS bestellingen;
DROP TABLE IF EXISTS dranken;

-- ── Tabel: dranken ───────────────────────────────────────────
CREATE TABLE dranken (
    id           INT           AUTO_INCREMENT PRIMARY KEY,
    naam         VARCHAR(100)  NOT NULL,
    beschrijving TEXT,
    prijs        DECIMAL(5,2)  NOT NULL,
    regio        VARCHAR(100),
    met_prik     TINYINT(1)    NOT NULL DEFAULT 0,  -- 1 = met prik, 0 = zonder
    alcohol      TINYINT(1)    NOT NULL DEFAULT 0,  -- 1 = met alcohol, 0 = zonder
    afbeelding   VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabel: bestellingen ──────────────────────────────────────
CREATE TABLE bestellingen (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    datum       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    totaalprijs DECIMAL(8,2)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabel: bestelling_items ──────────────────────────────────
-- Koppeltabel tussen een bestelling en de losse dranken
CREATE TABLE bestelling_items (
    id            INT  AUTO_INCREMENT PRIMARY KEY,
    bestelling_id INT  NOT NULL,
    drank_id      INT  NOT NULL,
    aantal        INT  NOT NULL,
    -- Als de bestelling verwijderd wordt, verwijder dan ook de items
    CONSTRAINT fk_bi_bestelling
        FOREIGN KEY (bestelling_id) REFERENCES bestellingen(id)
        ON DELETE CASCADE,
    -- Drank mag niet verwijderd worden als hij in een bestelling zit
    CONSTRAINT fk_bi_drank
        FOREIGN KEY (drank_id) REFERENCES dranken(id)
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Voorbeelddata ────────────────────────────────────────────
INSERT INTO dranken (naam, beschrijving, prijs, regio, met_prik, alcohol, afbeelding) VALUES
('Ayran',
 'Verfrissende yoghurtdrank op basis van melk, water en zout. Een klassiek Turks icoon dat bij elke maaltijd hoort.',
 1.50, 'Heel Turkije', 0, 0, 'ayran.png'),

('Çay',
 'Sterke zwarte thee geserveerd in een typisch tulpvormig glas. Onmisbaar in de Turkse cultuur – dag en nacht gedronken.',
 1.20, 'Rize', 0, 0, 'cay.png'),

('Turkse Koffie',
 'Rijke, fijngemalen koffie bereid in een cezve op laag vuur. Geserveerd met een glas water en Turks snoepgoed.',
 2.50, 'Istanbul', 0, 0, 'koffie.png'),

('Şalgam',
 'Pittige, donkerrode drank gemaakt van gepekelde raap en wortels. Populair in het zuiden als begeleider bij kebap.',
 2.00, 'Adana', 0, 0, 'salgam.png'),

('Limonata',
 'Frisse handgemaakte limonade met een tinteling, populair langs de Egeïsche en Middellandse Zee kust.',
 2.25, 'Ege', 1, 0, 'limonata.png'),

('Rakı',
 'Het nationale drankje van Turkije. Anijsgedestilleerd en troebel wit bij menging met water – ook wel "leeuwenmelk" genoemd.',
 4.50, 'Izmir', 0, 1, 'raki.png');