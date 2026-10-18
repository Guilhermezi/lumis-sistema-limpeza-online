-- Contas existentes continuam ativas; novos cadastros são marcados como pendentes pela aplicação.
ALTER TABLE Cliente
    ADD COLUMN IF NOT EXISTS email_verificado TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE Profissionais
    ADD COLUMN IF NOT EXISTS email_verificado TINYINT(1) NOT NULL DEFAULT 1;

CREATE TABLE IF NOT EXISTS VerificacaoEmail (
    id_verificacao BIGINT      NOT NULL AUTO_INCREMENT,
    tipo           VARCHAR(20) NOT NULL,
    usuario_id     INT         NOT NULL,
    token_hash     CHAR(64)    NOT NULL,
    expira_em      DATETIME    NOT NULL,
    usado_em       DATETIME    DEFAULT NULL,
    criado_em      DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_verificacao),
    UNIQUE KEY uk_verificacao_email_token (token_hash),
    KEY idx_verificacao_email_usuario (tipo, usuario_id, expira_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
