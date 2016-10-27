<?php

class My_Acl extends Zend_Acl
{
    public function __construct()
    {
        // Add a new role called "guest"
        $this->addRole(new Zend_Acl_Role('guest'));
        // Add a role called user, which inherits from guest
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        // Add a role called admin, which inherits from user
        $this->addRole(new Zend_Acl_Role('admin'));
 
        // Add some resources in the form controller::action
        $this->add(new Zend_Acl_Resource('error::error'));
        $this->add(new Zend_Acl_Resource('error::noauth'));
        $this->add(new Zend_Acl_Resource('account::login'));
        $this->add(new Zend_Acl_Resource('account::logout'));
        $this->add(new Zend_Acl_Resource('country::index'));
        $this->add(new Zend_Acl_Resource('country::view'));
        $this->add(new Zend_Acl_Resource('country::list'));
        $this->add(new Zend_Acl_Resource('country::show'));
        $this->add(new Zend_Acl_Resource('country::add'));
        $this->add(new Zend_Acl_Resource('country::edit'));
        $this->add(new Zend_Acl_Resource('country::delete'));
        $this->add(new Zend_Acl_Resource('city::view'));
        $this->add(new Zend_Acl_Resource('city::add'));
        $this->add(new Zend_Acl_Resource('city::edit'));
        $this->add(new Zend_Acl_Resource('city::delete'));
 
        // Allow guests to see the error, login and index pages
        $this->allow('guest', 'error::error');
        $this->allow('guest', 'account::login');
        $this->allow('guest', 'country::index');
        $this->allow('guest', 'country::view');
        $this->allow('guest', 'city::view');

	$this->allow('admin');
    }
}

