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
                if ($answer->isInteractiveMessageReply()) { //если ответ - интерактивный(нажата кнопка)
                if($answer->getValue()==='back'){$this->mainMenuView();} // если нажали назад - вернуться в меню
                elseif(!empty($answer) and is_numeric($answer->getValue())){ // если ответ не пустой и является числом
                    $idDepartament = $answer->getValue(); //получаем id кафедры
                    $this->DepartmentMenuView($idDepartament); //передаем в функцию отображения информации о кафедры id кафедры
                }
                else{  
                    $this->say('Неизвестная команда');
                    $this->mainMenuView();
                }
            }
            else{
                $this->say('Неизвестная команда');
                $this->mainMenuView();
            }
            });
    }

    public function DepartmentMenuView($idDepartament){
    $departament_column_name = DB::getSchemaBuilder()->getColumnListing("departments");
    $departament_query_array = (array)$departament_column_name;


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
        $idDepartamentInt = (int)$idDepartament; //конвертируем id из строки в число
        $departament = DB::table('departments')->select($value)->where('id', '=', $idDepartamentInt)->get(); //получаем столбец из кафедр
        $departament_array = (array)$departament[0]; // конфертирует ответ в массив и берем 1-ый элемент - значение столбца
        if($key != 'id' and !empty($departament_array[$value]) ){ //если это не id - его не надо отображать
                                             // и если значение столбца не пустое
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
                        $this->say('Неизвестная команда');
                        $this->DepartmentsMenuView();
                    }
                }
                else { 
                    $this->say('Неизвестная команда');
                    $this->DepartmentsMenuView();
                }
                });
    }



    public function DepartmentView($idDepartament, $tableName){
        //Фукнция отображения кнопок информации о кафедре
        $idDepartamentInt = (int)$idDepartament; //конвертируем полученный id в инт

        /** Получаем кафедру по Id */
        $departament = DB::table('departments')->select($tableName)->where('id', '=', $idDepartamentInt)->get();
        $departament_array = (array)$departament[0];
        $result = $departament_array[$tableName]; // значение столбца
        $question_main = Question::create($result)
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) use ($idDepartament){
            if ($answer->isInteractiveMessageReply()) { //если ответ - интерактивный(нажата кнопка)
            if ($answer->getValue() === 'back') { $this->DepartmentMenuView($idDepartament); }
            else{ $this->say('Неизвестная команда'); $this->DepartmentMenuView($idDepartament);}
            }
            else{ $this->say('Неизвестная команда'); $this->DepartmentMenuView($idDepartament);}

        });



    }


    public function backToMenufunc(){
    $question_main = Question::create("Нажмите чтобы вернуться")
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'back') { $this->mainMenuView(); }
            else{ $this->say('Неизвестная команда'); $this->mainMenuView();}}
            else{ $this->say('Неизвестная команда'); $this->mainMenuView();}

        });

        

    }

    public function backToStartfunc(){
        $question_main = Question::create("Нажмите чтобы вернуться")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([ Button::create('Назад')->value('back'),]);  
    
            
            return $this->ask($question_main, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'back') { $this->askReason(); }
                else{ $this->say('Неизвестная команда'); $this->askReason();}}
                else{ $this->say('Неизвестная команда'); $this->askReason();}
    
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
                $directions = DB::table('staticData')->select('directions')->get();
                $this->say($directions[0]->directions);
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'scienсу-activity') {
                //Если выбрали напрвление подготовки
                $scientificActivity = DB::table('staticData')->select('scientificActivity')->get();
                $this->say($scientificActivity[0]->scientificActivity);
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'contact') {
                //Если выбрали напрвление подготовки
                $contacts = DB::table('staticData')->select('contacts')->get();
                $this->say($contacts[0]->contacts);
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'back') {
                //назад   
                $this->askReason();
            }

            else {
                $this->say('Неизвестная команда');
                $this->askReason();
            }
        }

        else { //Если ввели текст а не нажали на кнопку
            $this->say('Неизвестная команда');
            $this->askReason();
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

                    $attachment = new Image('../map/'.$value->image, [
                        'custom_payload' => true,
                    ]);//сюда ссылку на изображение
                    
                    $floor = $value->floor; // номер этажа
                    $classrooms = $value->classrooms; // Диапазон кабинетов
                    $floorclass = 'Этаж тест: ' . $floor . '. Кабинеты: '.$floor."01-".$floor. $classrooms;

                    $full_card_message = OutgoingMessage::create($floorclass)
                            ->withAttachment($attachment);

                    $question = Question::create('Посмотреть этаж?')
                        ->addButtons(array(
                            Button::create("Описание")->value('floor_desk '.$floor.' 0'),
                            Button::create("Этаж №${$floor}")->value('floor_more '.$floor.' 0')
                        ));

                    $this->say($full_card_message);
                    $this->say($question);
                }
                    return;
                   $this->backToStartfunc(); 
                } 
                
                
                else {
                    $this->say('Неизвестная команда');
                    $this->backToStartfunc();
                }
            }
            else {
                $this->say('Неизвестная команда');
                $this->askReason();
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