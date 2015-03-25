<?php

class TransactionReference
{



	function hasAttribute($attributeName){
		return property_exists($this, "_$attributeName");
	}
}

?>