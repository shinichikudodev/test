<?php

class Application_Model_Language
{
    protected $_language;
    protected $_isOfficial;
    protected $_countryCode;
 
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
            throw new Exception('Invalid city property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid city property');
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
 
    public function setLanguage($lang)
    {
        $this->_language = (string) $lang;
        return $this;
    }
 
    public function getLanguage()
    {
        return $this->_language;
    }
 
    public function setCountryCode($code)
    {
        $this->_countryCode = (string) $code;
        return $this;
    }
 
    public function getCountryCode()
    {
        return $this->_countryCode;
    }

    public function setIsOfficial($flag)
    {
        $this->_isOfficial = (string) $flag;
        return $this;
    }
 
    public function getIsOfficial()
    {
        return $this->_isOfficial;
    }



}

