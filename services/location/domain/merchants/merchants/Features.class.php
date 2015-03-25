<?php

class Features
{
	private $CashBack;

	function getCashBack(){
		return $this->CashBack;
	}

	function setCashBack($value){
		return $this->CashBack = $value;
	}

	function hasAttribute($attributeName){
		return property_exists($this, "_$attributeName");
	}
}

?>