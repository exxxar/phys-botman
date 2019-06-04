<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\DB;

$botman = resolve('botman');


$botman->hears("floor_more ([0-9]+) ([0-9]+)", function ($bot, $floorNum, $page) {

    $floor = DB::table('physMap')
        ->where('floor', $floorNum)
        ->first();


    $classrooms = $floor->classrooms;
    $rooms = [];
    $begin = $page*10<=0?0:$page*10;
    $end = $page*10+10>$classrooms?$classrooms:$page*10+10;
    for ($i=$begin;$i<=$end;$i++){
        $tmpRoom = $i<10?"0".$i:$i;
        array_push($rooms,Button::create("Ауд " . $floorNum.$tmpRoom)->value('room_info ' .$floorNum." ". $floorNum.$tmpRoom));
    }

    array_push($rooms,Button::create("Предидущая подборка")->value("floor_more $floorNum " . ($page - 1 >= 0 ? $page - 1 : 0)));
    array_push($rooms,Button::create("Следующая подборка")->value("floor_more $floorNum " . ($page + 1)));

    $question = Question::create("Аудитории на $floorNum этаже")
        ->addButtons($rooms);

    $bot->reply($question, ["parse_mode" => "Markdown"]);
});


$botman->hears("floor_desk ([0-9]+)", function ($bot, $floorNum) {

    $floor = DB::table('physMap')
        ->where('floor', $floorNum)
        ->first();

    $question = Question::create("Этаж $floorNum: ".($floor->description))
        ->addButtons(
            [
                Button::create("Ауд этажа №$floorNum")->value('floor_more '.$floorNum.' 0')
            ]
        );

    $bot->reply($question, ["parse_mode" => "Markdown"]);

});


$botman->hears("room_info ([0-9]+) ([0-9]+)", function ($bot, $floorNum,$roomNum) {
    $room = DB::table('rooms')
        ->where('number', $roomNum)
        ->first();



    $question = Question::create($room==null?"Описание аудитории $roomNum не найдено в БД":"Ауд $roomNum: ".$room->title."\n".$room->description)
        ->addButtons(
            [
                Button::create("Вернуться к этажу №$floorNum")->value('floor_more '.$floorNum.' 0')
            ]
        );

    $bot->reply($question, ["parse_mode" => "Markdown"]);
});


$botman->hears('start', BotManController::class.'@startConversation');
$botman->hears('/start', BotManController::class.'@menu');
$botman->hears('cont', BotManController::class.'@cont');
$botman->hears('/menu2', MainMenuController::class.'@menu');


$botman->fallback('App\Http\Controllers\FallbackController@index');

