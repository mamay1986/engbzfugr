<?php
header('Content-type: text/html; charset=utf-8'); 
setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('display_errors',1);
error_reporting(7);
date_default_timezone_set('Europe/Moscow');
//error_reporting(E_ALL);


/*---------------время генерации страницы--------------------*/
// считываем текущее время
$start_time = microtime();
// разделяем секунды и миллисекунды (становятся значениями начальных ключей массива-списка)
$start_array = explode(" ",$start_time);
// это и есть стартовое время
$start_time = $start_array[1] + $start_array[0]; 
/*---------------время генерации страницы--------------------*/

require('./fmake/FController.php');
require('./fmake/libs/function_xajax.php');
require('./fmake/libs/login.php');

$modulObj = new fmakeAdminController();
$admin = $modulObj->getUserObj();
$admin->load();

if ($configs->site_on_off == '1' ){
	
	switch ($request->action){
		case 'feedback':
			$fmakeFeedback = new fmakeFeedback();
			$error = false;
			if(!trim($request ->email) || !ereg("^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)*$", $request ->email)) $error['email'] = "Некорректный Email";
			if($fmakeFeedback->isEmail(trim($request ->email))) $error['duble'] = "Данный email уже записан";
			if(!$error){
				$fmakeFeedback->addParam("email",$request->email);
				$fmakeFeedback->newItem();
				$message = "Ты узнаешь первым!";
				$globalTemplateParam->set('message',$message);
			}else {
				$globalTemplateParam->set('errors',$error);
			}
			
			break;
	}
	if(!$admin->isLogined()){
		$modul->template = "zagluwka/main.tpl";

		$template = $twig->loadTemplate($modul->template);
		$template->display($globalTemplateParam->get());
		exit();
	}
}

/*---------опрос-----------*/
$fmakeInterview = new fmakeInterview();
$interview = $fmakeInterview->getInterview();
if($iscookie = $fmakeInterview->isCookies($interview['id'])){
	//echo('qq');
}
else{
	if($request->action == 'interview_right'){
		include 'helpModules/interview.php';
	}
}

$fmakeInterview->table = $fmakeInterview->table_vopros;
$vopros = $fmakeInterview->getVoproses($interview['id'],true);
$globalTemplateParam->set('vopros',$vopros);
$globalTemplateParam->set('interview',$interview);
$globalTemplateParam->set('iscookie',$iscookie);
/*---------опрос-----------*/

/*---------курс валют----------*/
$cache = new cacheValue();
if(!$cache->isCache("usd_valuta")){
	$date = date("d/m/Y",time());
	$xmlParser = new xmlParser();
	$array = $xmlParser->fileXmlToArray("http://www.cbr.ru/scripts/XML_daily.asp?date_req={$date}");
	
	if($array['Valute'])foreach($array['Valute'] as $key=>$item){
		if($item['CharCode'] == 'USD'){
			$ar = explode(",",$item['Value']);
			$usd_valuta = $cache->addCache("usd_valuta",$ar[0].".".substr($ar[1],0,2));
		}
		if($item['CharCode'] == 'EUR'){
			$ar = explode(",",$item['Value']);
			$eur_valuta = $cache->addCache("eur_valuta",$ar[0].".".substr($ar[1],0,2));
		}
	}
}
else{
	$usd_valuta = $cache->getCacheValue("usd_valuta");
	$eur_valuta = $cache->getCacheValue("eur_valuta");
}

$globalTemplateParam->set('usd_valuta',$usd_valuta);
$globalTemplateParam->set('eur_valuta',$eur_valuta);
/*---------курс валют----------*/


$modul = new fmakeSiteModule();
$globalTemplateParam->set('site_obj',$modul);
$url = $request -> getEscape('url') ? $request -> getEscape('url') : $request -> getEscape('modul');
$url = explode('/', $url);
$url = $url[0];

//printAr($_REQUEST);
//echo $request -> getEscape('modul');
$modul->getPage($request -> getEscape('modul') , $twig, $url);
//добавляем каталог к основным модулям
$menu = $modul->getAllForMenu(0, true,$q=true,$flag=true,true);
//printAr($menu);
$request_uri = $_SERVER['REQUEST_URI'];

/*--------правый блок с последними новостями--------*/
$limit_news_right = 7;
$tmp_order = $modul->order;
$modul->order = "b.date DESC, a.id";
$news_right_block = $modul->getByPageAdmin(2, $limit_news_right,1,"a.`file` = 'item_news'",true);
$modul->order = $tmp_order;
$globalTemplateParam->set('news_right_block',$news_right_block);
/*--------правый блок с последними новостями--------*/

/*--------фоторепортаж--------*/
/*    $reports_obj = new fmakePhotoReports();
	$reports = $reports_obj->getReports(true,0,1,true);
        $reports_url = $reports_obj->getUrlReports();
	$globalTemplateParam->set('reports_url', $reports_url);
	$globalTemplateParam->set('reports',$reports);*/
/*--------фоторепортаж--------*/

/*--------интервью--------*/
/*    $reports_obj = new fmakeIntervu();
	$reports = $reports_obj->getReports(true,0,1,true);
        $reports_url = $reports_obj->getUrlReports();
	$globalTemplateParam->set('interv_url', $reports_url);
	$globalTemplateParam->set('interv',$reports);*/
/*--------интервью--------*/

//printAr($_SESSION['code']);

$time_new = date('d',time())." ".$modul->getMounth(date("m",time()))." ".date('Y',time())." ".date('H:i',time());
$globalTemplateParam->set('time_new',$time_new);
$globalTemplateParam->set('request_uri',$request_uri);
$globalTemplateParam->set('menu',$menu);
$globalTemplateParam->set('url',$url);
$globalTemplateParam->set('modul',$modul);

$modul->template = "base/main.tpl";

if($modul->file){
	include($modul->file.".php");
} 

/*---------------время генерации страницы--------------------*/
// делаем то же, что и в start.php, только используем другие переменные
$end_time = microtime();
$end_array = explode(" ",$end_time);
$end_time = $end_array[1] + $end_array[0];
// вычитаем из конечного времени начальное
$time = $end_time - $start_time;
$time = round($time,2);
// выводим в выходной поток (броузер) время генерации страницы
$generate_page_time = "Страница сгенерирована за {$time} секунд"; 
$globalTemplateParam->set('generate_page',$generate_page_time);
/*---------------время генерации страницы--------------------*/

$template = $twig->loadTemplate($modul->template);
$template->display($globalTemplateParam->get());
