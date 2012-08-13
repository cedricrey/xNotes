<?php    
	class Section extends Element{
        private $_notes;
		
        public function __construct () 
        {
			//$this->_title =  $title;
			//$this->_file =  strNormalize( $title . ".xml");
        }
		public function getNotes(){
			return $this->_notes;
		}
		public function setNotes($notes){
			$this->_notes = $notes;
		}
	}		
?>