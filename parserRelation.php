<?php
header('Content-type: text/html; charset=utf-8'); 
setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('display_errors',1);
error_reporting(7);
date_default_timezone_set('Europe/Moscow');
//error_reporting(E_ALL);



require('./fmake/FController.php');

/*
 * востановление таблицы relation
 **/ 
$fmakeSiteModulRelation = new fmakeSiteModule_relation();
$fmakeSiteModul = new fmakeSiteModule();
$items = $fmakeSiteModul->getAll();

if($items)foreach ($items as $key=>$item){
	$fmakeSiteModulRelation->setPageRelation($item['parent'],$item[$fmakeSiteModul->idField]);
}
