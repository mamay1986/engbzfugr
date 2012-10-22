<?php

	class fmakeSiteModule_multiple extends fmakeCore{
		
		public $idField = "id_parent";
		public $table = "site_modul_multiple_parent";
		
		function isItemParent($parent,$id_site_modul){
			$item = $this->getWhere(array("`id_site_modul` = '{$id_site_modul}'","`{$this->idField}` = '{$parent}'"));
			return $item[0];
		}
		
		function ItemsParent($parent){
			$items = $this->getWhere(array("`{$this->idField}` = '{$parent}'"));
			return $items;
		}
		
		function addItemParent($parent,$id_site_modul){
			$item = $this->isItemParent($parent,$id_site_modul);
			if(!$item){
				$this -> addParam($this->idField,$parent);
				$this -> addParam("id_site_modul",$id_site_modul);
				$this ->newItem();
			}
			return $parent;
		}

		function addParents($arrayParents, $id_site_modul){
			if($arrayParents){
				global $request ;
				$parentsNotDelete = array();
				foreach($arrayParents as $key=>$item){
					$parentsNotDelete[] = $this -> addItemParent($item, $id_site_modul);
				}
			}
			
			// удаляем те что не нужны больше
			//printAr($parentsNotDelete);
			$delete = $this->dataBase->DeleteFromDB( __LINE__ );
			if($parentsNotDelete){
				foreach ($parentsNotDelete as $parentNotDelete){
					$delete -> addWhere("`".$this->idField."` != '".$parentNotDelete."'");
				}
			}
			$delete	-> addTable($this->table) -> addWhere("`id_site_modul`='".$id_site_modul."'") -> queryDB();
		}
		
	}
?>