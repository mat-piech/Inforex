<?php

require_once("{$config->path_engine}/pages/ner.php");

/**
 */
class Ajax_report_tokenization_process extends CPage {
	
	var $isSecure = false;
	
	/**
	 * Generate AJAX output.
	 */
	function execute(){
		global $mdb2, $user, $corpus, $config;
	
		$text = strval($_POST['text']);
		$report_id = strval($_POST['report_id']);
		// Location of the WSDL file 
		//$url = "http://nlp.pwr.wroc.pl/clarin/ws/takipi/takipi.wsdl"; 
		// Create a stub of the web service 
		$client = new SoapClient($config->takipi_wsdl); 
		// Send a request 
		$request = $client->Tag($text, "TXT", true); 
		$token = $request->msg; 
		$status = $request->status; 
		// Check whether the request was queued 
		if ( $request->status == 2 ){ 
		    // Check the request status until is 2 (queued) or 3 (in processing) 
		    do { 
		        $status = $client->GetStatus($token); 
		    }while ( $status == 2 || $status == 3 ); 
		    // If the status is 1 then fetch the result and print it 
		    if ( $status == 1 ){ 
		        $result = $client->GetResult($token); 
		        $resultMsg = $result->msg;
		        $takipiDoc = null;
			  	try {
			  		$takipiDoc = TakipiReader::createDocumentFromText("<doc>$resultMsg</doc>");			  		
			  	}
			  	catch (Exception $e){
					echo json_encode(array("error"=>"TakipiReader error"));
					return;
			  	}
			  	$takipiText = "";
			  	$tokensValues = "";
			  	foreach ($takipiDoc->getTokens() as $token){
			  		$tokensValues = $tokensValues ."($report_id,".mb_strlen($takipiText);
			  		$takipiText = $takipiText . $token->orth;
			  		$tokensValues = $tokensValues ."," . (mb_strlen($takipiText)-1) . ")," ;
			  	}
				$dbHtml = new HtmlStr(
							html_entity_decode(
								normalize_content(
									$mdb2->queryOne("SELECT content " .
													"FROM reports " .
													"WHERE id=$report_id")), 
								ENT_COMPAT, 
								"UTF-8"), 
							true);
				$takipiText = html_entity_decode($takipiText, ENT_COMPAT, "UTF-8");
				$dbText = preg_replace("/\n+|\r+|\s+/","",$dbHtml->getText(0, false));
			  	if ($takipiText==$dbText){
			  		$tokensValues = mb_substr($tokensValues,0,-1);
			  		db_execute("DELETE FROM tokens WHERE report_id=$report_id");
					db_execute("INSERT INTO `tokens` (`report_id`, `from`, `to`) VALUES $tokensValues");  		
			  	}
			  	else {
					echo json_encode(array("error"=>"Database synchronization error", "takipitext"=>$takipiText, "dbText"=>$dbText, "resultmsg"=>$resultMsg, "takipiDoc"=>var_dump($takipiDoc)));
					return;		  		
			  	}		        
		    } 
		} 		
		$json = array( "success"=>1);
		echo json_encode($json);
	}
		
}
?>
