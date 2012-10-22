<?php
	/*новости*/
	$news_obj = new fmakeSiteModule();
	$limit_news = 5;
	$news_obj->order = "b.date DESC, a.id";
	$items_news_main = $news_obj->getByPageAdmin(2, $limit_news,1,"a.`file` = 'item_news' and `main` = '1'",true);

	$limit_news2 = 9;
	$items_news = $news_obj->getByPageAdmin(2, $limit_news2,1,"a.`file` = 'item_news' and `main` != '1'",true);
	
	
	$parent_news_chitateli = $configs->id_news_chitateli;
	if($parent_news_chitateli){
		$limit_news_chitateli = 3;
		$items_news_chitateli = $news_obj->getByPage($parent_news_chitateli, $limit_news_chitateli,1,"a.`file` = 'item_news'",2,true);
		$globalTemplateParam->set('items_news_chitateli', $items_news_chitateli);
	}
	
	$parent_news_obzor = $configs->id_news_obzor;
	if($parent_news_obzor){
		$limit_news_obzor = 3;
		$items_news_obzor = $news_obj->getByPage($parent_news_obzor, $limit_news_obzor,1,"a.`file` = 'item_news'",2,true);
		$globalTemplateParam->set('items_news_obzor', $items_news_obzor);
	}
	
	$globalTemplateParam->set('news_obj', $news_obj);
	$globalTemplateParam->set('items_news_main', $items_news_main);
	$globalTemplateParam->set('items_news', $items_news);
	/*новости*/
	
	/*фоторепортаж*/
	$limit_photo = 3;
	$photo_obj = new fmakeSiteModule();
	$photo_obj->order = "b.date DESC, a.id";
	//$items_photo = $photo_obj->getByPage(9, $limit_photo,1,"`main` = '1' and a.picture!=''",9,true);
	$items_photo = $photo_obj->getByPageAdmin(9, $limit_photo,1,"a.`file` = 'item_photo_reports' and `main` = '1' and a.picture!=''",true);
	//$items_meets_main = $meets_obj->getByPageAdmin(4, false,false,"a.`file` = 'item_meets' and {$filter_date} ",true);
	//$items_meets_main = $meets_obj->uniqParent($items_meets_main,$limit_meets);
	
	$globalTemplateParam->set('photo_obj', $photo_obj);
	$globalTemplateParam->set('items_photo', $items_photo);
	/*фоторепортаж*/
	
	/*интервью*/
	$limit_interv = 3;
	$interv_obj = new fmakeSiteModule();
	$interv_obj->order = "b.date DESC, a.id";
	$items_interv = $interv_obj->getByPage(12, $limit_interv,1,"`main` = '1' and a.picture!=''",12,true);
	
	$globalTemplateParam->set('interv_obj', $interv_obj);
	$globalTemplateParam->set('items_interv', $items_interv);
	/*интервью*/
	
	/*афиша*/
	$meets_obj = new fmakeMeets();
	
	$items_meets_cats = $meets_obj->getChilds(4,true);
	
	$limit_meets = 4;
	$date = strtotime("today"/*,$tmp_date*/);
	
	$date_array = $meets_obj->dateFilter(date('d.m.Y',$date));
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
	//printAr($items_meets_main);
	for($i=0;$i<40;$i++){
		$time = strtotime("+{$i} day");
		$calendar_meets[$i]['day'] = date('d',$time);
		$calendar_meets[$i]['week'] = $meets_obj->getWeek2(date('w',$time));
		$calendar_meets[$i]['date_full'] = $time;
		//echo date('w',$time);
	}
	//printAr($calendar_meets);
	
	$globalTemplateParam->set('meets_obj', $meets_obj);
	$globalTemplateParam->set('items_meets_main', $items_meets_main);
	$globalTemplateParam->set('items_meets_cats', $items_meets_cats);
	$globalTemplateParam->set('calendar_meets', $calendar_meets); 
	/*афиша*/
	
	/*места*/
	$place_obj = new fmakeSiteModule();
	
	$items_place_cats = $place_obj->getChilds(5,true);
	
	$limit_place = 4; 
	//$place_obj->order = "b.date DESC, a.id";
	$place_obj->order = "RAND()";
	$items_place_main = $place_obj->getByPageAdmin(5, $limit_place,1,"a.`file` = 'item_place' and `main` = '1'",true);

	$globalTemplateParam->set('place_obj', $place_obj);
	$globalTemplateParam->set('items_place_main', $items_place_main);
	$globalTemplateParam->set('items_place_cats', $items_place_cats);
	/*места*/
	
	/*последние комментарии*/
	$limit_comment = 4;
	$fmakeComments = new fmakeComments();
	//$fmakeComments->modul = $include_param_modul;
	$main_comments = $fmakeComments->getByPage(false,$limit_comment,1,true,true);
	$globalTemplateParam->set('main_comments', $main_comments);
	/*последние комментарии*/
	
	/*объявления*/
	
	$advert_obj = new fmakeSiteModule();
	
	$limit_advert = 6; 
	$advert_obj->order = "RAND()";
	$items_advert_main = $advert_obj->getByPageAdmin(796, $limit_advert,1,"a.`file` = 'item_advert'",true);

	$globalTemplateParam->set('advert_obj', $advert_obj);
	$globalTemplateParam->set('items_advert_main', $items_advert_main);
	
	/*объявления*/
	
	$modul->template = "base/main.tpl";
	
?>