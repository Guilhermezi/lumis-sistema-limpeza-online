-- ============================================================
-- Lumis - Sistema de Limpeza Online
-- Script de criacao do banco de dados
-- Versao: 1.0
-- Compativel com: MariaDB 10.x / MySQL 8.x
-- para rodar: mysql -u root lumis < Model/lumis.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- REMOCAO DAS TABELAS (ordem inversa de dependencia)
-- ============================================================
DROP TABLE IF EXISTS ItemAdicional;
DROP TABLE IF EXISTS ItemContratacao;
DROP TABLE IF EXISTS Especialidade;
DROP TABLE IF EXISTS Favorito;
DROP TABLE IF EXISTS Disponibilidade;
DROP TABLE IF EXISTS Mensagem;
DROP TABLE IF EXISTS Avaliacao;
DROP TABLE IF EXISTS Pagamentos;
DROP TABLE IF EXISTS Agendamento;
DROP TABLE IF EXISTS Contratacao;
DROP TABLE IF EXISTS Adicional;
DROP TABLE IF EXISTS Servicos;
DROP TABLE IF EXISTS Profissionais;
DROP TABLE IF EXISTS Planos;
DROP TABLE IF EXISTS Endereco;
DROP TABLE IF EXISTS Cliente;

-- ============================================================
-- TABELAS BASE (sem dependencias externas)
-- ============================================================

