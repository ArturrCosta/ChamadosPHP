CREATE DATABASE IF NOT EXISTS setor_ti
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE setor_ti;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    documento VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('funcionario', 'ti') NOT NULL DEFAULT 'funcionario',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_usuarios_documento (documento),
    KEY idx_usuarios_tipo_ativo (tipo, ativo)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    prioridade_padrao ENUM('baixa', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
    UNIQUE KEY uk_categorias_nome (nome)
) ENGINE=InnoDB;

INSERT INTO categorias (nome, prioridade_padrao) VALUES
    ('Computador', 'alta'),
    ('Internet', 'alta'),
    ('Impressora', 'media'),
    ('Software', 'baixa'),
    ('Rede', 'alta'),
    ('Acesso / Senha', 'media'),
    ('Outro', 'media')
ON DUPLICATE KEY UPDATE prioridade_padrao = VALUES(prioridade_padrao);

CREATE TABLE IF NOT EXISTS chamados (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    responsavel_id INT UNSIGNED NULL,
    categoria_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    local VARCHAR(150) NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') NOT NULL,
    status ENUM('aberto', 'em_andamento', 'resolvido', 'cancelado') NOT NULL DEFAULT 'aberto',
    observacao_ti TEXT NULL,
    data_abertura TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_fechamento DATETIME NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_chamados_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT fk_chamados_responsavel FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_chamados_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    KEY idx_chamados_usuario (usuario_id),
    KEY idx_chamados_responsavel (responsavel_id),
    KEY idx_chamados_categoria (categoria_id),
    KEY idx_chamados_status_prioridade (status, prioridade),
    KEY idx_chamados_abertura (data_abertura)
) ENGINE=InnoDB;
