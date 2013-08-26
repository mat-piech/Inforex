<?php
/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 * See LICENCE 
 */

/**
 * Database gateway. 
 */
class Database{
	
	var $mdb2 = null;
	var $log = false;
	
	function __construct($dsn, $log=false){
		// gets an existing instance with the same DSN
		// otherwise create a new instance using MDB2::factory()
		$this->mdb2 =& MDB2::factory($dsn);
		if (PEAR::isError($this->mdb2)) {
		    throw new Exception($mdb2->getMessage());
		}
		$this->mdb2->loadModule('Extended');
		
		$this->mdb2->query("SET CHARACTER SET 'utf8'");
		$this->mdb2->query("SET NAMES 'utf8'");
		// wgawel: Testowo aktywuję cache'owanie - dlaczego potrzebna była jego dezaktywacja?
		$this->mdb2->query("SET SESSION query_cache_type = ON");
		
		$this->log = $log;
	}
	
	function execute($sql, $args=array()){
		if ($this->log){
			fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                        $time_start = microtime(TRUE);
			fb($sql, "SQL");
		}
		if ($args == null){
			if (PEAR::isError($r = $this->mdb2->query($sql)))
				print("<pre>{$r->getUserInfo()}</pre>");
		}else{
			if (PEAR::isError($sth = $this->mdb2->prepare($sql)))
				print("<pre>{$sth->getUserInfo()}</pre>");
			$sth->execute($args);
			if ($this->log){				
				fb($args, "SQL DATA");
			}
		}		
		if ($this->log){
                    fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
                }
	}
	
	function fetch_rows($sql, $args = null){
		if ($this->log){
			fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                        $time_start = microtime(TRUE);
			fb($sql, "SQL");
		}
		if ($args == null){
			if (PEAR::isError($r = $this->mdb2->query($sql)))
				print("<pre>{$r->getUserInfo()}</pre>");
		}else{
			if (PEAR::isError($sth = $this->mdb2->prepare($sql)))
				print("<pre>{$sth->getUserInfo()}</pre>");
			$r = $sth->execute($args);
			if ($this->log){
				fb($args, "SQL DATA");
			}		
		}
		if ($this->log){
                    fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
                }
		if ( method_exists($r, "fetchAll"))
			return $r->fetchAll(MDB2_FETCHMODE_ASSOC);			
		else
			throw new Exception("Error in SQL query <pre>$sql</pre><pre>" . print_r($args) . "</pre>");				 
	}
	
	function fetch($sql, $args=null){
		if ($this->log){
			fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                        $time_start = microtime(TRUE);
			fb($sql, "SQL");
		}
		$args = $args == null ? array() : $args;
		
		if (PEAR::isError($sth = $this->mdb2->prepare($sql)))
			print("<pre>{$sth->getUserInfo()}</pre>");
			
		if (PEAR::isError($r = $sth->execute($args)))
			print("<pre>{$r->getUserInfo()}</pre>");	
		if ($this->log){
                    fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
                }
		return $r->fetchRow(MDB2_FETCHMODE_ASSOC);			
	}
	
	function fetch_one($sql, $args=null){
		if ($this->log){
			fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                        $time_start = microtime(TRUE);
			fb($sql, "SQL");
		}
		if ($args == null){
			if (PEAR::isError($r = $this->mdb2->query($sql)))
				print("<pre>{$r->getUserInfo()}</pre>");		
		}else{
			if (!is_array($args)){
				$args = array($args);
			}
			if (PEAR::isError($sth = $this->mdb2->prepare($sql)))
				print("<pre>{$sth->getUserInfo()}</pre>");
			$r = $sth->execute($args);
			if ($this->log){
				fb($args, "SQL DATA");
			}		
		}
		if ($this->log){
                    fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
                }
		return $r->fetchOne();				
	}
	
	function fetch_id($table_name){
		return $this->mdb2->getAfterID(0, $table_name);
	}
}

//######################### deprecated functions ##########################
//######################### deprecated functions ##########################
function db_fetch_rows($sql, $args = null){
	global $mdb2, $sql_log;
	if ($sql_log){
                fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                $time_start = microtime(TRUE);
		fb($sql, "SQL");
	}
	if ($args == null){
		if (PEAR::isError($r = $mdb2->query($sql)))
			throw new Exception("<pre>{$r->getUserInfo()}</pre>");
	}else{
		if (PEAR::isError($sth = $mdb2->prepare($sql)))
			throw new Exception("<pre>{$sth->getUserInfo()}</pre>");
		$r = $sth->execute($args);
		if ($sql_log){
			fb($args, "SQL DATA");
		}		
	}
        if ($sql_log){
            fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
        }
	return $r->fetchAll(MDB2_FETCHMODE_ASSOC);
}

