<?php

class Application_Model_DbTable_Country extends Zend_Db_Table_Abstract
{

	protected $_name = 'country';
	protected $_primary = 'Code';
	protected $_referenceMap = array(
	    'CapitalCity' => array(
		'columns' => 'Capital',
		'refTableClass' => 'Application_Model_DbTable_City',
		'refColumns' => 'ID',
	    )
	);
	protected $_dependentTables = array('Application_Model_DbTable_City', 'Application_Model_DbTable_Language');
}

