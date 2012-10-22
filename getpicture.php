<?
	session_start();
	
	require('fmake/FController.php');
	//echo('qq');
	$obj = new utlPicture();
	$obj->genPic();
	$fmakeSiteModule = new fmakeSiteModule();
	$fmakeSiteModule->setRedir($request->modul);
    $page_info = $fmakeSiteModule->getInfo();
	//$_SESSION['code'][$page_info['id']] = md5($obj->getLine());
	$_SESSION['code'] = md5($obj->getLine()); 
	//printAr($_SESSION);
?>