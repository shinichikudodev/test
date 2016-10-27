<?php

class Application_Model_DbTable_Language extends Zend_Db_Table_Abstract
{

	protected $_name = 'countrylanguage';
	protected $_primary = array('Language', 'CountryCode');
	protected $_referenceMap = array(
	    'Country' => array(
		'columns' => 'CountryCode',
		'refTableClass' => 'Application_Model_DbTable_Country',
		'refColumns' => 'Code',
		'onDelete' => self::CASCADE
	    )
	);

}

