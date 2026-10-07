-- Execute uma vez em bancos Lumis já existentes.
ALTER TABLE Cliente ADD COLUMN termos_aceitos_em DATETIME DEFAULT NULL;
ALTER TABLE Profissionais ADD COLUMN termos_aceitos_em DATETIME DEFAULT NULL;
ALTER TABLE Cliente ADD COLUMN auth_versao INT NOT NULL DEFAULT 1;
ALTER TABLE Profissionais ADD COLUMN auth_versao INT NOT NULL DEFAULT 1;

CREATE TABLE IF NOT EXISTS TentativaLogin (
    id_tentativa   BIGINT       NOT NULL AUTO_INCREMENT,
    identificador CHAR(64)     NOT NULL,
    ip             VARCHAR(45)  NOT NULL,
    sucesso        TINYINT(1)   NOT NULL DEFAULT 0,
    criada_em      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_tentativa),
    KEY idx_tentativa_conta_data (identificador, criada_em),
    KEY idx_tentativa_ip_data (ip, criada_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS RecuperacaoSenha (
    id_recuperacao BIGINT       NOT NULL AUTO_INCREMENT,
    tipo           VARCHAR(20)  NOT NULL,
    usuario_id     INT          NOT NULL,
    token_hash     CHAR(64)     NOT NULL,
    expira_em      DATETIME     NOT NULL,
    usado_em       DATETIME     DEFAULT NULL,
    criado_em      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_recuperacao),
    UNIQUE KEY uk_recuperacao_token (token_hash),
    KEY idx_recuperacao_usuario (tipo, usuario_id, expira_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
