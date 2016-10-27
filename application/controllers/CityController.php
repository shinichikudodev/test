<?php

class CityController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
	}

	public function viewAction()
	{
		// action body
		$id = $this->_getParam('id', '');
		// get country capital city information
		$city = new Application_Model_CityMapper();
		$entry = $city->find($id, new Application_Model_City());
		$this->view->entry = $entry;

		// get country information
		$country = new Application_Model_CountryMapper();
		$row = $country->find($entry->countryCode, new Application_Model_Country());
		$this->view->country = $row;
	}

	public function addAction()
	{
		// action body
		$this->view->pageTitle = 'Add New City';
		$this->view->errors = array();
		$form = new Application_Model_FormCity(array('action' => '/city/add'));
		$form->setDecorators(array(array('ViewScript', array('viewScript' => '_form_city_add.phtml'))));

		// Has the login form been posted?
		if ($this->getRequest()->isPost())
		{
			// If the submitted data is valid, attempt to authenticate the user
			if ($form->isValid($this->_request->getPost()))
			{
				// do the saving
				$data = $form->getValues();
				$city = new Application_Model_City();
				$city->countryCode = $data['countrycode'];
				$city->name = $data['name'];

				$cMapper = new Application_Model_CityMapper();
				$cMapper->save($city);
				$this->_helper->flashMessenger->addMessage(
					$data['name'] . ' has been successfully added.'
				);
				$this->_redirect('/country/show/'.$data['countrycode']);
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
		$id = $this->_getParam('id', '');

		$this->view->pageTitle = 'Edit Country';
		$this->view->errors = array();
		$form = new Application_Model_FormCity();
		$form->setDecorators(array(array('ViewScript', array('viewScript' => '_form_city_edit.phtml'))));

		// edit form
		$el = $form->getElement('submit');
		$el->setLabel('Update');

		if ($this->getRequest()->isPost())
		{
			// If the submitted data is valid, attempt to authenticate the user
			if ($form->isValid($this->_request->getPost()))
			{
				// do the saving
				$data = $form->getValues();
				$city = new Application_Model_City();
				$city->countryCode = $data['countrycode'];
				$city->name = $data['name'];
				$city->id = $data['id'];
				
				$this->view->entry = $city;
				$cMapper = new Application_Model_CityMapper();
				$cMapper->save($city);
				$this->_helper->flashMessenger->addMessage(
					$data['name'] . ' has been successfully updated.'
				);
				//$this->_redirect('/country/show/' . $data['countrycode']);
			}
			else
			{

				$this->view->errors = $this->_formatError($form->getMessages());
			}
		}
		else
		{
			$cMapper = new Application_Model_CityMapper();
			$city = new Application_Model_City();
			$cMapper->find($id, $city);

			$this->view->entry = $city;

			$arr = $city->toArray();
			$form->populate($city->toArray());
		}

		$this->view->form = $form;

	}

	public function deleteAction()
	{		
		// action body
		$id = $this->_getParam('id', '');
		$cMapper = new Application_Model_CityMapper();
		$city = new Application_Model_City();
		$cMapper->find($id, $city);

		if (null !== $city->getId())
		{
			$cMapper->deleteCityById($id);
			$this->_helper->flashMessenger->addMessage(
				$city->name . ' has been successfully deleted.'
			);
			$this->_redirect('/country/show/' . $city->countryCode);
		}
		else
		{
			$this->_helper->flashMessenger->addMessage(
				$city->name . ' cannot be deleted.'
			);
			$this->_redirect('/country/list');
		}

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
						if (isset($error[0]))
						{
							$tempErrors[][0] = $error[0];
						}
						else
						{
							foreach ($error AS $k => $v)
							{
								$tempErrors[][0] = ucfirst($key) . ": " . $v;
							}
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

