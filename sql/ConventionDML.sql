USE Convention;

INSERT INTO Azienda 
VALUES 
('RagioneSociale1', 'Via Roma', '3334987231'),
('RagioneSociale3', 'Via Settembre', '3336087271'),
('RagioneSociale2', 'Via Piazza', '3335980221'),
('RagioneSociale5', 'Via Milano', '3338996031'),
('RagioneSociale4', 'Via Torino', '3332585291'),
('RagioneSociale7', 'Via Incudini', '3338989201'),
('RagioneSociale6', 'Via Salerno', '34972102367'),
('RagioneSociale8', 'Via Lazio', '3486907535'),
('RagioneSociale9', 'Via Giugno', '3330587630'),
('RagioneSociale10', 'Via Novembre', '3335985038');

INSERT INTO Utenti (Email, Nome, Cognome, Password, Token, Ruolo)
VALUES 
('Mario.Rossi@gmail.com', 'Mario', 'Rossi', 'eb03ff65fec74f4c7d717ded4b74b760e85059c2ab1674b9b2ba2e937c95a8e8', '', 'Relatore'),
('Marco.Neri@gmail.com', 'Marco', 'Neri', '992838be7f2ee4c49323bff354bd593527a187b1ef3b40d8fbf0fbc954b78604', '', 'Relatore'),
('Simone.Gialli@gmail.com', 'Simone', 'Gialli', 'a131da241a8b017a548fe16f3780d7ee122e5549fd8cc209ba43e48ae0bb8947', '', 'Relatore'),
('Francesco.Verdi@gmail.com', 'Francesco', 'Verdi', '26f82857b5ae0aa8073b5b26434e775bf871acb897f7d0d98a7f6ae433a425b1', '', 'Relatore'),
('Matteo.Bianchi@gmail.com', 'Matteo', 'Bianchi', 'd59c1fac9a6a6019e0bea333cd02dcb6fac770e966b22b77add8733289031044', '', 'Relatore'),
('Mattia.Marroni@gmail.com', 'Mattia', 'Marroni', '599e928a6e1901f90e678823cc09d5570e627f3a12fd7258726d95af990c2a88', '', 'Relatore'),
('Stefano.Arancioni@gmail.com', 'Stefano', 'Arancioni', '855e5e48f817c852c8b335e7d9314ced8b1b2522bd345504580d98a96151fef1', '', 'Relatore'),
('Federico.Viola@gmail.com', 'Federico', 'Viola', 'c87af85da8011a7d7925daebe0d01ab116ad900266f8addac6b7101efa334240', '', 'Relatore'),
('Luca.Grigio@gmail.com', 'Luca', 'Grigio', '70f99bfa3ed520e550be9f0152512e9668ed2be44f8c50ab3a4f8325e8718e62', '', 'Relatore'),
('Lorenzo.Fucsia@gmail.com', 'Lorenzo', 'Fucsia', '144869b378a11b4e53d77a3997abd720c3086d519d3e71262116dcde332668dc', '', 'Relatore'),
('Mario.Mattoni@gmail.com', 'Mario', 'Mattoni', '17161d0d971c5fdb131528539a47e1161051d2474d6342034d603e5f99c7a2a0', '', 'Partecipante'),
('Maria.Rossi@gmail.com', 'Maria', 'Rossi', '23a71a14f11c9ca64542e186320c05240af71ec5471a72e689821ed259f3745f', '', 'Partecipante'),
('Stefano.Martelli@gmail.com', 'Stefano', 'Martelli', '73b2c73a7da423f9431deed2bd173a77789d6c535d6a4b2fb99b8e1ae1061863', '', 'Partecipante'),
('Anna.Gialli@gmail.com', 'Anna', 'Gialli', 'e9608c78ec540efdd044e8182a27f3c8a308607f0570f4d6a57376e1400c4b01', '', 'Partecipante'),
('Stefania.Muri@gmail.com', 'Stefania', 'Muri', '0d935d7f6698d7fd1f24a54e8053267ec3cbe6771b2d3dab42891318940db164', '', 'Partecipante'),
('Luca.Pala@gmail.com', 'Luca', 'Pala', '9268f69f70e25694ea72bed2ba6fe24e6541ec3c2b04842b041824039f131f38', '', 'Partecipante'),
('Giacomo.Secchiello@gmail.com', 'Giacomo', 'Secchiello', 'b1bd4ebc0006513d483a01d4ae75cff00cc7c34f7460455dc04edb15f487093e', '', 'Partecipante'),
('Letizia.Neri@gmail.com', 'Letizia', 'Neri', 'd5eaa771734e0b30ef805c5f3b2b0e8c5a63188582f3df172852006c1c8e1f11', '', 'Partecipante'),
('Sofia.Cementi@gmail.com', 'Sofia', 'Cementi', 'c609b09dc8620f6fc51817c0aea92630e10f75404ec2d3647f5efc71f9e6c3b0', '', 'Partecipante'),
('Giovanni.Verdi@gmail.com', 'Giovanni', 'Verdi', '235d3153800a9f0a7777475521fec9bcc22e087fec60a42946a2b5d2df053b46', '', 'Partecipante'),
('Marco.Carrelli@gmail.com', 'Marco', 'Carrelli', 'f04f89025d8a98e7598fcd185b645942884ca82256d7ffbacf59cef77083e818', '', 'Admin');

