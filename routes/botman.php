<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');







$botman->hears('hello world', function ($bot) {
    $bot->reply('hello... heloo');
});

$botman->hears('start', BotManController::class.'@startConversation');
$botman->hears('/start', BotManController::class.'@menu');
$botman->hears('cont', BotManController::class.'@cont');
$botman->hears('/menu2', MainMenuController::class.'@menu');

