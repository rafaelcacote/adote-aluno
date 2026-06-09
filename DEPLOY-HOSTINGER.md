# Deploy na Hostinger — Adote um Estudante

Guia para hospedagem **compartilhada** (PHP + MySQL).

---

## Pré-requisitos no hPanel

- PHP **8.2+** (Websites → Gerenciar → Configuração PHP)
- Extensões: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `zip`
- MySQL criado em **Bancos de dados → MySQL**
- SSL ativo (Let's Encrypt gratuito em **SSL**)

---

## Passo 1 — Preparar o pacote (na sua máquina)

```bash
cd "/caminho/adote-aluno"
chmod +x scripts/prepare-deploy.sh
./scripts/prepare-deploy.sh
```

Isso roda `composer install --no-dev` e `npm run build` (gera o PWA: `sw.js`, etc.).

---

## Passo 2 — Escolher método de deploy

### Método A — Document root na pasta `public` (recomendado)

Se o seu plano Hostinger permite alterar a **raiz do documento**:

1. Envie o projeto inteiro para `/home/SEU_USUARIO/adote-aluno/`
2. No hPanel: **Websites → Gerenciar → Avançado → Raiz da pasta** → aponte para:
   ```
   /home/SEU_USUARIO/adote-aluno/public
   ```
3. Use o `public/index.php` padrão do Laravel (sem alteração).

### Método B — `public_html` separado (plano compartilhado comum)

Estrutura:

```
/home/SEU_USUARIO/
├── adote-aluno/              ← Laravel completo (NÃO acessível pela web)
│   ├── app/
│   ├── vendor/
│   ├── .env
│   └── ...
└── domains/SEU_DOMINIO/
    └── public_html/          ← só arquivos públicos
        ├── index.php         ← use deploy/index-public_html.php
        ├── .htaccess
        ├── build/
        ├── icons/
        └── storage/          ← link simbólico (criado pelo artisan)
```

**Upload:**

1. Pasta `adote-aluno/` → `/home/SEU_USUARIO/adote-aluno/`
2. Conteúdo de `public/` → `public_html/` (incluindo `build/`, `icons/`, `.htaccess`)
3. Copie `deploy/index-public_html.php` → `public_html/index.php`
4. Se o caminho do Laravel for diferente, edite a linha `LARAVEL_ROOT` no `index.php`

---

## Passo 3 — Configurar `.env` no servidor

```bash
cp .env.hostinger.example .env
nano .env   # ou editor do Gerenciador de Arquivos
php artisan key:generate
```

Preencha:

| Variável | Onde achar no hPanel |
|----------|----------------------|
| `APP_URL` | `https://seudominio.com` |
| `DB_DATABASE` | MySQL → nome do banco |
| `DB_USERNAME` | MySQL → usuário |
| `DB_PASSWORD` | MySQL → senha |
| `DB_HOST` | geralmente `localhost` |

**Produção obrigatório:**

```
APP_ENV=production
APP_DEBUG=false
```

---

## Passo 4 — Comandos no servidor

Via **SSH** (se disponível) ou **Terminal** do hPanel:

```bash
cd /home/SEU_USUARIO/adote-aluno
chmod +x deploy/post-deploy.sh
./deploy/post-deploy.sh
```

Ou manualmente:

```bash
chmod -R 775 storage bootstrap/cache
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan view:cache
```

### Primeiro deploy — criar admin

```bash
php artisan db:seed --force
```

Depois **altere a senha** do admin em `/admin` → Perfil, ou crie novo usuário e apague o de teste.

Credenciais do seed (troque imediatamente):

- E-mail: `admin@adotealuno.test`
- Senha: `senha123`

---

## Passo 5 — Verificações

| Teste | URL / ação |
|-------|------------|
| Site público | `https://seudominio.com` |
| Login admin | `https://seudominio.com/login` |
| Painel | `https://seudominio.com/admin` |
| Foto de aluno | cadastre aluno com foto → deve abrir `/storage/alunos/...` |
| PWA | DevTools → Application → Manifest + Service Worker |
| HTTPS | cadeado verde no navegador |

---

## Upload de arquivos (limites PHP)

No hPanel → **Configuração PHP**, ajuste se necessário:

- `upload_max_filesize` = **10M**
- `post_max_size` = **12M**

O app limita comprovantes pelo painel **Configurações** (padrão 5 MB).

---

## Problemas comuns

### Erro 500 após deploy

- `storage/` e `bootstrap/cache/` sem permissão de escrita → `chmod -R 775 storage bootstrap/cache`
- `.env` ausente ou `APP_KEY` vazio → `php artisan key:generate`
- `vendor/` não enviado → rode `composer install --no-dev` no servidor ou envie a pasta

### Fotos/comprovantes não abrem

```bash
php artisan storage:link
```

Confirme que `public_html/storage` aponta para `storage/app/public`.

### CSS/JS quebrado

- Rode `npm run build` localmente antes do upload
- Confirme que `public/build/` foi enviado ao servidor
- `APP_URL` deve ser `https://seudominio.com` (com HTTPS)

### PWA não instala

- Site precisa estar em **HTTPS**
- `public/build/sw.js` e `manifest.webmanifest` devem existir
- Limpe cache do navegador

### Banco não conecta

- Host quase sempre `localhost` na Hostinger (não `127.0.0.1`)
- Usuário MySQL deve ter acesso ao banco criado no mesmo painel

---

## Backup

No hPanel → **Backups** → ative backup automático do site e do MySQL.

---

## Atualizações futuras

1. Na máquina local: altere o código → `./scripts/prepare-deploy.sh`
2. Envie arquivos alterados via FTP/Git
3. No servidor: `php artisan migrate --force && php artisan config:cache && php artisan view:cache`

---

## Checklist rápido

- [ ] `prepare-deploy.sh` executado
- [ ] Projeto no servidor
- [ ] `.env` de produção configurado
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `php artisan db:seed` (primeiro deploy)
- [ ] Senha do admin alterada
- [ ] SSL ativo
- [ ] Teste: listar alunos, PIX, upload comprovante, login admin
