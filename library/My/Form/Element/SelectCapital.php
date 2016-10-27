<?php

class My_Form_Element_SelectCapital extends Zend_Form_Element_Select
{

	// First element in the select box
	protected $_select = array(
	    '=== Please Select ===',
	);

	public function __construct($countryCode, $spec, $options)
	{
		parent::__construct($spec, $options);

		$this->addCapitalOptions($countryCode)
			->setRegisterInArrayValidator(true);
	}

	public function addCapitalOptions($countryCode)
	{

		// get country capital city information
		$city = new Application_Model_CityMapper();
		$rows = $city->getCitiesByCountry($countryCode);


		foreach ($rows as $row)
		{
			$this->_select[$row->id] = $row->name;
		}
		$this->addMultiOptions($this->_select);

		return $this;
	}

}
