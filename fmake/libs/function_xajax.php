<?php

/* ------------вспомогательные функции----------------- */

/* ------------вспомогательные функции----------------- */

require_once (ROOT . "/fmake/libs/xajax/xajax_core/xajax.inc.php");
//$xajax = new xajax();
$xajax = new xajax("/index.php");
$xajax->configure('decodeUTF8Input', true);
if($_GET['debug']==1 && $_GET['key']=='5523887') $xajax->configure('debug',true);
$xajax->configure('javascript URI', '/fmake/libs/xajax/');

/* регистрация функции */
$xajax->register(XAJAX_FUNCTION, "addStar");
$xajax->register(XAJAX_FUNCTION, "sendLetter");
$xajax->register(XAJAX_FUNCTION, "moreComments");
$xajax->register(XAJAX_FUNCTION, "getMeetsMain");
/* регистрация функции */

/* написание функции */

function addStar($id_content,$rating){
	$objResponse = new xajaxResponse();
	
	$fmakeRating = new fmakeRating();
	$item = $fmakeRating->getRating($id_content);
	if($item){
		$is_active = $fmakeRating->isRatingCookie($id_content);
		if(!$is_active){
			$fmakeRating->addRatingCookie($id_content);
			
			$new_rating = ((floatval($item['rating'])*intval($item['count'])+$rating)/(intval($item['count'])+1));
			$fmakeRating->setId($id_content);
			$fmakeRating->addParam("rating", round($new_rating,3));
			$fmakeRating->addParam("count", intval($item['count'])+1);
			$fmakeRating->update();
		}	
		
		
	}
	else{
		$fmakeRating->addRatingCookie($id_content);
		$item['rating'] = 0;
		$item['count'] = 0;
		
		$new_rating = ((floatval($item['rating'])*$item['count']+$rating)/($item['count']+1));
		//$objResponse->alert($new_rating);
		$fmakeRating->addParam("id",$id_content);
		$fmakeRating->addParam("rating", round($new_rating,3));
		$fmakeRating->addParam("count", $item['count']+1);
		$fmakeRating->newItem();
		
	}
	
	$item = $fmakeRating->getRating($id_content);
	$item[rating] = round($item[rating]);
	
	$str_active = 1;
	
	$str_star_update = "<div class=\"stars\" disabled-star=\"{$str_active}\" problem-id=\"{$id_content}\" problem-rating=\"{$item[rating]}\" id=\"stars{$id_content}\"></div>";
	
	$script = "$(function(){	
					$('#stars{$id_content}').ratings(5,{$item[rating]},{$str_active}).bind('ratingchanged', function(event, data) {
						addStarRating({$id_content},data.rating);
					});
				});";
	
	$objResponse->assign("div-stars-update{$id_problem}","innerHTML", $str_star_update);
	$objResponse->script($script);
	
	return $objResponse;
}

function sendLetter($email, $msg) {
	$configs = new globalConfigs();

	$msg = trim(nl2br($msg));

	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";//кодировка
	$mail->From = 'support@engels.bz';
	$mail->FromName = 'SUPPORT';
	$mail->AddAddress($configs->email);
	$mail->SetLanguage("ru");
	$mail->isHTML(true);
	$mail->Subject = "Сообщение с сайта engels.bz";
	$mail->Body = "<b>E-mail:</b>{$email}<br/><b>Сообщение :</b>{$msg}";
	$mail->Send();
	
    return true;
}
function moreComments($id_content,$limit,$page) {
	
	$fmakeComments = new fmakeComments();
	$comments = $fmakeComments->getByPage($id_content,$limit,$page,true);
	$count = $fmakeComments->getByPageCount($id_content,true);
	$pages = ceil($count/$limit);
	
	global $twig,$globalTemplateParam;
	$globalTemplateParam->set('comments',$comments);
	$text = $twig->loadTemplate("comments/xajax_add_items.tpl")->render($globalTemplateParam->get());
	$objResponse = new xajaxResponse();
	$objResponse->append("comments","innerHTML", $text);
	if($pages>$page){
		$page = $page+1;
		$objResponse->assign("more_comments", "innerHTML", "<a onclick=\"$('#preloader_comment').show();xajax_moreComments({$id_content},{$limit},{$page});return false;\" href=\"javascript: return false;\">Еще комментарии</a>");
		$objResponse->script("$('#preloader_comment').hide();");
	}
	else{
		$objResponse->assign("block_more_comments", "innerHTML", "");
	}
	return $objResponse;
}

function getMeetsMain($time){
	$meets_obj = new fmakeMeets();
	$limit_meets = 4;
	//$date_meets_to = $time;
	//$date_meets_from = strtotime("+1 day",$date_meets_to);
	//$filter_date = "((b.date >= {$date_meets_to} and b.date <= {$date_meets_from}) or (b.date_from !=0 and b.date <= {$date_meets_to} and b.date_from >= {$date_meets_to}))";	
	
	$date_array = $meets_obj->dateFilter(date('d.m.Y',$time));
	$date_to = $date_array["to"];
	/*отминмаем одну милисекунду чтобы использовать <= к правой границе даты*/
	$date_from = $date_array["from"]-1;
	
	$filter_date = "( ( ( '{$date_to}'<= b.date AND b.date <= '{$date_from}') OR ( '{$date_to}'<= b.date_from AND b.date_from <= '{$date_from}' ) ) OR 
				              ( b.date <= '{$date_to}' AND '{$date_from}' <= b.date_from ) )";
	
	//$meets_obj->order = "b.date DESC, a.id";
	$meets_obj->order = "RAND()";
	//$meets_obj->group_by = "parent";
	$items_meets_main = $meets_obj->getByPageAdmin(4, false,false,"a.`file` = 'item_meets' and {$filter_date} ",true);
	$items_meets_main = $meets_obj->uniqParent($items_meets_main,$limit_meets);
	global $twig,$globalTemplateParam;
	$globalTemplateParam->set('items_meets_main',$items_meets_main);
	$globalTemplateParam->set('meets_obj',$meets_obj);
	$text = $twig->loadTemplate("meets/meets_main.tpl")->render($globalTemplateParam->get());
	
	$date = date('d.m.Y',$time);
	$link_afiwa_more = "<a href=\"{$meets_obj->getLinkPage(4)}?filter[action]=search&filter[check]=true&filter[event_date]={$date}\">Посмотреть все события</a>";
	
	$objResponse = new xajaxResponse();
	$objResponse->assign("meets_main","innerHTML", $text);
	$objResponse->assign("afiwa_more","innerHTML", $link_afiwa_more);
	$objResponse->script("$('#preloader_meets').hide();");
	return $objResponse;
	
}

/* написание функции */

$xajax->processRequest();
$globalTemplateParam->set('xajax', $xajax);