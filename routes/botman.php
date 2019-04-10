<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('test', function ($bot) {
    $bot->reply('test comand!');
});

$botman->hears('Кирилл', function ($bot) {
    $bot->reply('Урезанный функционал....');
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');
