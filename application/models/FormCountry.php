<?php

class Application_Model_FormCountry extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('country');
		$this->setMethod('post');
		$this->setAction($options['action']);

		$code = new Zend_Form_Element_Text('code');
		$code->setLabel('<b>Three Capital Letter</b><br />');
		$code->setAttrib('size', 3);
		$code->setAttrib('maxlength', 3);
		$code->setRequired(true);
		$code->addValidator('StringLength', true, array(3, 3));
		$code->addValidator('Regex', true, array('/^[(A-Z)]{3}$/','messages'=>array(
			'regexNotMatch'=>'Country Code must be in Capital Letter with three character length.'
		) ));
		//$code->addErrorMessage('Country Code must be in Capital Letter with three character length.');
		$code->addValidator(new My_Validate_CountryUnique(), true, array('messages'=>array(
			'unique'=>'Country Code already exists')));
		//$code->addErrorMessage('Country Code already exists.');
		$code->removeDecorator('label');
		$code->removeDecorator('htmlTag');
		$code->removeDecorator('Errors');

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

		$this->addElements(array($code, $name, $submit));
	}

}