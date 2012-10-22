<?php
class fmakeTypeTable extends fmakeCore{
	public $table = "type_and_table";
	
	function getTable($type) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$result = $select->addFrom($this->table)->addWhere("`type` = '".$type."'")->queryDB();
		return $result[0]['table'];
	}

	function getFieldsTable($type){
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$result = $select->addFrom($this->getTable($type))->addLimit(0,1);
		return $result[0];
	}
	
	function newItem(){
		$insert = $this->dataBase->InsertInToDB(__LINE__);	
			
		$insert	-> addTable($this->table);
		$this->getFilds();
		
		if($this->filds){
			foreach($this->filds as $fild){
				if(!isset($this->params[$fild])) continue; 
				$insert -> addFild("`".$fild."`", $this->params[$fild]);
			}
			
		}
		$insert->queryDB();
		$this->id = $insert	-> getInsertId();
	}
} 