<?php
set_time_limit(250);

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
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

$theme = 1;
if($_POST['request']){
    $token = md5(uniqid(rand(), true));
    while(R::findOne('token', 'token = ?', [$token])){
        $token = md5(uniqid(rand(), true));
    }
    echo $token;
    exit;
}
if($_POST['token']) {
    $token = R::findOrCreate('token', [
        'token' => $_POST['token']]);

    if($_POST['typing_last']){
        $token->typing_last = $_POST['typing_last'];
    }

    if($_POST['theme']){
        $theme = $_POST['theme'];
    }

    R::store($token);
    
    if($_POST['msg']){
        $ask = R::dispense('ask');
        $ask->msg = mb_convert_encoding($_POST['msg'], "UTF-8");
        $ask->token = $token;
        $ask->created_at = time();
        $ask->theme = $theme;
        R::store($ask);
    	//$link = askpython($ask->msg, $ask->theme);

    }else{
        $ans = R::findOne('ans', ' active = 1 and token_id = ? order by created_at desc, id desc',[$token->id]);
        if($ans){
            echo "<a href='".$ans->msg."'>".$ans->msg."</a>";
            $ans->active = 2;
            R::store($ans);
        }
    }
}

function askpython($msg, $theme){
    return $msg;
	return "https://rt.ru/support";
	$output = '';
    exec("php /root/scripts/parser.php " . $t->id . " > /dev/null &",$output);
    "D:\Programs\anaconda3\shell\condabin\conda-hook.ps1 ; conda activate 'D:\Programs\anaconda3' ; python \"D:\smart\Desktop\hack\v1\mlscript.py\" \"". $msg ."\" \"". $theme ."\"";
    return $output;
	}


