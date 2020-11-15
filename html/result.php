<?php
require 'rb-mysql.php';
R::setup( 'mysql:host=localhost;dbname=hackaton',
    'hackaton', 'secret' ); //for both mysql or mariaDB

R::freeze( TRUE );
R::ext('xdispense', function($table_name){
    return R::getRedBean()->dispense($table_name);
});
$file = 'people.txt';
// Новый человек, которого нужно добавить в файл
$person = "-----------\n";
// Пишем содержимое в файл,
// используя флаг FILE_APPEND для дописывания содержимого в конец файла
// и флаг LOCK_EX для предотвращения записи данного файла кем-нибудь другим в данное время

file_put_contents($file, $person."\n\n", FILE_APPEND);
if(isset($_POST['answer'])){
    echo 'answer:'. $_POST['answer'];
    file_put_contents($file, 'answer:'. $_POST['answer']."\n\n", FILE_APPEND);
    $ans_arr = [];
    $ans_json = json_decode($_POST['answer'],true);
    if($ans_json){
        foreach ($ans_json as $ans){
            $ask = R::load('ask',$ans['id']);
            if($ask) {
                $ans_db = R::dispense('ans');
                $ans_db->token_id = $ask->token_id;
                $ans_db->msg = $ans['result'];
                $ans_db->created_at = time();
                $ans_db->theme = $ask->theme;
                $ans_db->ask_id = $ask->id;
                R::store($ans_db);
            }
        }
    }
}




