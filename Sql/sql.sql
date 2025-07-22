CREATE DATABASE acme;

USE acme;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

CREATE TABLE produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cor VARCHAR(50),
    imagem TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    data_cadastro DATE DEFAULT CURRENT_DATE,
    peso DECIMAL(5, 2),
    categoria_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria);
);

CREATE TABLE variacoes_produto (
    id_variacoes_produto INT AUTO_INCREMENT PRIMARY KEY,
    tamanho VARCHAR(20) NOT NULL,
    estoque INT DEFAULT 0,
    produto_id INT NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produtos(id_produto)
);

CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    data_nascimento DATE NOT NULL
);


CREATE TABLE endereco (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,
    logradouro VARCHAR(200),
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100),
    numero VARCHAR(10),
    cep VARCHAR(10),
    complemento VARCHAR(150),
    cliente_id INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES cliente(id_cliente)
);

CREATE TABLE pedidos (
    id_pedidos INT AUTO_INCREMENT PRIMARY KEY,
    valor_total DECIMAL(10, 2) NOT NULL,
    valor_frete DECIMAL(10, 2) DEFAULT 10.00 NOT NULL,
    desconto DECIMAL(10, 2) DEFAULT 0.00,
    forma_pagamento ENUM('PIX', 'BOLETO', 'CARTAO') NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    endereco_id INT NOT NULL,
    cliente_id INT NOT NULL,
    FOREIGN KEY (endereco_id) REFERENCES endereco(id_endereco),
    FOREIGN KEY (cliente_id) REFERENCES cliente(id_cliente)
);

CREATE TABLE itens_pedido (
    id_itens_pedido INT AUTO_INCREMENT PRIMARY KEY,
    preco_venda DECIMAL(10, 2) NOT NULL,
    quantidade INT NOT NULL,
    pedido_id INT NOT NULL,
    variacao_id INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id_pedidos),
    FOREIGN KEY (variacao_id) REFERENCES variacoes_produto(id_variacoes_produto)
);

--DADOS PREVIAMENTE CADASTRADOS--

INSERT INTO categorias (nome, descricao) VALUES
('Eletrônicos', 'Produtos eletrônicos em geral'),
('Roupas', 'Vestuário para todas as idades'),
('Móveis', 'Mobília para casa e escritório'),
('Esportes', 'Artigos esportivos e equipamentos');


INSERT INTO produtos (nome, cor, imagem, preco, descricao, peso, categoria_id) VALUES
('Smartphone X', 'Preto', 'smartphone.jpg', 2999.90, 'Última geração com câmera tripla', 0.18, 1),
('Camiseta Básica', 'Branca', 'camiseta.jpg', 49.90, '100% algodão', 0.15, 2),
('Sofá 3 Lugares', 'Cinza', 'sofa.jpg', 1899.00, 'Espuma de alta densidade', 35.50, 3),
('Bola de Futebol', 'Branca/Preta', 'bola.jpg', 89.90, 'Tamanho oficial', 0.43, 4);


INSERT INTO variacoes_produto (tamanho, estoque, produto_id) VALUES
('128GB', 50, 1),
('256GB', 30, 1),
('P', 100, 2),
('M', 80, 2),
('G', 60, 2),
('Único', 15, 3),
('5', 40, 4);


INSERT INTO cliente (nome_completo, cpf, data_nascimento) VALUES
('João Silva', '12345678901', '1990-05-15'),
('Maria Santos', '98765432109', '1985-08-22'),
('Carlos Oliveira', '45678912304', '1995-03-10');


INSERT INTO endereco (logradouro, cidade, bairro, numero, cep, complemento, cliente_id) VALUES
('Rua das Flores, 123', 'São Paulo', 'Centro', '123', '01001000', 'Apto 101', 1),
('Avenida Brasil, 456', 'Rio de Janeiro', 'Copacabana', '456', '22021000', NULL, 2),
('Rua das Palmeiras, 789', 'Belo Horizonte', 'Savassi', '789', '30130000', 'Casa 2', 3);


INSERT INTO pedidos (valor_total, valor_frete, desconto, forma_pagamento, endereco_id, cliente_id) VALUES
(3049.80, 15.00, 50.00, 'CARTAO', 1, 1),
(139.80, 10.00, 0.00, 'PIX', 2, 2),
(1988.90, 25.00, 0.00, 'BOLETO', 3, 3);


INSERT INTO itens_pedido (preco_venda, quantidade, pedido_id, variacao_id) VALUES
(2999.90, 1, 1, 1),
(49.90, 2, 2, 3),
(1899.00, 1, 3, 6),
(89.90, 1, 3, 7);

