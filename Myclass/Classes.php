<?php
namespace App\Myclass;
use App;
use Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use DB;

class Buttons{  

        public $buttons;

        function __construct($buttons){
            $this->buttons = $buttons;
    }
 
        public function addButton(){
            $this->buttons = array_map(function($name,$val){
                    return Keyboard::inlineButton(['text' => $name,'callback_data' => $val]);
                },array_keys($this->buttons),array_values($this->buttons));
                $inline   = Keyboard::make()->inline();
                return call_user_func_array([$inline, 'row'], $this->buttons);
    }
}

class EditMessa{  

        public $updfrid;
        public $message_num;
        public $ans;
        public $keyboard;
        
       

    function __construct($updfrid,$message_num,$ans,$keyboard)
    {

        
        $this->updfrid = $updfrid;
        $this->message_num = $message_num;
        $this->ans = $ans;
        $this->keyboard = $keyboard;
    }    
    
 
    public function editMess(){

        return Telegram::editMessageText([
                'chat_id' => $this->updfrid,
                'message_id' => $this->message_num,
                'text' => $this->ans,
                'parse_mode' => 'HTML',
                'reply_markup' => $this->keyboard   
            ]);
    }
}

class SendMessa{  

        
        public $chat_id;
        public $ans;
        public $keyboard;
        
       

    function __construct($chat_id,$ans,$keyboard)
    {

        
        $this->chat_id = $chat_id;
        $this->ans = $ans;
        $this->keyboard = $keyboard;
    }    
    
 
    public function sendMess(){

        return Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $this->ans,
                'parse_mode' => 'HTML',
                'reply_markup' => $this->keyboard   
            ]);
    }
}

