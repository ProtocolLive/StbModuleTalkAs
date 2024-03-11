<?php
/**
 * @version 2024.03.11.00
 */

declare(strict_types = 1);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', '-1');
ini_set('html_errors', '0');
ini_set('max_execution_time', '10');
ini_set('error_log', __DIR__ . '/error.log');

use ProtocolLive\SimpleTelegramBot\StbObjects\StbAdminModules;

require(dirname(__DIR__, 3) . '/autoload.php');

StbAdminModules::GlobalModuleUninstall(ProtocolLive\StbModuleTalkAs\TalkAs::class);
echo 'Desinstalação concluída' . PHP_EOL;