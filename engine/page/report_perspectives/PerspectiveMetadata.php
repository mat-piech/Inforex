<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */
 
class PerspectiveMetadata extends CPerspective {

    function execute()
	{
        global $corpus;
		$row = $this->page->get("row");

		$ext = DbReport::getReportExtById($row['id']);
		
		$features = DbCorpus::getCorpusExtColumns($corpus['ext']);
		$subcorpora = DbCorpus::getCorpusSubcorpora($corpus['id']);
		$statuses = DbStatus::getAll();
		$formats = DbReport::getAllFormats();

		/* Jeżeli nie ma rozszrzonego wiersza atrybutów, to utwórz pusty */
		if ( $ext == null && $corpus['ext'] != "" ){
			DbReport::insertEmptyReportExt($row['id']);
			$ext = DbReport::getReportExtById($row['id']);
		}
		
		$features_index = array();
		if (is_array($features)){
			foreach ($features as $index=>&$f){
				$features_index[$f['field']] = &$f;
			    if($features[$index]['type'] == "text" && $features[$index]['value'] == null && $features[$index]['default'] != "empty"){
			        $features[$index]['value'] = $features[$index]['default'];
                }
			}
		}

		if (is_array($ext)){
			foreach ($ext as $k=>$v){
				if ($k != "id"){
                    if($features_index[$k]['type'] == 'text' && $features_index[$k]['default'] != null && $v === ""){
                        $features_index[$k][] = $features_index[$k]['default'];
                    } else{
                        $features_index[$k][] = $v;
                        $features_index[$k]['value'] = $v;
                    }
                }
			}
		}	

		$content = $row['content'];
		if ( $row['format'] == 'plain'){
			$content = htmlspecialchars($content);
		}
		
		$this->page->set("content", $content);
		$this->page->set("features", $features);
		$this->page->set("subcorpora", $subcorpora);
		$this->page->set("statuses", $statuses);	
		$this->page->set("formats", $formats);
	}
}

?>