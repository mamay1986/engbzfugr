<?php

class cacheValue extends fmakeCore {

	public $table = "cache";

	function isCache($name){
		$result = $this->getCache($name);
		if($result){
			if(time()>($result['date']+$result['time_live'])){
				return false;
			}
			return true;
		}
		return false;
	}
	
	function getCache($name){
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$result = $select->addFrom($this->table)->addWhere("`name` = '{$name}'")->addLimit(0,1)->queryDB();
		return $result[0];
	}
	
	function getCacheValue($name){
		$result = $this->getCache($name);
		return $result['value'];
	}
	
	function addCache($name,$value,$time_live = false){
		if(!$this->isCache($name)){
			$result = $this->getCache($name);
			
			if($result) $this->setId($result[$this->idField]);
			
			$this->addParam('name',$name);
			$this->addParam('value',$value);
			$this->addParam('date',time());
			if($time_live) $this->addParam('time_live',intval($time_live)); 
			
			if($result) $this->update();
			else $this->newItem();
			
			return $value;
		}
		else{
			return $this->getCacheValue($name);
		}
	}
}