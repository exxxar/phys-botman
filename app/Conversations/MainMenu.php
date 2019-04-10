<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class MainMenu extends Conversation
{
    //Функция для выбора кафедры
    public function DepartmentsMenuView(){
         /*
                    Получить информацию о руководстве - TEXT, ожидается array
                    SELECT id, name 
                    FROM departments
                    WHERE 1
                */

                /*
                    Создаем массив кнопок с названием кафедр
                    id - уникальный индетефикатор кафедры
                    name - назание кафедры
                */

                /*
                    $departaments_array = array();
                    foreach($query as $key =>$value){
                                array_push($departaments_array, Button::create($value)->value($key));
                            }
                */
            
                $departaments_array = array();
                $query = array(
                    '1' => "кафедра 1",
                    '2' => "кафедра 2",
                    '3' => "кафедра 3",
                    '4' => "кафедра 4",
                );//заглушка для создания кнопок кафдры

                foreach($query as $key =>$value){
                    array_push($departaments_array, Button::create($value)->value($key));
                }
                $question = Question::create("Стартовое меню")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departaments_array);
            $this->say('hello ask');

            return $this->ask($question, function (Answer $answer) {
                //
            });
    }

    public function mainMenuView(){

        $question_main = Question::create("Главное меню")
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('О физ-техе')->value('about'),
            Button::create('Руководство')->value('leadership'),
            Button::create('Кафедры')->value('departments'),
            Button::create('Направления подготовки')->value('directions'),
            Button::create('Научная деятельность')->value('scienсу-activity'),
            Button::create('Контакты')->value('contact'),
        ]);

    

    return $this->ask($question_main, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'about') {
                
                /* 
                    Получить информацию о физ-техе - TEXT
                    SELECT aboutPhys 
                    FROM StaticData
                    WHERE 1
                
                */

                $this->say('О физтехе');
                
            } 
            elseif ($answer->getValue() === 'leadership') {
                //Если выбрали руководство
                /* 
                    Получить информацию о руководстве - TEXT, ожидается array
                    SELECT leadership 
                    FROM StaticData
                    WHERE 1

                */

                /*
                    foreach($query as $key){
                        $attachment = new Image($key['image']); // фото руководителя
                        $name = $key['name']; // фио руководителя
                        $name = $key['name'] . $key['secondname'] . $key['patronymic'];
                        //если в таблице хранится раздельно
                        $position = $key['position']; // должность
                        $name_and_pos = 'ФИО: ' . $name . '\nДолжность' .  $position;
                        $message = OutgoingMessage::create( $name_and_pos )
                        ->withAttachment($attachment);
                         $bot->reply($message);
                    }
                */
                $this->say('Кафедры');
                
            }

            
            elseif ($answer->getValue() === 'departments') {
                //Если выбрали кафедры
                $this->DepartmentsMenuView();

            }

            else {
                $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
            }
        }
    });
        
    }

    // Функция стартового меню
    public function askReason()
    {
        $question = Question::create("Стартовое меню")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Главное меню')->value('main-menu'),
                Button::create('Мероприятия физ-теха')->value('activity'),
                Button::create('Новости физ-теха')->value('news'),
                Button::create('Карта физ-теха')->value('card'),
            ]);

        

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'main-menu') {
                    $this->mainMenuView();
                } 

                elseif ($answer->getValue() === 'activity') {

                    /* 
                    Получить информацию о мероприятиях - TEXT
                    SELECT activity 
                    FROM StaticData
                    WHERE 1
                */

                    $this->say('Мероприятия физ-теха');
                    
                } 

                elseif ($answer->getValue() === 'news') {

                    /* 
                    Получить информацию о новостях - TEXT
                    SELECT news 
                    FROM StaticData
                    WHERE 1
                */
                
                    $this->say('Новости физ-теха');
                    
                } 
                elseif ($answer->getValue() === 'card') {

                    /* 
                    Получить информацию о карте - TEXT
                    Карта физ-теха отдельная таблица
                    floor - этаж 
                    classrooms - диапазон формата 100-999 - CHAR(7)
                    SELECT news 
                    FROM StaticData
                    WHERE 1
                */



                /*
                    foreach($query as $key){
                        $floor = $key['floor']; // номер этажа
                        $classrooms = $key['classrooms']; // Диапазон кабинетов
                        $floorclass = 'Этаж: ' . $floor . 'Кабинеты: ' . $classrooms;
                        $this->say($floorclass);
                    }
                */
                
                    $this->say('Карта физ-теха');
                    
                } 
                
                
                else {
                    $this->say('Неизвестная команда');
                }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }

}