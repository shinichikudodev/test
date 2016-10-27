<?php

class Application_Model_DbTable_City extends Zend_Db_Table_Abstract
{

	protected $_name = 'city';
	protected $_primary = 'ID';
	protected $_referenceMap = array(
	    'Country' => array(
		'columns' => 'CountryCode',
		'refTableClass' => 'Application_Model_DbTable_Country',
		'refColumns' => 'Code',
		'onDelete'   =>  self::CASCADE
	    )
	);
}

