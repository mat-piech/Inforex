<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE
 */


class Ajax_annotation_type_get extends CPageCorpus {
    function __construct(){
        parent::__construct("annotation_type_get", "Returns annotation type for given annotation id");
        $this->anyCorpusRole[] = CORPUS_ROLE_ANNOTATE;
        $this->anyCorpusRole[] = CORPUS_ROLE_ANNOTATE_AGREEMENT;
    }
	
	function execute(){
		$annotation_id = intval($_POST['annotation_id']);
		$row = DbAnnotation::get($annotation_id);
		return array("id" => $row['type_id'], "type" => $row['type']);
	}
}