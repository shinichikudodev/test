<?php

class Application_Model_CountryMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Country');
        }
        return $this->_dbTable;
    }
 
    public function update(Application_Model_Country $country)
    {
        $data = array(
            'Name' => $country->getName(),
            'Capital' => $country->getCapital()
        );
 
        if (null === ($code = $country->getCode())) {
            unset($data['code']);
        } else {
            $this->getDbTable()->update($data, array('code = ?' => $code));
        }
    }
    
    
    public function insert(Application_Model_Country $country)
    {
        $data = array(
            'Code' => $country->getCode(),
            'Name' => $country->getName(),
            'Capital' => $country->getCapital(),
        );
	$this->getDbTable()->insert($data);
    }
 
    public function find($code, Application_Model_Country $country)
    {
        $result = $this->getDbTable()->find($code);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
	$country->setCode($row->Code)
                ->setName($row->Name)
		->setCapital($row->Capital);
	return $country;
    }

    public function deleteCountryByCode($code)
    {
        $result = $this->getDbTable()->find($code);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
	
	$row->delete();
    }    
    
    public function fetchAll()
    {
	$select = $this->getDbTable()->select();
	$select->order('Name ASC');
	    
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Country();
            $entry->setCode($row->Code)
                  ->setName($row->Name)
		  ->setCapital($row->Capital);
            $entries[] = $entry;
        }
        return $entries;
    }
}

