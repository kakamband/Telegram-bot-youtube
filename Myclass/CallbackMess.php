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
include 'Classes.php';
class CallbackMess
{  

    function __construct($mess)
    {

        $this->mess = $mess;

    }

 
    public function callMess(){

        $updates = $this->mess['callback_query'];
        $inboxess = DB::table('inboxess');
        $youtube = DB::table('youtube');
        if(isset($updates['message']['text']))
        {
            $text = $updates['message']['text'];
            $text = preg_replace("/[^а-яА-ЯёЁіІїЇєЄa-zA-Z0-9\s\.\?\,\!\(\)\@\:\-\/]/iu", '', $text);
            
        }
        $updfromid = $updates['from']['id'];
        $upd_data = $updates['data'];
        $butto = ['1' => 'youtube 0','2' => 'youtube 5','3' => 'youtube 10','4' => 'youtube 15','5' => 'youtube 20','6' => 'youtube 25'];
        $inbox = $inboxess->where('upd_from_id', $updfromid)->first(); 
        $search = $inbox->keyword;
        $message_num = $inbox->message_num;
        if($inboxess->where('upd_from_id', $updfromid)->doesntExist()){  
        
            $inboxess->insertOrIgnore(['upd_from_id' => $updfromid]);
        }
     
        

//  Show Youtube video by ID ------------------------------------------------------------------------------------------------------

        switch ($upd_data) {
            case ($youtube->where('videoId', $upd_data)->exists()):
                $keyword = $inboxess->where('upd_from_id', $updfromid)->first(); 
                $key1 = $keyword->key1;
                $key2 = $keyword->key2;
                $keyboard = Keyboard::make()->inline();
                if($key2 === 'youtube'){
                    $yout = $youtube->where('videoId', $upd_data)->select('videoId','publishedAt','title')->latest('publishedAt')->get();
                }else{
                    $keyboard->row(Keyboard::inlineButton([
                            'text'          => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' back',
                            'callback_data' => 'Menu']));
                    $ans = "Ups, something went wrong!";
                    $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                    $sendmm->editMess();
                    exit;

                }
                foreach ($yout as $val) {
                    
                        $l = $val->publishedAt;
                        $p = $val->videoId;
                    
                }
                
                $keyboard->row(Keyboard::inlineButton([
                            'text'          => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' back',
                            'callback_data' => $key1]));
                $ans = "Опубліковано: ".Carbon::parse($l)->diffForHumans()."\n\nhttps://www.youtube.com/watch?v=".$p;
                        
                
                $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                return $sendmm->editMess();
                break;
            
//  Start Menu ---------------------------------------------------------------------------------------------------------------------

            case ($upd_data === "Menu"):
                
                $keyboard = Keyboard::make()->inline();
                if($updfromid === 1221534640){
                    $keyboard->row(Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x2757)).' Delete youtube', 'callback_data' => "yout_dell"]));
                }
                $keyboard->row(Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x2757)).' How to use', 'callback_data' => "info"]));
                $ans = "<b>Menu</b>";
                $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                return $sendmm->editMess();
                break;

 //  Delete youtube table and info ------------------------------------------------------------------------------------------------

        case ($upd_data === "yout_dell"||$upd_data === "info"):
            if($upd_data === 'yout_dell'){
                    $ans = 'Youtube table deleted';
                    $youtube->truncate();
            }else{
                    $ans = "Just type: <b>Youtube madonna</b> or whatever you are looking for..\n\nTo reboot type: /start";
            }
                $keyboard = Keyboard::make()->inline()->row(Keyboard::inlineButton(['text' => 'Menu', 'callback_data' => 'Menu']));
                $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                    return $sendmm->editMess();
                break;

//  Get Next 5 Youtube videos from Database  --------------------------------------------------------------------------------------

         case (Arr::except($butto, $upd_data) !== false):
                $sv = $inboxess->where('upd_from_id', $updfromid)->first(); 
                $search_val = $sv->keyword;
                
                $inboxess->where('upd_from_id', $updfromid)->update(['key1' => $upd_data]);
                $last_word_start = strrpos($upd_data, " ") + 1;
                $last_word = substr($upd_data, $last_word_start);
                $cutlastw = preg_replace('=\s\S+$=', "", $upd_data);
                $obr = intval($last_word);

                
                    $search = str_replace("%20", " ", $search_val);
                    $youtube = DB::table($cutlastw)->where('title','like','%'.$search.'%')->orWhere('channelTitle','like','%'.$search.'%')->skip($obr)->take(5)->latest('publishedAt')->get();
                    $ans = 'Found for your request '.$search;
                

                $buttons = Arr::except($butto, [$upd_data]);
                $ot = new Buttons($buttons);
                $keyboard = $ot->addButton();
                
                    foreach($youtube as $val){
                        
                            $title = $val->title;
                            $title = strval($title);
                            $video_id = $val->videoId;
                            $short_title = mb_substr($title, 0, 30);
                            $keyboard->row(Keyboard::inlineButton([
                                'text'          => $title,
                                'callback_data' => $video_id]));

                         
                    }
                    $keyboard->row(
                        Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => 'Menu']));
                        $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                        return $sendmm->editMess();
                break;

            case (strpos($upd_data, 'youtube') !== false):
                $keyword = $inboxess->where('upd_from_id','=', $updfromid)->first(); 
                $key1 = $keyword->key1;
                $key1 = str_replace("%20", " ", $key1);
                $yout = $youtube->where('title','like','%'.$key1.'%')->orWhere('channelTitle','like','%'.$key1.'%')->skip(0)->take(5)->latest('publishedAt')->get();
                $buttons = Arr::except($butto, ['youtube 0']);
                $ot = new Buttons($buttons);
                $keyboard = $ot->addButton();
                        foreach ($yout as $val) {
                            $chantit = $val->channelTitle;
                            $title = mb_substr($val->title, 0, 30);
                            $keyboard->row(Keyboard::inlineButton(['text' => $title, 'callback_data' => $val->videoId]));
                        }
                        $keyboard->row(
                                Keyboard::inlineButton(['text' => iconv('UCS-4LE', 'UTF-8', pack('V', 0x1F519)).' Back', 'callback_data' => "Menu"]));
                                $ans = 'founded by title '.$key1;
                                $sendmm = new EditMessa($updfromid,$message_num,$ans,$keyboard);
                                return $sendmm->editMess();
                                
                        break;

//  Вивід характеристик смартфонів ------------------------------------------------------------------------------------------

           
        }
        

    }
}