<?php

class Application_Model_Country
{
    protected $_code;
    protected $_name;
    protected $_capital;
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid country property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid country property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setCode($code)
    {
        $this->_code = (string) $code;
        return $this;
    }
 
    public function getCode()
    {
        return $this->_code;
    }
 
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }
 
    public function getName()
    {
        return $this->_name;
    }

    public function setCapital($capital)
    {
        $this->_capital = (int) $capital;
        return $this;
    }
 
    public function getCapital()
    {
        return $this->_capital;
    }
    
    public function toArray()
    {
	    $arr = array('code' => $this->getCode(), 'name' => $this->getName(), 'capital' => $this->getCapital());
	    return $arr;
    }
    
}

