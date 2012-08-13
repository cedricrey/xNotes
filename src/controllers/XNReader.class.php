<?php
   class XNReader{
		static public $PREGPATH_SECTION = '/S\[([0-9]*)\]/';
		static public $PREGPATH_NOTE = '/N\[([0-9]*)\]/';
		static public function loadNoteBook($file){
			$user = UserManager::getUser();
			// création du nouvel objet document
		    $dom = new DomDocument();
			
		    // chargement à partir du fichier
		    $dom->load( UserManager::$USER_DIR . $user->getLogin() . "/notebooks/" . $file);
			
		    // validation à partir de la DTD référencée dans le document.
		    // En cas d'erreur, on ne va pas plus loin
		    if (!@$dom->validate()) {
		    	echo $dom->validate();
		      return false;
		    }
			// on récupère l'élément racine, on le met dans un membre
		    // de l'objet nommé "root"
		    $root = $dom->documentElement;
			
		    // création de l'objet résultat
		    $noteBook = self::XMLToNotebook($root);
			$noteBook->setFile($file);
		    // on référence l'adresse du fichier source
		    //$object->source = $fileName;
		    
			$xpath = new DOMXPath($dom);
			// Nous commençons à l'élément racine
			$query = '//notebook/note';
			$entries = $xpath->query($query);
			
			$notes = array();
			foreach ( $entries as $noteKey => $noteValue) {
				$currNote = self::XMLToNote($noteValue);
				$notes[$noteKey] = $currNote;
				/*echo $currNote->content;*/
			}
			$noteBook->setNotes($notes);
			
			
			$sections = array();
			foreach ( $root->getElementsByTagName("section") as $key => $value) {
				$currSection = self::XMLToSection($value);
				
				$notes = array();
				foreach ( $value->getElementsByTagName("note") as $noteKey => $noteValue) {
					$currNote = self::XMLToNote($noteValue);
					$notes[$noteKey] = $currNote;
				}
				$currSection->setNotes($notes);
				
				$sections[$key] = $currSection;
			}
			
			//$object->root->sections = $sections;
			$noteBook->setSections($sections);
		    return $noteBook;
		}

	static public function XMLToNote($dom){
			$note = self::XMLToElement($dom);
			if($dom->getAttribute("type") != "")
			{
				$note->setType($dom->getAttribute("type"));
			}
			if($dom->getAttribute("viewstate") != "")
			{
				$note->setViewstate($dom->getAttribute("viewstate"));
			}
			$note->setContent($dom->nodeValue);
			return $note;
		}
		
		static public function XMLToSection($dom){
			$section = self::XMLToElement($dom);
			return $section;
		}
		static public function XMLToNotebook($dom){
			$noteBook = self::XMLToElement($dom);
			return $noteBook;
		}
		
		static public function XMLToElement($dom){
			$className = ucfirst($dom->nodeName);
			if(!class_exists($className)) return false;
			$element = new $className;
			if($dom->getAttribute("title") != "")
			{
				$element->setTitle($dom->getAttribute("title"));
			}
			if($dom->getAttribute("created") != "")
			{
				$element->setCreated($dom->getAttribute("created"));
			}
			if($dom->getAttribute("modified") != "")
			{
				$element->setModified($dom->getAttribute("modified"));
			}
			return $element;			
		}		
	}
?>