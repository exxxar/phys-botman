<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;

class MainMenu extends Conversation
{
    //Функция для выбора кафедры
    public function DepartmentsMenuView(){
        $idDepartament = "";
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
                $question = Question::create("Кафедры")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departaments_array);

            return $this->ask($question, function (Answer $answer) {
                if(!empty($answer)){
                    $idDepartament = $answer->getValue();
                    Log::info('111----------------111');
                    Log::info($idDepartament);
                    Log::info('111----------------222');
                    $this->DepartmentMenuView($idDepartament);
                }
                else{
                    $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                }
            });
    }


    //Функция для просмотра меню одной кафедры
    public function DepartmentMenuView($answerDepartament){
        Log::info('111----------------+++++++111');
        Log::info($answerDepartament);
        Log::info('111----------------+++++++222');
        //$answerDepartament (string) - хранится id кафедры
        /*
             SELECT *
                    FROM departments
                    WHERE id = $answerDepartament //конвертировать в int
        */

        /*

        */
        

        $query_department ="";
        $department_button_array = array();

        $department_query_array = array(
                'id' => 1,
                'name' => "kafedra takaya",
                'about' => "about kafedra",
                'history' => "history kafedra",
        );
        foreach($department_query_array as $key =>$value){
            if($key != 'id' and !empty($value) ){ //если это не id - его не надо отображать
                                                 // и значение не пустое
            array_push($department_button_array, Button::create($value)->value($key)); //то добавляем кнопки
            }
        }
        $question_department = Question::create("Кафедра")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($department_button_array);

            return $this->ask($question_department, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if (!empty($answer)){
                        Log::info("---------------------1");
                        Log::info($answerDepartament);
                        Log::info("---------------------2");
                        $departamentId = $answerDepartament;
                        $this->AboutDepartmentView($departamentId, $answer->getValue());
                    } 
                    else {
                        $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                    }
                }
            });

    }

    public function DepartmentAboutView($stringDepartamentName){
        $this->say($stringDepartamentName);
    }

    public function AboutDepartmentView($id_departament, $string_departament_name){
        
        
        //$id_departament (string) - id кафедры - хранится id кафедры
        //$string_departament_name (string) - хранится колонка из таблицы базы данных, нужна для запроса
        
        
        
        $id_departament_int = (int)$id_departament; //получаем из Answer значение и конвертируем в int
        $string_departament_name = $string_departament_name;
        
        
        /*
            SELECT FROM departament  $string_departament_name
            WHERE id = $id_departament_int
        */
        
       $result = $id_departament . " " . $string_departament_name;
       $this->say($result);
        
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