<?php
$error['interview'] = "";
if(!$request->response) $error['interview']['response'] = "Отметьте хотя бы 1 вариант";
if(!$error['interview']){
	$_fmakeInterview = new fmakeInterview();
	$_fmakeInterview->table = $_fmakeInterview->table_vopros;
	$_fmakeInterview->setId($request->response);
	$interview_item = $_fmakeInterview->getInfo(); 
	$_fmakeInterview->addParam('stat', intval($interview_item['stat'])+1);
	$_fmakeInterview->update();
	setcookie("interview".$interview['id'],1,time()+60*60*24*30,'/',$hostname);
	$iscookie = true;
	$globalTemplateParam->set('iscookie',$iscookie);
}
else{
	$globalTemplateParam->set('error',$error); 
}
?>