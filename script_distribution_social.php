<?php
header('Content-type: text/html; charset=utf-8'); 
setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('display_errors',1);
error_reporting(7);
//error_reporting(E_ALL);


require('./fmake/FController.php');

if($_GET['key']=='1029384756'){
	/*--------выбираем все неопубликованные новости---------*/
	$news_obj = new fmakeSiteModule();
	$news_obj->order = "b.date ASC, a.id";
	$items_news = $news_obj->getByPageAdmin(2, false,false,"a.`file` = 'item_news' and b.`public_social` = '0' ",true);
	/*--------выбираем все неопубликованные новости---------*/
	
	$curl = new cURL();
	$curl -> init();
	/*-------------публикуем---------------*/
	if($items_news)foreach($items_news as $key=>$item){
		
		/*-----------------публикация vkontakte.ru--------------------*/
		$curl -> get($hostname."/vk.php?key=1029384756&id_news={$item['id']}");
		//echo("Публикация в VK.com {$item['id']}<br/>");
		/*-----------------публикация vkontakte.ru--------------------*/
		
		/*-----------------публикация twitter.com--------------------*/
		$curl -> get($hostname."/twitter.php?action=post_news&key=1029384756&id_news={$item['id']}"); 
		//echo("Публикация в TWITTER.com {$item['id']}<br/>");
		/*-----------------публикация twitter.com--------------------*/
		
		/*-------обновляем параметр новости что запись опубликована------*/
		$fmakeTypeTable = new fmakeTypeTable();
		$news_obj_dop = new fmakeTypeTable();
		$news_obj_dop->table = $fmakeTypeTable->getTable(2);
		$news_obj_dop->setId($item['id']);
		$news_obj_dop->addParam('public_social',1);
		$news_obj_dop->update();
		echo("UPDATE параметра публикации {$item['id']}<br/>");
		/*-------обновляем параметр новости что запись опубликована------*/
		
	}
	/*-------------публикуем---------------*/
	echo 'Ok';
}