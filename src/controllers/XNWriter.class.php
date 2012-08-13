<?php
   class XNWriter{
		static public $PREGPATH_SECTION = '/S\[([0-9]*)\]/';
		static public $PREGPATH_NOTE = '/N\[([0-9]*)\]/';
		
		static public function saveNoteBook(Notebook $notebook){
			$user = UserManager::getUser();
			// création du nouvel objet document
			$imp = new DOMImplementation;
			
		    $dom = $imp->createDocument(null, null, 
		    	$imp->createDocumentType("notebook",
                "-//W3C//DTD XML 1.00//EN",
                "../../../dtd/xnotes.dtd"));			
			$dom->encoding = 'UTF-8';
			$dom->standalone = false;
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = true;
			
			$xmlNoteBook = self::NotebookToXML($notebook, $dom);		
			
			if($notebook->getNotes())
			{
				foreach ( $notebook->getNotes() as $noteKey => $noteValue) {
								$xmlNote = self::NoteToXML($noteValue, $dom);
								$xmlNoteBook->appendChild($xmlNote);
							}
			}
			
			if($notebook->getSections())
				foreach ( $notebook->getSections() as $sectionKey => $sectionValue) {
						$xmlSection = self::SectionToXML($sectionValue, $dom);
						if($sectionValue->getNotes())
							foreach ( $sectionValue->getNotes() as $noteKey => $noteValue) {
								$xmlNote = self::NoteToXML($noteValue, $dom);
								$xmlSection->appendChild($xmlNote);
							}
						$xmlNoteBook->appendChild($xmlSection);
					}
			
			$dom->appendChild($xmlNoteBook);
			
			return $dom->save( UserManager::$USER_DIR . $user->getLogin() . "/notebooks/" . $notebook->getFile(),LIBXML_NOEMPTYTAG);
		}

		static public function NoteToXML(Note $note, DOMDocument $dom){
			//$xmlNote = $dom->createElement('note');
			$xmlNote = self::ElementToXML($note, $dom);
			if($note->getType() != "")
			{
				$typeNoteAttribute = $dom->createAttribute('type');
				$typeNoteAttribute->value = $note->getType();
				$xmlNote->appendChild($typeNoteAttribute);
			}
			if($note->getViewstate() != "")
			{
				$typeNoteAttribute = $dom->createAttribute('viewstate');
				$typeNoteAttribute->value = $note->getViewstate();
				$xmlNote->appendChild($typeNoteAttribute);
			}
			$cdataContent = $dom->createCDATASection ($note->getContent());
			$xmlNote->appendChild($cdataContent);
			return $xmlNote;
		}
		
		static public function SectionToXML(Section $section, DOMDocument $dom){
			//$xmlSection = $dom->createElement('section');
			$xmlSection = self::ElementToXML($section, $dom);
			return $xmlSection;
		}
		static public function NotebookToXML(Notebook $notebook, DOMDocument $dom){
			//$xmlSection = $dom->createElement('section');
			$xmlNoteBook = self::ElementToXML($notebook, $dom);
			return $xmlNoteBook;
		}
		
		static public function ElementToXML(Element $element, DOMDocument $dom){
			$xmlElement = $dom->createElement(strtolower ( get_class($element) ) );
			if($element->getTitle() != "")
			{
				$titleNoteAttribute = $dom->createAttribute('title');
				$titleNoteAttribute->value = $element->getTitle();
				$xmlElement->appendChild($titleNoteAttribute);	
			}
			if($element->getCreated() != "")
			{
				$attribute = $dom->createAttribute('created');
				$attribute->value = $element->getCreated()->format(DateTime::W3C);
				$xmlElement->appendChild($attribute);	
			}
			if($element->getModified() != "")
			{
				$attribute = $dom->createAttribute('modified');
				$attribute->value = $element->getModified()->format(DateTime::W3C);
				$xmlElement->appendChild($attribute);	
			}
			return $xmlElement;			
		}
		
		
	}
?>