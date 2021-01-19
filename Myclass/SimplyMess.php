<?php
namespace App\Myclass;
use App;
use Telegram\Bot\Api;
use Telegram;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Inbox;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
require_once 'Classes.php';

class SimplyMess{  

    function __construct($mesage){

        $this->mesage = $mesage;

    }
 
    public function simMess(){

        $updates = $this->mesage;
        $chat_id    = $updates['message']['chat']['id'];
        $updfromid  = $updates['message']['from']['id'];
        $text       = $updates['message']['text'];
        $text = preg_replace("/[^а-яА-ЯёЁіІїЇєЄa-zA-Z0-9\s\/\-]/iu", '', $text);
        $text_low = mb_strtolower($text, 'UTF-8');
        $text_space = preg_replace('/^([ ]+)|([ ]){2,}/m', '$2', $text_low);
        $word_count = explode(" ", $text_space);
        $repl_1_word = substr(strstr($text_space," "), 1);
        $repl_1_word = preg_replace("/[^а-яА-ЯёЁіІїЇєЄa-zA-Z0-9\s]/iu", '', $repl_1_word);
        $inboxess = 'inboxess';
        $youtube = 'youtube';
        $next_message = $updates['message']['message_id'];
        DB::table($inboxess)->update(['message_num' => $next_message+1]);
        $button = ['1' => 'youtube 0','2' => 'youtube 5','3' => 'youtube 10','4' => 'youtube 15','5' => 'youtube 20','6' => 'youtube 25']; 
        if(DB::table($inboxess)->where('upd_from_id', $updfromid)->doesntExist()){  
        
            DB::table($inboxess)->insertOrIgnore(['upd_from_id' => $updfromid]);
        }
//  ---------------------------------------------------------------------------------------------------------------------------------
        if($text_space === ''||$text_space === ' '){

            $keyboard = Keyboard::make()->inline()->row(Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => "Menu"]));
            $ans = "Please write <b>youtube</b>... and what you are looking for";
            $sendmess = new SendMessa($chat_id,$ans,$keyboard);
            $send = $sendmess->sendMess();
            exit;

        }
        switch($text_space){
                case ($text_space === '/start'):

                        $update = Telegram::commandsHandler(true);
                    
                    break;

                case (strpos($text_space, $youtube) !== false):
                    if(count($word_count) > 1){
                        $word1 = str_replace(" ", "%20", $repl_1_word);
                        $url = "https://youtube.googleapis.com/youtube/v3/search?part=snippet&q=".$word1."&type=video&key=".$_ENV['YOUTUBE_API_KEY']."&maxResults=25";
                    }elseif((strpos($text_space, $youtube) !== false) && (count($word_count) === 1)){
                         $keyboard = Keyboard::make()->inline()->row(Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => "Menu"]));
                        $ans = "what we are searching?";
                        $sendmess = new SendMessa($chat_id,$ans,$keyboard);
                        $send = $sendmess->sendMess();
                    exit;
                    }

                    DB::table($inboxess)->where('upd_from_id', $updfromid)->update(['keyword' => $word1,
                                                                                    'key1' => 'youtube 0']);
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "GET"    
                        ));

                        $respon = curl_exec($curl);
                        $respon = json_decode($respon, true);
                        $keyboard = Keyboard::make()->inline();
                        if (curl_errno($curl)) {
                            
                            $keyboard->row(
                                Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => "Menu"]));
                            $ans = 'Nothing...';
                            $sendmess = new SendMessa($chat_id,$ans,$keyboard);
                            $send = $sendmess->sendMess();
                            exit;
                        } 

                        curl_close($curl);

                        foreach ($respon['items'] as $items => $item) {
                            $title = $item['snippet']['title'];
                            $chanel_title = $item['snippet']['channelTitle'];
                            $shrt_title = preg_replace("/[^а-яА-ЯёЁіІїЇєЄa-zA-Z0-9\s]/iu", "", $title);
                            $shrt_title = preg_replace('/^([ ]+)|([ ]){2,}/m', '$2', $shrt_title);
                            $shrt_title = mb_substr($shrt_title, 0, 30);
                            if(DB::table($youtube)->where('videoId',$item['id']['videoId'])->doesntExist()){
                                DB::table($youtube)->insertOrIgnore(['title' => $title,
                                                                  'channelTitle' => $chanel_title,
                                                                  'videoId' => $item['id']['videoId'],
                                                                  'channelId' => $item['snippet']['channelId'],
                                                                  'publishedAt' => $item['snippet']['publishedAt']]);
                            } 
                        }
                        $buttons = Arr::except($button, ['youtube 0']); // this must be in DB, something like ($buttons = DB::table('youtube_btns')->where(button != $upd_data)->get()->toArray())
                        $ot = new Buttons($buttons);
                        $keyboard = $ot->addButton();
                        $db_youtube = DB::table($youtube)->where('title','like','%'.$repl_1_word.'%')->orWhere('channelTitle','like','%'.$repl_1_word.'%')->skip(0)->take(5)->latest('publishedAt')->get();

                        foreach ($db_youtube as $yout) {
                            $keyboard->row(Keyboard::inlineButton(['text' => $yout->title, 'callback_data' => $yout->videoId]));
                        }
                        
                        $keyboard->row(
                                Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => "Menu"]));
                                $ans = 'founded by title '.$repl_1_word;
                                $sendmess = new SendMessa($chat_id,$ans,$keyboard);
                                $send = $sendmess->sendMess();
                    break;
                         
        }
                              
    }
}
        
           
        
    
