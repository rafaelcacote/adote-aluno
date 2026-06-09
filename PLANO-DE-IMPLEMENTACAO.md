# Adote um Estudante — Plano de Implementação

> PWA responsivo para conectar doadores a estudantes que precisam de ajuda financeira com mensalidades escolares/universitárias.  
> **Regra central:** o app **nunca** usa PIX pessoal — somente PIX da instituição onde o aluno está matriculado.

**Versão:** 1.1 — alinhado com decisões de 09/06/2026

---

## 0. Decisões alinhadas

| # | Pergunta | Decisão |
|---|----------|---------|
| 1 | Envio de comprovante | **Formulário dentro do app** (upload de imagem/PDF) |
| 2 | Administradores | **Somente 1** (você) |
| 3 | Cadastro de fotos | **Você cadastra** por enquanto (futuro: responsáveis) |
| 4 | Filtros na home | **Somente busca por nome** do aluno |
| 5 | Mensalidades | **Sempre 12 meses** (janeiro a dezembro) |
| 6 | Hospedagem | **Hostinger compartilhada** → banco **MySQL**, backend **PHP** |

---

## 1. Resumo do que o sistema faz

### Visão geral

Uma aplicação web (PWA) otimizada para celular, onde:

1. Visitantes veem uma lista de alunos com foto, dados básicos e progresso das 12 mensalidades do ano.
2. Podem **buscar aluno pelo nome** na home.
3. Ao escolher um aluno, abrem a tela de doação com o PIX da **instituição**.
4. Após pagar, o doador **envia o comprovante pelo formulário do app** (sem login).
5. Você (admin) vê os comprovantes pendentes, confere e lança a mensalidade como paga.
6. O gráfico de progresso do aluno atualiza para todos os visitantes.

### O que o app **não** faz

- Não processa pagamentos (sem gateway, sem checkout automático).
- Não exibe nomes de doadores publicamente.
- Não usa PIX de pessoa física.
- Não valida comprovantes automaticamente (validação manual pelo admin).

### Fluxo principal

```
Doador abre o app
    → busca ou navega na lista de alunos
    → clica em "Quero ajudar"
    → vê PIX da instituição + instruções
    → paga no app do banco (com nome do aluno na descrição)
    → envia comprovante pelo formulário do app
    → admin vê comprovante na fila de pendências
    → admin lança mensalidade como "pago"
    → gráfico do aluno atualiza para todos
```

---

## 2. Requisitos funcionais

### Público (doador)

| ID | Requisito |
|----|-----------|
| RF-01 | Ver lista de alunos ativos com foto, nome, idade, série/curso e instituição |
| RF-02 | **Buscar aluno pelo nome** (filtro em tempo real ou ao digitar) |
| RF-03 | Ver valor da mensalidade mensal de cada aluno |
| RF-04 | Ver gráfico/indicador das 12 mensalidades (jan–dez), pago/não pago |
| RF-05 | Clicar no card do aluno para abrir tela de doação |
| RF-06 | Na tela de doação: instituição, CNPJ, chave PIX, nome que aparece no PIX |
| RF-07 | Instrução: colocar o **nome do aluno** na descrição do PIX |
| RF-08 | Formulário de comprovante: upload (imagem ou PDF) + mês de referência opcional |
| RF-09 | Aviso: sem comprovante, o pagamento não tem validade para o colégio |
| RF-10 | App instalável como PWA no celular |
| RF-11 | Layout 100% responsivo (mobile-first) |

### Administrador (somente você)

| ID | Requisito |
|----|-----------|
| RF-12 | Login seguro com e-mail e senha |
| RF-13 | Cadastrar/editar/desativar instituições (nome, CNPJ, chave PIX, nome no PIX) |
| RF-14 | Cadastrar/editar/desativar alunos (foto, dados, instituição, valor mensal, ano letivo) |
| RF-15 | Ao cadastrar aluno, gerar automaticamente **12 mensalidades** do ano |
| RF-16 | Lançar mensalidade como paga (manualmente ou a partir de um comprovante) |
| RF-17 | Reverter/corrigir lançamento de mensalidade |
| RF-18 | Ver fila de **comprovantes pendentes** com preview do arquivo |
| RF-19 | Marcar comprovante como analisado (vinculando à mensalidade paga) |
| RF-20 | Suportar centenas de alunos sem degradação perceptível |

