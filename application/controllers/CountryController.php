<?php

class CountryController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
		/* Initialize action controller here */
		if ($this->_helper->FlashMessenger->hasMessages())
		{
			$this->view->messages = $this->_helper->FlashMessenger->getMessages();
		}
	}

	public function indexAction()
	{
		// action body
		$country = new Application_Model_CountryMapper();
		$this->view->entries = $country->fetchAll();
	}

	public function viewAction()
	{
		// action body
		$countryCode = $this->_getParam('code', '');
		$this->view->countryCode = $countryCode;
		// get country information
		$country = new Application_Model_CountryMapper();
		$row = $country->find($countryCode, new Application_Model_Country());
		$this->view->country = $row;

		// get country capital city information
		$city = new Application_Model_CityMapper();
		$this->view->capitalCity = $city->find($row->capital, new Application_Model_City());
		$this->view->cityEntries = $city->getCitiesByCountry($countryCode);

		// get country capital city information
		$language = new Application_Model_LanguageMapper();
		$this->view->languages = $language->getLanguagesByCountry($countryCode);
	}

	public function listAction()
	{
		// action body
		$country = new Application_Model_CountryMapper();
		$this->view->entries = $country->fetchAll();
	}

	public function addAction()
	{
		$this->view->pageTitle = 'Add New Country';
		$this->view->errors = array();
		$form = new Application_Model_FormCountry(array('action' => '/country/add'));
		$form->setDecorators(array(array('ViewScript', array('viewScript' => '_form_country_add.phtml'))));

		// Has the login form been posted?
		if ($this->getRequest()->isPost())
		{
			// If the submitted data is valid, attempt to authenticate the user
			if ($form->isValid($this->_request->getPost()))
			{
				// do the saving
				$data = $form->getValues();
				$country = new Application_Model_Country();
				$country->code = $data['code'];
				$country->name = $data['name'];

				$cMapper = new Application_Model_CountryMapper();
				$cMapper->insert($country);
				$this->_helper->flashMessenger->addMessage(
					$data['name'] . ' has been successfully added.'
				);
				$this->_redirect('/country/list');
			}
			else
			{
				$this->view->errors = $this->_formatError($form->getMessages());
			}
		}

		$this->view->form = $form;
	}

	public function editAction()
	{
		$countryCode = $this->_getParam('code', '');

		$this->view->pageTitle = 'Edit Country';
		$this->view->errors = array();
		$form = new Application_Model_FormCountry();
		$form->setDecorators(array(array('ViewScript', array('viewScript' => '_form_country_edit.phtml'))));

		// edit form
		$form->removeElement('code');

		$code = new Zend_Form_Element_Hidden('code');
		$code->setLabel('<b>Three Capital Letter</b><br />');
		$code->setAttrib('size', 3);
		$code->setAttrib('maxlength', 3);
		$code->setRequired(true);
		$code->addValidator('StringLength', true, array(3, 3));
		$code->addValidator('Regex', true, array('/^[(A-Z)]{3}$/', 'messages' => array(
			'regexNotMatch' => 'Country Code must be in Capital Letter with three character length.'
		)));
		//$code->addErrorMessage('Country Code must be in Capital Letter with three character length.');
		$code->removeDecorator('label');
		$code->removeDecorator('htmlTag');
		$code->removeDecorator('Errors');


		$capital = new My_Form_Element_SelectCapital($countryCode, 'capital', array(
		    "required" => true,
		));
		$capital->addValidator(new My_Validate_CapitalExist(), true);
		$capital->removeDecorator('label');
		$capital->removeDecorator('htmlTag');
		$capital->removeDecorator('Errors');
		$form->addElements(array($code, $capital));

		$el = $form->getElement('submit');
		$el->setLabel('Update');

		if ($this->getRequest()->isPost())
		{
			// If the submitted data is valid, attempt to authenticate the user
			if ($form->isValid($this->_request->getPost()))
			{
				// do the saving
				$data = $form->getValues();
				$country = new Application_Model_Country();
				$country->code = $data['code'];
				$country->name = $data['name'];
				$country->capital = $data['capital'];

				$this->view->entry = $country;
				$cMapper = new Application_Model_CountryMapper();
				$cMapper->update($country);
				$this->_helper->flashMessenger->addMessage(
					$data['name'] . ' has been successfully updated.'
				);
				$this->_redirect('/country/list');
			}
			else
			{

				$this->view->errors = $this->_formatError($form->getMessages());
			}
		}
		else
		{
			$cMapper = new Application_Model_CountryMapper();
			$country = new Application_Model_Country();
			$cMapper->find($countryCode, $country);

			$this->view->entry = $country;

			$arr = $country->toArray();
			$form->populate($country->toArray());
		}

		$this->view->form = $form;
	}

	public function showAction()
	{
		// action body
		$countryCode = $this->_getParam('code', '');
		$this->view->countryCode = $countryCode;
		// get country information
		$country = new Application_Model_CountryMapper();
		$row = $country->find($countryCode, new Application_Model_Country());
		$this->view->country = $row;

		// get country capital city information
		$city = new Application_Model_CityMapper();
		$this->view->capitalCity = $city->find($row->capital, new Application_Model_City());
		$this->view->cityEntries = $city->getCitiesByCountry($countryCode);

		// get country capital city information
		$language = new Application_Model_LanguageMapper();
		$this->view->languages = $language->getLanguagesByCountry($countryCode);
	}

	public function deleteAction()
	{
		// action body
		$countryCode = $this->_getParam('code', '');
		$cMapper = new Application_Model_CountryMapper();
		$country = new Application_Model_Country();
		$cMapper->find($countryCode, $country);

		if (null !== $country->getCode())
		{
			$cMapper->deleteCountryByCode($countryCode);
			$this->_helper->flashMessenger->addMessage(
				$country->name . ' has been successfully deleted.'
			);
		}
		else
		{
			$this->_helper->flashMessenger->addMessage(
				$country->name . ' cannot be deleted.'
			);
		}

		$this->_redirect('/country/list');
	}

	private function _formatError($errors)
	{
		if (!is_array($errors) && $errors != '')
		{
			return $errors;
		}
		else
		{
			$tempErrors = array();
			if (count($errors) > 0)
			{
				foreach ($errors AS $key => $error)
				{
					if (is_array($error))
					{
						foreach ($error AS $k => $v)
						{
							$tempErrors[][0] = ucfirst($key) . ": " . $v;
						}
					}
					else
					{
						$tempErrors[][0] = ucfirst($key) . ": " . $error;
					}
				}
			}
			return $tempErrors;
		}
	}

}

