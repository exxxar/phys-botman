<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('test', function ($bot) {
    $bot->reply('test comand!');
});

$botman->hears('класс', function ($bot) {
    $bot->reply('КАК ТЫ НАДОЕЛ!');
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');
