<?php

class Application_Model_LanguageMapper
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
            $this->setDbTable('Application_Model_DbTable_Language');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Language $language)
    {
	/*    
        $data = array(
            'email'   => $guestbook->getEmail(),
            'comment' => $guestbook->getComment(),
            'created' => date('Y-m-d H:i:s'),
        );
 
        if (null === ($id = $guestbook->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
	 * 
	 */
    }
 
    public function find($lang, $code, Application_Model_Language $language)
    {
        $result = $this->getDbTable()->find($lang,$code);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
	$language->setLanguage($row->Language)
	     ->setCountryCode($row->CountryCode)
	     ->setIsOfficial($row->IsOfficial);
	return $language;
    }
 
    public function getLanguagesByCountry($code)
    {
	$select = $this->getDbTable()->select();
	$select->where('CountryCode = ?', $code);
	$select->order('Language ASC');
	    
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Language();
            $entry->setLanguage($row->Language)
	     ->setCountryCode($row->CountryCode)
	     ->setIsOfficial($row->IsOfficial);  
            $entries[] = $entry;
        }
        return $entries;
    }


}

