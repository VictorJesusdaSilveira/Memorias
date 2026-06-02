CREATE DATABASE db_relacionamento;
USE db_relacionamento;

CREATE TABLE memorias (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(500) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    -- V = Vida, F = Filme, A = Anime, J = Jogo
    tipo CHAR(1) NOT NULL,
    -- T = Toda Hora, M = Muito, F = Frequentemente, A = As Vezes, D = Dificilmente, R = Raramente, N = Nunca
    frequencia CHAR(1) NOT NULL,
    dataMemoria DATE NOT NULL,
    CONSTRAINT pk_memorias PRIMARY KEY (id)
);

INSERT INTO memorias (nome, descricao, imagem, tipo, frequencia, dataMemoria) 
VALUES
('Giovana', 'Giovana com a língua para fora', 'assets/images/6a1f1afb2592b_giovana1.jpg', 'V', 'T', '2026-05-25'),
('Kroshik', 'Nossa foca de pelúcia Kroshik', 'assets/images/6a1f1b240af84_KroshikPelucia.jpg', 'V', 'T', '2026-05-12'), 
('Garota do Século 20', 'Um dos nossos filmes favoritos', 'assets/images/6a1f1b528d1c8_garotaDoSeculo20.jpg', 'F', 'M', '2026-04-19'),
('Grogu (Baby Yoda)', 'Viciada no baby yoda', 'assets/images/6a1f1c3ab17af_grogu.jpg', 'F', 'F', '2026-05-17'),
('Karate', 'Nós no Karate', 'assets/images/6a1f1c9098d03_karate.jpg', 'V', 'M', '2026-03-12'),
('I and you are polar opposities', 'Um dos nossos desenhos favoritos', 'assets/images/6a1f1ccbc3eb4_polar.webp', 'A', 'M', '2026-03-22'),
('CS2', 'Eu te carregando no CS', 'assets/images/6a1f17f888e38_CS.png', 'J', 'T', '2026-04-30'),
('Digital Circus', 'Viciada em Digital Circus', 'assets/images/6a1f1d3501405_digitalCircus.jpg', 'A', 'A', '2026-03-30');
