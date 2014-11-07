SET foreign_key_checks = 0;

CREATE TABLE produtos (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome varchar(100),
    descricao text,
    preco decimal(15,2),
    imagem text,
    categoria_id int,
    FOREIGN KEY(categoria_id) REFERENCES categoria(id)) ENGINE=INNODB;

CREATE TABLE pedidos (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    dataP date,
    status int,
    clientes_id int,
    FOREIGN KEY(clientes_id) REFERENCES clientes(id)) ENGINE=INNODB;

CREATE TABLE clientes (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome varchar(100),
    sobrenome varchar(100),
    cep varchar(100),
    logradouro varchar(100),
    bairro varchar(100),
    cidade varchar(100),
    email varchar(100),
    dd varchar(100),
    telefone varchar(100)) ENGINE=INNODB;

CREATE TABLE produtos_pedidos (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    qtd int,
    pedidos_id int,
    produtos_id int,
    FOREIGN KEY(pedidos_id) REFERENCES pedidos(id),
    FOREIGN KEY(produtos_id) REFERENCES produtos(id)) ENGINE=INNODB;

CREATE TABLE categoria (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome varchar(100),
    descricao text) ENGINE=INNODB;

