<?php

class AccountController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
		if ($this->_helper->FlashMessenger->hasMessages())
		{
			$this->view->messages = $this->_helper->FlashMessenger->getMessages();
		}
	}

	public function indexAction()
	{
		// action body
	}

	public function loginAction()
	{
		$this->view->pageTitle = 'Login to Your Account';
		$this->view->errors = array();
		$form = new Application_Model_FormLogin();

		// Has the login form been posted?
		if ($this->getRequest()->isPost())
		{

			// If the submitted data is valid, attempt to authenticate the user
			if ($form->isValid($this->_request->getPost()))
			{
				$users = new Application_Model_DbTable_User();
				$data = $form->getValues();
				$auth = Zend_Auth::getInstance();
				$authAdapter = $authAdapter = new Zend_Auth_Adapter_DbTable(
					$users->getAdapter(), 'user', 'username', 'password', 'SHA1(?)'
				);
				$authAdapter->setIdentity($data['username'])->setCredential($data['password']);
				$result = $auth->authenticate($authAdapter);

				// Did the user successfully login?
				if ($result->isValid())
				{
					$auth->getStorage()->write($authAdapter->getResultRowObject());
					$mysession = new Zend_Session_Namespace('mysession');
					if (isset($mysession->destination_url))
					{
						$url = $mysession->destination_url;
						unset($mysession->destination_url);
						$this->_redirect($url);
					}
					$this->_helper->flashMessenger->addMessage(
						'Successfully Logged In'
					);
					$this->_redirect('/country/list');
				}
				else
				{
					$this->view->errors["form"] = array(
					    'Invalid Login'
					);
				}
			}
			else
			{
				$this->view->errors = $form->getErrors();
			}
		}

		$this->view->form = $form;
	}

	public function logoutAction()
	{
		// action body
		$storage = new Zend_Auth_Storage_Session();
		$storage->clear();
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_helper->flashMessenger->addMessage(
			'Successfully Logged Out'
		);
		$this->_redirect('/');
	}

}

