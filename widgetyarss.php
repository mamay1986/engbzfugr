<?php
header('Content-type: text/html; charset=utf-8'); 
setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('display_errors',1);
error_reporting(7);
date_default_timezone_set('Europe/Moscow');

require('./fmake/FController.php');

$news_obj = new fmakeSiteModule();

$date = strtotime("-7 day",time());
$date = strtotime("today",$date);
//$limit_newsrss = 9;
$rssnews = $news_obj->getByPageAdmin(2, false,false,"a.`file` = 'item_news' and b.`date`>= '{$date}'",true);

$globalTemplateParam->set('rssnews', $rssnews);
$globalTemplateParam->set('news_obj', $news_obj);

$template = "widgetya/main.tpl";

$template = $twig->loadTemplate($template);
$template->display($globalTemplateParam->get());