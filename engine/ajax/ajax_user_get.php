<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */
 
class Ajax_user_get extends CPageCorpus {

	/**
	 * Zwraca tablice JSON z pełnymi danymi użytkownika.
	 */
	function execute(){
		global $db;

		$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
		
		$user = DbUser::get($user_id);
		$user['roles'] = DbUserRoles::get($user_id);
						 				
		return $user;		
	}	
}