### Regras de negócio

| ID | Regra |
|----|-------|
| RN-01 | Todo aluno pertence a exatamente uma instituição |
| RN-02 | O PIX exibido é sempre o da instituição do aluno |
| RN-03 | O progresso só muda quando o admin lança manualmente |
| RN-04 | Aluno inativo não aparece na lista pública |
| RN-05 | Mensalidades são sempre 12 por ano letivo (jan=1 … dez=12) |
| RN-06 | Uma mensalidade só pode ser marcada como paga uma vez |
| RN-07 | Comprovante fica vinculado ao aluno; admin decide qual mês quitar |
| RN-08 | Upload aceita JPG, PNG, WEBP e PDF (tamanho máx. configurável, ex.: 5 MB) |

---

## 3. Modelagem de dados (MySQL)

### Diagrama entidade-relacionamento

```
┌─────────────────┐       ┌─────────────────┐
│  instituicoes   │       │     alunos      │
├─────────────────┤       ├─────────────────┤
│ id (PK)         │◄──────│ instituicao_id  │
│ nome            │   1:N │ id (PK)         │
│ cnpj            │       │ nome            │
│ chave_pix       │       │ foto            │
│ nome_pix        │       │ idade           │
│ ativo           │       │ tipo            │
│ created_at      │       │ serie_ou_curso  │
│ updated_at      │       │ valor_mensal    │
└─────────────────┘       │ ano_letivo      │
                          │ ativo           │
                          │ created_at      │
                          └────────┬────────┘
                                   │ 1:N
              ┌────────────────────┼────────────────────┐
              │                    │                    │
     ┌────────▼────────┐   ┌────────▼────────┐          │
     │  mensalidades   │   │  comprovantes   │          │
     ├─────────────────┤   ├─────────────────┤          │
     │ id (PK)         │   │ id (PK)         │          │
     │ aluno_id (FK)   │   │ aluno_id (FK)   │          │
     │ mes (1-12)      │   │ arquivo         │          │
     │ ano             │   │ mes_referencia  │          │
     │ valor           │   │ observacao      │          │
     │ status          │   │ status          │          │
     │ pago_em         │   │ mensalidade_id  │──nullable┤
     │ observacao      │   │ analisado_em    │          │
     │ created_at      │   │ created_at      │          │
     └─────────────────┘   └─────────────────┘          │
                                                         │
┌─────────────────┐       ┌─────────────────┐           │
│     users       │       │ configuracoes   │           │
├─────────────────┤       ├─────────────────┤           │
│ id (PK)         │       │ id (PK)         │           │
│ name            │       │ titulo          │           │
│ email           │       │ subtitulo       │           │
│ password        │       │ texto_instrucao │           │
│ created_at      │       │ aviso_legal     │           │
└─────────────────┘       │ max_upload_mb   │           │
                          └─────────────────┘           │
```

### Tabelas detalhadas

#### `instituicoes`

| Campo | Tipo MySQL | Observação |
|-------|------------|------------|
| `id` | BIGINT UNSIGNED PK AI | |
| `nome` | VARCHAR(200) | Ex.: "Instituto Adventista Paranaense" |
| `cnpj` | VARCHAR(18) | |
| `chave_pix` | VARCHAR(255) | |
| `nome_pix` | VARCHAR(200) | Nome exibido no PIX |
| `ativo` | TINYINT(1) | Default 1 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### `alunos`

