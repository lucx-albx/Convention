DROP DATABASE IF EXISTS Convention;

CREATE DATABASE IF NOT EXISTS Convention;

USE Convention;

CREATE TABLE IF NOT EXISTS Azienda(
    RagioneSocialeAzienda VARCHAR(30) NOT NULL,
    IndirizzoAzienda VARCHAR(50) NOT NULL,
    TelefonoAzienda VARCHAR(15),
    PRIMARY KEY(RagioneSocialeAzienda)
);

CREATE TABLE IF NOT EXISTS Utenti(
    IdUtente INT AUTO_INCREMENT NOT NULL,
    Nome VARCHAR(30) NOT NULL,
    Cognome VARCHAR(30) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password CHAR(64) NOT NULL,
    Token CHAR(64) NOT NULL,
    Ruolo VARCHAR(15) NOT NULL,
    PRIMARY KEY(IdUtente)
);

CREATE TABLE IF NOT EXISTS Relatore(
    IDRel INT AUTO_INCREMENT NOT NULL,
    Ek_RagioneSocialeAzienda VARCHAR(30) NOT NULL,
    Ek_IdUtente INT NOT NULL,
    PRIMARY KEY(IDRel),
    FOREIGN KEY (Ek_RagioneSocialeAzienda) REFERENCES Azienda(RagioneSocialeAzienda) ON DELETE CASCADE,
    FOREIGN KEY (Ek_IdUtente) REFERENCES Utenti(IdUtente) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Speech(
    IDSpeech INT AUTO_INCREMENT NOT NULL,
    Titolo VARCHAR(50) NOT NULL,
    Argomento VARCHAR(100) NOT NULL,
    PRIMARY KEY(IDSpeech)
);

CREATE TABLE IF NOT EXISTS Piano(
    Numero INT NOT NULL,
    PRIMARY KEY(Numero)
);

CREATE TABLE IF NOT EXISTS Sala(
    NomeSala VARCHAR(50) NOT NULL,
    Ek_Numero INT NOT NULL,
    NpostiSala INT NOT NULL,
    PRIMARY KEY(NomeSala),
    FOREIGN KEY (Ek_Numero) REFERENCES Piano(Numero)
);

CREATE TABLE IF NOT EXISTS Programma(
    IDProg INT AUTO_INCREMENT NOT NULL,
    Ek_IDSpeech INT NOT NULL,
    Ek_NomeSala VARCHAR(50) NOT NULL,
    FasciaOraria TIMESTAMP NOT NULL,
    PRIMARY KEY(IDProg),
    FOREIGN KEY (Ek_IDSpeech) REFERENCES Speech(IDSpeech),
    FOREIGN KEY (Ek_NomeSala) REFERENCES Sala(NomeSala)
);

CREATE TABLE IF NOT EXISTS Relaziona(
    Ek_IDProg INT NOT NULL,
    EK_IDRel INT NOT NULL,
    FOREIGN KEY (Ek_IDProg) REFERENCES Programma(IDProg),
    FOREIGN KEY (EK_IDRel) REFERENCES Relatore(IDRel)
);

CREATE TABLE IF NOT EXISTS Partecipante(
    IDPart INT AUTO_INCREMENT NOT NULL,
    EK_IdUtente INT NOT NULL,
    TipologiaPart VARCHAR(30) NOT NULL,
    PRIMARY KEY(IDPart),
    FOREIGN KEY(EK_IdUtente) REFERENCES Utenti(IdUtente) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Sceglie(
    Ek_IDProg INT NOT NULL,
    EK_IDPart INT NOT NULL,
    FOREIGN KEY (Ek_IDProg) REFERENCES Programma(IDProg),
    FOREIGN KEY (EK_IDPart) REFERENCES Partecipante(IDPart)
);