<?php

	if ($user->isLogined()){		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: /");
	}

	$fmakeYandexMail = new fmakeYandexMail();
	$token = $fmakeYandexMail->token;
	$domain = $fmakeYandexMail->domain;
	$error = false;
	
	switch ($_POST['action']){
		case 'register':
			$error = false;
			$array = array("token"=>$token,"login"=>$_POST['login']);
			$s = $fmakeYandexMail->apiFunc('get_user_info',$array);
			//print_r($s);
			if($s->error){
				if($_POST['login'] && strlen($_POST['login'])>=3){
					if($_POST['password']==$_POST['password_succed']){
						if(strlen($_POST['password'])>=6 && strlen($_POST['password'])<=20){						
							
							$array = array("token"=>$token,"u_login"=>$_POST['login'],"u_password"=>$_POST['password']);
							$s = $fmakeYandexMail->apiFunc('reg_user_token',$array);
							
							if($s->error){
								$error['warning_yandex'] = "Незарегистрировали сбой в системе.";
							}
							else{
								
								$fmakeSiteUser = new fmakeSiteUser();
								$fmakeSiteUser->addParam('login',$_POST['login']);
								$fmakeSiteUser->addParam('password',md5($_POST['password']));
								$fmakeSiteUser->newItem();
								
								$registration_true = true;
								if( $user->login($request->getEscape('login'), $request->password) ){
			
									//echo "Вошел";
									if($request->save){
										$cookies = $user -> getAutication($request->login."_cookies");
										$user->addParam('cookies', $cookies );
										setcookie("c_login", $request->getEscape('logins'),time()+3600*24*15,"/");
										setcookie("c_autication", $cookies,time()+3600*24*15,"/");		
									}
									
									//$fmakeSiteUser = new fmakeSiteUser();
									$fmakeSiteUser->setId($user->id);
									$user_params = $fmakeSiteUser->getInfo();
									
									$array = array("token"=>$token,"login"=>$user_params['login']);
									$s = $fmakeYandexMail->apiFunc('get_mail_info',$array);
									//printAr($s);
									$attr = $s->ok->attributes();
									$user_params['message_new'] = $attr['new_messages'];
									
									$globalTemplateParam->set('user', $user);
									$globalTemplateParam->set('user_params', $user_params);
								}
														
							}
						}
						else{
							$error['lenght'] = "Пароль должен быть от 6 до 20 символов";
						}
					}
					else{
						$error['log_and_pass'] = "Неверно повторили пароль";
					}
				}
				else{
					$error['login'] = "Поле Логин обязательно для заполнения. Больше 3 символов.";
				}
			}
			else{
				$error['login'] = "Данный логин уже занят";
			}
						
			break;
		default:
			break;
	}

	
	$breadcrubs = $modul->getBreadCrumbs($modul->id);	
	
	$globalTemplateParam->set('domain', $domain);
	$globalTemplateParam->set('registration_true', $registration_true);
	$globalTemplateParam->set('error', $error);
	$globalTemplateParam->set('breadcrubs', $breadcrubs);
	$modul->template = "register/main.tpl";
	
?>