//######################### deprecated functions ##########################
function db_execute($sql, $args=null){
	global $mdb2, $sql_log;
	if ($sql_log){
                fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                $time_start = microtime(TRUE);
		fb($sql, "SQL");
	}
	if ($args == null){
		if (PEAR::isError($r = $mdb2->query($sql)))
			throw new Exception("<pre>{$r->getUserInfo()}</pre>");
	}else{
		if (PEAR::isError($sth = $mdb2->prepare($sql)))
			throw new Exception("<pre>{$sth->getUserInfo()}</pre>");
		$sth->execute($args);
		if ($sql_log){
			fb($args, "SQL DATA");
		}
	}
        if ($sql_log){
            fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
        }
    }

/**
 * Fetch single row as assoc array.
 * @param $sql SELECT query statement
 * @return array with the query result
 */
//######################### deprecated functions ##########################
function db_fetch($sql, $args=null){
	global $mdb2, $sql_log;
	if ($sql_log){
                fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                $time_start = microtime(TRUE);
		fb($sql, "SQL");
	}
	$args = $args == null ? array() : $args;
	
	if (PEAR::isError($sth = $mdb2->prepare($sql)))
		throw new Exception("<pre>{$sth->getUserInfo()}</pre>");
		
	if (PEAR::isError($r = $sth->execute($args)))
		throw new Exception("<pre>{$r->getUserInfo()}</pre>");	
        if ($sql_log){
            fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
        }
	return $r->fetchRow(MDB2_FETCHMODE_ASSOC);			
}

//######################### deprecated functions ##########################
function db_fetch_one($sql, $args=null){
	global $mdb2, $sql_log;
	if ($sql_log){
                fb(__CLASS__.':'.__METHOD__.'() ('.__FILE__.':'.__LINE__.')', "SQL");
                $time_start = microtime(TRUE);
		fb($sql, "SQL");
	}
	if ($args == null){
		if (PEAR::isError($r = $mdb2->query($sql)))
			throw new Exception("<pre>{$r->getUserInfo()}</pre>");		
	}else{
		if (!is_array($args)){
			$args = array($args);
		}
		if (PEAR::isError($sth = $mdb2->prepare($sql)))
			throw new Exception("<pre>{$sth->getUserInfo()}</pre>");
		$r = $sth->execute($args);
		if ($sql_log){
			fb($args, "SQL DATA");
		}		
	}
        if ($sql_log){
            fb('Execute time: '.number_format(microtime(TRUE)-$time_start, 6).' s.', "SQL");
        }
	return $r->fetchOne();				
}

//######################### deprecated functions ##########################
function db_fetch_class_rows($class_name, $sql, $args = null){
	$rows = db_fetch_rows($sql, $args);
	$objects = array();
	foreach ($rows as $row){
		$o = new $class_name();
		foreach ($row as $k=>$v)
			$o->$k = $v;
		$objects[] = $o;			
	}
	return $objects;
}

/**
 * Replace a row in a given table.
 * @param $table -- table name
 * @param $values -- assoc table with values column=>value
 */
//######################### deprecated functions ##########################
function db_replace($table, $values){
	$value = "";
	foreach ($values as $k=>$v)
		$value[] = "$k='$v'";
	$key = "";
	$sql = "REPLACE $table SET ".implode(", ", $value);
	db_execute($sql);
}

//######################### deprecated functions ##########################
function db_update($table, $values, $keys){
	$value = "";
	foreach ($values as $k=>$v)
		$value[] = "$k='$v'";
	$key = "";
	foreach ($keys as $k=>$v)
		$key[] = "$k='$v'";
	$sql = "UPDATE $table SET ".implode(", ", $value)." WHERE ".implode(" AND ", $key);
	db_execute($sql);
}

/**
 * Generuje i wykonuje kwerendę INSERT.
 * @param $table -- nazwa tabeli, do której mają być wstawione dane
 * @param $attributes -- tablica asocjacyjna atrybytów (nazwa_kolumny=>wartość)
 */
//######################### deprecated functions ##########################
function db_insert($table, $attributes){
	$cols = array();
	$vals = array();
	foreach ($attributes as $k=>$v){
		$cols[] = "`$k`";
		$vals[] = "?"; 
	}
	$sql = "INSERT INTO $table(".implode(",", $cols).") VALUES(".implode(",", $vals).")";
	db_execute($sql, array_values($attributes));
}

?>