| Campo | Tipo MySQL | Observação |
|-------|------------|------------|
| `id` | BIGINT UNSIGNED PK AI | |
| `instituicao_id` | BIGINT UNSIGNED FK | |
| `nome` | VARCHAR(200) | Index para busca |
| `foto` | VARCHAR(255) | Caminho em `storage/app/public/alunos/` |
| `idade` | TINYINT UNSIGNED | |
| `tipo` | ENUM('colegio','faculdade') | |
| `serie_ou_curso` | VARCHAR(100) | |
| `valor_mensal` | DECIMAL(10,2) | |
| `ano_letivo` | SMALLINT | Ex.: 2026 |
| `ativo` | TINYINT(1) | Default 1 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

> **Índice:** `INDEX idx_alunos_nome (nome)` e `INDEX idx_alunos_ativo (ativo)` para busca rápida.

#### `mensalidades`

| Campo | Tipo MySQL | Observação |
|-------|------------|------------|
| `id` | BIGINT UNSIGNED PK AI | |
| `aluno_id` | BIGINT UNSIGNED FK | |
| `mes` | TINYINT | 1–12 (fixo) |
| `ano` | SMALLINT | = `ano_letivo` do aluno |
| `valor` | DECIMAL(10,2) | Copiado no cadastro |
| `status` | ENUM('pendente','pago') | Default `pendente` |
| `pago_em` | TIMESTAMP NULL | |
| `observacao` | TEXT NULL | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

> **Constraint:** `UNIQUE (aluno_id, mes, ano)`

#### `comprovantes`

| Campo | Tipo MySQL | Observação |
|-------|------------|------------|
| `id` | BIGINT UNSIGNED PK AI | |
| `aluno_id` | BIGINT UNSIGNED FK | Aluno que recebeu a doação |
| `arquivo` | VARCHAR(255) | Caminho do upload |
| `mes_referencia` | TINYINT NULL | 1–12, opcional (doador indica o mês) |
| `observacao` | TEXT NULL | Mensagem opcional do doador |
| `status` | ENUM('pendente','analisado') | Default `pendente` |
| `mensalidade_id` | BIGINT UNSIGNED NULL FK | Preenchido ao quitar mensalidade |
| `analisado_em` | TIMESTAMP NULL | |
| `created_at` | TIMESTAMP | |

#### `users` (admin único — tabela padrão Laravel)

| Campo | Tipo | Observação |
|-------|------|------------|
| `id` | BIGINT PK AI | |
| `name` | VARCHAR(255) | Seu nome |
| `email` | VARCHAR(255) UNIQUE | |
| `password` | VARCHAR(255) | Hash bcrypt |
| `created_at` | TIMESTAMP | |

#### `configuracoes` (singleton — 1 registro)

| Campo | Tipo | Observação |
|-------|------|------------|
| `id` | BIGINT PK AI | |
| `titulo` | VARCHAR(100) | "Adote um Estudante" |
| `subtitulo` | TEXT | Texto da home |
| `texto_instrucao_pix` | TEXT | Instruções na tela de doação |
| `texto_form_comprovante` | TEXT | Instruções no formulário |
| `aviso_legal` | TEXT | Aviso sobre PIX institucional |
| `max_upload_mb` | TINYINT | Default 5 |

### Criação automática de mensalidades

Ao cadastrar um aluno, o sistema gera **sempre 12 registros** em `mensalidades` (meses 1–12) com status `pendente` e valor copiado de `valor_mensal`.

---

## 4. Stack tecnológica (Hostinger compartilhada)

### Por que não Next.js/Node neste cenário?

Hospedagem compartilhada na Hostinger roda **PHP + MySQL + Apache** de forma nativa. Node.js não fica disponível de forma confiável nesse plano. Por isso a stack foi ajustada para um monólito PHP que você sobe via FTP/Git ou Gerenciador de Arquivos.

| Camada | Tecnologia | Motivo |
|--------|------------|--------|
| Backend | **Laravel 11** | PHP nativo na Hostinger, MySQL, uploads, auth prontos |
| Frontend interativo | **Livewire 3** | UI reativa sem API separada; ótimo para admin e formulários |
| CSS | **Tailwind CSS 4** | Mobile-first, rápido de estilizar |
| PWA | **vite-plugin-pwa** | Manifest + service worker no build do Vite |
| Banco | **MySQL 8** | Incluso no cPanel da Hostinger |
| ORM | **Eloquent** (Laravel) | Migrations, seeders, relacionamentos |
| Auth | **Laravel Breeze** (sessão) | Simples para 1 admin |
| Uploads | **Storage local** (`storage/app/public`) | Fotos e comprovantes no próprio servidor |
| Deploy | **Hostinger** (`public_html` → pasta `public/` do Laravel) | Compatível com plano compartilhado |

