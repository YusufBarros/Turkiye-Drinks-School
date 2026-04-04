-- ============================================================
--  Turkiye Drinks – Database-script
--  Voer dit script uit in phpMyAdmin of via de CLI:
--    mysql -u root -p < turkiye_drinks.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS turkiye_drinks
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE turkiye_drinks;

-- ── Tabel: dranken ───────────────────────────────────────────
DROP TABLE IF EXISTS bestelling_items;
DROP TABLE IF EXISTS bestellingen;
DROP TABLE IF EXISTS dranken;

CREATE TABLE dranken (
    id           INT            AUTO_INCREMENT PRIMARY KEY,
    naam         VARCHAR(100)   NOT NULL,
    beschrijving TEXT,
    prijs        DECIMAL(5,2)  NOT NULL,
    regio        VARCHAR(100),
    met_prik     TINYINT(1)    NOT NULL DEFAULT 0,
    alcohol      TINYINT(1)    NOT NULL DEFAULT 0,
    afbeelding   VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabel: bestellingen ──────────────────────────────────────
CREATE TABLE bestellingen (
    id           INT            AUTO_INCREMENT PRIMARY KEY,
    datum        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    totaalprijs  DECIMAL(8,2)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabel: bestelling_items ──────────────────────────────────
CREATE TABLE bestelling_items (
    id              INT  AUTO_INCREMENT PRIMARY KEY,
    bestelling_id   INT  NOT NULL,
    drank_id        INT  NOT NULL,
    aantal          INT  NOT NULL,
    CONSTRAINT fk_bi_bestelling
        FOREIGN KEY (bestelling_id) REFERENCES bestellingen(id)
        ON DELETE CASCADE,
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
