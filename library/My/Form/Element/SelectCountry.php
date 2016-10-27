<?php

class My_Form_Element_SelectCountry extends Zend_Form_Element_Select
{

	// First element in the select box
	protected $_select = array(
	    '=== Please Select ===',
	);

	public function __construct($spec, $options)
	{
		parent::__construct($spec, $options);

		$this->addCountryOptions()
			->setRegisterInArrayValidator(true);
	}

	public function addCountryOptions()
	{

		// get country capital city information
		$country = new Application_Model_CountryMapper();
		$rows = $country->fetchAll();

		foreach ($rows as $row)
		{
			$this->_select[$row->code] = $row->name;
		}
		$this->addMultiOptions($this->_select);

		return $this;
	}

}
