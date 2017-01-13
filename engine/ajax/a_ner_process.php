<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */

require_once("{$config->path_engine}/pages/ner.php");

/**
 */
class Ajax_ner_process extends CPage {
	
	var $isSecure = false;
	
	/**
	 * Generate AJAX output.
	 */
	function execute(){
		global $mdb2, $user, $corpus, $config;
		
		$annotations = array();
		$timestamp_start = time();	
		$text = stripslashes(strval($_POST['text']));
		$parts = explode("|", stripslashes(strval($_POST['wsdl'])));
		$wsdl = $parts[0];
		$model = $parts[1];
		$annotation_types = null;
		$api = null;
		
		foreach ($config->liner2_api as $m){
			if ($m['wsdl'] == $wsdl && $m['model'] == $model){
				if ( isset($m['annotations'] )){
					$annotation_types = $m['annotations'];
				}
				$api = $m; 
				break;
			}
		}
				
		$type_ignore = array();
		if (isset($api["type_ignore"])){
			foreach ($api["type_ignore"] as $type){
				$type_ignore[$type] = 1;
			}
		}
		$text = preg_replace('/(\p{L}|\p{N})$/m', '$1', $text);
		
		$liner2 = new WSLiner2($wsdl);
		$tuples = $liner2->chunk($text, "PLAIN:WCRFT", "TUPLES", $model);
		
		$htmlStr = new HtmlStr2($text);

		// Insert relations
		$relation_tuple_pattern = "/\(([0-9]+),([0-9]+),(.*),\"(.*)\",([0-9]+),([0-9]+),(.*),\"(.*)\",(.*)\)/"; 
		if (preg_match_all($relation_tuple_pattern, $tuples, $matches, PREG_SET_ORDER)){
			foreach ($matches as $m){
				$an_from_start = intval($m[1]);
				$an_from_end = intval($m[2]);
				$an_to_start = intval($m[5]);
				$an_to_end = intval($m[6]);
				$rel = $m[9];
				try{
					$htmlStr->insertTag( $an_from_start, "", $an_from_end+1, "<sup class=\"rel\">↦$an_to_start</sup>", false);
					$htmlStr->insertTag( $an_to_start, "<sup class=\"rel\">$an_to_start</sup>", $an_to_end+1, "", false);
				}
				catch(Exception $ex){
					fb($ex);	
				}
			}
		}
		fb($type_ignore);
		// Insert annotations
		if (preg_match_all("/\((.*),(.*),(.*)\)/", $tuples, $matches, PREG_SET_ORDER)){
			foreach ($matches as $m){
				$annotation_type = strtolower($m[2]);
				if ( !array_key_exists($annotation_type, $type_ignore) 
						&& ($annotation_types == null or in_array($annotation_type, $annotation_types))){
					list($from, $to) = split(',', $m[1]);
					$key = sprintf("%d_%d_%s", $from, $to, $m[2]);
					$tag = sprintf("<span class='%s %s' title='%s'>", strtolower($m[2]), $key, strtolower($m[2]));
					try{
						$htmlStr->insertTag( $from, $tag, $to+1, "</span>");
					}
					catch(Exception $ex){
	
					}
					$annotations[$m[2]][] = array("text"=>trim($m[3], '"'), "key"=>$key);
				}
			}
		}

		$timestamp_end = time();
		$duration_sec = $timestamp_end - $timestamp_start;
		$duration = (floor($duration_sec/60) ? floor($duration_sec/60) . " min(s), " : "") . $duration_sec%60 ." sec(s)"; 
		
		$html = $htmlStr->getContent();
		$html = str_replace("\n", "<br/>", $html);

		return array("html"=>$html, "annotations"=>$this->format_list_of_annotations_table($annotations), "duration"=>$duration);
 	}
		
	/**
	 * 
	 */
	function format_list_of_annotations($annotations){
		$annotations_html = "";
		ksort($annotations);
		foreach ($annotations as $name=>$v){
			$annotation_group = "";
			foreach ($v as $an){
				$name_lower = strtolower($name);
				$annotation_group .= "<li><span class='$name_lower'>$an</span></li>";
			}
			$annotations_html .= "<li><b>$name</b><ul>$annotation_group</ul></li>";
		}
		$annotations_html = "<ul>$annotations_html</ul>";
		return $annotations_html;		
	}
	
	/**
	 * Konwertuje listę anotacji do postaci tabelki
	 * @param unknown $annotations
	 * @return string
	 */
	function format_list_of_annotations_table($annotations){
		$html = "<table class='table table-sm table-bordered' cellspacing='1'><tbody>";
		ksort($annotations);
		foreach ($annotations as $name=>$v){
			$name_lower = strtolower($name);
			$html .= "<tr class='type bg-primary'><th>$name_lower</th></tr>";
			$annotation_group = "";
			foreach ($v as $an){
				$html .= "<tr class='annotation $name_lower'><td><span class='$name_lower' key='${an['key']}'>${an['text']}</span></td></tr>";
			}
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
}
?>
