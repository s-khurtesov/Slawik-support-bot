<?php
require 'rb-mysql.php';
R::setup( 'mysql:host=localhost;dbname=hackaton',
    'hackaton', 'secret' ); //for both mysql or mariaDB

R::freeze( TRUE );
R::ext('xdispense', function($table_name){
    return R::getRedBean()->dispense($table_name);
});
$theme_map = [
    1 => 'Видеонаблюдение',
    2 => 'Домашний телефон',
    3 => 'Интернет',
    4 => 'Мобильная связь',
    5 => 'ТВ',
];

$requests = [
    ['id' => 4, 'msg' => 'Не работает интернет', 'theme' => 'Интернет'],
    ['id' => 5, 'msg' => 'Не работает интернет', 'theme' => 'Мобильная связь'],
    ['id' => 6, 'msg' => 'Не работает интернет', 'theme' => 'ТВ'],
];

$requests = [
];

$new_ask_arr = R::find('ask', ' active = 1');
foreach($new_ask_arr as $new_ask){
    $requests[] = ['id' => $new_ask->id, 'msg' => $new_ask->msg, 'theme' => $theme_map[$new_ask->theme]];
    $new_ask->active = 2;
    R::store($new_ask);
}
echo json_encode($requests);