### Estrutura de deploy na Hostinger

```
public_html/          ← aponta para laravel/public/
├── index.php
├── .htaccess
├── build/            ← assets compilados (Vite)
├── icons/            ← ícones PWA
└── storage/          ← symlink para uploads públicos

fora do public_html/
├── app/
├── database/
├── storage/app/
│   ├── public/alunos/       ← fotos
│   └── public/comprovantes/ ← comprovantes enviados
└── .env                     ← credenciais MySQL do cPanel
```

> Na Hostinger, o `.env` usa o host MySQL do cPanel (geralmente `localhost`), não `127.0.0.1`.

---

## 5. Arquitetura de telas

### Mapa de rotas

```
GET  /                          → Home (lista + busca por nome)
GET  /aluno/{id}                → Detalhe do aluno
GET  /aluno/{id}/doar           → Tela PIX + link para comprovante
GET  /aluno/{id}/comprovante    → Formulário de envio de comprovante
POST /aluno/{id}/comprovante    → Salva upload

GET  /admin/login               → Login
GET  /admin                     → Dashboard (resumo + comprovantes pendentes)
GET  /admin/instituicoes        → CRUD instituições
GET  /admin/alunos              → CRUD alunos (você cadastra fotos)
GET  /admin/alunos/{id}         → Detalhe + grid 12 mensalidades
GET  /admin/comprovantes        → Fila de comprovantes pendentes
GET  /admin/configuracoes       → Textos e limites de upload
```

### Wireframes atualizados

#### Home — com busca por nome

```
┌──────────────────────────────────────┐
│  ADOTE UM ESTUDANTE                  │
│  Ajude com a mensalidade. PIX da     │
│  instituição, nunca pessoal.         │
├──────────────────────────────────────┤
│  🔍 Buscar aluno pelo nome...        │
├──────────────────────────────────────┤
│ ┌──────┐  João Paulo da Silva        │
│ │ FOTO │  18 anos · Faculdade        │
│ │      │  FAP — Contabilidade        │
│ └──────┘  Mensalidade: R$ 1.200,00   │
│           ████████░░░░  7/12 pagas     │
│           [ Quero ajudar → ]         │
└──────────────────────────────────────┘
```

#### Doação (PIX) + link para formulário

```
┌──────────────────────────────────────┐
│  ← Voltar                            │
│  Ajude: João Paulo da Silva          │
│  Instituição: FAP                    │
├──────────────────────────────────────┤
│  CNPJ: 00.000.000/0001-00            │
│  Chave PIX: pix@fap.edu.br [copiar]  │
│  Nome no PIX: FAP - Faculdade...     │
│                                      │
│  ⚠️ Na descrição do PIX, escreva:    │
│     "João Paulo da Silva"            │
│                                      │
│  [ Enviar comprovante do PIX ]       │
│                                      │
│  Sem comprovante, o pagamento não    │
│  tem validade para o colégio.        │
└──────────────────────────────────────┘
```

#### Formulário de comprovante (novo)

```
┌──────────────────────────────────────┐
│  ← Voltar                            │
│  Comprovante — João Paulo da Silva   │
├──────────────────────────────────────┤
│  Mês de referência (opcional)        │
│  [ Janeiro ▼ ]                       │
│                                      │
│  Arquivo do comprovante *            │
│  [ Escolher arquivo ]                │
│  JPG, PNG ou PDF — máx. 5 MB         │
│                                      │
│  Observação (opcional)               │
│  [________________________]          │
│                                      │
│  [ Enviar comprovante ]              │
│                                      │
│  ✓ Enviado! O administrador irá      │
│    analisar em breve.                │
└──────────────────────────────────────┘
```

