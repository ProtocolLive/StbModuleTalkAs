<?php
/**
 * @version 2024.11.30.00
 */

declare(strict_types = 1);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', '-1');
ini_set('html_errors', '0');
ini_set('max_execution_time', '10');
ini_set('error_log', __DIR__ . '/error.log');

use ProtocolLive\SimpleTelegramBot\StbObjects\StbAdminModules;
use ProtocolLive\SimpleTelegramBot\StbParams\StbGlobalModuleCmds;
use ProtocolLive\TelegramBotLibrary\TgInterfaces\TgMessageInterface;

require(dirname(__DIR__, 3) . '/autoload.php');

$cmd = new StbGlobalModuleCmds;
$cmd->Add('msg', 'Enviar uma mensagem para um id', false);
$cmd->Add('talk', 'Conversar com um id', false);
$cmd->Add('done', 'Terminar conversa', false);
$cmd->Add('del', 'Excluir uma mensagem', false);

StbAdminModules::GlobalModuleInstall(
  ProtocolLive\StbModuleTalkAs\TalkAs::class,
  $cmd,
  [TgMessageInterface::class]
);
echo 'Instalação concluída' . PHP_EOL;