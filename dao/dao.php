<?php

class Dao extends ADOdb_Active_Record{
	
	var $_table = "";
	
	public $id = null;
	public function Save(){		
		
		global $db;
		
		$this->created_at = Date('Y-m-d H:i:s');
		
		if($this->id == ""){
			$this->updated_at = Date('Y-m-d H:i:s');

			
			$this->id = $db->nextId($this->_table . '_id_seq');
		}
		
		parent::Save();
	}
	
	
	public function getId(){
		return $this->id;
	}
}