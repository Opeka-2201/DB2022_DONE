-- © Group25 - Bases de Données 2022 : Projet 2
-- Script nécessaire à la génération des tables en MySQL

DROP DATABASE IF EXISTS group25;

CREATE DATABASE group25;
USE group25;

CREATE TABLE Fonction(
   NOM          VARCHAR(64) NOT NULL PRIMARY KEY
  ,TAUX_HORAIRE INTEGER UNSIGNED NOT NULL
) ENGINE = InnoDB;
INSERT INTO Fonction(NOM,TAUX_HORAIRE) VALUES ('Assistant chercheur',75);
INSERT INTO Fonction(NOM,TAUX_HORAIRE) VALUES ('Chercheur',100);
INSERT INTO Fonction(NOM,TAUX_HORAIRE) VALUES ('Developpeur',60);

CREATE TABLE Departement(
   NOM    VARCHAR(64) NOT NULL PRIMARY KEY
  ,BUDGET INTEGER UNSIGNED 
) ENGINE = InnoDB;
INSERT INTO Departement(NOM,BUDGET) VALUES ('INF',50000);
INSERT INTO Departement(NOM,BUDGET) VALUES ('MATH',NULL);
INSERT INTO Departement(NOM,BUDGET) VALUES ('PHYS',120000);

CREATE TABLE Employe(
   NO              INTEGER UNSIGNED NOT NULL PRIMARY KEY 
  ,NOM             VARCHAR(64) NOT NULL
  ,NOM_DEPARTEMENT VARCHAR(64)
  ,NOM_FONCTION    VARCHAR(64)
  ,FOREIGN KEY (NOM_DEPARTEMENT) REFERENCES Departement(NOM)
  ,FOREIGN KEY (NOM_FONCTION) REFERENCES Fonction(NOM)
) ENGINE = InnoDB;
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (7777,'DEBRUYNE','INF','Chercheur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8105,'JANSSENS','MATH','Developpeur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8221,'SERNEELS','PHYS','Chercheur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8467,'VRANCKEN','INF','Developpeur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8823,'JANSSENS','PHYS','Chercheur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8829,'FLERACKER','PHYS','Assistant chercheur');
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8845,'THIENPONDT',NULL,NULL);
INSERT INTO Employe(NO,NOM,NOM_DEPARTEMENT,NOM_FONCTION) VALUES (8888,'MERCURY','INF','Developpeur');

CREATE TABLE Projet(
   NOM         VARCHAR(64) NOT NULL PRIMARY KEY
  ,DEPARTEMENT VARCHAR(64) NOT NULL
  ,DATE_DEBUT  DATE NOT NULL
  ,CHEF        INTEGER UNSIGNED NOT NULL
  ,BUDGET      INTEGER UNSIGNED
  ,COUT        INTEGER UNSIGNED
  ,DATE_FIN    DATE 
  ,FOREIGN KEY (DEPARTEMENT) REFERENCES Departement(NOM)
  ,FOREIGN KEY (CHEF) REFERENCES Employe(NO)
) ENGINE = InnoDB;
INSERT INTO Projet(NOM,DEPARTEMENT,DATE_DEBUT,CHEF,BUDGET,COUT,DATE_FIN) VALUES ('DIDACT','INF','2022-01-01',8467,3000,3100,'2022-02-28');
INSERT INTO Projet(NOM,DEPARTEMENT,DATE_DEBUT,CHEF,BUDGET,COUT,DATE_FIN) VALUES ('INF-SYST','INF','2021-09-01',8467,5500,5000,'2021-12-31');
INSERT INTO Projet(NOM,DEPARTEMENT,DATE_DEBUT,CHEF,BUDGET,COUT,DATE_FIN) VALUES ('MED-STAT','MATH','2022-01-01',8105,2000,1000,'2022-02-28');
INSERT INTO Projet(NOM,DEPARTEMENT,DATE_DEBUT,CHEF,BUDGET,COUT,DATE_FIN) VALUES ('STOCH','PHYS','2022-03-01',8105,4500,NULL,NULL);

CREATE TABLE Rapport(
  EMPLOYE INTEGER UNSIGNED NOT NULL 
  ,PROJET  VARCHAR(64) NOT NULL
  ,TITRE   VARCHAR(64) NOT NULL PRIMARY KEY
  ,FOREIGN KEY (EMPLOYE) REFERENCES Employe(NO)
  ,FOREIGN KEY (PROJET) REFERENCES Projet(NOM)
) ENGINE = InnoDB;
INSERT INTO Rapport(EMPLOYE,PROJET,TITRE) VALUES (8221,'MED-STAT','MED-STAT Experimental Results');
INSERT INTO Rapport(EMPLOYE,PROJET,TITRE) VALUES (8467,'MED-STAT','MED-STAT Development Report');
INSERT INTO Rapport(EMPLOYE,PROJET,TITRE) VALUES (8467,'STOCH','STOCH: Implementation Report');

