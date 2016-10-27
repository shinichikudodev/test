<?php

class My_Validate_CapitalExist extends Zend_Validate_Abstract
{

	const NotExist = 'notexist';

	protected $_messageTemplates = array(
	    self::NotExist => "Capital City does not exist"
	);

	public function isValid($value)
	{
		$this->_setValue($value);
		
		$cityMapper = new Application_Model_CityMapper();
		
		$city = new Application_Model_City();
		
		$cityMapper->find($value, $city);
		
		if (null === ($id = $city->getId()))
		{
			$this->_error(self::NotExist);
			return false;
		}
		
		return true;
	}

}

?>
