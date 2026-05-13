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

CREATE TABLE roles(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

INSERT INTO roles (nom) VALUES
('admin'),
('rh'),
('employe');

INSERT INTO departements (nom, description) VALUES
('Ressources Humaines', 'Gestion du personnel et des congés'),
('Informatique', 'Développement et maintenance des systèmes informatiques'),
('Finance', 'Gestion des finances et de la comptabilité');

INSERT INTO employes (nom, prenom, email, password, role, id_departement, date_embauche) VALUES
('Dupont', 'Jean', 'jean.dupont@entreprise.com', 'password123', 'employe', 1, '2020-01-01'),
('Martin', 'Sophie', 'sophie.martin@entreprise.com', 'password456', 'employe', 2, '2020-01-01'),
('Durand', 'Pierre', 'pierre.durand@entreprise.com', 'password789', 'employe', 3, '2020-01-01');

INSERT INTO types_conges (libelle, jours_annuels, deductible) VALUES
('Congé payé', 25, 1),
('Congé maladie', 10, 0),
('Congé maternité', 90, 0);