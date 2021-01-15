<?php

namespace App\Http\Controllers;
use App;
use Telegram\Bot\Api;
use Telegram;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Inbox;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use App\Myclass\SimplyMess;
use App\Myclass\CallbackMess;
use DB;
use Carbon\Carbon;

class WebhookController extends Controller 
{


       public function start()
    {
        
        $mesage = Telegram::getWebhookUpdate();

        if($mesage->isType('callback_query')) {

                $callback = new CallbackMess($mesage);
                $atv = $callback->callMess();

        }elseif(isset($mesage['message']['text'])){

                $simple = new SimplyMess($mesage);
                $otv = $simple->simMess();

        }      
    }
}








