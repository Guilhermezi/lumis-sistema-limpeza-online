-- Massa de demonstração para apresentação escolar da Lumis.
-- É seguro executar este arquivo mais de uma vez: as contas usam e-mails
-- exclusivos de demonstração e contratos/avaliações são inseridos apenas
-- quando ainda não existem.
-- Senha de todas as contas abaixo: LumisDemo@2026

START TRANSACTION;

INSERT INTO Cliente
    (nome, email, telefone, data_nascimento, senha, termos_aceitos_em)
VALUES
    ('Amanda Rocha', 'amanda.rocha@demo.lumis.test', '11981110001', '1993-04-12', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:00:00'),
    ('Bruno Nascimento', 'bruno.nascimento@demo.lumis.test', '11981110002', '1988-11-23', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:05:00'),
    ('Camila Duarte', 'camila.duarte@demo.lumis.test', '11981110003', '1996-02-08', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:10:00'),
    ('Diego Martins', 'diego.martins@demo.lumis.test', '11981110004', '1990-07-17', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:15:00'),
    ('Elisa Ferreira', 'elisa.ferreira@demo.lumis.test', '11981110005', '1985-09-30', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:20:00'),
    ('Felipe Almeida', 'felipe.almeida@demo.lumis.test', '11981110006', '1998-05-14', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:25:00'),
    ('Gabriela Costa', 'gabriela.costa@demo.lumis.test', '11981110007', '1992-12-03', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:30:00'),
    ('Helena Ribeiro', 'helena.ribeiro@demo.lumis.test', '11981110008', '1987-06-21', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '2026-09-01 10:35:00')
ON DUPLICATE KEY UPDATE
    nome = VALUES(nome), telefone = VALUES(telefone),
    data_nascimento = VALUES(data_nascimento), email_verificado = 1;

INSERT INTO Profissionais
    (nome, email, telefone, senha, data_nascimento, experiencia,
     valor_minimo, regiao_atuacao, verificado, termos_aceitos_em)
VALUES
    ('Mariana Oliveira', 'mariana.oliveira@demo.lumis.test', '11982220001', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1991-03-18', '6 anos', 120.00, 'Zona Leste e Centro de São Paulo', 1, '2026-09-01 11:00:00'),
    ('João Pedro Santos', 'joao.santos@demo.lumis.test', '11982220002', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1986-08-09', '9 anos', 150.00, 'Zona Sul de São Paulo', 1, '2026-09-01 11:05:00'),
    ('Luana Fernandes', 'luana.fernandes@demo.lumis.test', '11982220003', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1994-01-27', '5 anos', 100.00, 'Centro e Zona Oeste de São Paulo', 1, '2026-09-01 11:10:00'),
    ('Carlos Mendes', 'carlos.mendes@demo.lumis.test', '11982220004', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1983-10-05', '12 anos', 180.00, 'Grande São Paulo', 1, '2026-09-01 11:15:00'),
    ('Renata Lima', 'renata.lima@demo.lumis.test', '11982220005', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1989-05-16', '8 anos', 140.00, 'Zona Norte e Centro de São Paulo', 1, '2026-09-01 11:20:00'),
    ('Paulo Henrique Souza', 'paulo.souza@demo.lumis.test', '11982220006', '$2y$12$JXznKWGF4YyIJBYsd8HzXenyoSnD7icgpx6YWImB.NyD7KB9ZZg5K', '1992-12-11', '7 anos', 130.00, 'Zona Leste de São Paulo', 1, '2026-09-01 11:25:00')
ON DUPLICATE KEY UPDATE
    nome = VALUES(nome), telefone = VALUES(telefone),
    data_nascimento = VALUES(data_nascimento), experiencia = VALUES(experiencia),
    valor_minimo = VALUES(valor_minimo), regiao_atuacao = VALUES(regiao_atuacao),
    verificado = VALUES(verificado), email_verificado = 1;

INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua das Acácias', '120', 'Apto 31', 'Tatuapé', 'São Paulo', 'SP', '03310-000' FROM Cliente WHERE email = 'amanda.rocha@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Avenida Paulista', '900', 'Apto 84', 'Bela Vista', 'São Paulo', 'SP', '01310-100' FROM Cliente WHERE email = 'bruno.nascimento@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua Harmonia', '245', NULL, 'Vila Madalena', 'São Paulo', 'SP', '05435-000' FROM Cliente WHERE email = 'camila.duarte@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua Serra de Bragança', '450', 'Casa 2', 'Vila Gomes Cardim', 'São Paulo', 'SP', '03318-000' FROM Cliente WHERE email = 'diego.martins@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua Voluntários da Pátria', '1880', 'Apto 52', 'Santana', 'São Paulo', 'SP', '02010-400' FROM Cliente WHERE email = 'elisa.ferreira@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua Vergueiro', '1600', 'Apto 17', 'Vila Mariana', 'São Paulo', 'SP', '04102-000' FROM Cliente WHERE email = 'felipe.almeida@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Rua Clélia', '730', NULL, 'Lapa', 'São Paulo', 'SP', '05042-000' FROM Cliente WHERE email = 'gabriela.costa@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);
INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
SELECT id_cliente, 'Avenida Jabaquara', '2100', 'Apto 63', 'Saúde', 'São Paulo', 'SP', '04046-400' FROM Cliente WHERE email = 'helena.ribeiro@demo.lumis.test'
ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero), complemento = VALUES(complemento), bairro = VALUES(bairro), cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep);

-- Especialidades que alimentam os perfis profissionais.
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (1, 2, 5) WHERE p.email = 'mariana.oliveira@demo.lumis.test';
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (6, 7, 9) WHERE p.email = 'joao.santos@demo.lumis.test';
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (2, 10) WHERE p.email = 'luana.fernandes@demo.lumis.test';
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (8, 11) WHERE p.email = 'carlos.mendes@demo.lumis.test';
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (3, 4) WHERE p.email = 'renata.lima@demo.lumis.test';
INSERT IGNORE INTO Especialidade (id_profissional, id_servico)
SELECT p.id_profissional, s.id_servico FROM Profissionais p JOIN Servicos s ON s.id_servico IN (7, 12) WHERE p.email = 'paulo.souza@demo.lumis.test';

DROP TEMPORARY TABLE IF EXISTS DemoContratos;
CREATE TEMPORARY TABLE DemoContratos (
    cliente_email VARCHAR(100) NOT NULL,
    profissional_email VARCHAR(100) NOT NULL,
    servico_id INT NOT NULL,
    data_servico DATE NOT NULL,
    inicio TIME NOT NULL,
    fim TIME NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    nota INT NOT NULL,
    comentario TEXT NOT NULL
);

INSERT INTO DemoContratos VALUES
('amanda.rocha@demo.lumis.test', 'mariana.oliveira@demo.lumis.test', 1, '2026-10-09', '08:00:00', '12:00:00', 120.00, 5, 'Atendimento muito cuidadoso e pontual. Minha casa ficou ótima.'),
('bruno.nascimento@demo.lumis.test', 'joao.santos@demo.lumis.test', 6, '2026-10-07', '09:00:00', '13:00:00', 150.00, 4, 'O escritório ficou bem organizado e o atendimento foi muito profissional.'),
('camila.duarte@demo.lumis.test', 'luana.fernandes@demo.lumis.test', 10, '2026-10-05', '13:00:00', '16:00:00', 90.00, 5, 'O sofá ficou renovado. Gostei muito do cuidado durante todo o serviço.'),
('diego.martins@demo.lumis.test', 'carlos.mendes@demo.lumis.test', 11, '2026-10-02', '08:30:00', '11:30:00', 80.00, 5, 'Os vidros ficaram impecáveis e o serviço terminou dentro do horário combinado.'),
('elisa.ferreira@demo.lumis.test', 'renata.lima@demo.lumis.test', 3, '2026-09-29', '08:00:00', '14:00:00', 220.00, 5, 'A limpeza antes da mudança facilitou muito o nosso dia. Excelente resultado.'),
('felipe.almeida@demo.lumis.test', 'paulo.souza@demo.lumis.test', 12, '2026-09-26', '14:00:00', '17:00:00', 100.00, 4, 'O carro ficou muito bem limpo e o processo foi rápido.'),
('gabriela.costa@demo.lumis.test', 'mariana.oliveira@demo.lumis.test', 5, '2026-09-23', '09:00:00', '13:00:00', 140.00, 5, 'Ótima experiência. A profissional foi atenciosa e deixou tudo em ordem.'),
('helena.ribeiro@demo.lumis.test', 'joao.santos@demo.lumis.test', 9, '2026-09-20', '08:00:00', '12:00:00', 150.00, 5, 'Serviço muito bem executado no escritório. Pretendo contratar novamente.'),
('amanda.rocha@demo.lumis.test', 'luana.fernandes@demo.lumis.test', 2, '2026-09-17', '08:00:00', '14:00:00', 180.00, 4, 'A limpeza pesada resolveu os pontos que mais precisavam de atenção.'),
('bruno.nascimento@demo.lumis.test', 'carlos.mendes@demo.lumis.test', 8, '2026-09-14', '07:30:00', '15:30:00', 280.00, 5, 'O espaço ficou pronto para uso depois da obra. Trabalho excelente.'),
('camila.duarte@demo.lumis.test', 'renata.lima@demo.lumis.test', 4, '2026-09-11', '08:00:00', '15:00:00', 250.00, 5, 'A equipe teve bastante cuidado com os móveis e removeu toda a sujeira da reforma.'),
('diego.martins@demo.lumis.test', 'paulo.souza@demo.lumis.test', 7, '2026-09-08', '08:00:00', '14:00:00', 200.00, 4, 'Bom atendimento e resultado consistente na limpeza comercial.');

INSERT INTO Contratacao
    (status_contratacao, data_contratacao, hora_inicio_contratacao,
     hora_fim_contratacao, valor_total, id_cliente, id_profissional)
SELECT 'Concluído', d.data_servico, d.inicio, d.fim, d.valor,
       cl.id_cliente, p.id_profissional
FROM DemoContratos d
JOIN Cliente cl ON cl.email = d.cliente_email
JOIN Profissionais p ON p.email = d.profissional_email
WHERE NOT EXISTS (
    SELECT 1 FROM Contratacao c
    WHERE c.id_cliente = cl.id_cliente
      AND c.id_profissional = p.id_profissional
      AND c.data_contratacao = d.data_servico
);

INSERT INTO ItemContratacao
    (id_contratacao, id_servico, qtde_banheiros, qtde_quartos, qtde_horas, subtotal)
SELECT c.id_contratacao, d.servico_id, 1, 2,
       GREATEST(1, TIMESTAMPDIFF(HOUR, d.inicio, d.fim)), d.valor
FROM DemoContratos d
JOIN Cliente cl ON cl.email = d.cliente_email
JOIN Profissionais p ON p.email = d.profissional_email
JOIN Contratacao c ON c.id_cliente = cl.id_cliente
    AND c.id_profissional = p.id_profissional
    AND c.data_contratacao = d.data_servico
WHERE NOT EXISTS (
    SELECT 1 FROM ItemContratacao ic
    WHERE ic.id_contratacao = c.id_contratacao
      AND ic.id_servico = d.servico_id
);

INSERT INTO Avaliacao
    (nota_avaliacao, comentario_avaliacao, id_cliente, id_profissional, id_contratacao)
SELECT d.nota, d.comentario, cl.id_cliente, p.id_profissional, c.id_contratacao
FROM DemoContratos d
JOIN Cliente cl ON cl.email = d.cliente_email
JOIN Profissionais p ON p.email = d.profissional_email
JOIN Contratacao c ON c.id_cliente = cl.id_cliente
    AND c.id_profissional = p.id_profissional
    AND c.data_contratacao = d.data_servico
WHERE NOT EXISTS (
    SELECT 1 FROM Avaliacao av WHERE av.id_contratacao = c.id_contratacao
);

-- Mantém a média gravada em cada serviço coerente com as avaliações existentes.
UPDATE Servicos s
LEFT JOIN (
    SELECT ic.id_servico, ROUND(AVG(av.nota_avaliacao), 1) AS media
    FROM Avaliacao av
    JOIN ItemContratacao ic ON ic.id_contratacao = av.id_contratacao
    GROUP BY ic.id_servico
) notas ON notas.id_servico = s.id_servico
SET s.avaliacao_media = COALESCE(notas.media, 0);

DROP TEMPORARY TABLE DemoContratos;
COMMIT;
