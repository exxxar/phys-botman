<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use App\Http\Controllers\Controller;
use App\Conversations\DefaultConversation;
use App\Conversations\MaunMenu;




class MainMenuController extends Controller
{

    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }


    public function menu(BotMan $bot){
        $bot->startConversation(new MainMenu());
    }
}
