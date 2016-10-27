<?php

class Zend_View_Helper_GoogleMap extends Zend_View_Helper_Abstract
{

	public function GoogleMap($city, $country)
	{
		$value = $country;
		
		if ( $city )
		{
			$value = $city . ', ' . $value;
		}

		return $value;
	}

}

