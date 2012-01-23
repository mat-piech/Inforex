<?php

class Page_lps_metric extends CPage{
	
	var $isSecure = true;
	
	function checkPermission(){
		return "Brak dostępu";
	}
	
	function execute(){
		global $corpus;
		
		$metric = strval($_GET['metric']);
		$class = strval($_GET['class']);		
		$class1 = strval($_GET['class1']);		
		$class2 = strval($_GET['class2']);		
		$stats = null;
		$bucket_size = 10;
		
		if ( !in_array($metric, array("tokens", "class", "ratio")))
			$metric = "tokens";
		
		if ( $metric == "tokens" )
			$stats = DbCorpusStats::getDocumentLengthsInSubcorpora(3);
		elseif ( $metric == "class" )
			$stats = DbCorpusStats::getDocumentClassCountsInSubcorpora($class, 3);
		elseif ( $metric == "ratio" ){
			$stats = DbCorpusStats::getDocumentClassCountsRatioInSubcorpora($class1, $class2, 3);
			$bucket_size = 1;
		}
				
		$this->set('stats', $this->groupIntoBuckets($stats, $bucket_size));
		$this->set('classes', Tagset::getSgjpClasses());
		$this->set('metric', $metric);
		$this->set('class', $class);
		$this->set('class1', $class1);
		$this->set('class2', $class2);
		
	}
	
	/**
	 * Transforms
	 *   [group_id] => array(counts) 
	 * into
	 *   [bucket] = array( [group_id] => count )
	 */
	function groupIntoBuckets($groups, $bucket_size=10){
		$max = 0;
		foreach ($groups as $name=>$count)
			foreach ($count as $c)
				$max = max((int)$c['count'], $max);
		
		$buckets = ceil($max/$bucket_size);
		
		$stats = array();
		
		foreach ($groups as $name=>$count)
			$stats[-1][] = $name;
		
		for ($i=0; $i<=$buckets; $i++){
			$stats[$i*$bucket_size] = array();
			foreach ($groups as $name=>$count);
				$stats["" . $i*$bucket_size][] = 0;
		}

		$i=0;
		foreach ($groups as $name=>$count){				
			foreach ($count as $c){
				if ($c['count']==0)
					$stats[0][$i]++;
				else{
					$index = ceil($c['count']/$bucket_size) * $bucket_size;
					$stats["" . $index][$i]++;
				}
			}
			$i++;
		}
		return $stats;				
	}
}
?>

