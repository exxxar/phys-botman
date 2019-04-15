<?php

namespace App\Conversations;

use Carbon\Carbon;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Support\Facades\DB;
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
            
                //$departaments_array = array();
                // $query = array(
                //     '1' => "кафедра 1",
                //     '2' => "кафедра 2",
                //     '3' => "кафедра 3",
                //     '4' => "кафедра 4",
                // );//заглушка для создания кнопок кафдры

                $query = DB::table('departments')->select('id', 'name')->get();
                $departaments_array = array();
                foreach($query as $key=>$value){
                    array_push($departaments_array, Button::create($value->name)->value($value->id));
                }
                array_push($departaments_array, Button::create('Назад')->value('back'));
                $question = Question::create("Кафедры")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departaments_array);
            

            return $this->ask($question, function (Answer $answer) {
                if($answer->getValue()==='back'){$this->mainMenuView();}
                elseif(!empty($answer)){
                    $idDepartament = $answer->getValue();
                    //Log::info($idDepartament);
                    $this->DepartmentMenuView($idDepartament);
                }
                else{
                    $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                }
            });
    }

    public function DepartmentMenuView($idDepartament){


    //$query = DB::table('departments')->select('id', 'name')->get();
   // $departament_query = DB::table('departments')->where('id', '=', $idDepartament)->get();

    $departament_column_name = DB::getSchemaBuilder()->getColumnListing("departments");
    //Log::info(gettype($departament_column_name));
    //Log::info($departament_column_name);
    $departament_query_array = (array)$departament_column_name;


/*
    Log::info($departament_query_array);
    foreach($departament_query_array as $key=>$value){
        Log::info($key);
        Log::info($value);
    }
*/    

    /*
    foreach($departament_query_array as $key=>$value){
        Log::info("key: ".$key);
        Log::info($value);
    }
*/

        $pseudonyms_array = array(
            'id' => "Уникальный номер",
            'name' => "Название",
            'info' => "Информация",
            'history' => "История",
            'science' => "Наука",
            'enrollee' => "Абитуриенту",
            'specialty' => "Специальность",
            'educationalMaterials' => "Учбеные материалы",
            'electronicTextbook' => "Электронный учебник",
            'contacts' => "Контакты",
            'teachers' => "Преподаватели",
            'courses' => "Курсы",
            'radioPhysics' => "Радио-Физика",
            'informationSecurity' => "Информационная безопасность",
            'trainingDocuments' => "Учебные документы",
            'olympiad' => "Олимпиады",
            'cooperation' => "Содрудничество",
        ); 
    $departament_buttons_array = array();

    foreach($departament_query_array as $key =>$value){
        if($key != 'id' and !empty($value) ){ //если это не id - его не надо отображать
                                             // и значение не пустое
        array_push($departament_buttons_array, Button::create($pseudonyms_array[$value])->value($value));
        }
    }
    array_push($departament_buttons_array, Button::create('Назад')->value('back')); //добавляем кнопку назад
    $question = Question::create("Кафедры")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departament_buttons_array);
            $cloneIdDepartament = $idDepartament;
            return $this->ask($question, function (Answer $answer) use ($cloneIdDepartament)  {
                if ($answer->isInteractiveMessageReply()) {
                    if($answer->getValue()==='back'){$this->DepartmentsMenuView();}
                    elseif (!empty($answer)){
                    $tableName = $answer->getValue(); //название колонки выбранной кафедры
                    $this->DepartmentView($cloneIdDepartament, $tableName );
                    }
                    else {
                        $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                    }
                }
                });

    

        //$this->say($idDepartament);
    }



    public function DepartmentView($idDepartament, $tableName){
        //Фукнция отображения кафедры
        $idDepartamentInt = (int)$idDepartament; //конвертируем полученный id в инт
        
        /*
            SELECT . $tableName . 
            FROM departaments
            WHERE id = . $idDepartamentInt 
        */

        /** Получаем кафедру по Id */
        $departament = DB::table('departments')->select($tableName)->where('id', '=', $idDepartamentInt)->get();
        $departament_array = (array)$departament[0];
        Log::info($departament_array[$tableName]);
        $this->say('id: ' . $idDepartamentInt);
        $this->say('Table Name:' . $tableName);

        $result = $departament_array[$tableName];

        $question_main = Question::create($result)
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) use ($idDepartament){
            if ($answer->getValue() === 'back') { $this->DepartmentMenuView($idDepartament); }
            else{ $this->say('Неизвестная команда введите /start чтобы вернуться в меню');}

        });



    }


    public function backToMenufunc(){
    $question_main = Question::create("Нажмите чтобы вернуться")
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) {
            if ($answer->getValue() === 'back') { $this->mainMenuView(); }
            else{ $this->say('Неизвестная команда введите /start чтобы вернуться в меню');}

        });

        

    }

    public function backToStartfunc(){
        $question_main = Question::create("Нажмите чтобы вернуться")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([ Button::create('Назад')->value('back'),]);  
    
            
            return $this->ask($question_main, function (Answer $answer) {
                if ($answer->getValue() === 'back') { $this->askReason(); }
                else{ $this->say('Неизвестная команда введите /start чтобы вернуться в меню');}
    
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
            Button::create('Назад')->value('back'),
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

                $aboutPhys = DB::table('staticData')->select('aboutPhys')->get();

                $this->say($aboutPhys[0]->aboutPhys);
                $this->backToMenufunc();
                
            } 
            elseif ($answer->getValue() === 'test') {
                $attachment = new Image('https://cs5.pikabu.ru/post_img/2014/05/16/6/1400226805_964578274.jpg', [
                    'custom_payload' => true,
                ]);
                
                // Build message object
                $message = OutgoingMessage::create('This is my text')
                            ->withAttachment($attachment);
                
                // Reply message object
                $this->say($message);
                
            }

            elseif ($answer->getValue() === 'leadership') {
                //Если выбрали руководство
                /* 
                    Получить информацию о руководстве - TEXT, ожидается array
                    SELECT leadership 
                    FROM StaticData
                    WHERE 1

                */

                $query = DB::table('leaderships')->get();

                foreach($query as $key=>$value){
                    $attachment = new Image($value->image, [
                        'custom_payload' => true,
                    ]); //фото руководителя
                    $name = $value->name; // фио руководителя
                    //$name = $key['name'] . $key['secondname'] . $key['patronymic'];
                    //если в таблице хранится раздельно
                    $position = $value->position; // должность
                    $name_and_pos = 'ФИО: ' . $name . "\nДолжность: " .  $position; //ФИО и должность

                    $message = $name_and_pos;
                    $full_message = OutgoingMessage::create($message)
                            ->withAttachment($attachment); //Генерируем текстовое сообщение с вложением
                        
                    $this->say($full_message);

                }
                $this->backToMenufunc();
                
            }

            
            elseif ($answer->getValue() === 'departments') {
                //Если выбрали кафедры
                $this->DepartmentsMenuView();

            }

            elseif ($answer->getValue() === 'directions') {
                //Если выбрали напрвление подготовки
                $this->say('Направление подготовки');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'scienсу-activity') {
                //Если выбрали напрвление подготовки
                $this->say('Научная деятельность');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'contact') {
                //Если выбрали напрвление подготовки
                $this->say('Контакты');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'back') {
                //назад   
                $this->askReason();
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
                
                    $this->say('Мероприятия физ-теха за 7 дней:');
                    $lastWeek = Carbon::now()->subWeek();
                    $activity = DB::table('events')->where('createAt', '>', $lastWeek)->get();
                    $eventIndex = 1;
                    foreach($activity as $key=>$value){
                        $name = $value->name;
                        $description = $value->description;

                        $this->say('Мероприятие №'.$eventIndex. '. '. $name);
                        $event = 'Заголовок: ' . $name . '. Мероприятие:' .  $description;
                            
                        $this->say($event);
                        $eventIndex++;
                    }

                    $this->backToStartfunc(); 
                } 

                elseif ($answer->getValue() === 'news') {

                    /* 
                    Получить информацию о новостях - TEXT
                    SELECT news 
                    FROM StaticData
                    WHERE 1
                */
                    
                $this->say('Новости физ-теха за 7 дней:');
                    $lastWeek = Carbon::now()->subWeek();
                    $news = DB::table('news')->where('createAt', '>', $lastWeek)->get();
                    $newsIndex = 1;
                    foreach($news as $key=>$value){
                        $name = $value->name;
                        $description = $value->description;

                        $this->say('Новость №'.$newsIndex. '. '. $name);
                        $news = 'Заголовок: ' . $name . '. Новость:' .  $description;
                            
                        $this->say($news);
                        $newsIndex++;
                    }
                    $this->backToStartfunc(); 
                    
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
                    
                $floors = DB::table('physMap')->get();
                
                $this->say('Карта физ-теха');
                foreach($floors as $key=>$value){

                    $attachment = new Image('https://cs5.pikabu.ru/post_img/2014/05/16/6/1400226805_964578274.jpg', [
                        'custom_payload' => true,
                    ]);//сюда ссылку на изображение
                    
                    $floor = $value->floor; // номер этажа
                    $classrooms = $value->classrooms; // Диапазон кабинетов
                    $floorclass = 'Этаж: ' . $floor . '. Кабинеты: ' . $classrooms;

                    $full_card_message = OutgoingMessage::create($floorclass)
                            ->withAttachment($attachment);
                    $this->say($full_card_message);
                }
                   $this->backToStartfunc(); 
                } 
                
                
                else {
                    $this->say('Неизвестная команда');
                    $this->backToStartfunc();
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