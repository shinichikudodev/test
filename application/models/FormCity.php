<?php

class Application_Model_FormCity extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('city');
		$this->setMethod('post');
		$this->setAction($options['action']);

		$id = new Zend_Form_Element_Hidden('id');
		//$code->addErrorMessage('Country Code must be in Capital Letter with three character length.');
		$id->removeDecorator('label');
		$id->removeDecorator('htmlTag');
		$id->removeDecorator('Errors');		
		
		$countrycode = new My_Form_Element_SelectCountry('countrycode', array(
		    "required" => true,
		));
		$countrycode->setRequired(true);
		$countrycode->addValidator('StringLength', true, array(3, 3));
		$countrycode->addValidator('Regex', true, array('/^[(A-Z)]{3}$/') );
		$countrycode->addErrorMessage('Country is required');
		$countrycode->removeDecorator('label');
		$countrycode->removeDecorator('htmlTag');
		$countrycode->removeDecorator('Errors');

		$name = new Zend_Form_Element_Text('name');
		$name->setAttrib('size', 50);
		$name->setRequired(true);
		//$name->addErrorMessage('Please enter a country name');
		$name->removeDecorator('label');
		$name->removeDecorator('htmlTag');
		$name->removeDecorator('Errors');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Add');
		$submit->removeDecorator('DtDdWrapper');

		$this->addElements(array($id, $countrycode, $name, $submit));
	}

}