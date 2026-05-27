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


