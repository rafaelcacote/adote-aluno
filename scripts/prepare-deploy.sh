#!/bin/bash
# Prepara o pacote para upload na Hostinger (rode na sua máquina local)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

echo "→ Composer (produção, sem dev)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Build front-end + PWA..."
npm ci
npm run build

echo "→ Cache de views local (opcional)..."
php artisan view:cache 2>/dev/null || true

echo ""
echo "=============================================="
echo " Pacote pronto para upload!"
echo "=============================================="
echo ""
echo "Envie para a Hostinger (exceto o que está no .gitignore):"
echo "  - app/ bootstrap/ config/ database/ public/ resources/ routes/"
echo "  - storage/ (pastas vazias + .gitignore internos)"
echo "  - vendor/"
echo "  - artisan, composer.json, composer.lock"
echo "  - .env (crie no servidor a partir de .env.hostinger.example)"
echo ""
echo "NÃO envie: node_modules/, .git/, .env local"
echo ""
echo "Confirme que existem:"
echo "  - public/build/sw.js"
echo "  - public/build/manifest.webmanifest"
ls -la public/build/sw.js public/build/manifest.webmanifest