#### Admin — fila de comprovantes

```
┌──────────────────────────────────────┐
│  Comprovantes pendentes (3)          │
├──────────────────────────────────────┤
│  João Paulo · enviado há 2h          │
│  Mês ref.: Março · [ver arquivo]     │
│  [ Analisar → quitar mensalidade ]   │
├──────────────────────────────────────┤
│  Maria Santos · enviado ontem        │
│  [ Analisar ]                        │
└──────────────────────────────────────┘
```

---

## 6. Fases de implementação

### Fase 0 — Preparação (1–2 dias) ✅

**Objetivo:** projeto Laravel rodando localmente.

- [x] Criar repositório Git
- [x] `composer create-project laravel/laravel`
- [x] Instalar Laravel Breeze (login) + Livewire + Tailwind
- [x] Configurar `.env.example` com variáveis MySQL
- [x] Identidade visual (cores teal, fonte Nunito)
- [x] README com setup local (PHP 8.2+, Composer, MySQL)
- [x] Página pública "Em construção" + manifest PWA inicial
- [x] Registro público desabilitado (somente 1 admin)

**Entregável:** `php artisan serve` abre página inicial.

---

### Fase 1 — Banco de dados e models (2–3 dias) ✅

**Objetivo:** schema MySQL completo com seed.

- [x] Criar migrations: `instituicoes`, `alunos`, `mensalidades`, `comprovantes`, `configuracoes`
- [x] Criar Models Eloquent com relacionamentos
- [x] Seeder: 2 instituições (IAP, FAP), 5 alunos, 12 mensalidades cada
- [x] Seeder: 1 usuário admin (`admin@adotealuno.test`)
- [x] Seeder: registro de configurações padrão
- [x] Observer/Service: ao criar aluno → gerar 12 mensalidades automaticamente

**Entregável:** `php artisan migrate --seed` popula o banco.

---

### Fase 2 — Área pública (3–4 dias) ✅

**Objetivo:** doador navega, busca, vê PIX e envia comprovante.

- [x] Livewire: home com lista paginada de alunos ativos
- [x] Busca por nome (debounce, `WHERE nome LIKE %termo%`)
- [x] Componente `AlunoCard` (foto, dados, valor, barra de progresso)
- [x] Componente `ProgressoMensalidades` (barra compacta + grid 12 meses)
- [x] Página de doação com PIX copiável (Alpine + clipboard)
- [x] Formulário de comprovante com validação de arquivo
- [x] Salvar upload em `storage/app/public/comprovantes/`
- [x] Mensagem de sucesso após envio
- [x] Estados: loading, lista vazia, nenhum resultado na busca

**Entregável:** fluxo completo do doador funcionando no celular.

---

### Fase 3 — Painel administrativo (4–5 dias) ✅

**Objetivo:** você gerencia tudo sem tocar no banco.

- [x] Login Breeze + rotas `/admin` protegidas com `auth`
- [x] Redirect: logado → `/admin`, visitante → `/login`
- [x] Dashboard: alunos ativos, mensalidades pagas no mês, comprovantes pendentes
- [x] CRUD instituições
- [x] CRUD alunos com upload de foto
- [x] Grid de 12 mensalidades: marcar pago / reverter (com confirmação)
- [x] Fila de comprovantes: ver arquivo, vincular mensalidade, marcar analisado
- [x] Página de configurações (títulos, textos, limite de upload)
- [x] Flash messages de feedback

**Entregável:** cadastrar aluno, receber comprovante e quitar mensalidade pelo painel.

---

### Fase 4 — PWA e performance (1–2 dias) ✅

**Objetivo:** instalável no celular, leve.

- [x] `vite-plugin-pwa`: manifest + service worker (`public/build/sw.js`)
- [x] Cache de CSS/JS e fontes; `/storage` e `/livewire` em NetworkOnly
- [x] Ícones 192px e 512px em `public/icons/`
- [x] Meta tags iOS + `apple-touch-icon`
- [x] `ImageService`: resize de fotos de alunos (max 400px, JPEG)
- [x] Paginação na lista (20 alunos por página)
- [x] `loading="lazy"` nas imagens dos cards

