CREATE TABLE IF NOT EXISTS Utilisateur
(
	idUtilisateur INTEGER NOT NULL PRIMARY KEY,
	nom VARCHAR(25) NOT NULL,
	prenom VARCHAR(25) NOT NULL,
	email VARCHAR(200) NOT NULL UNIQUE,
	motDePasse VARCHAR(64) NOT NULL, -- Un VARCHAR de longueur 64 pour contenir un mot de passe hashé avec l'algorithme SHA-256.
	role ENUM('Utilisateur', 'Administrateur') NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Gestionnaire
(
	idGestionnaire INTEGER NOT NULL PRIMARY KEY,
	CONSTRAINT FK_Gestionnaire_Utilisateur FOREIGN KEY (idGestionnaire) REFERENCES Utilisateur(idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Tournoi
(
	idTournoi INTEGER NOT NULL PRIMARY KEY,
	nom VARCHAR(25) NOT NULL,
	dateDeb DATE NOT NULL,
	duree INTEGER NOT NULL,
	idGestionnaire INTEGER NOT NULL,
	lieu VARCHAR(25) NOT NULL,
	nombreTotalEquipes INTEGER NOT NULL,
	CONSTRAINT FK_Tournoi_Gestionnaire FOREIGN KEY (idGestionnaire) REFERENCES Gestionnaire(idGestionnaire) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Type
(
	idType INTEGER NOT NULL PRIMARY KEY,
	idTournoi INTEGER NOT NULL,
	typeTournoi ENUM('Concours', 'Compétition'),
	CONSTRAINT FK_Type_Tournoi FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Equipe
(
	idEquipe INTEGER NOT NULL PRIMARY KEY,
	nomEquipe VARCHAR(25) NOT NULL UNIQUE,
	niveau INTEGER DEFAULT 0,
	adresse VARCHAR(50) NOT NULL UNIQUE,
	numTel VARCHAR(15) NOT NULL UNIQUE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS EquipeTournoi
(
	idEquipe INTEGER NOT NULL,
	idTournoi INTEGER NOT NULL,
	estInscrite BOOLEAN DEFAULT FALSE,
	CONSTRAINT PK_EquipeTournoi PRIMARY KEY (idEquipe, idTournoi),
	CONSTRAINT FK_EquipeTournoi_Equipe FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_EquipeTournoi_Tournoi FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Joueur
(
	idJoueur INTEGER NOT NULL PRIMARY KEY,
	idEquipe INTEGER NOT NULL,
	estCapitaine BOOLEAN NOT NULL,
	CONSTRAINT FK_Joueur_Utilisateur FOREIGN KEY (idJoueur) REFERENCES Utilisateur(idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_Joueur_Equipe FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS MatchT
(
	idMatchT INTEGER NOT NULL PRIMARY KEY,
	idTournoi INTEGER NOT NULL,
	date DATE NOT NULL,
	horaire TIME NOT NULL,
	CONSTRAINT FK_MatchT_Tournoi FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS EquipeMatchT
(
	idEquipe INTEGER NOT NULL,
	idMatchT INTEGER NOT NULL,
	score INTEGER NOT NULL,
	CONSTRAINT PK_EquipeMatchT PRIMARY KEY (idEquipe, idMatchT),
	CONSTRAINT FK_EquipeMatchT_Equipe FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_EquipeMatchT_MatchT FOREIGN KEY (idMatchT) REFERENCES MatchT(idMatchT) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS Preinscription
(
	idEquipe INTEGER NOT NULL,
	idTournoi INTEGER NOT NULL,
	CONSTRAINT PK_Preinscription PRIMARY KEY (idEquipe, idTournoi),
	CONSTRAINT FK_Preinscription_Equipe FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_Preinscription_Tournoi FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARSET=utf8;

INSERT INTO Utilisateur VALUES(1, "ADMIN", "Admin", "admin@test.com", "8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918", "Administrateur"), -- Mot de passe : admin
(2, "DUJARDIN", "Jean", "JeanDujardin@test.com", "4ff17bc8ee5f240c792b8a41bfa2c58af726d83b925cf696af0c811627714c85", "Utilisateur"); -- Mot de passe : jean

INSERT INTO Gestionnaire VALUES(2);

INSERT INTO Tournoi VALUES(1, "OPEN TOUR", "2021-05-01", "30", 2, "Montpellier", 8);
INSERT INTO Tournoi VALUES(2, "Coupe du Monde", "2022-01-01", "30", 2, "Neuilly", 10);
INSERT INTO Tournoi VALUES(3, "Tournoi International", "2021-04-25", "45", 2, "Paris", 64);
INSERT INTO Tournoi VALUES(4, "Compétition de la vodka", "2021-04-08", "30", 2, "Nice", 4);

INSERT INTO Utilisateur VALUES(3, "Machin", "Truc", "M@T.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(4, "Jean", "Dupont", "J@D.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(5, "Henri", "Guibet", "H@G.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(6, "Louis", "De Funès", "L@F.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(7, "Jean", "Gabin", "J@G.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(8, "Robert", "Redford", "R@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(9, "Lino", "Ventura", "L@V.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(10, "Francis", "Blanche", "F@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(11, "Venantino", "Venantini", "V@V.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(12, "Jean", "Lefevre", "J@L.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(13, "Bernard", "Blier", "B@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(14, "Line", "Renaud", "L@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(15, "Jean-Pierre", "Marielle", "JP@M.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(16, "Jean", "Rochefort", "J@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(17, "Jean-Pierre", "Belmondo", "JP@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(18, "Philippe", "Noiret", "P@N.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(19, "Claude", "Rich", "C@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(20, "Guy", "Bedos", "G@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(21, "Claude", "Brasseur", "C@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(22, "Pierre", "Richard", "P@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(23, "Mireille", "Darc", "M@D.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur");
-- Mot de passes des comptes 1 à 12 : unMotDePasse

INSERT INTO Equipe VALUES(1, "PSG", 0, "Une adresse 1", "01-02-01-02-01"),
(2, "BORDEAUX", 0, "Une adresse 2", "02-03-02-03-02"),
(3, "MARSEILLE", 0, "Une adresse 3", "03-04-03-04-03"),
(4, "LYON", 0, "Une adresse 4", "04-05-04-05-04"),
(5, "ST-ETIENNE", 0, "Une adresse 5", "05-06-05-06-05");

INSERT INTO Joueur VALUES(2, 1, true),
(3, 1, false),
(4, 1, false),
(5, 2, true),
(6, 2, false),
(7, 2, false),
(8, 3, true),
(9, 3, false),
(10, 3, false),
(11, 4, true),
(12, 4, false),
(13, 4, false);
