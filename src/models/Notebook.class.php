<?php
    class Notebook extends Element
    {
        private $_sections;
        private $_notes;
        private $_file;
        
        public function __construct () 
        {
			//$this->_title =  $title;
			//$this->_file =  strNormalize( $title . ".xml");
        }
        
        public function load()
        {
        
        }
        
        public function save()
        {
        
        }
		public function getNotes(){
			return $this->_notes;
		}
		public function setNotes($notes){
			$this->_notes = $notes;
		}		
		public function getSections(){
			return $this->_sections;
		}
		public function setSections($sections){
			$this->_sections = $sections;
		}
		public function getSection($number){
			return $this->_sections[$number];
		}
		public function getFile(){
			return $this->_file;
		}
		public function setFile($file){
			$this->_file = $file;
		}
    }
?>