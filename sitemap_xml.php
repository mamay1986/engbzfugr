<?
header('Content-type: text/html; charset=utf-8'); 

setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
ini_set('set_time_limit',60*5);

if($_GET['key']==1234509876){
	
	require('./fmake/FController.php');
	
	$file = fopen(ROOT."/sitemap.xml","w+");
	
	$text = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9             http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	
</urlset>';
	
	if (fwrite($file, $text) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }
	fclose($file);
	//exit;
	$count_page = 0;
	$count_page_update = 0;
	$fmakeSiteModul = new fmakeSiteModule();
	$fmakeSiteModul->order_as = "ASC";
	$items = $fmakeSiteModul->getAll(true);

	foreach($items as $key=>$item){
		if($item['index']) $url = 'http://'.$hostname.'/';
		else $url = 'http://'.$hostname.$fmakeSiteModul->getLinkPage($item[$fmakeSiteModul->idField]);
		if(!$fmakeSiteModul->SerachUrlXml($url)){
			$fmakeSiteModul->addXml($url);
			$count_page_update++;
		}
		$count_page++;
		//echo 'http://'.$hostname.'/'.$item['redir'].'/<br/>';
	}
	
	echo "Всего страинц в sitemap.xml = ".$count_page."<br/>";
	//echo "Новых страниц страинц в sitemap.xml = ".$count_page_update."<br/>";
}
?>
