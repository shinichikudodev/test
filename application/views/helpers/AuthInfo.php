<?php

require_once 'Zend/View/Interface.php';

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_AuthInfo extends Zend_View_Helper_Abstract
{   

    protected $_authService;
    
    /**
     * Get user info from the auth session
     *
     * @param string|null $info The data to fetch, null to chain
     * @return string|Zend_View_Helper_AuthInfo
     */
    public function AuthInfo ($info = null)
    {
        if (null === $this->_authService) {
            $this->_authService = Zend_Auth::getInstance();
        }
         
        if (null === $info) {
            return $this;
        }
        
        if (false === $this->isLoggedIn()) {
            return null;
        }
        return $this->_authService->getIdentity()->$info;
    }
    
    /**
     * Check if we are logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->_authService->hasIdentity();
    }
}
