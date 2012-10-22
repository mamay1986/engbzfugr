<?php
/**
 * залогиневание пользователя
 */

$user = new fmakeSiteUser();
// если был залогинен, то загружаем его данные
$user -> load();


if(!$user->isLogined() && $_COOKIE['login']){
	$login = $request->getEscapeVal( $_COOKIE['c_login'] );
	$autication = $request->getEscapeVal( $_COOKIE['c_autication'] );
	if( $user->loginCokie($login, $autication)){
		//echo "Залогинен через куку";
		$message['login'] = 'Залогинен через куку';
	}
}
//printAr($user->isLogined());

if($request->code){
	$vk = new VKapi();
	$vk->login($request->code);
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: /');
}

switch ($request->action){
	case 'login':
		//echo('qq');
		// если уже залогинен
		if($user->isLogined()){
			break;
		}
		
		if( $user->login($request->getEscape('login'), $request->password) ){
			
		 	//echo "Вошел";
		 	$message['login'] = 'Вы вошли';
		 	if($request->save){
		 		$cookies = $user -> getAutication($request->login."_cookies");
		 		$user->addParam('cookies', $cookies );
		 		setcookie("c_login", $request->getEscape('logins'),time()+3600*24*15,"/");
				setcookie("c_autication", $cookies,time()+3600*24*15,"/");		
		 	}
			//unset($_REQUEST['action']);
			//printAr($_REQUEST);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.$_SERVER['REQUEST_URI']);
			//header('Location: /');
		}else{
			$error['password'] = 'Неверный логин - пароль';
			$globalTemplateParam->set('error', $error);
			//printAr($request->modul);
			if($request->modul != 'vhod-na-sajt') header('Location: /vhod-na-sajt/');
		}
		//printAr($error);
	break;
	case 'logout':
		
		// если не залогинен
		if(!$user->isLogined()){
			//header('Location: '.$_SERVER['REQUEST_URI']);
			header('Location: /');
			break;
		}
		
		$user->logout();
	  	setcookie("c_login",'',time()-3600*24*60,"/");
		setcookie("c_autication",'',time()-3600*24*60,"/");
		//header('Location: '.$_SERVER['REQUEST_URI']);
		header('Location: /');
		
	break;
}
//echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if($user->id){
	$fmakeSiteUser = new fmakeSiteUser();
	$fmakeSiteUser->setId($user->id);
	$user_params = $fmakeSiteUser->getInfo();
	
	$fmakeYandexMail = new fmakeYandexMail();
	$token = $fmakeYandexMail->token;
	
	$array = array("token"=>$token,"login"=>$user_params['login']);
	$s = $fmakeYandexMail->apiFunc('get_mail_info',$array);
	//printAr($s);
	$attr = $s->ok->attributes();
	$user_params['message_new'] = $attr['new_messages'];
	
}

$globalTemplateParam->set('user', $user);
$globalTemplateParam->set('user_params', $user_params);
/*echo('qq');
printAr($user_params);
*/	