<?php
	//printAr($_SESSION['code']); 
	switch ($request->action){
		case 'comments':
			if($user->id){
				$text = $request->getEscape('text');
				//printAr($user_params);
				$name = $user_params['login'];
				//if($_SESSION['code'][$include_param_id_comment]!=md5($request->getEscape('picode'))) $error['comment']['code'] = 'Неправильно введен код';
				if($_SESSION['code']!=md5($_REQUEST['picode'])) $error['comment']['code'] = 'Неправильно введен код';
				//if(!$name) $error['comment']['name'] = 'Введите имя';
				//if(!$request->getEscape('email') || !ereg("^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)*$", $request ->getEscape('email'))) $error['email'] = 'Некорректный  email';
				if(!$text) $error['comment']['text'] = 'Введите сообщение';
				
				if(!$error){
					$post_id = $request->getEscape('id');
					$fmakeComments = new fmakeComments();
					$fmakeComments->addParam("name",$name);
					$fmakeComments->addParam("id_content",$include_param_id_comment);
					$fmakeComments->addParam("id_user",$user->id);
					//$fmakeComments->addParam("modul",$include_param_modul);
					$fmakeComments->addParam("text",$text);
					$fmakeComments->addParam("date",time());
					$fmakeComments->addParam("active",1);
					$fmakeComments->newItem();
					$send_comment = true;
					$globalTemplateParam->set('send_comment',$send_comment);
					$request->name = $request->text = false;
									
					/*$text .= "";
					
					//отправка админам
					$mail = new PHPMailer();
					$mail->CharSet = "utf-8";//кодировка
					$mail->From = "noreplay@{$hostname}";
					$mail->FromName = "Mailing www.".$hostname;
					$mail->AddAddress($configs->email,"Робот-хобот"); 
					//$mail->AddAddress("mamaev.aleksander@gmail.com","Робот-хобот");
					$mail->WordWrap = 50;                                 
					$mail->SetLanguage("ru");
					$mail->IsHTML(true);                                  // с помощью html
					$mail->Subject = "Коммент с  сайта www.".$hostname;
					$mail->Body    = $text;
					$mail->AltBody = $text;
					$mail->Send();*/
				}
				$globalTemplateParam->set('error',$error);
			}
		break;
	}
	
	
	$limit_comment = 10;
	//$page = ($request->page)? $request->page : 1;
	
	$fmakeComments = new fmakeComments();
	//$fmakeComments->modul = $include_param_modul;
	$comments = $fmakeComments->getByPage($include_param_id_comment,$limit_comment,1,true);
	$count = $fmakeComments->getByPageCount($include_param_id_comment,true);
	$pages = ceil($count/$limit_comment);
	if($pages>1) $is_more_link = true;
	else $is_more_link = false;
	//printAr($_SERVER);
	//$url_comment = $_SERVER['REQUEST_URI'];
	//preg_match_all("#^(.*)/page-([0-9]+)(/)?#",$url_comment,$array);
	//printAr($array);
	//$url_comment = ($array[1][0])? $array[1][0].'/' : $url_comment;
	$globalTemplateParam->set('comments',$comments);
	$globalTemplateParam->set('limit_comment',$limit_comment);
	$globalTemplateParam->set('include_param_id_comment',$include_param_id_comment);
	$globalTemplateParam->set('is_more_link',$is_more_link);
	//$globalTemplateParam->set('page',$page);
	//$globalTemplateParam->set('pages',$pages);
	//$globalTemplateParam->set('url_comment',$url_comment);
	