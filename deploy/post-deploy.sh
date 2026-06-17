#!/bin/bash
# Comandos pós-deploy — rodar na pasta raiz do Laravel no servidor Hostinger
set -euo pipefail

echo "→ Permissões storage e cache..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "→ Link simbólico storage..."
php artisan storage:link --force 2>/dev/null || php artisan storage:link

echo "→ Migrations..."
php artisan migrate --force

echo "→ Limpando caches antigos..."
php artisan optimize:clear 2>/dev/null || php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear 2>/dev/null || true

echo "→ Otimização..."
php artisan config:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

echo "→ Concluído."
php artisan about --only=environment,cache,drivers 2>/dev/null || true
