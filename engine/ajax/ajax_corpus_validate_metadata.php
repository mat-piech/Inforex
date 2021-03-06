<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE
 */

class Ajax_corpus_validate_metadata extends CPageCorpus {

    function execute(){
        $field = $_POST['create_metadata_column_id'];
        $corpus_id = $_POST['corpus_id'];
        $table = DbCorpus::getCorpusExtTable($corpus_id);
        if($table != null){
            $fields = DbCorpus::getCorpusExtColumns($table);
            foreach($fields as $one_field){
                ChromePhp::log($one_field['field'] . " = " . $field);
                if($one_field['field'] == $field){
                    echo "false";
                    //Obejście return na potrzeby pluginu jQuery Validation
                    die();
                }
            }
        }

        echo "true";
        die();
    }
}