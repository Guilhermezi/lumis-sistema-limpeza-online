CREATE TABLE IF NOT EXISTS AssinaturaPlano (
    id_assinatura       BIGINT        NOT NULL AUTO_INCREMENT,
    id_cliente          INT           NOT NULL,
    id_plano            INT           NOT NULL,
    status_assinatura   VARCHAR(30)   NOT NULL DEFAULT 'Aguardando pagamento',
    valor_assinatura    DECIMAL(10,2) NOT NULL,
    forma_pagamento     VARCHAR(30)   NOT NULL,
    status_pagamento    VARCHAR(30)   NOT NULL DEFAULT 'Pendente',
    criada_em           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_assinatura),
    KEY idx_assinatura_plano_cliente (id_cliente, criada_em),
    CONSTRAINT fk_assinatura_plano_cliente
        FOREIGN KEY (id_cliente) REFERENCES Cliente (id_cliente),
    CONSTRAINT fk_assinatura_plano_plano
        FOREIGN KEY (id_plano) REFERENCES Planos (id_plano)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
