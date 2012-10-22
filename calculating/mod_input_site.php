<?php

	if ($user->isLogined()){		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: /");
	}
	
	
	$breadcrubs = $modul->getBreadCrumbs($modul->id);	
	
	$globalTemplateParam->set('domain', $domain);
	$globalTemplateParam->set('breadcrubs', $breadcrubs);
	$modul->template = "register/input.tpl";
	
?>