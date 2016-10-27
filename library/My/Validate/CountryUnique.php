<?php

class My_Validate_CountryUnique extends Zend_Validate_Abstract
{

	const UNIQUE = 'unique';

	protected $_messageTemplates = array(
	    self::UNIQUE => "'%value%' already exists"
	);

	public function isValid($value)
	{
		$this->_setValue($value);

		$countryMapper = new Application_Model_CountryMapper();
		
		$country = new Application_Model_Country();
		
		$countryMapper->find($value, $country);
		
		if (null !== ($code = $country->getCode()))
		{
			$this->_error(self::UNIQUE);
			return false;
		}
		
		return true;
	}

}

?>
