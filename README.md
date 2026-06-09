# Adote um Estudante

PWA para conectar doadores a estudantes que precisam de ajuda com mensalidades. O PIX é sempre da **instituição** onde o aluno estuda — nunca pessoal.

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+ (local e produção na Hostinger)

Extensões PHP necessárias: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`

## Setup local

```bash
# 1. Instalar dependências PHP
composer install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Criar banco MySQL
mysql -u root -p -e "CREATE DATABASE adote_aluno CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Ajustar .env com usuário/senha do MySQL
# DB_DATABASE=adote_aluno
# DB_USERNAME=root
# DB_PASSWORD=sua_senha

# 5. Instalar dependências front-end e compilar
npm install
npm run build

# 6. Rodar migrations e seed
php artisan migrate --seed

# 7. Iniciar servidor
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

### Login admin

- URL: `/login` ou `/admin/login` (redireciona)
- Painel: `/admin`
- Cadastro público **desabilitado** — apenas 1 administrador
- Após `migrate --seed`: **admin@adotealuno.test** / **senha123**

## Rotas admin

| Rota | Descrição |
|------|-----------|
| `/admin` | Dashboard (resumo) |
| `/admin/alunos` | Lista e cadastro de alunos |
| `/admin/alunos/{id}` | Grid de 12 mensalidades |
| `/admin/instituicoes` | CRUD de instituições (PIX) |
| `/admin/comprovantes` | Fila de comprovantes pendentes |
| `/admin/configuracoes` | Textos e limites do app |

## Desenvolvimento

```bash
# Servidor + assets em tempo real
php artisan serve
npm run dev
```

## Stack

| Camada | Tecnologia |
|--------|------------|
| Backend | Laravel 13 |
| UI reativa | Livewire 3 + Volt |
| Auth | Laravel Breeze |
| CSS | Tailwind CSS 3 |
| Banco | MySQL |
| Deploy | Hostinger (hospedagem compartilhada) |

## Estrutura principal

```
resources/views/
├── home.blade.php              # Página pública (em construção)
├── components/layouts/public.blade.php
└── livewire/                   # Componentes interativos

routes/
├── web.php                     # Rotas públicas e admin
└── auth.php                    # Login (sem registro público)
```

## PWA (instalar no celular)

Após `npm run build`, o app gera:
- `public/build/sw.js` — service worker
- `public/build/manifest.webmanifest` — manifest

No Chrome/Android: abra o site → menu → **Adicionar à tela inicial**.

No iOS/Safari: botão compartilhar → **Adicionar à Tela de Início**.

> O service worker cacheia CSS/JS e fontes. Páginas e uploads (`/storage`, `/livewire`) sempre usam rede.

## Deploy Hostinger (Fase 5)

**Guia completo:** [DEPLOY-HOSTINGER.md](DEPLOY-HOSTINGER.md)

```bash
# Na sua máquina, antes do upload:
./scripts/prepare-deploy.sh

# No servidor, após configurar .env:
./deploy/post-deploy.sh
```

Modelo de `.env` de produção: `.env.hostinger.example`

## Documentação do projeto

Ver [PLANO-DE-IMPLEMENTACAO.md](PLANO-DE-IMPLEMENTACAO.md) para fases, modelagem e wireframes.

## Fase atual

**Fase 0 — concluída:** projeto base Laravel + Breeze + Livewire + Tailwind.

**Fase 1 — concluída:** migrations MySQL, models, observer de mensalidades e seed.

**Fase 2 — concluída:** lista pública de alunos, busca por nome, tela PIX e formulário de comprovante.

**Fase 3 — concluída:** painel admin em `/admin` (dashboard, CRUD, comprovantes, mensalidades).

**Fase 4 — concluída:** PWA com service worker, manifest e otimização de fotos.

**Fase 5 — concluída:** scripts e guia de deploy Hostinger (`DEPLOY-HOSTINGER.md`).

**Próximo:** executar o deploy no seu domínio Hostinger seguindo o guia.

## Rotas públicas

| Rota | Descrição |
|------|-----------|
| `/` | Lista de alunos + busca por nome |
| `/aluno/{id}/doar` | PIX da instituição + botão copiar |
| `/aluno/{id}/comprovante` | Upload do comprovante |
