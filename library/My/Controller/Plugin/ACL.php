<?php

class My_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract
{

	protected $_defaultRole = 'guest';

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$auth = Zend_Auth::getInstance();
		$acl = new My_Acl();
		$mysession = new Zend_Session_Namespace('mysession');

		if ($auth->hasIdentity())
		{
			$user = $auth->getIdentity();
			if (!$acl->isAllowed($user->role, $request->getControllerName() . '::' . $request->getActionName()))
			{
				$mysession->destination_url = $request->getPathInfo();
				return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('account/noauth');
			}
		}
		else
		{
			if (!$acl->isAllowed($this->_defaultRole, $request->getControllerName() . '::' . $request->getActionName()))
			{
				$mysession->destination_url = $request->getPathInfo();

				return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('login');
			}
		}
	}

}