<?php
	class Element{
		private $_id;
		private $_title;
		private $_created;
		private $_modified;
		
        public function __construct () 
        {
        	
        }
		public function getId(){
			return $this->_id;
		}
		public function setId($id){
			$this->_id = $id;
		}
		public function getTitle(){
			return $this->_title;
		}
		public function setTitle($title){
			$this->_title = $title;
		}
		
		public function getCreated(){
			return $this->_created;
		}
		public function setCreated($created){
			if($created instanceof DateTime)
				$this->_created = $created;
			else
				$this->_created = new DateTime($created);
		}
		
		public function getModified(){
			return $this->_modified;
		}
		public function setModified($modified){
			if($modified instanceof DateTime)
				$this->_modified = $modified;
			else if(is_string($modified))
				$this->_modified = new DateTime($modified);
		}
	}
?>