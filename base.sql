PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS conges;
DROP TABLE IF EXISTS soldes;
DROP TABLE IF EXISTS employes;
DROP TABLE IF EXISTS types_conges;
DROP TABLE IF EXISTS departements;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

CREATE TABLE departements(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    description TEXT
);

CREATE TABLE types_conges(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE,
    jours_annuels INTEGER NOT NULL,
    deductible BOOLEAN NOT NULL DEFAULT 1
);

CREATE TABLE employes(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    id_role INTEGER NOT NULL,
    id_departement INTEGER,
    date_embauche DATE NOT NULL,
    actif BOOLEAN NOT NULL DEFAULT 1,
    FOREIGN KEY (id_departement) REFERENCES departements(id),
    FOREIGN KEY (id_role) REFERENCES roles(id)
);

CREATE TABLE soldes(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_employe INTEGER NOT NULL,
    id_type_conge INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    jours_attribues INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (id_employe) REFERENCES employes(id),
    FOREIGN KEY (id_type_conge) REFERENCES types_conges(id)
);

CREATE TABLE conges(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_employe INTEGER NOT NULL,
    id_type_conge INTEGER NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours INTEGER NOT NULL,
    motif TEXT,
    statut TEXT NOT NULL CHECK(statut IN ('en_attente', 'approuve', 'refuse','annule')) DEFAULT 'en_attente',
    commentaire_rh TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traite_par INTEGER,
    FOREIGN KEY (id_employe) REFERENCES employes(id),
    FOREIGN KEY (id_type_conge) REFERENCES types_conges(id),
    FOREIGN KEY (traite_par) REFERENCES employes(id)
);

INSERT INTO roles (nom) VALUES
('admin'),
('rh'),
('employe');

INSERT INTO departements (nom, description) VALUES
('Ressources Humaines', 'Gestion du personnel et des conges'),
('Informatique', 'Developpement et maintenance des systemes informatiques'),
('Finance', 'Gestion des finances et de la comptabilite');

INSERT INTO types_conges (libelle, jours_annuels, deductible) VALUES
('Conge paye', 25, 1),
('Conge maladie', 10, 0),
('Conge special', 5, 1),
('Sans solde', 0, 0);

INSERT INTO employes (nom, prenom, email, password, id_role, id_departement, date_embauche, actif) VALUES
('Admin', 'TechMada', 'admin@techmada.mg', 'admin123', 1, 1, '2022-01-05', 1),
('Rabe', 'Marie', 'rh@techmada.mg', 'rh123', 2, 1, '2021-02-01', 1),
('Rakoto', 'Soa', 'employe@techmada.mg', 'emp123', 3, 2, '2023-03-15', 1),
('Fidy', 'Tsiry', 'tsiry.fidy@techmada.mg', 'emp123', 3, 3, '2020-05-10', 1);

INSERT INTO soldes (id_employe, id_type_conge, annee, jours_attribues, jours_pris) VALUES
(1, 1, 2026, 25, 0),
(1, 2, 2026, 10, 0),
(1, 3, 2026, 5, 0),
(1, 4, 2026, 0, 0),
(2, 1, 2026, 25, 3),
(2, 2, 2026, 10, 0),
(2, 3, 2026, 5, 1),
(2, 4, 2026, 0, 0),
(3, 1, 2026, 25, 5),
(3, 2, 2026, 10, 1),
(3, 3, 2026, 5, 2),
(3, 4, 2026, 0, 0),
(4, 1, 2026, 25, 0),
(4, 2, 2026, 10, 2),
(4, 3, 2026, 5, 0),
(4, 4, 2026, 0, 0);

INSERT INTO conges (id_employe, id_type_conge, date_debut, date_fin, nb_jours, motif, statut, commentaire_rh, traite_par) VALUES
(3, 1, '2026-06-10', '2026-06-14', 5, 'Vacances', 'approuve', 'Bonnes vacances', 2),
(3, 2, '2026-06-20', '2026-06-21', 2, 'Maladie', 'en_attente', NULL, NULL),
(4, 3, '2026-07-05', '2026-07-05', 1, 'Evenement familial', 'refuse', 'Solde insuffisant', 2);