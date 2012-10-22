<?php

/*
CREATE TABLE `advert` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `type_advert` int(11) NOT NULL,
  `price` text NOT NULL,
  `name_user` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `main` enum('0','1') NOT NULL default '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class fmakeNews extends fmakeSiteModule {

	private $array_cat = false;

	function getFilter($filters,$select){
		if($filters)foreach ($filters as $key=>$item){
			if($item){
				switch ($key){
					case 'date':
						switch ($item){
							case 'today':
								$date = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date}'");
								break;
							case 'yesterday':
								$tmp_date = strtotime("-1 day");
								$date1 = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date));
								$date2 = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date1}'") -> addWhere("`{$key}` < '{$date2}'");
								break;
							case 'week':
								$tmp_date = strtotime("-7 day");
								$date1 = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date));
								$date2 = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date1}'") -> addWhere("`{$key}` < '{$date2}'");
								break;
							case 'month':
								$date = mktime(0,0,0,date("m",time()),1,date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date}'");
								break;
							default:
								if($item['to']){
									$array1 = explode('.', $item['to']);
									$date = mktime(0,0,0,intval($array1[1]),intval($array1[0]),intval($array1[2]));	
									$select -> addWhere("b.`{$key}` >= '{$date}'");
								}
								if($item['from']){
									$array2 = explode('.', $item['from']);
									$date = mktime(0,0,0,intval($array2[1]),intval($array2[0]),intval($array2[2]));
									$select -> addWhere("b.`{$key}` <= '{$date}'");
								}
								
								break;
						}
						break;
					case 'parent':
						$tmp = $this->order;
						$this->order = "a.id";
						$parents = $this->getCats($item);
						$select -> addWhere("a.`{$key}` in ({$parents}) ");
						$this->order = $tmp;
						break;
					default:
						$select -> addWhere("a.`{$key}` = '{$item}'");
						break;
				}
			}
		}
	}
	
	function getChilds($id = null, $active = false, $inmenu = false,$where = false,$count_advert = false) {
		//echo('childs '.$type.'<br/>');
		if ($id === null)
			$id = $this->id;

		$select = $this->dataBase->SelectFromDB(__LINE__);

		if ($active)
			$select->addWhere("active='1'");
		if ($inmenu)
			$select->addWhere("inmenu='1'");
		if($where)
			$select->addWhere($where);
		if($count_advert){
			$join_table = " LEFT JOIN (SELECT `parent`,COUNT('*') as count FROM `site_modul` WHERE `active`='1' AND file='item_news' GROUP BY parent) as b ON a.id = b.parent ";
			$fild = ",b.count";
			//echo 'qq';
		}	
			
		return $select->addFild("a.*".$fild)->addFrom($this->table." as a".$join_table)->addWhere("a.`parent`='" . $id . "'")->addOrder($this->order)->queryDB();
	}
	
	function getCatAsTree($parent = 0, $level = 0, $active = false, $inmenu = false, $level_vlojennost = false) {
		//$array = array(2,3,4,6);
		if ($level != $level_vlojennost || !$level_vlojennost) {
			$level++;
			$items = $this->getChilds($parent, $active, $inmenu);
			//printAr($items);
			if ($items) {
				foreach ($items as $item) {
					if ($item['delete_security'] || $item['file']=='item_news')
						continue;
					$item['level'] = $level;
					$this->tree[] = $item;
					$this->getCatAsTree($item['id'], $level, $active, $inmenu, $level_vlojennost);
				}
			}
		}
		return $this->tree;
	}
	
	function getCatForMenu($parent = 0, $active = false, $inmenu = false,$count_advert = false) {
		$items = $this->getChilds($parent, $active, $inmenu, "`file` != 'item_news'",$count_advert);
		//printAr($items);
		if (!$items)
			return;
		foreach ($items as $key => $item) {
			if ($item['delete_security'] || $item['file']=='item_news')
				continue;
			$items[$key]['child'] = $this->getCatForMenu($item['id'], $active, $inmenu,$count_advert);
		}
		return $items;
	}
	function getHtmlSelectCat($parent,$name_select = 'parent',$id_parent = false){
		$items = $this->getCatForMenu($parent);
		$html = "<select name=\"{$name_select}\">";
		if($items)foreach($items as $key=>$item){
			$html .= "<option ".(($id_parent==$item[id])? 'selected': '')." value=\"{$item[id]}\">{$item[caption]}</option>";
			
			if($item['child'])foreach($item['child'] as $c_key=>$c_item){
				$html .= "<option style=\"padding-left:20px;\" ".(($id_parent==$c_item[id])? 'selected': '')." value=\"{$c_item[id]}\">{$c_item[caption]}</option>";
			}
			
			$html .= "</optgroup>";
		}
		$html .= "</select>";
		return $html;
	}
	
	function getCats($parent, $active = false){
		$items = $this->getChilds($parent, $active, false);
		if ($items) {
			foreach ($items as $item) {
				if ($item['delete_security'] || $item['file']=='item_news')
					continue;
				$this->array_cat[] = $item['id'];
				$this->getCats($item['id'], $active);
			}
		}
		//printAr($this->array_cat);
		if($this->array_cat){
			foreach($this->array_cat as $key=>$item){
				if($key==0) $str .= $item;
				else $str .= ",".$item;
			}
		}
		else{
			$str = $parent;
		}
		return $str;
	}
}

?>
