<?php

namespace App\Telegram\Commands;
use App;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;


/**
 * Class HelpCommand.
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var array Command Aliases
     */
    /**
     * @var string Command Description
     */
    protected $description = 'info';

    /**
     * {@inheritdoc}
     */
        public function handle() {

            $response = $this->getUpdate();
            $this->replyWithChatAction(['action' => Actions::TYPING ]);
            $keyboard = Keyboard::make()->inline()->row(
                        Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F4F1)).' Info', 
                                                'callback_data' => "info"]));
       
            $this->replyWithMessage([ 
                'text' => "<b>Menu</b>",  
                'parse_mode' => 'HTML',
                'reply_markup' => $keyboard
            ]);
        
    }    

}

  
