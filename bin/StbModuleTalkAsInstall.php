<?php
/**
 * @version 2024.02.13.02
 */

declare(strict_types = 1);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', '-1');
ini_set('html_errors', '0');
ini_set('max_execution_time', '10');
ini_set('error_log', __DIR__ . '/error.log');

use ProtocolLive\SimpleTelegramBot\StbObjects\StbAdminModules;
use ProtocolLive\TelegramBotLibrary\TgObjects\{
  TgAnimation,
  TgAudio,
  TgDocument,
  TgPhoto,
  TgReactionUpdate,
  TgSticker,
  TgText,
  TgVideo,
  TgVideoNote
};

require(dirname(__DIR__, 3) . '/autoload.php');

StbAdminModules::InstallGlobalModule(
  'ProtocolLive\\StbModuleTalkAs\\TalkAs',
  [
    ['msg', 'Enviar uma mensagem para um id', __CLASS__, false],
    ['talk', 'Conversar com um id', __CLASS__, false],
    ['done', 'Terminar conversa', __CLASS__, false],
    ['del', 'Excluir uma mensagem', __CLASS__, false]
  ],
  [
    TgText::class, __CLASS__,
    TgAudio::class, __CLASS__,
    TgVideo::class, __CLASS__,
    TgPhoto::class, __CLASS__,
    TgDocument::class, __CLASS__,
    TgSticker::class, __CLASS__,
    TgAnimation::class, __CLASS__,
    TgVideoNote::class, __CLASS__,
    TgReactionUpdate::class, __CLASS__
  ]
);