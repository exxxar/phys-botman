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

$botman->hears('hello world', function ($bot) {
    $bot->reply('hello... heloo');
});

$botman->hears('start', BotManController::class.'@startConversation');
$botman->hears('/start', BotManController::class.'@menu');
$botman->hears('cont', BotManController::class.'@cont');
$botman->hears('/menu2', MainMenuController::class.'@menu');