CREATE TABLE Evaluation(
  PROJET        VARCHAR(64) NOT NULL PRIMARY KEY
  ,EXPERT       INTEGER UNSIGNED NOT NULL
  ,COMMENTAIRES VARCHAR(2048)
  ,AVIS         VARCHAR(64)
  ,FOREIGN KEY (PROJET) REFERENCES Projet(NOM)
  ,FOREIGN KEY (EXPERT) REFERENCES Employe(NO)
) ENGINE = InnoDB;
INSERT INTO Evaluation(PROJET,EXPERT,COMMENTAIRES,AVIS) VALUES ('DIDACT',7777,'Duis efficitur urna erat, vel convallis mauris aliquet non. Praesent aliquam leo vitae lorem mattis, nec egestas tellus convallis. Aenean vestibulum condimentum auctor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam congue leo elit, in cursus tortor scelerisque quis. Quisque dapibus nulla tellus, ac eleifend massa auctor non. Duis a rhoncus orci. Sed at fringilla arcu, vitae molestie purus. Nulla lacinia ante a urna tincidunt feugiat. Nullam risus tortor, mollis vel justo sit amet, dictum fermentum libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin fringilla, nunc tempor sollicitudin suscipit, quam ante maximus magna, eleifend elementum nunc odio quis massa.','SUCCES');
INSERT INTO Evaluation(PROJET,EXPERT,COMMENTAIRES,AVIS) VALUES ('INF-SYST',8221,'Vestibulum eget leo et est rhoncus egestas. Ut ut justo a turpis maximus imperdiet. Curabitur pretium porttitor urna. Mauris iaculis sem felis, sed dignissim felis lacinia nec. Cras pharetra turpis dolor, nec volutpat purus fermentum a. Vivamus ut viverra sapien. Ut vestibulum eros quis commodo finibus. Proin quis purus justo. Suspendisse in ex ac velit facilisis euismod. Sed id magna ultricies, elementum dui vel, sodales purus.','SUCCES');
INSERT INTO Evaluation(PROJET,EXPERT,COMMENTAIRES,AVIS) VALUES ('MED-STAT',7777,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quam ligula, lobortis id dignissim a, suscipit in lorem. Donec aliquam dui non ex sollicitudin, a scelerisque enim tincidunt. Cras mollis lacus eget libero rhoncus, non viverra nunc semper. Phasellus interdum dapibus ligula sed rhoncus. Donec velit libero, convallis at elit eu, varius mollis massa. Aenean aliquet maximus quam id rutrum. Ut auctor congue nisl, vel facilisis mauris fermentum sed. Proin cursus suscipit enim, id lobortis elit iaculis at. Aenean ligula erat, sodales at urna ac, volutpat sollicitudin massa. Maecenas vel quam id lorem elementum fermentum. Nam faucibus dolor elit, semper rutrum leo molestie vel. Curabitur at lectus vitae risus finibus tristique ornare eget nisl.','ECHEC');

CREATE TABLE Mots_Cles(
  RAPPORT VARCHAR(64) NOT NULL
  ,MOT_CLE VARCHAR(64) NOT NULL
  ,FOREIGN KEY (RAPPORT) REFERENCES Rapport(TITRE)
  ,PRIMARY KEY (RAPPORT,MOT_CLE)
) ENGINE = InnoDB;
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('MED-STAT Development Report','C++');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('MED-STAT Development Report','Matlab');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('MED-STAT Experimental Results','Excel');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('MED-STAT Experimental Results','Jupyter');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('MED-STAT Experimental Results','Science');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('STOCH: Implementation Report','Implementation');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('STOCH: Implementation Report','Java');
INSERT INTO Mots_Cles(RAPPORT,MOT_CLE) VALUES ('STOCH: Implementation Report','Scheme');

CREATE TABLE Tache(
  EMPLOYE INTEGER UNSIGNED NOT NULL
  ,PROJET  VARCHAR(64) NOT NULL
  ,NB_HEURES INTEGER UNSIGNED
  ,FOREIGN KEY (EMPLOYE) REFERENCES Employe(NO)
  ,FOREIGN KEY (PROJET) REFERENCES Projet(NOM)
  ,PRIMARY KEY(EMPLOYE,PROJET)
) ENGINE = InnoDB;
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8105,'INF-SYST','25');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8221,'DIDACT','7');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8221,'MED-STAT','1');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8467,'MED-STAT','15');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8467,'STOCH','10');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8823,'INF-SYST','20');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8829,'DIDACT','32');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8829,'INF-SYST','20');
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8829,'STOCH',NULL);
INSERT INTO Tache(EMPLOYE,PROJET,NB_HEURES) VALUES (8888,'STOCH','3');