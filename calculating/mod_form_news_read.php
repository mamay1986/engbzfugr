<?php

	$breadcrubs = $modul->getBreadCrumbs($modul->id);	

	switch($request->action){
		case 'add_news':
			//обработка формы и добавление
			$error = false;

			//if(!$request->getEscape('parent')) $error['parent'] = "Выберите категорию";
			if(!$request->getEscape('caption')) $error['caption'] = "Введите название новости";
			//if(!$request->getEscape('email') || !ereg("^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)*$", $request->getEscape('email'))) $error['email'] = "Некорректный email";
			//if(!$request->getEscape('phone')) $error['phone'] = "Введите телефон";
			if(!$request->getEscape('text')) $error['text'] = "Введите текст новости";
			
			
			if(!$error){
				$fmakeSiteModulRelation = new fmakeSiteModule_relation();
				$fmakeNews = new fmakeSiteModule();

				$fmakeNews_dop = new fmakeTypeTable();
				$fmakeNews_dop->table = $fmakeNews_dop->getTable(2);
				
				$id_parent = 638;
				
				$fmakeNews->addParam("parent",$id_parent);
				$fmakeNews->addParam("caption",$request->getEscape('caption'));
				$fmakeNews->addParam("title",$request->getEscape('caption'));
				$fmakeNews->addParam("redir",$fmakeNews->transliter($request->getEscape('caption')));
				$fmakeNews->addParam("text",$request->text);
				$fmakeNews->addParam("file","item_news");
				$fmakeNews->addParam("active",0);
				$fmakeNews->newItem();
				
				$fmakeSiteModulRelation->setPageRelation($id_parent, $fmakeNews->id);
				
				$item_info = $fmakeNews->getInfo();
				$fmakeNews->addParam("redir", $item_info['redir'].$fmakeNews->id);
				$fmakeNews->update();
				
				$fmakeNews_dop->addParam("id", $fmakeNews->id);
				$fmakeNews_dop->addParam("date", time());
				$fmakeNews_dop->addParam("contact_info", $request->getEscape('contact_info'));
									
				$fmakeNews_dop->newItem();
				
				if($_FILES['image']['tmp_name'])
					$fmakeNews->addFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
				
				$add_ok_news = true;
				$add_ok_news_id = $fmakeNews->id;
				
				$text = "Пришла на модерацию новая новость от читателей. <a href=\"http://engels.bz/admin/?modul=news&filter[parent]=638\">{$request->getEscape('caption')}</a>";
				$mail = new PHPMailer();
				
				include ROOT."/fmake/libs/PHPMailer/connect_smtp.php";
				
				$mail->CharSet = "utf-8";//кодировка
				$mail->From = "info@{$hostname}";
				$mail->FromName = $hostname;
				$mail->AddAddress($configs->email);
				//$mail->AddAddress("mamaev.aleksander@gmail.com");
				$mail->WordWrap = 50;                                 
				$mail->SetLanguage("ru");
				$mail->IsHTML(true);
				$mail->Subject = "Engels.bz - Новость от читателей.";
				$mail->Body    = $text;
				
				//$mail->AltBody = "Если не поддерживает html";
				$mail->Send();
				
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: '.$_SERVER['REQUEST_URI'].'&addnews=1');
				
			}else{
				$globalTemplateParam->set('error', $error);
			}
			break;
		default:
		
			break;
	}
	
	$globalTemplateParam->set('breadcrubs', $breadcrubs);
	$modul->template = "news/form.tpl";
	
?>