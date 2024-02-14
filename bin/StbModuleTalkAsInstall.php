<?php
/**
 * @version 2024.02.13.00
 */

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

$Classe = 
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