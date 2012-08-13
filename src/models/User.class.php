<?php
    class User
    {
        private $_login;
        private $_lastname;
        private $_firstname;
        private $_role;
        
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
		
		public function getLogin(){
			return $this->_login;
		}
		public function setLogin($login){
			$this->_login = $login;
		}
		public function getLastname(){
			return $this->_lastname;
		}
		public function setLastname($lastname){
			$this->_lastname = $lastname;
		}
		public function getFirstname(){
			return $this->_firstname;
		}
		public function setFirstname($firstname){
			$this->_firstname = $firstname;
		}
		public function getRole(){
			return $this->_role;
		}
		public function setRole($role){
			$this->_role = $role;
		}
    }
?>