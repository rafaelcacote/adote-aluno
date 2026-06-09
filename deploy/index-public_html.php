<?php

/**
 * index.php para Hostinger quando o Laravel fica FORA do public_html.
 *
 * Estrutura esperada:
 *   /home/seu_usuario/adote-aluno/     ← projeto Laravel (vendor, app, .env…)
 *   /home/seu_usuario/domains/SEU_DOMINIO/public_html/  ← este arquivo + assets
 *
 * Ajuste LARAVEL_ROOT se o caminho do projeto for diferente.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Caminho até a pasta do Laravel (uma pasta acima de domains, típico na Hostinger)
define('LARAVEL_ROOT', dirname(__DIR__, 3).'/adote-aluno');

if (! is_dir(LARAVEL_ROOT)) {
    http_response_code(500);
    echo 'Laravel não encontrado. Ajuste LARAVEL_ROOT em public_html/index.php';
    exit(1);
}

if (file_exists($maintenance = LARAVEL_ROOT.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require LARAVEL_ROOT.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once LARAVEL_ROOT.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
