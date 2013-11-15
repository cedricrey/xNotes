<?php
   class UserManager{
		static public $USER_DIR = "users/" ;
		static public function login($user, $password){
			//if login = demo
			if(ConfigManager::$DEMO_COUNT && $user=="demo")
				return self::loginDemo($user, $password);
			
			
			// création du nouvel objet document
		    $dom = new DomDocument();
			
			
			$file = self::$USER_DIR . $user . '/datas.xml';
			if (!file_exists($file))
				{
					MessageCenter::appendError("_ERROR_LOGIN");
					return false;
				}
			
			// chargement à partir du fichier
		    $dom->load($file);
			
		    // validation à partir de la DTD référencée dans le document.
		    // En cas d'erreur, on ne va pas plus loin
		    if (!@$dom->validate()) {
				MessageCenter::appendAdmin("_DOM_ERROR");
				MessageCenter::appendAdmin($dom->validate());
		      return false;
		    }
		    // on référence l'adresse du fichier source
		    //$object->source = $fileName;
			
		    // on récupère l'élément racine, on le met dans un membre
		    // de l'objet nommé "root"
		    $root = $dom->documentElement;
		    //$object->root = new stdClass();
			
			$pwd = $root->getElementsByTagName("password")->item(0)->nodeValue;
			
			
			// Si le mot de passe n'est pas bon, on arrete
			if(md5($password) != $pwd)
				{
					MessageCenter::appendError("_ERROR_LOGIN");
					return false;
				}
			
			// On charge l'utilisateur
			$user = self::loadUserFromDom($root);			
			
			$_SESSION['user'] = $user;
		    return $user;
		}
		static public function loginDemo($user, $password){
			if($password == "demo")
			{

				$userName = self::generateRandomUser();
				$password = self::generateRandomUser();
				$datas = array("login" => $userName, "role" => "demo", "password" => $password, "lastname" => "Clintee", "firstname" => "Swoud");

				self::createUser($datas);

				$user = self::login($userName, $password);				
				$_SESSION['user'] = $user;

				copy("tools/demoFiles/notebooks/Recette_de_cuisine.xml", self::$USER_DIR . $userName . "/notebooks/Recette_de_cuisine.xml");
				return $user;
			}
			return false;
		}
			
		static public function loadUser($login){
			// création du nouvel objet document
		    $dom = new DomDocument();
			
			
			$file = self::$USER_DIR . $login . '/datas.xml';
			if (!file_exists($file))
				{
					MessageCenter::appendError("_INEXISTANT_USER");
					return false;
				}
			
			// chargement à partir du fichier
		    $dom->load($file);
			
		    // validation à partir de la DTD référencée dans le document.
		    // En cas d'erreur, on ne va pas plus loin
		    if (!@$dom->validate()) {
				MessageCenter::appendAdmin("_DOM_ERROR");
				MessageCenter::appendAdmin($dom->validate());
		      return false;
		    }
						
		    // on récupère l'élément racine, on le met dans un membre
		    // de l'objet nommé "root"
		    $root = $dom->documentElement;
			// On charge l'utilisateur
			$user = self::loadUserFromDom($root);
			
		    return $user;
		}
		
		static public function loadUserFromDom($root){
		    $user = new User();
			$user->setLogin($root->getElementsByTagName("login")->item(0)->nodeValue );
			$user->setLastname($root->getElementsByTagName("lastname")->item(0)->nodeValue );
			$user->setFirstname($root->getElementsByTagName("firstname")->item(0)->nodeValue );
			if($root->getElementsByTagName("role")->length > 0)
				$user->setRole($root->getElementsByTagName("role")->item(0)->nodeValue );
			if($root->getElementsByTagName("lang")->length > 0)
				$user->setLang($root->getElementsByTagName("lang")->item(0)->nodeValue );
			if($root->getAttribute("idSequence") != "")
				$user->setIdSequence($root->getAttribute("idSequence") );
			return $user;			
		}
		static public function saveUser($user){
			$imp = new DOMImplementation;
			$dom = $imp->createDocument(null, null, 
		    	$imp->createDocumentType("user",
                "-//W3C//DTD XML 1.00//EN",
                "../../dtd/users.dtd"));			
			$dom->encoding = 'UTF-8';
			$dom->standalone = false;
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = true;
			
			$file = self::$USER_DIR . $user->getLogin() . '/datas.xml';
			if (file_exists($file))
			{
				$dom->load($file);
		    	$xmlUser = $dom->documentElement;
				$xmlUser->getElementsByTagName("login")->item(0)->nodeValue = $user->getLogin();
				$xmlUser->getElementsByTagName("lastname")->item(0)->nodeValue = $user->getLastname();
				$xmlUser->getElementsByTagName("firstname")->item(0)->nodeValue = $user->getFirstname();
				if($xmlUser->getElementsByTagName("role")->length > 0)
					$xmlUser->getElementsByTagName("role")->item(0)->nodeValue = $user->getRole();
				else
					$xmlUser->appendChild($dom->createElement('role',$user->getRole()));
				
				if($xmlUser->getElementsByTagName("lang")->length > 0)
					$xmlUser->getElementsByTagName("lang")->item(0)->nodeValue = $user->getLang();
				else
					$xmlUser->appendChild($dom->createElement('lang',$user->getLang()));
				
				$idSequence = $user->getIdSequence();
				$xmlUser->setAttribute('idSequence', $idSequence);
				
			}
			else {
				$xmlUser = $dom->createElement('user');
				$xmlLogin = $dom->createElement('login',$user->getLogin());
				$xmlUser->appendChild($xmlLogin);
				$xmlLastname = $dom->createElement('lastname',$user->getLastname());
				$xmlUser->appendChild($xmlLastname);
				$xmlFirstname = $dom->createElement('firstname',$user->getFirstname());
				$xmlUser->appendChild($xmlFirstname);
				$xmlRole = $dom->createElement('role',$user->getRole());
				$xmlUser->appendChild($xmlRole);
				$xmlLang = $dom->createElement('lang',$user->getLang());
				$xmlUser->appendChild($xmlLang);
				$dom->appendChild($xmlUser);			
			}
			
			return $dom->save(self::$USER_DIR . $user->getLogin() . "/datas.xml" , LIBXML_NOEMPTYTAG);
		}
		static public function logout(){
			unset($_SESSION['user']);
			MessageCenter::appendInfo("_LOGOUT");
			return true;
		}
		static public function isUserLogged(){
			return isset($_SESSION['user']);
		}
		static public function getUser(){
			return $_SESSION['user'];
		}
		static public function isUserAdmin(){
			if(isset($_SESSION['user']))
				return $_SESSION['user']->getRole() == "admin";
			else
				return false;
		}
		static public function getAllUsers(){
			$allUsers = array();
			//$files = scandir(".");			
			$files = scandir(self::$USER_DIR);
			//print_r($files);
			foreach ($files as $key => $value) {
				$file = self::$USER_DIR . $value;
				if(is_dir($file) && $value != "." && $value != "..")
					$allUsers[] = self::loadUser($value);
			}
			return $allUsers;			
		}
		static public function modifUser($datas){
			if(!isset($datas['login']))				
			{
				MessageCenter::appendError("_INEXISTANT_USER");
				return false;
			}
			$user = new User();

			$file = self::$USER_DIR . $datas['login'] . '/datas.xml';
			if (file_exists($file))
				$user = self::loadUser($datas['login']);
			else 
				$user->setLogin($datas['login']);
			if(isset($datas['lastname']))
				$user->setLastname($datas['lastname']);
			if(isset($datas['firstname']))
				$user->setFirstname($datas['firstname']);
			if(isset($datas['role']))
				$user->setRole($datas['role']);
			if(isset($datas['lang']))
				$user->setLang($datas['lang']);
			$user = self::saveUser($user);	
			if(isset($datas['password']) && $datas['password'] != "")
				{	
				$user = self::modifUserPassword($datas['login'], $datas['password']);
				}
			return $user;	
		}
		static public function createUser($datas){
			if(file_exists(self::$USER_DIR . $datas['login'] ))
			{
				MessageCenter::appendError("_USER_ALREADY_EXISTS");
				return false;
			}
			mkdir(self::$USER_DIR . $datas['login']);
			mkdir(self::$USER_DIR . $datas['login'] . "/notebooks");
			$returned = self::modifUser($datas);
			if(isset($datas['password']) && $datas['password'] != "")
				{	
				$returned = $returned && self::modifUserPassword($datas['login'], $datas['password']);
				}
			return $returned;
		}
		
		static public function modifPassword($oldPassword, $password){
			$user = self::getUser();
			$dom = new DomDocument();
			$file = self::$USER_DIR . $user->getLogin() . '/datas.xml';			
			// chargement à partir du fichier
		    $dom->load($file);
		    if (!@$dom->validate()) {
				MessageCenter::appendAdmin("_DOM_ERROR");
				MessageCenter::appendAdmin($dom->validate());
		    }
		    $root = $dom->documentElement;			
			$current_pwd = $root->getElementsByTagName("password")->item(0)->nodeValue;			
			// Si le mot de passe n'est pas bon, on arrete
			if(md5($oldPassword) != $current_pwd)
				{
					MessageCenter::appendError("_WRONG_PASSWORD");
					return false;
				}
			$root->getElementsByTagName("password")->item(0)->nodeValue = md5($password);
			return $dom->save(self::$USER_DIR . $user->getLogin() . "/datas.xml" , LIBXML_NOEMPTYTAG);
		}
		static public function modifUserPassword($login,$password){
			$user = self::loadUser($login);
			$dom = new DomDocument();
			$file = self::$USER_DIR . $user->getLogin() . '/datas.xml';			
			// chargement à partir du fichier
		    $dom->load($file);
		    if (!@$dom->validate()) {
				MessageCenter::appendAdmin("_DOM_ERROR");
				MessageCenter::appendAdmin($dom->validate());
		    }
		    $root = $dom->documentElement;
		    if($root->getElementsByTagName("password")->length > 0)
				$root->getElementsByTagName("password")->item(0)->nodeValue = md5($password);
			else
				$root->insertBefore( $dom->createElement("password",md5($password)) , $root->firstChild );
			return $dom->save(self::$USER_DIR . $user->getLogin() . "/datas.xml" , LIBXML_NOEMPTYTAG);
		}
		static private function generateRandomUser($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
		    return $randomString;
		}
	}
?>