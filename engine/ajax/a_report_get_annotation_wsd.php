<?php
class Ajax_report_get_annotation_wsd extends CPage {
	
	function execute(){
		
		$annotation_id = intval($_POST['annotation_id']);
		
		if ($annotation_id<=0){
			echo json_encode(array("error"=>"No identifier of annotation found"));
			return;
		}

		$sql = "SELECT at.* FROM reports_annotations an JOIN annotation_types_attributes at ON (an.type=at.annotation_type) WHERE at.name = 'sense' AND an.id = ?";
		$attr = db_fetch($sql, array($annotation_id));

		$attributes = array();					
		$rows_values = db_fetch_rows("SELECT * FROM annotation_types_attributes_enum WHERE annotation_type_attribute_id=".intval($attr['id']));
		$values = array();
		foreach ($rows_values as $v)
			$values[] = array("value"=>$v['value'], "description"=>$v['description']);
		$attr['values'] = $values;
		
		// Pobierz ustawiony sens
		$sql = "SELECT value" .
				" FROM reports_annotations_attributes att" .
				" WHERE att.annotation_id = ?" .
				"   AND att.annotation_attribute_id = ?";
		$value = db_fetch_one($sql, array($annotation_id, $attr['id']));
		$attr['value'] = $value;
		
		echo json_encode($attr);
		
	}
	
}
?>