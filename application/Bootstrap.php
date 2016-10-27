<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * @var Zend_Log
	 */
	protected $_logger;

	/**
	 * @var Zend_Application_Module_Autoloader
	 */
	protected $_resourceLoader;

	/**
	 * @var Zend_Controller_Front
	 */
	public $frontController;

	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}

	/**
	 * Setup the logging
	 */
	protected function _initLogging()
	{
		$this->bootstrap('frontController');
		$logger = new Zend_Log();

		$writer = 'production' == $this->getEnvironment() ?
			new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/app.log') :
			new Zend_Log_Writer_Firebug();
		$logger->addWriter($writer);

		if ('production' == $this->getEnvironment())
		{
			$filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
			$logger->addFilter($filter);
		}

		$this->_logger = $logger;
	}

	/**
	 * Add the config to the registry
	 */
	protected function _initConfig()
	{
		$this->_logger->info('Bootstrap ' . __METHOD__);
	}

	protected function _initRoutes()
	{
		$this->bootstrap('frontController');
		$frontController = $this->getResource('frontController');
		$router = $frontController->getRouter();

		$route = new Zend_Controller_Router_Route_Static(
			'login', array('controller' => 'account', 'action' => 'login')
		);
		$router->addRoute('login', $route);

		$route = new Zend_Controller_Router_Route_Static(
			'logout', array('controller' => 'account', 'action' => 'logout')
		);
		$router->addRoute('logout', $route);

		$route = new Zend_Controller_Router_Route_Static(
			'noauth', array('controller' => 'index', 'action' => 'noauth')
		);
		$router->addRoute('noauth', $route);

		$route = new Zend_Controller_Router_Route(
			'country/view/:code/', array('controller' => 'country',
		    'action' => 'view',
		    'code' => ''
			), array('code' => '[A-Z]{3}')
		);
		$router->addRoute('country-code-view', $route);

		$route = new Zend_Controller_Router_Route(
			'country/show/:code/', array('controller' => 'country',
		    'action' => 'show',
		    'code' => ''
			), array('code' => '[A-Z]{3}')
		);
		$router->addRoute('country-code-show', $route);
		
		$route = new Zend_Controller_Router_Route(
			'country/edit/:code/', array('controller' => 'country',
		    'action' => 'edit',
		    'code' => ''
			), array('code' => '[A-Z]{3}')
		);
		$router->addRoute('country-code-edit', $route);

		$route = new Zend_Controller_Router_Route(
			'country/delete/:code/', array('controller' => 'country',
		    'action' => 'delete',
		    'code' => ''
			), array('code' => '[A-Z]{3}')
		);
		$router->addRoute('country-code-delete', $route);
		
		$route = new Zend_Controller_Router_Route(
			'city/view/:id/', array('controller' => 'city',
		    'action' => 'view',
		    'id' => ''
			), array('id' => '\d+')
		);
		$router->addRoute('city-id-view', $route);

		$route = new Zend_Controller_Router_Route(
			'city/edit/:id/', array('controller' => 'city',
		    'action' => 'edit',
		    'id' => ''
			), array('id' => '\d+')
		);
		$router->addRoute('city-id-edit', $route);

		$route = new Zend_Controller_Router_Route(
			'city/delete/:id/', array('controller' => 'city',
		    'action' => 'delete',
		    'id' => ''
			), array('id' => '\d+')
		);
		$router->addRoute('city-id-delete', $route);
		
		
	}

	/**
	 * Setup the view
	 */
	protected function _initViewSettings()
	{
		$this->_logger->info('Bootstrap ' . __METHOD__);

		$this->bootstrap('view');

		$this->_view = $this->getResource('view');

		// add global helpers
		$this->_view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Zend_View_Helper');

		// set encoding and doctype
		$this->_view->setEncoding('UTF-8');
		$this->_view->doctype('XHTML1_STRICT');

		// set the content type and language
		$this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
		$this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');

		// set css links and a special import for the accessibility styles
		$this->_view->headStyle()->setStyle('@import "/css/access.css";');
		$this->_view->headLink(array('rel' => 'icon', 'href' => '/images/favicon.ico'), 'PREPEND')->appendStylesheet('/css/style.css');

		// setting the site in the title
		$this->_view->headTitle('World Database');

		// setting a separator string for segments:
		$this->_view->headTitle()->setSeparator(' - ');
	}

	protected function _initJquery()
	{

		$this->bootstrap('view');
		$view = $this->getResource('view'); //get the view object
//add the jquery view helper path into your project
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

//jquery lib includes here (default loads from google CDN)
		$view->jQuery()->enable()//enable jquery ; ->setCdnSsl(true) if need to load from ssl location
			->setVersion('3.1.1')
			->setUiVersion('1.12.1')
			->addStylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css')
			->uiEnable(); //enable ui
		$view->headScript()->prependFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.min.js');
		$view->headScript()->appendFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/additional-methods.min.js');
	}

	protected function _initPlugins()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('My_');

		$this->bootstrap('frontController');
		$frontController = $this->getResource('frontController');
		$frontController->registerPlugin(new My_Controller_Plugin_ACL(), 1);
	}

	
	/**
	 * Handle errors gracefully, this will work as long as the views,
	 * and the Zend classes are available
	 *
	 * @param Exception $e
	 * @param string $email
	 */
	protected function __handleErrors(Exception $e)
	{
		echo "HIHIHI";
		header('HTTP/1.1 500 Internal Server Error');
		$view = new Zend_View();
		$view->addScriptPath(dirname(__FILE__) . '/../views/scripts');
		echo $view->render('fatalError.phtml');
	}

}

