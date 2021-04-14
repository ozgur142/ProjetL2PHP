CREATE TABLE IF NOT EXISTS Utilisateur
(
	idUtilisateur INTEGER NOT NULL PRIMARY KEY,
	nom VARCHAR(25) NOT NULL,
	prenom VARCHAR(25) NOT NULL,
	email VARCHAR(200) NOT NULL,
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
	nomEquipe VARCHAR(25) NOT NULL,
	niveau INTEGER DEFAULT 0,
	adresse VARCHAR(50) NOT NULL,
	numTel VARCHAR(15) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS EquipeTournoi
(
	idEquipe INTEGER NOT NULL,
	idTournoi INTEGER NOT NULL,
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

INSERT INTO Utilisateur VALUES(0, "DUJARDIN", "Jean", "JeanDujardin@test.com", "4ff17bc8ee5f240c792b8a41bfa2c58af726d83b925cf696af0c811627714c85", "Utilisateur"); -- Mot de passe : jean

INSERT INTO Gestionnaire VALUES(0);

INSERT INTO Tournoi VALUES(0, "Tournoi Tampon", "2021-04-08", "30", 0, "Montpellier");
INSERT INTO Tournoi VALUES(1, "Championnat du vin", "2021-04-08", "30", 0, "Montpellier");
INSERT INTO Tournoi VALUES(2, "Tournoi des saucissons", "2021-04-08", "30", 0, "Montpellier");
INSERT INTO Tournoi VALUES(3, "Compétition de la vodka", "02021-04-08", "30", 0, "Montpellier");

INSERT INTO Utilisateur VALUES(1, "Machin", "Truc", "M@T.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(2, "Jean", "Dupont", "J@D.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(3, "Henri", "Guibet", "H@G.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(4, "Louis", "De Funès", "L@F.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(5, "Jean", "Gabin", "J@G.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(6, "Robert", "Redford", "R@R.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(7, "Lino", "Ventura", "L@V.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(8, "Francis", "Blanche", "F@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(9, "Venantino", "Venantini", "V@V.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(10, "Jean", "Lefevre", "J@L.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(11, "Bernard", "Blier", "B@B.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur"),
(12, "Line", "Renaud", "M@T.com", "74913f96f46a13995ef292f85deffae7b86a35d5d3180a5581b04b12b7b30245", "Utilisateur");
-- Mot de passes des comptes 1 à 12 : unMotDePasse

INSERT INTO Equipe VALUES(0, "Équipe 1", 0, "Une adresse 1", "04-06-04-06-04"),
(1, "Équipe 2", 0, "Une adresse 2", "04-06-04-06-04"),
(2, "Équipe 3", 0, "Une adresse 3", "04-06-04-06-04"),
(3, "Équipe 4", 0, "Une adresse 4", "04-06-04-06-04"),
(4, "Équipe 5", 0, "Une adresse 5", "04-06-04-06-04");

INSERT INTO Joueur VALUES(1, 0, true),
(2, 0, false),
(3, 0, false),
(4, 1, true),
(5, 1, false),
(6, 1, false),
(7, 3, true),
(8, 3, false),
(9, 3, false),
(10, 4, true),
(11, 4, false),
(12, 4, false);
