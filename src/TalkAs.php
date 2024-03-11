<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/StbModuleTalkAs

namespace ProtocolLive\StbModuleTalkAs;
use ProtocolLive\SimpleTelegramBot\StbInterfaces\StbModuleInterface;
use ProtocolLive\SimpleTelegramBot\StbObjects\{
  StbDatabase,
  StbLanguageSys,
  StbModuleHelper
};
use ProtocolLive\TelegramBotLibrary\TblObjects\{
  TblCmd,
  TblException
};
use ProtocolLive\TelegramBotLibrary\TelegramBotLibrary;
use ProtocolLive\TelegramBotLibrary\TgEnums\TgParseMode;
use ProtocolLive\TelegramBotLibrary\TgInterfaces\{
  TgEditedInterface,
  TgEventInterface
};
use ProtocolLive\TelegramBotLibrary\TgObjects\{
  TgCallback,
  TgReactionUpdate
};

/**
 * @version 2024.03.11.00
 */
abstract class TalkAs
extends StbModuleHelper
implements StbModuleInterface{
  public static function Command(
    TelegramBotLibrary $Bot,
    TblCmd $Webhook,
    StbDatabase $Db,
    StbLanguageSys $Lang
  ):void{
    if(in_array($Webhook->Data->User->Id, array_column($Db->Admins(), 'Id'))
    and is_callable(__CLASS__ . '::Command_' . $Webhook->Command)):
      call_user_func(__CLASS__ . '::Command_' . $Webhook->Command, $Bot, $Webhook, $Db);
    endif;
  }

  private static function Command_del(
    TelegramBotLibrary $Bot,
    TblCmd $Webhook,
    StbDatabase $Db,
  ):void{
    DebugTrace();
    $Bot->MessageDelete(
      $Db->VariableGetValue('Talk', __CLASS__, $Webhook->Data->User->Id),
      $Webhook->Parameters
    );
    $Bot->MessagesDelete(
      $Webhook->Data->User->Id,
      [$Webhook->Parameters - 1, $Webhook->Parameters + 1, $Webhook->Data->Id]
    );
  }

  private static function Command_done(
    TelegramBotLibrary $Bot,
    TblCmd $Webhook,
    StbDatabase $Db,
  ):void{
    DebugTrace();
    $to = $Db->VariableGetValue('Talk', __CLASS__, $Webhook->Data->User->Id);
    $Db->VariableDel('Talk', null, __CLASS__, $Webhook->Data->User->Id);
    $Db->VariableDel('Talk', null, __CLASS__, $to);
    $Db->VariableDel('LastMsg', null, __CLASS__, $Webhook->Data->User->Id);
    $Bot->TextSend(
      $Webhook->Data->User->Id,
      'Conversa finalizada'
    );
  }

  private static function Command_msg(
    TelegramBotLibrary $Bot,
    TblCmd $Webhook,
    StbDatabase $Db,
  ):void{
    DebugTrace();
    $params = explode(' ', $Webhook->Parameters);
    try{
      $id = $Bot->MessageCopy(
        $Webhook->Data->Chat->Id,
        $Webhook->Data->Id,
        $params[0],
        Caption: $params[1]
      );
      $Bot->TextSend(
        $Webhook->Data->User->Id,
        'Id: <code>' . $id . '</code>',
        ParseMode: TgParseMode::Html
      );
    }catch(TblException $e){
      $Bot->TextSend(
        $Webhook->Data->User->Id,
        $e->getMessage()
      );
    }
  }

  private static function Command_talk(
    TelegramBotLibrary $Bot,
    TblCmd $Webhook,
    StbDatabase $Db,
  ):void{
    DebugTrace();
    if($Db->ChatGet($Webhook->Parameters) === null):
      $Bot->TextSend(
        $Webhook->Data->User->Id,
        '⚠️ Usuário não encontrado ou não iniciou o bot'
      );
    endif;
    $Db->VariableSet('Talk', $Webhook->Parameters, __CLASS__, $Webhook->Data->User->Id);
    $Db->VariableSet('Talk', $Webhook->Data->User->Id, __CLASS__, $Webhook->Parameters);
    $user = $Bot->ChatGet($Webhook->Parameters);
    $nome = $user->Name;
    if($user->NameLast !== null):
      $nome .= ' ' . $user->NameLast;
    endif;
    $Bot->TextSend(
      $Webhook->Data->User->Id,
      'Agora você conversando com ' . $nome . '. Use o comando /done para finalizar.'
    );
  }

  public static function Install(
    TelegramBotLibrary $Bot,
    TgCallback $Webhook,
    StbDatabase $Db,
    StbLanguageSys $Lang
  ):void{
  }

  public static function Listener(
    TelegramBotLibrary $Bot,
    TgEventInterface $Webhook,
    StbDatabase $Db,
    StbLanguageSys $Lang
  ):bool{
    DebugTrace();
    $to = $Db->VariableGetValue('Talk', __CLASS__, $Webhook->Data->User->Id);
    if($to === null):
      return false;
    endif;
    if(in_array($Webhook->Data->User->Id, array_column($Db->Admins(), 'Id'))):
      if($Webhook instanceof TgEditedInterface):
        $Bot->TextSend(
          $Webhook->Data->User->Id,
          '⚠️ A mensagem não pode ser editada para o outro usuário'
        );
        return true;
      elseif($Webhook instanceof TgReactionUpdate):
        try{
          $Bot->MessageReaction($to, $Webhook->Data->Id - 1, $Webhook->New->Emoji ?? null);
        }catch(TblException){}
        return true;
      endif;
      $id = $Bot->MessageCopy($Webhook->Data->User->Id, $Webhook->Data->Id, $to);
      $Bot->TextSend(
        $Webhook->Data->User->Id,
        'Id: <code>' . $id . '</code>',
        ParseMode: TgParseMode::Html
      );
    else:
      if($Webhook::class === TgReactionUpdate::class):
        try{
          $Bot->MessageReaction($to, $Webhook->Data->Id - 1, $Webhook->New->Emoji ?? null);
        }catch(TblException){}
        return true;
      endif;
      $Bot->MessageForward($Webhook->Data->User->Id, $Webhook->Data->Id, $to);
    endif;
    return true;
  }

  public static function Uninstall(
    TelegramBotLibrary $Bot,
    TgCallback $Webhook,
    StbDatabase $Db,
    StbLanguageSys $Lang
  ):void{
    DebugTrace();
    parent::UninstallHelper($Bot, $Webhook, $Db, $Lang, __CLASS__);
  }
}