<?php

class Application_Model_CityMapper
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
            $this->setDbTable('Application_Model_DbTable_City');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_City $city)
    {
        $data = array(
            'ID'   => $city->getId(),
            'Name' => $city->getName(),
            'CountryCode' => $city->getCountryCode(),
        );
 
	
        if (null === ($id = $city->getId())) {
            unset($data['ID']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_City $city)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
	$city->setId($row->ID)
	     ->setName($row->Name)
	     ->setCountryCode($row->CountryCode);
	return $city;
    }
 
    public function deleteCityById($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
	
	$row->delete();
    }
    
    public function getCitiesByCountry($code)
    {
	$select = $this->getDbTable()->select();
	$select->where('CountryCode = ?', $code);
	$select->order('Name ASC');
	    
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_City();
            $entry->setId($row->ID)
                  ->setName($row->Name)
		  ->setCountryCode($row->CountryCode);  
            $entries[] = $entry;
        }
        return $entries;
    }
}

