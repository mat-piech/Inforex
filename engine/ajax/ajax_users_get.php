<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */
 
class Ajax_users_get extends CPage {

    function __construct(){
        parent::__construct();
        $this->anySystemRole[] = ROLE_SYSTEM_USER_LOGGEDIN;
    }

    function execute(){
		global $db;

		$sql = "SELECT user_id, screename FROM users";
		return $db->fetch_rows($sql);		
	}	
}