**Entregável:** "Adicionar à tela inicial" funciona no Android/iOS.

---

### Fase 5 — Deploy Hostinger (2–3 dias) ✅

**Objetivo:** app no ar em produção.

- [x] Guia completo `DEPLOY-HOSTINGER.md`
- [x] `.env.hostinger.example` para produção
- [x] `scripts/prepare-deploy.sh` (build local antes do upload)
- [x] `deploy/post-deploy.sh` (migrate, storage:link, cache)
- [x] `deploy/index-public_html.php` (método public_html separado)
- [x] Documentação: SSL, permissões, limites PHP, troubleshooting

**Entregável:** pacote pronto para upload — você executa no seu domínio Hostinger.

---

### Fase 7 — Polimento e documentação (1 dia)

- [ ] Guia rápido para você: como cadastrar aluno, analisar comprovante, quitar mês
- [ ] Teste com 50+ alunos no seed (performance da busca)
- [ ] Revisar textos legais na tela de doação
- [ ] Rate limit no formulário de comprovante (evitar spam)

**Entregável:** sistema pronto para uso real.

---

## 7. Estimativa de tempo total

| Fase | Duração estimada |
|------|------------------|
| Fase 0 — Preparação | 1–2 dias |
| Fase 1 — Banco MySQL | 2–3 dias |
| Fase 2 — Área pública | 3–4 dias |
| Fase 3 — Auth admin | 1 dia |
| Fase 4 — Painel admin | 4–5 dias |
| Fase 5 — PWA | 1–2 dias |
| Fase 6 — Deploy Hostinger | 2–3 dias |
| Fase 7 — Polimento | 1 dia |
| **Total** | **~15–21 dias** (1 dev, ritmo tranquilo) |

---

## 8. Estrutura de pastas do projeto

```
adote-aluno/
├── app/
│   ├── Http/Controllers/
│   │   ├── AlunoController.php
│   │   ├── ComprovanteController.php
│   │   └── Admin/...
│   ├── Livewire/
│   │   ├── AlunoLista.php          → home com busca
│   │   ├── ComprovanteForm.php
│   │   ├── Admin/AlunoForm.php
│   │   ├── Admin/MensalidadeGrid.php
│   │   └── Admin/ComprovanteFila.php
│   ├── Models/
│   │   ├── Instituicao.php
│   │   ├── Aluno.php
│   │   ├── Mensalidade.php
│   │   ├── Comprovante.php
│   │   └── Configuracao.php
│   └── Services/
│       └── MensalidadeService.php  → gera 12 meses ao criar aluno
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── icons/                      → PWA
│   └── build/                      → Vite assets
├── resources/
│   ├── views/
│   │   ├── alunos/
│   │   ├── comprovantes/
│   │   └── admin/
│   └── css/app.css
├── storage/app/public/
│   ├── alunos/
│   └── comprovantes/
├── .env.example
├── PLANO-DE-IMPLEMENTACAO.md
└── README.md
```

---

## 9. Evoluções futuras (fora do escopo inicial)

| Feature | Quando |
|---------|--------|
| Responsáveis cadastram o próprio filho | Após validar o fluxo atual |
| Notificação por e-mail ao receber comprovante | Quando volume crescer |
| Relatório mensal em PDF | Opcional |
| Múltiplos admins | Se necessário no futuro |
| Migrar para VPS + PostgreSQL | Se Hostinger ficar limitado |

---

## 10. Próximo passo imediato

Com tudo alinhado, a ordem agora é:

1. **Fase 0** — criar o projeto Laravel com Breeze + Livewire + Tailwind
2. **Fase 1** — migrations MySQL + seed com dados de teste

**Fase 5 concluída.** Siga o [DEPLOY-HOSTINGER.md](DEPLOY-HOSTINGER.md) para colocar no ar no seu domínio.

---

*Documento atualizado em 09/06/2026 — versão 1.1*
