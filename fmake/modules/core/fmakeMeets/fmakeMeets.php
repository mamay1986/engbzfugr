<?php
class fmakeMeets extends fmakeSiteModule{
	
	function dateFilter($date){
		switch($date){
			case 'today': 
				$date_to = strtotime("today");
				$date_from = strtotime("+1 day",$date_to);
				break;
			case 'yersterday': 
				$date_to = strtotime("-1 day",strtotime("today"));
				$date_from = strtotime("today");
				break;
			case 'week': 
				$date_to = strtotime("today");
				$date_from = strtotime("+8 days",$date_to);
				break;
			case 'month': 
				$date_to = strtotime("today");
				$date_from = strtotime("+1 month",$date_to);
				break;
			default:
				if(preg_match("/(\d{2})\.(\d{2})\.(\d{4})/", $date)){
					$date_to = strtotime($date." 00:00:00");
					$date_from = strtotime("+1 day",$date_to);
				}
				break;
		}
		return array("to"=>$date_to,"from"=>$date_from);
	}
	
	function setSearch($search_string,$date,$category,$parent, $limit, $page, $where = "a.`file` = 'item_meets'", $active = true) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		if($search_string){
			$search_string = mysql_real_escape_string($search_string);
			//$where_search_string = "MATCH(a.caption,a.text) AGAINST ('{$search_string}')";
			$where_search_string = "( a.`caption` LIKE '%{$search_string}%' OR a.`text` LIKE '%{$search_string}%' OR  b.`anons` LIKE '%{$search_string}%' )";
			//$where = sprintf($where, );
			$select->addWhere($where_search_string);
		}
		/*
			ALTER TABLE `site_modul` as a LEFT JOIN `meets` as b ON a.id=b.id ADD FULLTEXT (
				a.`caption`,a.`text`,b.`anons`
			);
		*/
		
		if($category){
			//$where = "a.parent = '{$category}'";
			$select->addWhere("a.parent = '{$category}'");
		}
		
		if($date){
			$date_array = $this->dateFilter($date);
			$date_to = $date_array["to"];
			$date_from = $date_array["from"];
			//echo date('d.m.Y H:i:s',$date_to)." - ".date('d.m.Y H:i:s',$date_from)."<br/>";
			/*отминмаем одну милисекунду чтобы использовать <= к правой границе даты*/
			$date_from = $date_from-1;
			
			//echo date('d.m.Y H:i:s',$date_to)."({$date_to}) - ".date('d.m.Y H:i:s',$date_from)."({$date_from})";
			if($date_to || $date_from){
				$str_where = "( ( ( '{$date_to}'<= b.date AND b.date <= '{$date_from}') OR ( '{$date_to}'<= b.date_from AND b.date_from <= '{$date_from}' ) ) OR 
				              ( b.date <= '{$date_to}' AND '{$date_from}' <= b.date_from ) )";
				$select->addWhere($str_where);
			}
		}	
			
		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		if($this->order) 
				$select->addOrder($this->order, DESC);
		//echo $table_join;
		return $select->addFild("a.*,b.*,c.caption name_category")->addFrom($this->table . $table_join)->addLimit((($page - 1) * $limit), $limit)->queryDB();
	}
	function setSearchCount($search_string,$date,$category,$parent, $where = "a.`file` = 'item_meets'", $active = true) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		if($search_string){
			$search_string = mysql_real_escape_string($search_string);
			//$where_search_string = "MATCH(a.`caption`, a.`text`) AGAINST ('{$search_string}')";
			$where_search_string = "( a.`caption` LIKE '%{$search_string}%' OR a.`text` LIKE '%{$search_string}%' OR  b.`anons` LIKE '%{$search_string}%' )";
			//$where = sprintf($where, );
			$select->addWhere($where_search_string);
		} 
		
		if($category){
			//$where = "a.parent = '{$category}'";
			$select->addWhere("a.parent = '{$category}'");
		}
		
		if($date){
			$date_array = $this->dateFilter($date);
			$date_to = $date_array["to"];
			$date_from = $date_array["from"];
			
			//echo date('d.m.Y H:i:s',$date_to)." - ".date('d.m.Y H:i:s',$date_from)."<br/>";
			/*отминмаем одну милисекунду чтобы использовать <= к правой границе даты*/
			$date_from = $date_from-1;
			
			//echo date('d.m.Y H:i:s',$date_to)."({$date_to}) - ".date('d.m.Y H:i:s',$date_from)."({$date_from})";
			if($date_to || $date_from){
				$str_where = "( ( ( '{$date_to}'<= b.date AND b.date <= '{$date_from}') OR ( '{$date_to}'<= b.date_from AND b.date_from <= '{$date_from}' ) ) OR 
				              ( b.date <= '{$date_to}' AND '{$date_from}' <= b.date_from ) )";
				$select->addWhere($str_where);
			}
		}	
			
		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		$result = $select->addFild("COUNT(*)")->addFrom($this->table . $table_join)->queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function uniqParent($array,$limit){
		$result = false;
		$use_parent = array();
		$i = 0;
		if($array)foreach($array as $key=>$item){
			if(!in_array($item['parent'],$use_parent)){
				$result[] = $item;
				$use_parent[] = $item['parent'];
				$i++;
			}
			if($i>=$limit) return $result;
		}
		
		return $result;
	}
} 