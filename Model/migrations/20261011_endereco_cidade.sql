-- Armazena a cidade retornada pela consulta de CEP.
-- IF NOT EXISTS torna a migração segura para mais de uma execução.
ALTER TABLE Endereco
    ADD COLUMN IF NOT EXISTS cidade VARCHAR(60) DEFAULT NULL AFTER bairro;
