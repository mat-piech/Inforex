<?

class CclIntegrity{
	
	static function checkChunks($content){
		$count_empty_chunks = 0;
		$chunk_list = explode('</chunk>', $content);
		foreach ($chunk_list as $chunk){
			$chunk = str_replace("<"," <",$chunk);
			$chunk = str_replace(">","> ",$chunk);
			$tmpStr = trim(preg_replace("/\s\s+/"," ",html_entity_decode(strip_tags($chunk),ENT_COMPAT, 'UTF-8')));
			$tmpStr2 = preg_replace("/\n+|\r+|\s+/","",$tmpStr);
			if($tmpStr2 == "")
				$count_empty_chunks++;							
		}
		return $count_empty_chunks-1;
	}	
}

?>