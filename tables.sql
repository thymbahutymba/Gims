BEGIN;
CREATE DATABASE IF NOT EXISTS Gims;
COMMIT;

USE Gims;

CREATE TABLE IF NOT EXISTS Persona(
  ID_Persona integer PRIMARY KEY AUTO_INCREMENT,
  Nome varchar(20) NOT NULL,
  Cognome varchar(20) NOT NULL,
  DataNascita date NOT NULL,
  Email varchar(20) NOT NULL,
  Password varchar(64) NOT NULL,
  Sesso char NOT NULL CHECK(Sesso in('F', 'M'))
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Palestra(
  ID_Palestra integer PRIMARY KEY AUTO_INCREMENT,
  Nome varchar(25) NOT NULL,
  OrarioApertura time NOT NULL,
  OrarioChiusura time NOT NULL,
  Email varchar(30) NOT NULL,
  Telefono varchar(12) NOT NULL,
  Citta varchar(20) NOT NULL,
  Descrizione text DEFAULT NULL,
  Slogan varchar(80) DEFAULT NULL
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS Corso(
  ID_Corso integer PRIMARY KEY AUTO_INCREMENT,
  Nome varchar(20) NOT NULL,
  LimiteMassimo integer NOT NULL,
  QuotaIscrizione float NOT NULL,
  Descrizione text DEFAULT NULL,
  ID_Palestra integer NOT NULL,
  ID_PersonalTrainer integer NOT NULL,
  FOREIGN KEY (ID_Palestra) REFERENCES Palestra(ID_Palestra) ON DELETE CASCADE,
  FOREIGN KEY (ID_PersonalTrainer) REFERENCES Persona(ID_Persona)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Riferimento(
  ID integer PRIMARY KEY AUTO_INCREMENT,
  ID_Palestra integer NOT NULL,
  ID_Corso integer NOT NULL,
  FOREIGN KEY (ID_Palestra) REFERENCES Palestra(ID_Palestra),
  FOREIGN KEY (ID_Corso) REFERENCES Corso(ID_Corso)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Partecipazione(
  ID integer PRIMARY KEY AUTO_INCREMENT,
  ID_Persona integer NOT NULL,
  ID_Corso integer NOT NULL,
  FOREIGN KEY (ID_Persona) REFERENCES Persona(ID_Persona) ON DELETE CASCADE,
  FOREIGN KEY (ID_Corso) REFERENCES Corso(ID_Corso) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Orario(
  ID_Orario integer PRIMARY KEY AUTO_INCREMENT,
  Giorno varchar(10) NOT NULL,
  OraInizio time NOT NULL,
  OraFine time not NULL,
  ID_Corso integer NOT NULL,
  FOREIGN KEY (ID_Corso) REFERENCES Corso(ID_Corso) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Dispone(
  ID integer PRIMARY KEY AUTO_INCREMENT,
  ID_Palestra integer NOT NULL,
  ID_Persona integer NOT NULL,
  Qualifica int(1) NOT NULL CHECK(Qualifica<4 and Qualifica>=0),
  Valutazione int(1) DEFAULT NULL,
  FOREIGN KEY (ID_Palestra) REFERENCES Palestra(ID_Palestra) ON DELETE CASCADE,
  FOREIGN KEY (ID_Persona) REFERENCES Persona(ID_Persona) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Messaggio(
  ID integer PRIMARY KEY AUTO_INCREMENT,
  ID_Persona integer NOT NULL,
  ID_Palestra integer NOT NULL,
  Testo text NOT NULL,
  Letto boolean NOT NULL DEFAULT false,
  Mittente integer NOT NULL CHECK(Mittente in(0,1)), -- 0 per palestra, 1 per persona
  FOREIGN KEY (ID_Palestra) REFERENCES Palestra(ID_Palestra),
  FOREIGN KEY (ID_Persona) REFERENCES Persona(ID_Persona) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
