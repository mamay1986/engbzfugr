<?php
header('Content-type: text/html; charset=utf-8'); 
setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('display_errors',1);
error_reporting(7);
//error_reporting(E_ALL);


require('./fmake/FController.php');

$user = new fmakeSiteUser();
// если был залогинен, то загружаем его данные
$user -> load();
if($user->isLogined()){
	$login = $_GET['login'];

	if($user->id){
		$fmakeSiteUser = new fmakeSiteUser();
		$fmakeSiteUser->setId($user->id);
		$user_params = $fmakeSiteUser->getInfo();		
	}
	if($user_params['login']==$login){
		$fmakeYandexMail = new fmakeYandexMail();
		$token = $fmakeYandexMail->token;
		$domain = $fmakeYandexMail->domain; 
		$array = array("token"=>$token,"domain"=>$domain,"login"=>$login);
		$s = $fmakeYandexMail->apiFunc('api/user_oauth_token',$array);

		$oauth_token = $s->domains->domain->email->{'oauth-token'};

		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://passport.yandex.ru/passport?mode=oauth&type=trusted-pdd-partner&error_retpath=fmake.ru&access_token={$oauth_token}");
	}
	else{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://mail.yandex.ru/for/fmake.ru");
	}
}
else{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://mail.yandex.ru/for/fmake.ru");
}