INSERT INTO Relatore (Ek_RagioneSocialeAzienda, Ek_IdUtente)
VALUES 
('RagioneSociale1', 1),
('RagioneSociale3', 2),
('RagioneSociale2', 3),
('RagioneSociale5', 4),
('RagioneSociale4', 5),
('RagioneSociale7', 6),
('RagioneSociale6', 7),
('RagioneSociale8', 8),
('RagioneSociale9', 9),
('RagioneSociale10', 10);

INSERT INTO Speech (Titolo, Argomento)
VALUES 
('Speech001', 'Del testo per lo speech1.'),
('Speech003', 'Del testo per lo speech3.'),
('Speech004', 'Del testo per lo speech4.'),
('Speech005', 'Del testo per lo speech5.'),
('Speech008', 'Del testo per lo speech8.'),
('Speech007', 'Del testo per lo speech7.'),
('Speech009', 'Del testo per lo speech9.'),
('Speech010', 'Del testo per lo speech10.'),
('Speech002', 'Del testo per lo speech2.'),
('Speech006', 'Del testo per lo speech6.');

INSERT INTO Piano
VALUES 
(1),
(3),
(2),
(5),
(4),
(7),
(6),
(8),
(9),
(10);

INSERT INTO Sala 
VALUES 
('Sala001', 1, 20),
('Sala003', 3, 20),
('Sala002', 2, 19),
('Sala005', 5, 20),
('Sala004', 4, 20),
('Sala007', 7, 20),
('Sala006', 6, 20),
('Sala008', 8, 20),
('Sala009', 9, 20),
('Sala010', 10, 20);

INSERT INTO Programma (Ek_IDSpeech, Ek_NomeSala, FasciaOraria)
VALUES 
(1, 'Sala001', '2024-04-21 12:30'),
(2, 'Sala003', '2024-02-13 18:30'),
(3, 'Sala002', '2024-07-03 12:00'),
(4, 'Sala005', '2024-03-01 13:45'),
(5, 'Sala004', '2024-05-09 10:50'),
(6, 'Sala007', '2024-08-27 20:00'),
(7, 'Sala006', '2024-09-26 21:30'),
(8, 'Sala008', '2024-010-05 11:55'),
(9, 'Sala009', '2024-012-07 15:20'),
(10, 'Sala010', '2024-11-10 17:15');

INSERT INTO Relaziona 
VALUES 
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

INSERT INTO Partecipante (EK_IdUtente, TipologiaPart)
VALUES 
(1, 'Relatore'),
(2, 'Relatore'),
(3, 'Relatore'),
(4, 'Relatore'),
(5, 'Relatore'),
(6, 'Relatore'),
(7, 'Relatore'),
(8, 'Relatore'),
(9, 'Relatore'),
(10, 'Relatore'),
(11, 'Partecipante'),
(12, 'Partecipante'),
(13, 'Partecipante'),
(14, 'Partecipante'),
(15, 'Partecipante'),
(16, 'Partecipante'),
(17, 'Partecipante'),
(18, 'Partecipante'),
(19, 'Partecipante'),
(20, 'Partecipante');

INSERT INTO Sceglie 
VALUES 
(1, 11),
(2, 12),
(3, 13),
(4, 14),
(5, 15),
(6, 16),
(7, 17),
(8, 18),
(9, 19),
(10, 20);