# Lumis — Sistema de Limpeza Online

Aplicação web escolar para consulta e contratação de serviços de limpeza. Clientes podem explorar o catálogo, agendar serviços e editar o perfil; profissionais têm perfil próprio. O projeto usa PHP e MySQL/MariaDB, então precisa de um servidor PHP e não funciona como aplicação dinâmica no GitHub Pages.

## Tecnologias

- PHP 8+ e PDO
- MySQL ou MariaDB
- HTML, CSS e JavaScript
- DBeaver (opcional) para administrar o banco

## Executar localmente

1. Instale PHP com a extensão PDO MySQL e inicie o MySQL/MariaDB.
2. No DBeaver, crie um banco chamado `lumis` com charset `utf8mb4`.
3. Para uma instalação nova, importe `Model/lumis.sql` no banco vazio. **Esse arquivo apaga e recria as tabelas; não o execute em um banco que contenha dados que deseja manter.**
4. Configure as variáveis de ambiente do PHP para apontar ao banco. Exemplo em Bash — substitua usuário e senha pelos da sua instalação:

   ```bash
   export DB_HOST=127.0.0.1
   export DB_NAME=lumis
   export DB_USER=seu_usuario
   export DB_PASSWORD=sua_senha
   export APP_ENV=development
   export APP_URL=http://localhost:8000
   php -S 127.0.0.1:8000
   ```

5. Acesse <http://localhost:8000>.

O projeto lê as variáveis do ambiente do PHP; o arquivo `.env.example` é apenas um modelo e não é carregado automaticamente. Para conexão local por socket, configure também `DB_SOCKET` conforme o caminho usado pelo seu sistema.

No modo `development`, o link de confirmação de e-mail pode ser exibido na tela para facilitar os testes locais. Em produção, configure o envio de e-mails do servidor e use HTTPS. A consulta automática de CEP usa o ViaCEP, e os ícones Remix Icon são carregados por CDN; esses recursos precisam de conexão com a internet.

## Banco de dados

Em um banco já existente, aplique somente as migrações que ainda não foram incorporadas, em ordem. Elas estão em `Model/migrations/`:

1. `20261007_auth_hardening.sql` — endurecimento de autenticação e recuperação de senha.
2. `20261011_endereco_cidade.sql` — campo de cidade no endereço.
3. `20261012_verificacao_email.sql` — confirmação de e-mail.
4. `20261012_assinaturas_plano.sql` — solicitações de planos com pagamento simulado.

Antes de aplicar uma migração antiga, confira se as colunas e tabelas correspondentes já existem. Em especial, `20261007_auth_hardening.sql` não deve ser repetida se as colunas já tiverem sido criadas. As duas migrações de 12/10/2026 já foram aplicadas no banco local `lumis` desta máquina; isso não altera outros bancos nem substitui a necessidade de aplicá-las nos ambientes dos demais desenvolvedores.

Para carregar dados fictícios de apresentação escolar, use `Model/seeds/demo_escolar.sql` depois de criar o esquema. As contas desse arquivo são demonstrações, não usuários reais; a senha compartilhada de demonstração está indicada no próprio arquivo. Não use esses dados em produção.

## Funcionalidades

- Cadastro e login de clientes e profissionais, recuperação de senha e confirmação de e-mail.
- Catálogo de serviços, filtros, ordenação, avaliações e dados consultados do banco.
- Agendamento com consulta de endereço pelo CEP e registro de opção de pagamento.
- Perfis com agendamentos designados, confirmação/conclusão do serviço, avaliação e favoritos.
- Edição de dados e foto do perfil, além de consulta da solicitação de plano e seu estado de pagamento.
- Interface disponível em português, inglês e espanhol em páginas que possuem traduções cadastradas.

Pagamentos de serviços e planos são **simulados**: o sistema registra a escolha e o estado pendente, mas não processa cobranças bancárias.

## Estrutura principal

```text
Controller/       JavaScript da interface e endpoints da API
Model/            conexão, consultas, esquema, migrações e dados de demonstração
View/pages/       páginas PHP da aplicação
View/partials/    componentes compartilhados
View/css/         estilos
View/uploads/     fotos de perfil enviadas pelos usuários
index.php         entrada da aplicação; redireciona para a página inicial
```

Não apague `View/uploads/`: a aplicação grava fotos de perfil em `View/uploads/avatars/`, e registros do banco podem apontar para esses arquivos. Para limpar imagens antigas, primeiro faça backup e confirme que nenhum perfil ainda as referencia. O arquivo `.gitkeep` mantém a pasta no repositório.

## Verificações

O repositório ainda não possui uma suíte de testes automatizados. Após alterações, valide manualmente cadastro/confirmação, login, edição de perfil, catálogo, agendamento, fluxo de planos e visualização em celular e computador.

## Licença

Consulte [`LICENSES/LICENSE`](LICENSES/LICENSE).
