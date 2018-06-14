<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */
 
class DBReportPerspective{

    /**
	 * Checks if a user has access to a given report perspective.
	 *
     * @param $user_id
     * @param $corpus_id
     * @param $report_perspectives
     * @return bool
     */
	static function userHasPerspectiveAccess($user_id, $corpus_id, $report_perspectives){
		global $db;

		$params = array($user_id, $corpus_id);
		$params = array_merge($params, $report_perspectives);

		$sql = "SELECT * FROM corpus_perspective_roles WHERE user_id = ? AND corpus_id = ? AND report_perspective_id IN (" . implode(",", array_fill(0, count($report_perspectives), "?")) . ")";
		$hasAccess = count($db->fetch_rows($sql, $params)) > 0;
		return $hasAccess;

	}

    /**
	 * Get an array of perspectives that a user has access to.
     * @param $user_id
     * @param $corpus_id
     * @return array
     */
	static function getUserPerspectiveAccess($user_id, $corpus_id){
        global $db;

        $params = array($user_id, $corpus_id);

        $sql = "SELECT * FROM corpus_perspective_roles WHERE user_id = ? AND corpus_id = ?";
        $perspectives = $db->fetch_rows($sql, $params);
        return $perspectives;
	}
	
	static function get_corpus_perspectives($corpus_id, $user){
		$sql = "SELECT * FROM `corpus_and_report_perspectives` c JOIN `report_perspectives` p ON (c.perspective_id = p.id) WHERE `corpus_id`=? AND `access` = 'public'";
		$arr = array($corpus_id);
		if(isCorpusOwner()){
			$sql = "SELECT p.*, " .
					"c.corpus_id " .
					"FROM `corpus_and_report_perspectives` c " .
					"JOIN `report_perspectives` p " .
						"ON (c.perspective_id = p.id) " .
						"WHERE `corpus_id`=? ";			
		}
		elseif ( hasRole('loggedin')){
			$arr = array($corpus_id, $corpus_id, $user['user_id']);
			//$sql = "SELECT * FROM `corpus_and_report_perspectives` c JOIN `report_perspectives` p ON (c.perspective_id = p.id) WHERE `corpus_id`=? AND (`access` != 'role')";
			$sql = "SELECT p.*, " .
					"c.corpus_id " .
					"FROM `corpus_and_report_perspectives` c " .
					"JOIN `report_perspectives` p " .
						"ON (c.perspective_id = p.id) " .
						"WHERE `corpus_id`=? " .
						"AND (`access` != 'role') " .
					"UNION " .
					"SELECT p.*, c.corpus_id " .
					"FROM corpus_perspective_roles c " .
					"JOIN report_perspectives p " .
						"ON (c.report_perspective_id=p.id) " .
						"WHERE corpus_id=? " .
						"AND user_id=?";
		}		
		$rows = db_fetch_class_rows("ReportPerspective", $sql, $arr);
		
		$rows_to_sort = array();
		foreach ($rows as $r){
			$rows_to_sort[str_pad($r->order, 6, "0", STR_PAD_LEFT)."-".$r->id] = $r;
		}

		ksort($rows_to_sort);
		
		return array_values($rows_to_sort);
	}
}
?>