CREATE TABLE Cliente (
    id_cliente       INT            NOT NULL AUTO_INCREMENT,
    nome             VARCHAR(100)   NOT NULL,
    email            VARCHAR(100)   NOT NULL,
    telefone         VARCHAR(15)    NOT NULL,
    data_nascimento  DATE           DEFAULT NULL,
    senha            VARCHAR(255)   NOT NULL,
    foto             VARCHAR(255)   DEFAULT NULL,
    PRIMARY KEY (id_cliente),
    UNIQUE KEY uk_cliente_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Planos (
    id_plano    INT           NOT NULL AUTO_INCREMENT,
    nome        VARCHAR(50)   NOT NULL,
    preco       DECIMAL(8,2)  NOT NULL,
    descricao   TEXT          DEFAULT NULL,
    duracao     VARCHAR(30)   NOT NULL,
    beneficios  TEXT          DEFAULT NULL,
    tipo        VARCHAR(30)   NOT NULL,
    PRIMARY KEY (id_plano)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Profissionais (
id_profissional      INT            NOT NULL AUTO_INCREMENT,
    nome                 VARCHAR(100)   NOT NULL,
    email                VARCHAR(100)   NOT NULL,
    telefone             VARCHAR(15)    NOT NULL,
    senha                VARCHAR(255)   NOT NULL,
    data_nascimento      DATE           DEFAULT NULL,
    experiencia          VARCHAR(50)    DEFAULT NULL,
    valor_minimo         DECIMAL(8,2)   DEFAULT NULL,
    regiao_atuacao       VARCHAR(100)   NOT NULL,
    verificado           TINYINT(1)     DEFAULT 0,
    foto                 VARCHAR(255)   DEFAULT NULL,
    PRIMARY KEY (id_profissional),
    UNIQUE KEY uk_profissional_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Servicos (
    id_servico          INT            NOT NULL AUTO_INCREMENT,
    nome_servico        VARCHAR(100)   NOT NULL,
    descricao_servico   TEXT           DEFAULT NULL,
    tipo                VARCHAR(50)    NOT NULL,
    preco               DECIMAL(8,2)   NOT NULL,
    avaliacao_media     DECIMAL(2,1)   DEFAULT 0.0,
    imagem              VARCHAR(255)   DEFAULT NULL,
    periodo             VARCHAR(30)    DEFAULT NULL,
    PRIMARY KEY (id_servico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Adicional (
    id_adicional      INT            NOT NULL AUTO_INCREMENT,
    nome_adicional    VARCHAR(80)    NOT NULL,
    preco_adicional   DECIMAL(8,2)   NOT NULL,
    PRIMARY KEY (id_adicional)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABELAS COM DEPENDENCIA EM TABELAS BASE
-- ============================================================

CREATE TABLE Endereco (
    id_endereco   INT           NOT NULL AUTO_INCREMENT,
    id_cliente    INT           NOT NULL,
    rua           VARCHAR(100)  NOT NULL,
    numero        VARCHAR(10)   NOT NULL,
    complemento   VARCHAR(50)   DEFAULT NULL,
    bairro        VARCHAR(50)   NOT NULL,
    estado        CHAR(2)       NOT NULL,
    cep           VARCHAR(9)    NOT NULL,
    PRIMARY KEY (id_endereco),
    UNIQUE KEY uk_endereco_cliente (id_cliente),
    CONSTRAINT fk_endereco_cliente
        FOREIGN KEY (id_cliente) REFERENCES Cliente (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Contratacao (
    id_contratacao          INT           NOT NULL AUTO_INCREMENT,
    status_contratacao      VARCHAR(30)   NOT NULL,
    data_contratacao        DATE          NOT NULL,
    hora_inicio_contratacao TIME          DEFAULT NULL,
    hora_fim_contratacao    TIME          DEFAULT NULL,
    valor_total             DECIMAL(10,2) NOT NULL,
    id_cliente              INT           NOT NULL,
    id_profissional         INT           NOT NULL,
    id_plano                INT           DEFAULT NULL,
    PRIMARY KEY (id_contratacao),
    CONSTRAINT fk_contratacao_cliente
        FOREIGN KEY (id_cliente) REFERENCES Cliente (id_cliente),
    CONSTRAINT fk_contratacao_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional),
    CONSTRAINT fk_contratacao_plano
        FOREIGN KEY (id_plano) REFERENCES Planos (id_plano)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ItemContratacao (
    id_item           INT           NOT NULL AUTO_INCREMENT,
    id_contratacao    INT           NOT NULL,
    id_servico        INT           NOT NULL,
    qtde_banheiros    INT           DEFAULT 1,
    qtde_quartos      INT           DEFAULT 1,
    qtde_horas        INT           DEFAULT 4,
    subtotal          DECIMAL(8,2)  NOT NULL,
    PRIMARY KEY (id_item),
    CONSTRAINT fk_item_contratacao_contratacao
        FOREIGN KEY (id_contratacao) REFERENCES Contratacao (id_contratacao),
    CONSTRAINT fk_item_contratacao_servico
        FOREIGN KEY (id_servico) REFERENCES Servicos (id_servico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Pagamentos (
    id_pagamento       INT           NOT NULL AUTO_INCREMENT,
    status_pagamento   VARCHAR(30)   NOT NULL,
    valor_pagamento    DECIMAL(10,2) NOT NULL,
    forma_pagamento    VARCHAR(30)   NOT NULL,
    data_pagamento     DATETIME      DEFAULT NULL,
    id_contratacao     INT           NOT NULL,
    PRIMARY KEY (id_pagamento),
    UNIQUE KEY uk_pagamento_contratacao (id_contratacao),
    CONSTRAINT fk_pagamento_contratacao
        FOREIGN KEY (id_contratacao) REFERENCES Contratacao (id_contratacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Agendamento (
    id_agendamento       INT          NOT NULL AUTO_INCREMENT,
    data_agenda          DATE         NOT NULL,
    hora_inicio_agenda   TIME         NOT NULL,
    hora_fim_agenda      TIME         NOT NULL,
    status_agenda        VARCHAR(30)  NOT NULL,
    id_contratacao       INT          NOT NULL,
    id_profissional      INT          NOT NULL,
    PRIMARY KEY (id_agendamento),
    CONSTRAINT fk_agendamento_contratacao
        FOREIGN KEY (id_contratacao) REFERENCES Contratacao (id_contratacao),
    CONSTRAINT fk_agendamento_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Avaliacao (
    id_avaliacao              INT    NOT NULL AUTO_INCREMENT,
    nota_avaliacao            INT    NOT NULL,
    comentario_avaliacao      TEXT   DEFAULT NULL,
    id_cliente                INT    NOT NULL,
    id_profissional           INT    NOT NULL,
    id_contratacao            INT    NOT NULL,
    PRIMARY KEY (id_avaliacao),
    CHECK (nota_avaliacao BETWEEN 1 AND 5),
    CONSTRAINT fk_avaliacao_cliente
        FOREIGN KEY (id_cliente) REFERENCES Cliente (id_cliente),
    CONSTRAINT fk_avaliacao_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional),
    CONSTRAINT fk_avaliacao_contratacao
        FOREIGN KEY (id_contratacao) REFERENCES Contratacao (id_contratacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Favorito (
    id_cliente       INT  NOT NULL,
    id_profissional  INT  NOT NULL,
    id_servico       INT  DEFAULT NULL,
    data             DATE NOT NULL,
    PRIMARY KEY (id_cliente, id_profissional),
    CONSTRAINT fk_favorito_cliente
        FOREIGN KEY (id_cliente) REFERENCES Cliente (id_cliente),
    CONSTRAINT fk_favorito_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional),
    CONSTRAINT fk_favorito_servico
        FOREIGN KEY (id_servico) REFERENCES Servicos (id_servico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Mensagem (
    id_mensagem    INT          NOT NULL AUTO_INCREMENT,
    nome_msg       VARCHAR(100) NOT NULL,
    telefone_msg   VARCHAR(15)  DEFAULT NULL,
    email_msg      VARCHAR(100) NOT NULL,
    mensagem       TEXT         NOT NULL,
    PRIMARY KEY (id_mensagem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Disponibilidade (
    id_disponibilidade  INT          NOT NULL AUTO_INCREMENT,
    dia_semana          VARCHAR(15)  NOT NULL,
    hora_inicio_disp    TIME         NOT NULL,
    hora_fim_disp       TIME         NOT NULL,
    id_profissional     INT          NOT NULL,
    PRIMARY KEY (id_disponibilidade),
    CONSTRAINT fk_disponibilidade_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABELAS ASSOCIATIVAS (N:N)
-- ============================================================

CREATE TABLE Especialidade (
    id_profissional  INT NOT NULL,
    id_servico       INT NOT NULL,
    PRIMARY KEY (id_profissional, id_servico),
    CONSTRAINT fk_especialidade_profissional
        FOREIGN KEY (id_profissional) REFERENCES Profissionais (id_profissional),
    CONSTRAINT fk_especialidade_servico
        FOREIGN KEY (id_servico) REFERENCES Servicos (id_servico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ItemAdicional (
    id_item        INT NOT NULL,
    id_adicional   INT NOT NULL,
    PRIMARY KEY (id_item, id_adicional),
    CONSTRAINT fk_item_adicional_item
        FOREIGN KEY (id_item) REFERENCES ItemContratacao (id_item),
    CONSTRAINT fk_item_adicional_adicional
        FOREIGN KEY (id_adicional) REFERENCES Adicional (id_adicional)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED: PLANOS (valores do Plano.html)
-- ============================================================

INSERT INTO Planos (nome, preco, descricao, duracao, beneficios, tipo) VALUES
('Basico',
 99.90,
 'Perfeito para quem busca praticidade com economia.',
 'mensal',
 '2 limpezas/mes (ate 4 comodos); 1 servico especializado por trimestre; Atendimento em ate 3 dias uteis; Dicas mensais de organizacao e sustentabilidade',
 'mensal'),

('Regular',
 179.90,
 'Nosso plano mais escolhido, ideal para familias e pequenos negocios.',
 'mensal',
 '3 limpezas/mes (ate 4 comodos); 2 servicos especializados por trimestre; Atendimento em ate 2 dias uteis; Dicas mensais de organizacao e sustentabilidade; Relatorios simples de impacto ambiental',
 'mensal'),

('Premium',
 399.90,
 'Maximo de comodidade e resultados profissionais.',
 'semanal',
 'Limpeza semanal (residencial ou pequena empresa); Todos os servicos especializados liberados (ate 4/mes); Atendimento em ate 24h; Equipe fixa ou exclusiva para empresas',
 'semanal'),

('Personalizado',
 0.00,
 'Flexivel, do seu jeito. Monte o plano perfeito.',
 'sob consulta',
 'Voce escolhe quanto quer pagar, a frequencia e o tamanho da limpeza. Um profissional analisa sua proposta e, se for viavel, o servico e aprovado.',
 'personalizado');

-- ============================================================
-- SEED: SERVICOS (cards da pagina Servicos.html + precos do carrinho.js)
-- ============================================================

INSERT INTO Servicos (nome_servico, descricao_servico, tipo, preco, avaliacao_media, imagem, periodo) VALUES
('Limpeza Padrao Residencial',
 'Limpeza completa de casa ou apartamento com produtos ecologicos e profissionais treinados.',
 'residencial',
 120.00,
 4.9,
 'IMG/Limpeza_padrao.png',
 '4 horas'),

('Limpeza Pesada Residencial',
 'Limpeza profunda para situacoes que exigem dedicacao extra e produtos especializados.',
 'residencial',
 180.00,
 4.8,
 'IMG/Limpeza_pesada.png',
 '4 horas'),

('Limpeza Pre-Mudanca Residencial',
 'Preparacao completa do imovel para nova ocupacao, deixando tudo pronto para receber.',
 'residencial',
 220.00,
 4.8,
 'IMG/Casa_Limpa.png',
 '4 horas'),

('Limpeza Pos-Reforma Residencial',
 'Remocao de residuos de construcao, poeira fina e manchas de tinta com equipamentos profissionais.',
 'residencial',
 250.00,
 4.7,
 'IMG/Limpeza_localizada.png',
 '4 horas'),

('Limpeza de Rotina Residencial',
 'Servico recorrente para manter sua casa sempre limpa e organizada com qualidade garantida.',
 'residencial',
 140.00,
 4.7,
 'IMG/Home_cidade.png',
 '4 horas'),

('Limpeza Padrao Comercial',
 'Limpeza diaria ou semanal de ambientes corporativos com foco em produtividade.',
 'comercial',
 150.00,
 4.9,
 'IMG/pessoas_mesa.png',
 '4 horas'),

('Limpeza Pesada Comercial',
 'Limpeza profunda para escritorios e lojas com alta demanda de higienizacao.',
 'comercial',
 200.00,
 4.8,
 'IMG/pessoas_mesa.png',
 '4 horas'),

('Limpeza Pos-Obra Comercial',
 'Limpeza completa de ambientes comerciais apos reformas e construcao.',
 'comercial',
 280.00,
 4.7,
 'IMG/Limpeza_localizada.png',
 '4 horas'),

('Limpeza de Escritorios',
 'Manutencao diaria ou semanal de ambientes corporativos com foco em produtividade.',
 'comercial',
 150.00,
 4.9,
 'IMG/pessoas_mesa.png',
 '4 horas'),

('Limpeza de Estofados',
 'Higienizacao profunda de sofas, cadeiras e tapetes com aspiracao e sanificacao.',
 'estofados',
 90.00,
 4.6,
 'IMG/Limpeza_rapida.png',
 '2 horas'),

('Limpeza de Vidros',
 'Limpeza de janelas, fachadas de vidro e superficies envidracadas com acabamento impecavel.',
 'vidros',
 80.00,
 4.5,
 'IMG/Trabalho_Limpo.png',
 '2 horas'),

('Limpeza de Carros',
 'Limpeza interna e externa de veiculos com produtos especificos para cada tipo de material.',
 'carros',
 100.00,
 4.7,
 'IMG/carros.png',
 '2 horas');

-- ============================================================
-- SEED: ADICIONAIS (itens extras do carrinho.js)
-- ============================================================

INSERT INTO Adicional (nome_adicional, preco_adicional) VALUES
('Geladeira',         25.00),
('Janelas',           20.00),
('Cozinha',           30.00),
('Estofado',          35.00),
('Area Externa',      40.00),
('Vestuario',         25.00),
('Lavar Roupas',      45.00),
('Fachadas',          60.00),
('Banheiros',         35.00),
('Ar-Condicionado',   50.00),
('Pisos',             55.00),
('Areas Externas',    45.00),
('Cozinhas',          40.00),
('Eventos',           70.00);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FIM DO SCRIPT
-- ============================================================
