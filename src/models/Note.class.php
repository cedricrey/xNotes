<?php
	class Note extends Element{
        private $_content;
		private $_type;
		private $_viewstate;
		
		
        public function __construct () 
        {
        	
        }
		public function getContent(){
			return $this->_content;
		}
		public function setContent($content){
			$this->_content = $content;
		}
		public function getType(){
			return $this->_type;
		}
		public function setType($type){
			$this->_type = $type;
		}
		public function getViewstate(){
			return $this->_viewstate;
		}
		public function setViewstate($viewstate){
			$this->_viewstate = $viewstate;
		}
	}
?>